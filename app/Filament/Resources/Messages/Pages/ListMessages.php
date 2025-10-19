<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use App\Filament\Resources\Messages\Widgets\MessageStatsChart;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getPollingInterval(): ?string
    {
        return '5s';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MessageStatsChart::class,
        ];
    }
}
