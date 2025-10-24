<?php

namespace App\Providers;

use App\Filament\Livewire\CustomDatabaseNotifications;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class DatabaseNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register our custom database notifications component
        Livewire::component('database-notifications', CustomDatabaseNotifications::class);
    }
}
