<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('whatsapp')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('instagram')
                    ->placeholder('-'),
                TextEntry::make('facebook')
                    ->placeholder('-'),
                TextEntry::make('linkedin')
                    ->placeholder('-'),
                TextEntry::make('position')
                    ->placeholder('-'),
                TextEntry::make('department')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
