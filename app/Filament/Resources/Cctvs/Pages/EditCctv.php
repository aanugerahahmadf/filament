<?php

namespace App\Filament\Resources\Cctvs\Pages;

use App\Filament\Resources\Cctvs\CctvResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditCctv extends EditRecord
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
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
