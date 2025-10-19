<?php

namespace App\Filament\Resources\Cctvs\Pages;

use App\Filament\Resources\Cctvs\CctvResource;
use App\Filament\Resources\Cctvs\Widgets\CctvStatusChart;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCctvs extends ListRecords
{
    protected static string $resource = CctvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getPollingInterval(): ?string
    {
        return '5s';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CctvStatusChart::class,
        ];
    }
}
