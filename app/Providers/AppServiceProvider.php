<?php

namespace App\Providers;

use App\Events\MapDataChanged;
use App\Models\Alert;
use App\Models\Building;
use App\Models\Cctv;
use App\Models\Contact;
use App\Models\Maintenance;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Recording;
use App\Models\Room;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register audit observers
        Building::observe(AuditObserver::class);
        Room::observe(AuditObserver::class);
        Cctv::observe(AuditObserver::class);
        Contact::observe(AuditObserver::class);
        Message::observe(AuditObserver::class);
        Notification::observe(AuditObserver::class);
        Maintenance::observe(AuditObserver::class);
        Recording::observe(AuditObserver::class);
        Alert::observe(AuditObserver::class);

        // Broadcast map data changes so UI (maps/location) refresh live
        Building::created(fn ($m) => MapDataChanged::dispatch('building', 'created', ['id' => $m->id]));
        Building::updated(fn ($m) => MapDataChanged::dispatch('building', 'updated', ['id' => $m->id]));
        Building::deleted(fn ($m) => MapDataChanged::dispatch('building', 'deleted', ['id' => $m->id]));

        Room::created(fn ($m) => MapDataChanged::dispatch('room', 'created', ['id' => $m->id, 'building_id' => $m->building_id]));
        Room::updated(fn ($m) => MapDataChanged::dispatch('room', 'updated', ['id' => $m->id, 'building_id' => $m->building_id]));
        Room::deleted(fn ($m) => MapDataChanged::dispatch('room', 'deleted', ['id' => $m->id, 'building_id' => $m->building_id]));

        Cctv::created(fn ($m) => MapDataChanged::dispatch('cctv', 'created', ['id' => $m->id, 'room_id' => $m->room_id, 'building_id' => $m->building_id]));
        Cctv::updated(fn ($m) => MapDataChanged::dispatch('cctv', 'updated', ['id' => $m->id, 'room_id' => $m->room_id, 'building_id' => $m->building_id]));
        Cctv::deleted(fn ($m) => MapDataChanged::dispatch('cctv', 'deleted', ['id' => $m->id, 'room_id' => $m->room_id, 'building_id' => $m->building_id]));
    }
}
