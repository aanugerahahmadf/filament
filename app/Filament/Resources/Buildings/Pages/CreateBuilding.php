<?php

namespace App\Filament\Resources\Buildings\Pages;

use App\Filament\Resources\Buildings\BuildingResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateBuilding extends CreateRecord
{
    protected static string $resource = BuildingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Buildings')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
