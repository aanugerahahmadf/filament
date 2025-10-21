<?php

namespace App\Filament\Resources\Rooms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;

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
                    ->required()
                    ->live(),
                TextInput::make('name')
                    ->required(),
                Grid::make()
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->minValue(-90)
                            ->maxValue(90)
                            ->placeholder('e.g. -6.917464')
                            ->label('Latitude')
                            ->readOnly()
                            ->default(fn ($get) => $get('building_id') ? \App\Models\Building::find($get('building_id'))?->latitude : null),
                        TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->minValue(-180)
                            ->maxValue(180)
                            ->placeholder('e.g. 108.619123')
                            ->label('Longitude')
                            ->readOnly()
                            ->default(fn ($get) => $get('building_id') ? \App\Models\Building::find($get('building_id'))?->longitude : null),
                    ])
                    ->columns(2),
                TextInput::make('marker_icon')
                    ->label('Marker Icon URL (opsional)')
                    ->placeholder('https://.../icon.png')
                    ->default('https://cdn.jsdelivr.net/gh/atisawd/boxicons/svg/solid/bxs-cctv.svg')
                    ->helperText('Kosongkan untuk pakai ikon CCTV bulat bawaan. Jika diisi, gunakan URL ikon kustom.'),
            ]);
    }
}
