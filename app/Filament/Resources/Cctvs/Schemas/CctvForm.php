<?php

namespace App\Filament\Resources\Cctvs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CctvForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('building_id')
                    ->relationship('building', 'name')
                    ->required(),
                Select::make('room_id')
                    ->relationship('room', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('stream_username')
                    ->default('admin'),
                TextInput::make('stream_password')
                    ->password()
                    ->default('password.123')
                    ->revealable(),
                TextInput::make('ip_rtsp')
                    ->required(),
                TextInput::make('port')
                    ->required()
                    ->numeric()
                    ->default(554),
                Select::make('connection_type')
                    ->options([
                        'wired' => 'Wired (LAN Cable)',
                        'wireless' => 'Wireless (Wi-Fi)'
                    ])
                    ->default('wired')
                    ->required(),
                Select::make('status')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                        'maintenance' => 'Maintenance'
                    ])
                    ->default('offline')
                    ->required()
                    ->disabled(),
                DateTimePicker::make('last_seen_at')
                    ->disabled(), // Make it disabled since it should be automatically updated
            ]);
    }
}
