<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Building;
use App\Models\Cctv;
use App\Models\Contact;
use App\Models\Maintenance;
use App\Models\Recording;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BackendComprehensiveTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected User $technicianUser;

    protected User $operatorUser;

    protected User $viewerUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $technicianRole = Role::firstOrCreate(['name' => 'technician']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // Create users with roles
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);

        $this->technicianUser = User::factory()->create();
        $this->technicianUser->assignRole($technicianRole);

        $this->operatorUser = User::factory()->create();
        $this->operatorUser->assignRole($operatorRole);

        $this->viewerUser = User::factory()->create();
        $this->viewerUser->assignRole($viewerRole);
    }

    /** @test */
    public function it_can_create_and_manage_buildings()
    {
        $buildingData = [
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'contact_person' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
        ];

        // Admin can create buildings
        $response = $this->actingAs($this->adminUser)
            ->post('/buildings', $buildingData);
        $response->assertStatus(302);

        $this->assertDatabaseHas('buildings', [
            'name' => $buildingData['name'],
        ]);

        $building = Building::where('name', $buildingData['name'])->first();

        // Admin can update buildings
        $updateData = ['name' => 'Updated Building Name'];
        $response = $this->actingAs($this->adminUser)
            ->put("/buildings/{$building->id}", array_merge($buildingData, $updateData));
        $response->assertStatus(302);

        $this->assertDatabaseHas('buildings', [
            'id' => $building->id,
            'name' => 'Updated Building Name',
        ]);

        // Admin can delete buildings
        $response = $this->actingAs($this->adminUser)
            ->delete("/buildings/{$building->id}");
        $response->assertStatus(302);

        $this->assertDatabaseMissing('buildings', [
            'id' => $building->id,
        ]);
    }

    /** @test */
    public function it_can_create_and_manage_rooms()
    {
        $building = Building::factory()->create();

        $roomData = [
            'building_id' => $building->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'floor' => $this->faker->numberBetween(1, 10),
            'capacity' => $this->faker->numberBetween(10, 100),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];

        // Admin can create rooms
        $response = $this->actingAs($this->adminUser)
            ->post('/rooms', $roomData);
        $response->assertStatus(302);

        $this->assertDatabaseHas('rooms', [
            'name' => $roomData['name'],
        ]);

        $room = Room::where('name', $roomData['name'])->first();

        // Admin can update rooms
        $updateData = ['name' => 'Updated Room Name'];
        $response = $this->actingAs($this->adminUser)
            ->put("/rooms/{$room->id}", array_merge($roomData, $updateData));
        $response->assertStatus(302);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'name' => 'Updated Room Name',
        ]);
    }

    /** @test */
    public function it_can_create_and_manage_cctvs()
    {
        $building = Building::factory()->create();

        $cctvData = [
            'building_id' => $building->id,
            'name' => $this->faker->word,
            'model' => $this->faker->word,
            'serial_number' => $this->faker->uuid,
            'firmware_version' => '1.0.0',
            'description' => $this->faker->sentence,
            'ip_rtsp' => $this->faker->ipv4,
            'port' => 554,
            'resolution' => '1920x1080',
            'fps' => 30,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];

        // Admin can create CCTVs
        $response = $this->actingAs($this->adminUser)
            ->post('/cctvs', $cctvData);
        $response->assertStatus(302);

        $this->assertDatabaseHas('cctvs', [
            'name' => $cctvData['name'],
        ]);

        $cctv = Cctv::where('name', $cctvData['name'])->first();

        // Admin can update CCTVs
        $updateData = ['name' => 'Updated CCTV Name'];
        $response = $this->actingAs($this->adminUser)
            ->put("/cctvs/{$cctv->id}", array_merge($cctvData, $updateData));
        $response->assertStatus(302);

        $this->assertDatabaseHas('cctvs', [
            'id' => $cctv->id,
            'name' => 'Updated CCTV Name',
        ]);
    }

    /** @test */
    public function it_can_create_and_manage_contacts()
    {
        $contactData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'whatsapp' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'position' => $this->faker->jobTitle,
            'department' => $this->faker->word,
            'instagram' => $this->faker->userName,
            'facebook' => $this->faker->userName,
            'linkedin' => $this->faker->userName,
        ];

        // Admin can create contacts
        $response = $this->actingAs($this->adminUser)
            ->post('/contacts', $contactData);
        $response->assertStatus(302);

        $this->assertDatabaseHas('contacts', [
            'name' => $contactData['name'],
        ]);

        $contact = Contact::where('name', $contactData['name'])->first();

        // Admin can update contacts
        $updateData = ['name' => 'Updated Contact Name'];
        $response = $this->actingAs($this->adminUser)
            ->put("/contacts/{$contact->id}", array_merge($contactData, $updateData));
        $response->assertStatus(302);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'Updated Contact Name',
        ]);
    }

    /** @test */
    public function it_can_create_and_manage_maintenances()
    {
        $cctv = Cctv::factory()->create();
        $technician = User::factory()->create();

        $maintenanceData = [
            'cctv_id' => $cctv->id,
            'technician_id' => $technician->id,
            'scheduled_at' => now()->addDay(),
            'type' => 'preventive',
            'description' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
            'cost' => $this->faker->randomFloat(2, 100, 1000),
        ];

        // Technician can create maintenances
        $response = $this->actingAs($this->technicianUser)
            ->post('/maintenances', $maintenanceData);
        $response->assertStatus(302);

        $this->assertDatabaseHas('maintenances', [
            'cctv_id' => $cctv->id,
        ]);

        $maintenance = Maintenance::where('cctv_id', $cctv->id)->first();

        // Technician can update maintenances
        $updateData = ['notes' => 'Updated maintenance notes'];
        $response = $this->actingAs($this->technicianUser)
            ->put("/maintenances/{$maintenance->id}", array_merge($maintenanceData, $updateData));
        $response->assertStatus(302);

        $this->assertDatabaseHas('maintenances', [
            'id' => $maintenance->id,
            'notes' => 'Updated maintenance notes',
        ]);
    }

    /** @test */
    public function it_can_create_and_manage_alerts()
    {
        $cctv = Cctv::factory()->create();

        $alertData = [
            'alertable_type' => Cctv::class,
            'alertable_id' => $cctv->id,
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'severity' => 'high',
            'category' => 'hardware',
            'source' => 'system',
        ];

        // System can create alerts (this would typically be done by the system)
        $alert = Alert::create($alertData);

        $this->assertDatabaseHas('alerts', [
            'title' => $alertData['title'],
        ]);

        // Technician can acknowledge alerts
        $response = $this->actingAs($this->technicianUser)
            ->post("/alerts/{$alert->id}/acknowledge");
        $response->assertStatus(200);

        $alert->refresh();
        $this->assertTrue($alert->isAcknowledged());
    }

    /** @test */
    public function it_can_create_and_manage_recordings()
    {
        $cctv = Cctv::factory()->create();

        $recordingData = [
            'cctv_id' => $cctv->id,
            'filename' => 'recording_'.time().'.mp4',
            'filepath' => 'recordings/recording_'.time().'.mp4',
            'size' => 1024000,
            'duration' => 3600,
            'started_at' => now(),
            'ended_at' => now()->addHour(),
            'format' => 'mp4',
            'resolution' => '1920x1080',
            'status' => 'active',
        ];

        // System can create recordings (this would typically be done by the system)
        $recording = Recording::create($recordingData);

        $this->assertDatabaseHas('recordings', [
            'filename' => $recordingData['filename'],
        ]);

        // Operator can view recordings
        $response = $this->actingAs($this->operatorUser)
            ->get("/recordings/{$recording->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function it_has_proper_role_based_access_control()
    {
        $building = Building::factory()->create();

        // Viewer cannot create buildings
        $response = $this->actingAs($this->viewerUser)
            ->post('/buildings', [
                'name' => 'Test Building',
            ]);
        $response->assertStatus(403);

        // Operator cannot delete buildings
        $response = $this->actingAs($this->operatorUser)
            ->delete("/buildings/{$building->id}");
        $response->assertStatus(403);

        // Admin can do everything
        $response = $this->actingAs($this->adminUser)
            ->post('/buildings', [
                'name' => 'Admin Building',
            ]);
        $response->assertStatus(302);
    }

    /** @test */
    public function it_can_generate_dashboard_statistics()
    {
        // Create some test data
        Building::factory()->count(5)->create();
        Room::factory()->count(10)->create();
        Cctv::factory()->count(20)->create(['status' => 'online']);
        Cctv::factory()->count(5)->create(['status' => 'offline']);
        User::factory()->count(10)->create();
        Alert::factory()->count(3)->create(['severity' => 'critical']);
        Alert::factory()->count(5)->create(['severity' => 'high']);
        Maintenance::factory()->count(8)->create(['status' => 'scheduled']);

        // Test dashboard statistics
        $response = $this->actingAs($this->adminUser)
            ->get('/dashboard');
        $response->assertStatus(200);

        // Test API statistics
        $response = $this->actingAs($this->adminUser)
            ->get('/api/cctvs/statistics');
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)
            ->get('/api/maintenances/statistics');
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)
            ->get('/api/alerts/statistics');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_perform_system_health_checks()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/api/health');
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)
            ->get('/api/health/alerts');
        $response->assertStatus(200);
    }
}
