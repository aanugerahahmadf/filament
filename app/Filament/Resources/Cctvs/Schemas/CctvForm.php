<?php

namespace App\Filament\Resources\Cctvs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->relationship('room', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('model'),
                TextInput::make('serial_number'),
                TextInput::make('firmware_version'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('stream_username'),
                TextInput::make('stream_password')
                    ->password(),
                TextInput::make('ip_rtsp')
                    ->required(),
                TextInput::make('port')
                    ->required()
                    ->numeric()
                    ->default(554),
                TextInput::make('resolution'),
                TextInput::make('fps')
                    ->required()
                    ->numeric()
                    ->default(30),
                TextInput::make('recording_schedule'),
                Select::make('status')
                    ->options(['online' => 'Online', 'offline' => 'Offline', 'maintenance' => 'Maintenance'])
                    ->default('offline')
                    ->required(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('hls_path'),
                DateTimePicker::make('last_seen_at'),
            ]);
    }
}
