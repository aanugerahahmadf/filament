<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->avatar(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('username')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->revealable(),
                TextInput::make('place_of_birth')
                    ->label('Tempat Lahir')
                    ->maxLength(255),
                TextInput::make('city')
                    ->label('Kota')
                    ->maxLength(255),
                DatePicker::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                TextInput::make('phone_number')
                    ->label('No. Handphone / WhatsApp')
                    ->tel()
                    ->maxLength(20),
                TextInput::make('status')
                    ->required()
                    ->default('offline'),
                TextInput::make('department'),
                TextInput::make('position'),
                DateTimePicker::make('last_seen_at'),
                DateTimePicker::make('email_verified_at'),
            ]);
    }
}
