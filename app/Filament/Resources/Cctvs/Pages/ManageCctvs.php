<?php

namespace App\Filament\Resources\Cctvs\Pages;

use App\Filament\Resources\Cctvs\CctvResource;
use App\Filament\Exports\CctvExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCctvs extends ManageRecords
{
    protected static string $resource = CctvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
                 ExportAction::make()
                ->exporter(CctvExporter::class)
                ->columnMapping(false),
        ];
    }

    public function getPollingInterval(): ?string
    {
        return '5s';
    }
}
