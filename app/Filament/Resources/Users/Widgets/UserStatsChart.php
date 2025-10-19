<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserStatsChart extends ChartWidget
{
    protected ?string $heading = 'User Statistics';

    protected ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get user statistics
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $usersWith2FA = User::where('google2fa_enabled', true)->count();
        $usersWithAvatar = User::whereNotNull('avatar')->orWhereNotNull('avatar_url')->count();

        return [
            'labels' => ['Total Users', 'Verified', '2FA Enabled', 'With Avatar'],
            'datasets' => [
                [
                    'label' => 'User Statistics',
                    'data' => [$totalUsers, $verifiedUsers, $usersWith2FA, $usersWithAvatar],
                    'backgroundColor' => ['#10B981', '#8B5CF6', '#06B6D4', '#F59E0B'],
                    'borderColor' => ['#10B981', '#8B5CF6', '#06B6D4', '#F59E0B'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}