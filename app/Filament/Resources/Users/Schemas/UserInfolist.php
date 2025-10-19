<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('password')
                    ->placeholder('-'),
                TextEntry::make('place_of_birth')
                    ->label('Tempat Lahir')
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->label('Kota')
                    ->placeholder('-'),
                TextEntry::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('phone_number')
                    ->label('No. Handphone / WhatsApp')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('department')
                    ->placeholder('-'),
                TextEntry::make('position')
                    ->placeholder('-'),
                TextEntry::make('avatar')
                    ->placeholder('-'),
                TextEntry::make('last_seen_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('two_factor_secret')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('two_factor_recovery_codes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('two_factor_confirmed_at')
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
