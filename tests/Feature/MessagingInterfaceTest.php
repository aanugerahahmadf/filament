<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

class MessagingInterfaceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_messages_list_page()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/messages');

        $response->assertStatus(200);
        $response->assertViewIs('messages.messages-list');
    }

    /** @test */
    public function user_can_view_messages_box_page()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $otherUser */
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)->get("/messages/conversation/{$otherUser->id}");

        $response->assertStatus(200);
        $response->assertViewIs('messages.messages-box');
    }

    /** @test */
    public function guest_cannot_access_messages()
    {
        $response = $this->get('/messages');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_send_message_via_ajax()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $recipient */
        $recipient = User::factory()->create();

        $response = $this->actingAs($user)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/messages', [
                'to_user_id' => $recipient->id,
                'body' => 'Hello, this is a test message!'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'from_user_id' => $user->id,
            'to_user_id' => $recipient->id,
            'body' => 'Hello, this is a test message!'
        ]);
    }

    /** @test */
    public function user_can_delete_own_message_via_ajax()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $recipient */
        $recipient = User::factory()->create();

        $message = Message::factory()->create([
            'from_user_id' => $user->id,
            'to_user_id' => $recipient->id,
            'body' => 'Test message to delete'
        ]);

        $response = $this->actingAs($user)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->delete("/messages/{$message->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Check that the message has been soft deleted (has a deleted_at timestamp)
        $this->assertNotNull($message->fresh()->deleted_at);
    }

    /** @test */
    public function user_cannot_delete_others_messages_via_ajax()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $otherUser */
        $otherUser = User::factory()->create();
        /** @var Authenticatable $recipient */
        $recipient = User::factory()->create();

        $message = Message::factory()->create([
            'from_user_id' => $otherUser->id,
            'to_user_id' => $recipient->id,
            'body' => 'Test message'
        ]);

        $response = $this->actingAs($user)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->delete("/messages/{$message->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => false]);

        // Check that the message still exists (not deleted)
        $this->assertNull($message->fresh()->deleted_at);
    }

    /** @test */
    public function user_can_send_typing_indicator()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $recipient */
        $recipient = User::factory()->create();

        $response = $this->actingAs($user)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/messages/typing', [
                'recipient_id' => $recipient->id,
                'is_typing' => true
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function messages_box_contains_functional_action_buttons()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        /** @var Authenticatable $recipient */
        $recipient = User::factory()->create();

        $response = $this->actingAs($user)->get("/messages/conversation/{$recipient->id}");

        $response->assertStatus(200);
        // Check for the phone button (using part of the SVG path)
        $response->assertSee('m20.487 17.14-4.065-3.696');
        // Check for the video button (using part of the SVG path)
        $response->assertSee('M19.999 4h-16c-1.103 0-2');
        // Check for the info button (using part of the SVG path)
        $response->assertSee('m7 15 5 5 5-5');
        // Check for the attachment button (using part of the SVG path)
        $response->assertSee('M12 2C6.486 2 2 6.486 2 12');
        // Check for the image button (using part of the SVG path)
        $response->assertSee('M19.999 4h-16c-1.103 0-2');
    }
}
