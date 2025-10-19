<?php

namespace App\Filament\Resources\Cctvs\Pages;

use App\Filament\Resources\Cctvs\CctvResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewCctv extends ViewRecord
{
    protected static string $resource = CctvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to CCTVs')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            EditAction::make(),
        ];
    }
}
