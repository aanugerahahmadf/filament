<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Exports\UserExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New User'),
            ExportAction::make()
                ->exporter(UserExporter::class)
                ->label('Export User'),

        ];
    }

    public function getPollingInterval(): ?string
    {
        return '5s';
    }
}
