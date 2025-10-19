<?php

namespace App\Filament\Resources\Buildings\Widgets;

use App\Models\Building;
use Filament\Widgets\ChartWidget;

class BuildingStatsChart extends ChartWidget
{
    protected ?string $heading = 'Buildings Overview';

    protected ?string $pollingInterval = '10s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get building count and related data
        $buildingCount = Building::count();
        $buildingsWithRooms = Building::has('rooms')->count();
        $buildingsWithCctvs = Building::has('cctvs')->count();

        return [
            'labels' => ['Total Buildings', 'With Rooms', 'With CCTV'],
            'datasets' => [
                [
                    'label' => 'Building Statistics',
                    'data' => [$buildingCount, $buildingsWithRooms, $buildingsWithCctvs],
                    'backgroundColor' => ['#10B981', '#8B5CF6', '#06B6D4'],
                    'borderColor' => ['#10B981', '#8B5CF6', '#06B6D4'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
