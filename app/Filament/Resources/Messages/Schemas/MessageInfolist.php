<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('fromUser.name')
                    ->label('From User'),
                TextEntry::make('toUser.name')
                    ->label('To User'),
                TextEntry::make('subject')
                    ->placeholder('-'),
                TextEntry::make('body')
                    ->columnSpanFull()
                    ->formatStateUsing(fn (string $state): string => nl2br(e($state))),
                TextEntry::make('type'),
                TextEntry::make('priority'),
                TextEntry::make('read_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('archived_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
