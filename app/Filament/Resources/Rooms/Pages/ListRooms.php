<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use App\Filament\Resources\Rooms\Widgets\RoomStatusChart;
use App\Filament\Exports\RoomExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(RoomExporter::class)
                ->columnMapping(false),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoomStatusChart::class,
        ];
    }
}
