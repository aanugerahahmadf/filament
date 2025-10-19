<?php

namespace App\Filament\Resources\Rooms\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('building_id')
                    ->relationship('building', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->searchPrompt('Cari gedung...')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('marker_icon')
                    ->label('Marker Icon URL (opsional)')
                    ->placeholder('https://.../icon.png')
                    ->default('https://cdn.jsdelivr.net/gh/atisawd/boxicons/svg/solid/bxs-cctv.svg')
                    ->helperText('Kosongkan untuk pakai ikon CCTV bulat bawaan. Jika diisi, gunakan URL ikon kustom.'),

            ]);
    }
}
