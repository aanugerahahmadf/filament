<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditMessage extends EditRecord
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
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
