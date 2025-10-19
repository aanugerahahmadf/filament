<?php

use App\Models\Cctv;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/livestream');
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the livestream page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/livestream');
    $response->assertStatus(200);
    $response->assertSee('Live Stream');
});

test('authenticated users can see cctv list', function () {
    $user = User::factory()->create();
    $cctv = Cctv::factory()->create([
        'name' => 'Test CCTV',
        'status' => 'online',
    ]);
    $this->actingAs($user);

    $response = $this->get('/livestream');
    $response->assertStatus(200);
    $response->assertSee('Test CCTV');
});
