<?php

namespace App\Filament\Resources\Buildings\Pages;

use App\Filament\Resources\Buildings\BuildingResource;
use App\Filament\Exports\BuildingExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBuildings extends ManageRecords
{
    protected static string $resource = BuildingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Building'),
            ExportAction::make()
                ->exporter(BuildingExporter::class)
                ->label('Export Building'),
        ];
    }

     public function getPollingInterval(): ?string
    {
        return '5s';
    }
}
