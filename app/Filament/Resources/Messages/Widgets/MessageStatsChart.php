<?php

namespace App\Filament\Resources\Messages\Widgets;

use App\Models\Message;
use Filament\Widgets\ChartWidget;

class MessageStatsChart extends ChartWidget
{
    protected ?string $heading = 'Message Statistics';

    protected ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get message statistics
        $totalMessages = Message::count();
        $unreadMessages = Message::whereNull('read_at')->count();
        $deliveredMessages = Message::whereNotNull('delivered_at')->count();
        $editedMessages = Message::where('is_edited', true)->count();

        return [
            'labels' => ['Total Messages', 'Unread', 'Delivered', 'Edited'],
            'datasets' => [
                [
                    'label' => 'Message Statistics',
                    'data' => [$totalMessages, $unreadMessages, $deliveredMessages, $editedMessages],
                    'backgroundColor' => ['#10B981', '#EF4444', '#8B5CF6', '#F59E0B'],
                    'borderColor' => ['#10B981', '#EF4444', '#8B5CF6', '#F59E0B'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
