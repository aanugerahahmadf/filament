<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use App\Filament\Resources\Messages\Widgets\MessageStatsChart;
use App\Filament\Exports\MessageExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(MessageExporter::class)
                ->columnMapping(false),
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
