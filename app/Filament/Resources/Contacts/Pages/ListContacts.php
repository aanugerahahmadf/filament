<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Resources\Contacts\Widgets\ContactStatsChart;
use App\Filament\Exports\ContactExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(ContactExporter::class)
                ->columnMapping(false),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ContactStatsChart::class,
        ];
    }
}
