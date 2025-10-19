<?php

namespace App\Filament\Widgets;

use App\Models\Cctv;
use Filament\Widgets\ChartWidget;

class CctvStatusChart extends ChartWidget
{
    protected ?string $heading = 'Cctv Status Chart';

    protected ?string $pollingInterval = '5s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $online = Cctv::where('status', 'online')->count();
        $offline = Cctv::where('status', 'offline')->count();
        $maintenance = Cctv::where('status', 'maintenance')->count();

        return [
            'labels' => ['Online', 'Offline', 'Maintenance'],
            'datasets' => [
                [
                    'label' => 'CCTV Status',
                    'data' => [$online, $offline, $maintenance],
                    'backgroundColor' => ['#10B981', '#EF4444', '#F59E0B'],
                    'borderColor' => ['#10B981', '#EF4444', '#F59E0B'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
