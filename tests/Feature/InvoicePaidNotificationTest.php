<?php

namespace Tests\Feature;

use App\Notifications\InvoicePaid;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InvoicePaidNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_send_cctv_offline_notification()
    {
        Notification::fake();

        $user = User::factory()->create();
        $details = [
            'type' => 'cctv_offline',
            'cctv_name' => 'Camera_001',
            'location' => 'Processing Unit',
            'building' => 'Main Processing Building',
            'room' => 'Control Room 1',
            'offline_time' => now()->format('Y-m-d H:i:s'),
        ];

        $notification = new InvoicePaid($details);
        $user->notify($notification);

        Notification::assertSentTo($user, InvoicePaid::class);

        // Assert the notification was sent with correct data
        Notification::assertSentTo($user, function (InvoicePaid $notified) use ($user, $details) {
            $mail = $notified->toMail($user);
            $array = $notified->toArray($user);

            // Check mail content
            $this->assertStringContainsString('ATCS ALERT: CCTV Camera Offline', $mail->subject);
            $this->assertStringContainsString('Refinery Unit VI Balongan', $mail->greeting);

            // Check array content
            $this->assertEquals('cctv_offline', $array['type']);
            $this->assertEquals($details['cctv_name'], $array['cctv_name']);
            $this->assertEquals($details['location'], $array['location']);

            return true;
        });
    }

    /** @test */
    public function it_can_send_maintenance_completed_notification()
    {
        Notification::fake();

        $user = User::factory()->create();
        $details = [
            'type' => 'maintenance_completed',
            'cctv_name' => 'Camera_002',
            'location' => 'Refinery Unit VI',
            'technician' => 'John Doe',
            'completed_at' => now()->format('Y-m-d H:i:s'),
            'status' => 'Completed',
        ];

        $notification = new InvoicePaid($details);
        $user->notify($notification);

        Notification::assertSentTo($user, InvoicePaid::class);

        // Assert the notification was sent with correct data
        Notification::assertSentTo($user, function (InvoicePaid $notified) use ($user, $details) {
            $mail = $notified->toMail($user);
            $array = $notified->toArray($user);

            // Check mail content
            $this->assertStringContainsString('ATCS: Maintenance Completed', $mail->subject);
            $this->assertStringContainsString('Maintenance Report', $mail->greeting);

            // Check array content
            $this->assertEquals('maintenance_completed', $array['type']);
            $this->assertEquals($details['cctv_name'], $array['cctv_name']);
            $this->assertEquals($details['location'], $array['location']);

            return true;
        });
    }

    /** @test */
    public function it_can_send_system_alert_notification()
    {
        Notification::fake();

        $user = User::factory()->create();
        $details = [
            'type' => 'system_alert',
            'alert_type' => 'Network Disruption',
            'message' => 'Network connectivity issue detected in Building A',
            'severity' => 'High',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];

        $notification = new InvoicePaid($details);
        $user->notify($notification);

        Notification::assertSentTo($user, InvoicePaid::class);

        // Assert the notification was sent with correct data
        Notification::assertSentTo($user, function (InvoicePaid $notified) use ($user, $details) {
            $mail = $notified->toMail($user);
            $array = $notified->toArray($user);

            // Check mail content
            $this->assertStringContainsString('ATCS SYSTEM ALERT', $mail->subject);
            $this->assertStringContainsString('System Alert', $mail->greeting);

            // Check array content
            $this->assertEquals('system_alert', $array['type']);
            $this->assertEquals($details['severity'], $array['severity']);
            $this->assertEquals($details['message'], $array['message']);

            return true;
        });
    }

    /** @test */
    public function it_handles_missing_details_gracefully()
    {
        Notification::fake();

        $user = User::factory()->create();
        $details = [];

        $notification = new InvoicePaid($details);
        $user->notify($notification);

        Notification::assertSentTo($user, InvoicePaid::class);

        // Assert the notification was sent without errors
        Notification::assertSentTo($user, function (InvoicePaid $notified) use ($user) {
            $mail = $notified->toMail($user);
            $array = $notified->toArray($user);

            // Check that it handles missing data gracefully
            $this->assertStringContainsString('ATCS', $mail->subject);
            $this->assertEquals('maintenance', $array['type']); // Default type

            return true;
        });
    }
}
