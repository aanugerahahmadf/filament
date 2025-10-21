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
                TextEntry::make('stream_username')
                    ->placeholder('-'),
                TextEntry::make('ip_rtsp'),
                TextEntry::make('port')
                    ->numeric(),
                TextEntry::make('connection_type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'wired' ? 'success' : 'info'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('recording')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
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
