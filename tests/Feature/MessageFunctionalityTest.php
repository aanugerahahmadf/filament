<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MessageFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_messages()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $otherUser */
        $otherUser = User::factory()->create();

        $message = Message::factory()->create([
            'from_user_id' => $user->id,
            'to_user_id' => $otherUser->id,
            'body' => 'Test message' // Changed from 'message' to 'body'
        ]);

        $response = $this->actingAs($user)->get('/messages');

        $response->assertStatus(200);
        $response->assertSee('Test message');
    }

    /** @test */
    public function user_can_send_message()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $otherUser */
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)->post('/messages', [
            'to_user_id' => $otherUser->id,
            'body' => 'Hello, this is a test message!' // Changed from 'message' to 'body'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('messages', [
            'from_user_id' => $user->id,
            'to_user_id' => $otherUser->id,
            'body' => 'Hello, this is a test message!' // Changed from 'message' to 'body'
        ]);
    }

    /** @test */
    public function user_can_delete_own_message()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $otherUser */
        $otherUser = User::factory()->create();

        $message = Message::factory()->create([
            'from_user_id' => $user->id,
            'to_user_id' => $otherUser->id,
            'body' => 'Test message to delete' // Changed from 'message' to 'body'
        ]);

        $response = $this->actingAs($user)->delete("/messages/{$message->id}");

        $response->assertStatus(302);
        // Check that the message has been soft deleted (has a deleted_at timestamp)
        $this->assertNotNull($message->fresh()->deleted_at);
    }

    /** @test */
    public function user_cannot_delete_others_messages()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $otherUser */
        $otherUser = User::factory()->create();
        /** @var Authenticatable $thirdUser */
        $thirdUser = User::factory()->create();

        $message = Message::factory()->create([
            'from_user_id' => $otherUser->id,
            'to_user_id' => $thirdUser->id,
            'body' => 'Test message' // Changed from 'message' to 'body'
        ]);

        $response = $this->actingAs($user)->delete("/messages/{$message->id}");

        $response->assertStatus(302);
        $this->assertDatabaseHas('messages', [
            'id' => $message->id
        ]);
    }

    /** @test */
    public function super_admin_can_view_conversation_with_user()
    {
        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        /** @var User $superAdmin */
        $superAdmin = User::factory()->create();
        /** @phpstan-ignore-next-line */
        $superAdmin->assignRole($superAdminRole);

        /** @var Authenticatable $user */
        $user = User::factory()->create();

        $message = Message::factory()->create([
            'from_user_id' => $superAdmin->id,
            'to_user_id' => $user->id,
            'body' => 'Admin message' // Changed from 'message' to 'body'
        ]);

        $response = $this->actingAs($superAdmin)->get("/messages/conversation/{$user->id}");

        $response->assertStatus(200);
        $response->assertSee('Admin message');
        $response->assertSee($user->name);
    }
}
