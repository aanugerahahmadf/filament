<?php

namespace App\Filament\Widgets;

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Room;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $totalBuildings = Building::count();
        $totalRooms = Room::count();
        $online = Cctv::where('status', 'online')->count();
        $offline = Cctv::where('status', 'offline')->count();
        $maintenance = Cctv::where('status', 'maintenance')->count();
        $users = User::count();

        return [
            Stat::make('Buildings', (string) $totalBuildings),
            Stat::make('Rooms', (string) $totalRooms),
            Stat::make('CCTV Online', (string) $online)
                ->description('Offline '.$offline.' • Maint '.$maintenance)
                ->color('success'),
            Stat::make('Users', (string) $users),
        ];
    }
}
