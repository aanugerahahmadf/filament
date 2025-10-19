<?php

namespace App\Filament\Resources\Rooms\Widgets;

use App\Models\Room;
use App\Models\Building;
use Filament\Widgets\ChartWidget;

class RoomStatusChart extends ChartWidget
{
    protected ?string $heading = 'Rooms by Building';

    protected ?string $pollingInterval = '10s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get room count by building
        $buildings = Building::withCount('rooms')->get();

        // Handle case where there are no buildings yet
        if ($buildings->isEmpty()) {
            return [
                'labels' => ['No Buildings'],
                'datasets' => [
                    [
                        'label' => 'Number of Rooms',
                        'data' => [0],
                        'backgroundColor' => ['#9CA3AF'],
                        'borderColor' => ['#9CA3AF'],
                    ],
                ],
            ];
        }

        $buildingNames = $buildings->pluck('name')->toArray();
        $roomCounts = $buildings->pluck('rooms_count')->toArray();

        return [
            'labels' => $buildingNames,
            'datasets' => [
                [
                    'label' => 'Number of Rooms',
                    'data' => $roomCounts,
                    'backgroundColor' => ['#10B981', '#EF4444', '#F59E0B', '#8B5CF6', '#06B6D4'],
                    'borderColor' => ['#10B981', '#EF4444', '#F59E0B', '#8B5CF6', '#06B6D4'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
