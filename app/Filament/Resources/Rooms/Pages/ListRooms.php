<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use App\Filament\Resources\Rooms\Widgets\RoomStatusChart;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoomStatusChart::class,
        ];
    }
}
