<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserActivityChart extends ChartWidget
{
    protected ?string $heading = 'User Activity Trends';

    protected ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Generate data for the last 7 days
        $dates = [];
        $activeUsers = [];
        $newUsers = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('M d');

            // In a real application, you would query historical data
            // For now, we'll generate sample data
            $totalUsers = User::count();

            // Simulate active users (70-90% of total users)
            $activePercentage = rand(70, 90);
            $activeCount = intval($totalUsers * $activePercentage / 100);
            $newCount = rand(0, 5); // Simulate new users

            $activeUsers[] = $activeCount;
            $newUsers[] = $newCount;
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $activeUsers,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'New Users',
                    'data' => $newUsers,
                    'borderColor' => '#8B5CF6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
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
