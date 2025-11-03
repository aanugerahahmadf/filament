<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use App\Filament\Exports\RoomExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRooms extends ManageRecords
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Room'),
            ExportAction::make()
                ->exporter(RoomExporter::class)
                ->label('Export Room'),
        ];
    }

        public function getPollingInterval(): ?string
    {
        return '5s';
    }
}
