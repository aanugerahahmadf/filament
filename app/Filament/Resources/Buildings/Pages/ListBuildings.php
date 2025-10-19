<?php

namespace App\Filament\Resources\Buildings\Pages;

use App\Filament\Resources\Buildings\BuildingResource;
use App\Filament\Resources\Buildings\Widgets\BuildingStatsChart;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBuildings extends ListRecords
{
    protected static string $resource = BuildingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BuildingStatsChart::class,
        ];
    }
}
