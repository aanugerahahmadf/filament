<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Exports\ContactExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageContacts extends ManageRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Contact'),
            ExportAction::make()
                ->exporter(ContactExporter::class)
                ->label('Export Contact'),

        ];
    }

    public function getPollingInterval(): ?string
    {
        return '5s';
    }
}
