<?php

namespace App\Filament\Resources\Buildings\Pages;

use App\Filament\Resources\Buildings\BuildingResource;
use App\Filament\Resources\Buildings\Widgets\BuildingStatsChart;
use App\Filament\Exports\BuildingExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListBuildings extends ListRecords
{
    protected static string $resource = BuildingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(BuildingExporter::class)
                ->columnMapping(false),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BuildingStatsChart::class,
        ];
    }
}
