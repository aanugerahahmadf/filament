<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppMessengerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message_with_whatsapp_features()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);

        $response = $this->postJson('/messages', [
            'to_user_id' => $user2->id,
            'body' => 'Hello, this is a test message!',
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'Hello, this is a test message!',
            'message_type' => 'text',
        ]);
    }

    public function test_user_can_edit_message()
    {
        $user = User::factory()->create();
        $message = Message::factory()->create([
            'from_user_id' => $user->id,
            'body' => 'Original message',
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/messages/{$message->id}/edit", [
            'body' => 'Edited message',
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'body' => 'Edited message',
            'is_edited' => true,
        ]);
    }

    public function test_user_can_reply_to_message()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $originalMessage = Message::factory()->create([
            'from_user_id' => $user2->id,
            'to_user_id' => $user1->id,
            'body' => 'Original message',
        ]);

        $this->actingAs($user1);

        $response = $this->postJson('/messages/reply', [
            'to_user_id' => $user2->id,
            'body' => 'This is a reply',
            'reply_to_message_id' => $originalMessage->id,
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'This is a reply',
            'reply_to_message_id' => $originalMessage->id,
        ]);
    }

    public function test_user_can_forward_message()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        
        $originalMessage = Message::factory()->create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'Message to forward',
        ]);

        $this->actingAs($user2);

        $response = $this->postJson('/messages/forward', [
            'message_id' => $originalMessage->id,
            'to_user_id' => $user3->id,
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'from_user_id' => $user2->id,
            'to_user_id' => $user3->id,
            'body' => 'Message to forward',
            'forwarded_from_user_id' => $user1->id,
        ]);
    }

    public function test_user_can_add_reaction_to_message()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $message = Message::factory()->create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'Message to react to',
        ]);

        $this->actingAs($user2);

        $response = $this->postJson("/messages/{$message->id}/reaction", [
            'reaction' => '❤️',
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'reaction' => '❤️',
        ]);
    }

    public function test_user_can_pin_message()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $message = Message::factory()->create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'Message to pin',
        ]);

        $this->actingAs($user1);

        $response = $this->postJson("/messages/{$message->id}/pin");

        $response->assertStatus(200)
                ->assertJson(['success' => true, 'is_pinned' => true]);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'is_pinned' => true,
        ]);
    }

    public function test_user_can_mark_messages_as_read()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $message1 = Message::factory()->create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'Message 1',
            'read_at' => null,
        ]);

        $message2 = Message::factory()->create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'Message 2',
            'read_at' => null,
        ]);

        $this->actingAs($user2);

        $response = $this->postJson('/messages/mark-read', [
            'message_ids' => [$message1->id, $message2->id],
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true, 'count' => 2]);

        $this->assertDatabaseHas('messages', [
            'id' => $message1->id,
            'read_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => $message2->id,
            'read_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function test_typing_indicator_functionality()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);

        $response = $this->postJson('/messages/typing', [
            'recipient_id' => $user2->id,
            'is_typing' => true,
        ]);

        $response->assertStatus(200)
                ->assertJson(['status' => 'ok']);

        $response = $this->postJson('/messages/typing', [
            'recipient_id' => $user2->id,
            'is_typing' => false,
        ]);

        $response->assertStatus(200)
                ->assertJson(['status' => 'ok']);
    }

    public function test_message_status_tracking()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $message = Message::factory()->create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'body' => 'Test message',
            'delivered_at' => null,
            'read_at' => null,
        ]);

        // Test sent status
        $this->assertEquals('sent', $message->status);

        // Test delivered status
        $message->markAsDelivered();
        $this->assertEquals('delivered', $message->fresh()->status);

        // Test read status
        $message->markAsRead();
        $this->assertEquals('read', $message->fresh()->status);
    }
}
