<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use App\Filament\Exports\MessageExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMessages extends ManageRecords
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Message'),
            ExportAction::make()
                ->exporter(MessageExporter::class)
                ->label('Export Message'),
        ];
    }

    public function getPollingInterval(): ?string
    {
        return '5s';
    }
}
