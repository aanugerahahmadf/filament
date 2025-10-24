<?php

namespace App\Filament\Resources\Cctvs\Tables;

use App\Filament\Exports\CctvExporter;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CctvsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->columns([
                TextColumn::make('building.name')
                    ->searchable(),
                TextColumn::make('room.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stream_username')
                    ->searchable(),
                TextColumn::make('ip_rtsp')
                    ->searchable(),
                TextColumn::make('port')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state))
                    ->sortable(),
                TextColumn::make('connection_type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'wired' ? 'success' : 'info'),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('recording')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                TextColumn::make('last_seen_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->button()
                    ->color('info')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                EditAction::make()
                    ->button()
                    ->color('warning')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                Action::make('live_stream')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->size('xl')
                    ->button()
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ])
                    ->url(fn ($record) => \App\Filament\Resources\Cctvs\CctvResource::getUrl('live-stream', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
