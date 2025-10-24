<?php

namespace App\Filament\Livewire;

use App\Models\Notification;
use Filament\Notifications\Livewire\DatabaseNotifications as BaseDatabaseNotifications;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomDatabaseNotifications extends BaseDatabaseNotifications
{
    public function mount(): void
    {
        Log::info('CustomDatabaseNotifications mounted');
        parent::mount();
    }

    public function getNotifications(): DatabaseNotificationCollection | Paginator
    {
        Log::info('CustomDatabaseNotifications getNotifications called');

        // Get notifications using our custom model
        $query = $this->getNotificationsQuery();

        if (! $this->isPaginated()) {
            /** @phpstan-ignore-next-line */
            return $query->get();
        }

        return $query->simplePaginate(50, pageName: 'database-notifications-page');
    }

    public function getNotificationsQuery(): Builder | Relation
    {
        Log::info('CustomDatabaseNotifications getNotificationsQuery called');

        // Use our custom Notification model instead of the default one
        /** @phpstan-ignore-next-line */
        return Notification::where('user_id', $this->getUser()->id)
            ->where('data->format', 'filament')
            ->orderBy('created_at', 'desc');
    }

    public function getNotification($notification): FilamentNotification
    {
        Log::info('CustomDatabaseNotifications getNotification called', [
            'notification_type' => gettype($notification),
            'notification_class' => is_object($notification) ? get_class($notification) : null,
        ]);

        // Handle our custom Notification model
        if ($notification instanceof Notification) {
            Log::info('Handling custom Notification model');
            // Convert your custom Notification model to a Filament Notification
            $data = $notification->data;

            // Ensure the data has the required format for Filament notifications
            if (!isset($data['format'])) {
                $data['format'] = 'filament';
            }

            // Create a Filament notification from the data
            $filamentNotification = FilamentNotification::fromArray($data);
            $filamentNotification->date($this->formatNotificationDate($notification->created_at));

            return $filamentNotification;
        }

        // Handle Laravel's DatabaseNotification
        if ($notification instanceof DatabaseNotification) {
            Log::info('Handling Laravel DatabaseNotification');
            return parent::getNotification($notification);
        }

        // For array notifications (from toArray conversion)
        if (is_array($notification)) {
            Log::info('Handling array notification');
            // This is likely from our toArray conversion
            $data = $notification['data'] ?? [];

            if (!isset($data['format'])) {
                $data['format'] = 'filament';
            }

            $filamentNotification = FilamentNotification::fromArray($data);

            // Handle created_at from array
            $createdAt = $notification['created_at'] ?? now();
            $filamentNotification->date($this->formatNotificationDate($createdAt));

            return $filamentNotification;
        }

        // Fallback - try to convert to array and handle
        if (is_object($notification) && method_exists($notification, 'toArray')) {
            Log::info('Handling object notification with toArray method');
            $arrayData = $notification->toArray();
            return $this->getNotification($arrayData);
        }

        // Final fallback to parent implementation
        Log::warning('Falling back to parent getNotification - unexpected notification type', [
            'type' => gettype($notification),
            'class' => is_object($notification) ? get_class($notification) : null,
        ]);

        try {
            return parent::getNotification($notification);
        } catch (\TypeError $e) {
            Log::error('TypeError in parent::getNotification', [
                'message' => $e->getMessage(),
                'notification_type' => gettype($notification),
                'notification_class' => is_object($notification) ? get_class($notification) : null,
            ]);

            // Create a minimal notification as a last resort
            return FilamentNotification::make()
                ->title('Notification')
                ->body('Unable to display notification details');
        }
    }

    public function formatNotificationDate($date): string
    {
        $user = Auth::user();

        if (!$user) {
            return '';
        }

        /** @var \Illuminate\Support\Carbon $date */
        $date = $user->normalizeDateTime($date);

        if ($date->isToday()) {
            return __('filament-notifications::database.notifications.table.columns.created_at.date.today');
        }

        if ($date->isYesterday()) {
            return __('filament-notifications::database.notifications.table.columns.created_at.date.yesterday');
        }

        return $date->translatedFormat(__('filament-notifications::database.notifications.table.columns.created_at.date.formats.datetime'));
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        Log::info('CustomDatabaseNotifications render called');
        return parent::render();
    }
}
