<?php

namespace App\Observers;

use App\Events\NotificationCreated;
use App\Models\Notification;

class NotificationObserver
{
    /**
     * Handle the Notification "created" event.
     */
    public function created(Notification $notification): void
    {
        // Broadcast the notification creation event
        NotificationCreated::dispatch($notification);
    }

    /**
     * Handle the Notification "updated" event.
     */
    public function updated(Notification $notification): void
    {
        // You can add logic here if needed when a notification is updated
    }

    /**
     * Handle the Notification "deleted" event.
     */
    public function deleted(Notification $notification): void
    {
        // You can add logic here if needed when a notification is deleted
    }
}