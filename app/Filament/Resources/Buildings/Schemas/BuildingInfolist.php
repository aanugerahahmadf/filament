<?php

namespace App\Filament\Resources\Buildings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BuildingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('marker_icon_path')
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
