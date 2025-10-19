<?php

namespace App\Filament\Resources\Cctvs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CctvInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('building.name')
                    ->label('Building'),
                TextEntry::make('room.name')
                    ->label('Room')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('model')
                    ->placeholder('-'),
                TextEntry::make('serial_number')
                    ->placeholder('-'),
                TextEntry::make('firmware_version')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('stream_username')
                    ->placeholder('-'),
                TextEntry::make('ip_rtsp'),
                TextEntry::make('port')
                    ->numeric(),
                TextEntry::make('resolution')
                    ->placeholder('-'),
                TextEntry::make('fps')
                    ->numeric(),
                TextEntry::make('recording_schedule')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('hls_path')
                    ->placeholder('-'),
                TextEntry::make('last_seen_at')
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
