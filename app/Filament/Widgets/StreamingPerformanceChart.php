<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class StreamingPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Streaming Performance';

    protected ?string $pollingInterval = '10s';

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Generate data for the last 7 days
        $dates = [];
        $latencyData = [];
        $bandwidthData = [];
        $uptimeData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('M d');

            // Simulate streaming performance metrics
            $latency = rand(50, 300); // Latency in ms
            $bandwidth = rand(500, 2000); // Bandwidth in Kbps
            $uptime = rand(95, 100); // Uptime percentage

            $latencyData[] = $latency;
            $bandwidthData[] = $bandwidth;
            $uptimeData[] = $uptime;
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Latency (ms)',
                    'data' => $latencyData,
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => false,
                    'tension' => 0.3,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Bandwidth (Kbps)',
                    'data' => $bandwidthData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => false,
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                ],
                [
                    'label' => 'Uptime (%)',
                    'data' => $uptimeData,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => false,
                    'tension' => 0.3,
                    'borderWidth' => 2,
                    'yAxisID' => 'y2',
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
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'padding' => 15,
                        'usePointStyle' => true,
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Date',
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold',
                        ],
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 10,
                        ],
                    ],
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Latency (ms)',
                        'font' => [
                            'size' => 11,
                            'weight' => 'bold',
                        ],
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 10,
                        ],
                    ],
                    'grid' => [
                        'color' => 'rgba(245, 158, 11, 0.1)',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Bandwidth (Kbps)',
                        'font' => [
                            'size' => 11,
                            'weight' => 'bold',
                        ],
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 10,
                        ],
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
                'y2' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Uptime (%)',
                        'font' => [
                            'size' => 11,
                            'weight' => 'bold',
                        ],
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 10,
                        ],
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ];
    }
}
