<?php

namespace App\Filament\Resources\Cctvs\Widgets;

use App\Models\Cctv;
use Filament\Widgets\ChartWidget;

class CctvStatusChart extends ChartWidget
{
    protected ?string $heading = 'CCTV Status Distribution';

    protected ?string $pollingInterval = '10s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get CCTV count by status
        $online = Cctv::where('status', 'online')->count();
        $offline = Cctv::where('status', 'offline')->count();
        $maintenance = Cctv::where('status', 'maintenance')->count();

        return [
            'labels' => ['Online', 'Offline', 'Maintenance'],
            'datasets' => [
                [
                    'label' => 'CCTV Status',
                    'data' => [$online, $offline, $maintenance],
                    'fill' => false,
                    'borderColor' => '#3B82F6',
                    'tension' => 0.1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
