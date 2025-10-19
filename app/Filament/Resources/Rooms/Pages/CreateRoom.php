<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Rooms')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
