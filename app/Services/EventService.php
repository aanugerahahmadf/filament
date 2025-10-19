<?php

namespace App\Services;

use App\Events\CctvStatusUpdated;
use App\Models\Alert;
use App\Models\Cctv;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Event;

class EventService
{
    public function dispatchCctvStatusUpdated(Cctv $cctv): void
    {
        Event::dispatch(new CctvStatusUpdated($cctv));
    }

    public function dispatchMaintenanceCreated(Maintenance $maintenance): void
    {
        Event::dispatch('maintenance.created', $maintenance);
    }

    public function dispatchMaintenanceUpdated(Maintenance $maintenance): void
    {
        Event::dispatch('maintenance.updated', $maintenance);
    }

    public function dispatchMaintenanceCompleted(Maintenance $maintenance): void
    {
        Event::dispatch('maintenance.completed', $maintenance);
    }

    public function dispatchAlertCreated(Alert $alert): void
    {
        Event::dispatch('alert.created', $alert);
    }

    public function dispatchAlertUpdated(Alert $alert): void
    {
        Event::dispatch('alert.updated', $alert);
    }

    public function dispatchAlertResolved(Alert $alert): void
    {
        Event::dispatch('alert.resolved', $alert);
    }

    public function dispatchAlertAcknowledged(Alert $alert): void
    {
        Event::dispatch('alert.acknowledged', $alert);
    }

    public function listen(string $event, callable $callback): void
    {
        Event::listen($event, $callback);
    }

    public function subscribe(string $subscriber): void
    {
        Event::subscribe($subscriber);
    }

    public function forget(string $event): void
    {
        Event::forget($event);
    }

    public function flush(): void
    {
        Event::flush();
    }

    public function hasListeners(string $event): bool
    {
        return Event::hasListeners($event);
    }
}
