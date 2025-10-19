<?php

namespace App\Providers;

use App\Models\Cctv;
use App\Services\CctvService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class CctvStatusServiceProvider extends ServiceProvider
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
        // Schedule CCTV status checks
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Check status of all CCTVs every 5 minutes
            $schedule->call(function () {
                $cctvService = app(CctvService::class);
                $cctvs = Cctv::all();

                foreach ($cctvs as $cctv) {
                    $cctvService->checkCctvStatus($cctv);
                }
            })->everyFiveMinutes();

            // Clean up old alerts every day
            $schedule->call(function () {
                // Delete resolved alerts older than 30 days
                \App\Models\Alert::resolved()
                    ->where('resolved_at', '<=', now()->subDays(30))
                    ->delete();

                // Delete suppressed alerts older than 7 days
                \App\Models\Alert::suppressed()
                    ->where('suppressed_at', '<=', now()->subDays(7))
                    ->delete();
            })->daily();

            // Clean up old recordings every week
            $schedule->call(function () {
                // Mark recordings older than 30 days as archived
                \App\Models\Recording::active()
                    ->where('ended_at', '<=', now()->subDays(30))
                    ->update(['status' => 'archived']);
            })->weekly();
        });
    }
}
