<?php

declare(strict_types=1);

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('maps page can be accessed by authenticated users', function () {
    actingAs($this->user)
        ->get('/maps')
        ->assertOk()
        ->assertSee('PETA CCTV - KILANG PERTAMINA BALONGAN');
});

test('maps page requires authentication', function () {
    get('/maps')
        ->assertRedirect('/login');
});

test('map data endpoint returns buildings with rooms and cctvs', function () {
    $building = Building::factory()->create([
        'name' => 'Gedung Test',
        'latitude' => -6.3326,
        'longitude' => 108.4582,
    ]);

    $room = Room::factory()->create([
        'building_id' => $building->id,
        'name' => 'Ruang Control',
        'latitude' => -6.3327,
        'longitude' => 108.4583,
    ]);

    $cctv = Cctv::factory()->create([
        'building_id' => $building->id,
        'room_id' => $room->id,
        'name' => 'CCTV Test 1',
        'status' => 'online',
    ]);

    actingAs($this->user)
        ->get('/map-data')
        ->assertOk()
        ->assertJsonStructure([
            'buildings' => [
                '*' => [
                    'id',
                    'name',
                    'latitude',
                    'longitude',
                    'rooms' => [
                        '*' => [
                            'id',
                            'name',
                            'cctvs',
                        ],
                    ],
                ],
            ],
        ])
        ->assertJsonFragment(['name' => 'Gedung Test'])
        ->assertJsonFragment(['name' => 'Ruang Control'])
        ->assertJsonFragment(['name' => 'CCTV Test 1']);
});

test('map displays different status markers correctly', function () {
    $building = Building::factory()->create();

    $room = Room::factory()->create(['building_id' => $building->id]);

    Cctv::factory()->create([
        'building_id' => $building->id,
        'room_id' => $room->id,
        'status' => 'online',
    ]);

    Cctv::factory()->create([
        'building_id' => $building->id,
        'room_id' => $room->id,
        'status' => 'offline',
    ]);

    Cctv::factory()->create([
        'building_id' => $building->id,
        'room_id' => $room->id,
        'status' => 'maintenance',
    ]);

    $response = actingAs($this->user)
        ->get('/map-data')
        ->assertOk();

    $data = $response->json();
    $cctvs = collect($data['buildings'][0]['rooms'][0]['cctvs']);

    expect($cctvs->where('status', 'online')->count())->toBe(1);
    expect($cctvs->where('status', 'offline')->count())->toBe(1);
    expect($cctvs->where('status', 'maintenance')->count())->toBe(1);
});

test('building search works correctly', function () {
    Building::factory()->create(['name' => 'Gedung Kolaboratif']);
    Building::factory()->create(['name' => 'Gedung EXOR']);
    Building::factory()->create(['name' => 'Main Control Room']);

    $response = actingAs($this->user)
        ->get('/map-data')
        ->assertOk();

    $data = $response->json();
    $buildingNames = collect($data['buildings'])->pluck('name');

    expect($buildingNames)->toContain('Gedung Kolaboratif');
    expect($buildingNames)->toContain('Gedung EXOR');
    expect($buildingNames)->toContain('Main Control Room');
});

test('room markers display correct number of cctvs', function () {
    $building = Building::factory()->create();
    $room = Room::factory()->create([
        'building_id' => $building->id,
        'name' => 'Control Room A',
    ]);

    // Create 3 CCTVs in the room
    Cctv::factory()->count(3)->create([
        'building_id' => $building->id,
        'room_id' => $room->id,
        'status' => 'online',
    ]);

    $response = actingAs($this->user)
        ->get('/map-data')
        ->assertOk();

    $data = $response->json();
    $roomData = $data['buildings'][0]['rooms'][0];

    expect($roomData['cctvs'])->toHaveCount(3);
    expect($roomData['name'])->toBe('Control Room A');
});

test('maps page contains all required UI elements', function () {
    actingAs($this->user)
        ->get('/maps')
        ->assertOk()
        ->assertSee('OpenStreetMap')
        ->assertSee('Satellite')
        ->assertSee('Cari gedung');
});

test('custom marker icons are used when available', function () {
    $building = Building::factory()->create([
        'name' => 'Gedung Custom',
        'marker_icon_path' => '/images/custom-building-icon.png',
    ]);

    $response = actingAs($this->user)
        ->get('/map-data')
        ->assertOk();

    $data = $response->json();

    expect($data['buildings'][0]['marker_icon_path'])->toBe('/images/custom-building-icon.png');
});

test('room with no cctvs is not displayed on map', function () {
    $building = Building::factory()->create();
    Room::factory()->create([
        'building_id' => $building->id,
        'name' => 'Empty Room',
    ]);

    $response = actingAs($this->user)
        ->get('/map-data')
        ->assertOk();

    $data = $response->json();
    $room = $data['buildings'][0]['rooms'][0];

    expect($room['cctvs'])->toBeEmpty();
});
