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
