<?php

namespace App\Services;

use App\Events\NotificationCreated;
use App\Models\Alert;
use App\Models\Cctv;
use App\Models\Maintenance;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function sendCctvNotification(Cctv $cctv, string $type, string $message, array $data = []): void
    {
        $notificationData = array_merge([
            'cctv_id' => $cctv->id,
            'cctv_name' => $cctv->name,
            'message' => $message,
        ], $data);

        // Send to all users for now (in a real app, you might want to filter by role/permission)
        $users = User::all();

        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'notifiable_type' => Cctv::class,
                'notifiable_id' => $cctv->id,
                'data' => $notificationData,
            ]);
            NotificationCreated::dispatch($notification);

            // Send email notification if enabled
            if ($this->settingsService->get('notifications_email', true)) {
                $this->sendEmailNotification($user, $type, $message, $notificationData);
            }
        }
    }

    public function sendMaintenanceNotification(Maintenance $maintenance, string $type, string $message, array $data = []): void
    {
        $notificationData = array_merge([
            'maintenance_id' => $maintenance->id,
            'cctv_name' => $maintenance->cctv->name,
            'message' => $message,
        ], $data);

        // Send to technician and admins
        $recipients = collect();

        if ($maintenance->technician) {
            $recipients->push($maintenance->technician);
        }

        // Add admins (in a real app, you would filter by role)
        $admins = User::where('id', '!=', $maintenance->technician_id ?? 0)->get();
        $recipients = $recipients->merge($admins);

        foreach ($recipients as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'notifiable_type' => Maintenance::class,
                'notifiable_id' => $maintenance->id,
                'data' => $notificationData,
            ]);
            NotificationCreated::dispatch($notification);

            // Send email notification if enabled
            if ($this->settingsService->get('notifications_email', true)) {
                $this->sendEmailNotification($user, $type, $message, $notificationData);
            }
        }
    }

    public function sendAlertNotification(Alert $alert, string $type, string $message, array $data = []): void
    {
        // Check if this severity should trigger notifications
        $enabledSeverities = $this->settingsService->get('notifications_alert_severities', ['critical', 'high']);
        if (! in_array($alert->severity, $enabledSeverities)) {
            return;
        }

        $notificationData = array_merge([
            'alert_id' => $alert->id,
            'title' => $alert->title,
            'message' => $message,
        ], $data);

        // Send to all users for critical alerts, or specific users for others
        $users = User::all();

        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'notifiable_type' => Alert::class,
                'notifiable_id' => $alert->id,
                'data' => $notificationData,
            ]);
            NotificationCreated::dispatch($notification);

            // Send email notification if enabled
            if ($this->settingsService->get('notifications_email', true)) {
                $this->sendEmailNotification($user, $type, $message, $notificationData);
            }
        }
    }

    public function sendUserNotification(User $user, string $type, string $message, array $data = []): void
    {
        $notificationData = array_merge([
            'user_id' => $user->id,
            'message' => $message,
        ], $data);

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => $notificationData,
        ]);
        NotificationCreated::dispatch($notification);

        // Send email notification if enabled
        if ($this->settingsService->get('notifications_email', true)) {
            $this->sendEmailNotification($user, $type, $message, $notificationData);
        }
    }

    protected function sendEmailNotification(User $user, string $type, string $message, array $data): void
    {
        try {
            // In a real implementation, you would send an actual email
            // For now, we'll just log it
            Log::info("Email notification sent to {$user->email}", [
                'type' => $type,
                'message' => $message,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: '.$e->getMessage());
        }
    }

    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead(Auth::user());
    }

    public function markAsUnread(Notification $notification): void
    {
        $notification->markAsUnread();
    }

    public function archive(Notification $notification): void
    {
        $notification->archive();
    }

    public function unarchive(Notification $notification): void
    {
        $notification->unarchive();
    }

    public function getUnreadCount(User $user): int
    {
        return $user->notifications()->unread()->count();
    }

    public function getUnarchivedNotifications(User $user, int $limit = 10)
    {
        return $user->notifications()->unarchived()->latest()->limit($limit)->get();
    }
}
