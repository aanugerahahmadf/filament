<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('whatsapp'),
                TextInput::make('address'),
                TextInput::make('instagram'),
                TextInput::make('facebook'),
                TextInput::make('linkedin'),
                TextInput::make('position'),
                TextInput::make('department'),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
