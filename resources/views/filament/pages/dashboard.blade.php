<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Dashboard Stats Widgets --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @livewire(\App\Filament\Widgets\DashboardStats::class)
        </div>

        {{-- Charts and Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @livewire(\App\Filament\Widgets\CctvStatusChart::class)
            @livewire(\App\Filament\Widgets\CctvOperationalTable::class)
        </div>

        {{-- Alerts --}}
        <div class="grid grid-cols-1 gap-6">
            @livewire(\App\Filament\Widgets\OfflineAlerts::class)
        </div>
    </div>
</x-filament-panels::page>
