<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the notification may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds the notification can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * The CCTV or maintenance details.
     *
     * @var array
     */
    public $details;

    /**
     * Create a new notification instance.
     *
     * @param array $details
     * @return void
     */
    public function __construct(array $details)
    {
        $this->details = $details;
        // Use database queue connection to ensure compatibility on Windows environments
        $this->onConnection('database');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Determine notification type and customize message accordingly
        $type = $this->details['type'] ?? 'maintenance';

        switch ($type) {
            case 'cctv_offline':
                return $this->cctvOfflineMail($notifiable);
            case 'maintenance_completed':
                return $this->maintenanceCompletedMail($notifiable);
            case 'system_alert':
                return $this->systemAlertMail($notifiable);
            default:
                return $this->maintenanceCompletedMail($notifiable);
        }
    }

    /**
     * Mail for CCTV offline notification
     */
    private function cctvOfflineMail($notifiable)
    {
        return (new MailMessage)
            ->subject('ATCS ALERT: CCTV Camera Offline - ' . ($this->details['cctv_name'] ?? 'Unknown Camera'))
            ->greeting('ATCS System Alert - Refinery Unit VI Balongan')
            ->line('CCTV Camera Offline Detected:')
            ->line('Camera: ' . ($this->details['cctv_name'] ?? 'N/A'))
            ->line('Location: ' . ($this->details['location'] ?? 'Unknown Location'))
            ->line('Building: ' . ($this->details['building'] ?? 'N/A'))
            ->line('Room: ' . ($this->details['room'] ?? 'N/A'))
            ->line('Offline Since: ' . ($this->details['offline_time'] ?? now()->format('Y-m-d H:i:s')))
            ->action('View CCTV Status', url('/admin/cctvs'))
            ->line('Please check the camera connection and take necessary action.')
            ->salutation('PT Kilang Pertamina Internasional - Refinery Unit VI Balongan');
    }

    /**
     * Mail for maintenance completed notification
     */
    private function maintenanceCompletedMail($notifiable)
    {
        return (new MailMessage)
            ->subject('ATCS: Maintenance Completed - ' . ($this->details['cctv_name'] ?? 'CCTV System'))
            ->greeting('Maintenance Report - Refinery Unit VI Balongan')
            ->line('Maintenance Activity Completed:')
            ->line('Equipment: ' . ($this->details['cctv_name'] ?? 'CCTV System'))
            ->line('Location: ' . ($this->details['location'] ?? 'Refinery Unit VI'))
            ->line('Technician: ' . ($this->details['technician'] ?? 'N/A'))
            ->line('Completion Time: ' . ($this->details['completed_at'] ?? now()->format('Y-m-d H:i:s')))
            ->line('Status: ' . ($this->details['status'] ?? 'Completed'))
            ->action('View Maintenance Records', url('/admin/maintenances'))
            ->line('Thank you for your service.')
            ->salutation('PT Kilang Pertamina Internasional - Refinery Unit VI Balongan');
    }

    /**
     * Mail for system alert notification
     */
    private function systemAlertMail($notifiable)
    {
        return (new MailMessage)
            ->subject('ATCS SYSTEM ALERT: ' . ($this->details['alert_type'] ?? 'System Notification'))
            ->greeting('System Alert - Refinery Unit VI Balongan')
            ->line('Alert Type: ' . ($this->details['alert_type'] ?? 'System Notification'))
            ->line('Message: ' . ($this->details['message'] ?? 'No details provided'))
            ->line('Severity: ' . ($this->details['severity'] ?? 'Medium'))
            ->line('Timestamp: ' . ($this->details['timestamp'] ?? now()->format('Y-m-d H:i:s')))
            ->action('View System Alerts', url('/admin/alerts'))
            ->line('Please review the system status and take appropriate action if necessary.')
            ->salutation('PT Kilang Pertamina Internasional - Refinery Unit VI Balongan');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => $this->details['type'] ?? 'maintenance',
            'cctv_id' => $this->details['cctv_id'] ?? null,
            'cctv_name' => $this->details['cctv_name'] ?? null,
            'location' => $this->details['location'] ?? null,
            'building' => $this->details['building'] ?? null,
            'room' => $this->details['room'] ?? null,
            'message' => $this->details['message'] ?? 'No message provided',
            'severity' => $this->details['severity'] ?? 'info',
            'timestamp' => $this->details['timestamp'] ?? now(),
        ];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'mail' => 'mail',
            // Route database (in-app) notification to a high-priority queue for faster processing
            'database' => 'high',
        ];
    }

    /**
     * Get the type of the notification being sent.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function broadcastType()
    {
        return 'atcs.notification';
    }
}
