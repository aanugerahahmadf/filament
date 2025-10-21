<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AdditionalLineChart extends ChartWidget
{
    protected ?string $heading = 'Additional Metrics';

    protected ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Sample data for demonstration
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
        $data = [65, 59, 80, 81, 56, 55, 40];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Sample Data',
                    'data' => $data,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'labels' => [
                        'font' => [
                            'size' => 10,
                        ],
                        'padding' => 10,
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'font' => [
                            'size' => 9,
                        ],
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'ticks' => [
                        'font' => [
                            'size' => 9,
                        ],
                    ],
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
            ],
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
