<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Messages')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            EditAction::make(),
        ];
    }
}
