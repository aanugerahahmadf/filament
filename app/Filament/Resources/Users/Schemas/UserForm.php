<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->firstDayOfWeek(1)
                    ->weekStartsOnMonday()
                    ->maxDate(now()->subYears(18))
                    ->minDate(now()->subYears(100))
                    ->placeholder('Select birth date')
                    ->hint('Must be at least 18 years old')
                    ->hintIcon('heroicon-o-information-circle')
                    ->required(),
                TextInput::make('phone_number')
                    ->label('No. Handphone / WhatsApp')
                    ->tel()
                    ->maxLength(20),
                TextInput::make('status')
                    ->required()
                    ->default('offline'),
                TextInput::make('department'),
                TextInput::make('position'),
                TextInput::make('avatar'),
                DateTimePicker::make('last_seen_at'),
                DateTimePicker::make('email_verified_at'),
                Textarea::make('two_factor_secret')
                    ->columnSpanFull(),
                Textarea::make('two_factor_recovery_codes')
                    ->columnSpanFull(),
                DateTimePicker::make('two_factor_confirmed_at'),
            ]);
    }
}
