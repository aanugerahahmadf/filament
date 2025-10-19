<?php

namespace App\Filament\Resources\Cctvs\Tables;

use App\Exports\CctvExport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

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
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('firmware_version')
                    ->searchable(),
                TextColumn::make('stream_username')
                    ->searchable(),
                TextColumn::make('ip_rtsp')
                    ->searchable(),
                TextColumn::make('port')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state))
                    ->sortable(),
                TextColumn::make('resolution')
                    ->searchable(),
                TextColumn::make('fps')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state))
                    ->sortable(),
                TextColumn::make('recording_schedule')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('latitude')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 6))
                    ->sortable(),
                TextColumn::make('longitude')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 6))
                    ->sortable(),
                TextColumn::make('hls_path')
                    ->searchable(),
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
                    ->iconButton()
                    ->color('info')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                EditAction::make()
                    ->iconButton()
                    ->color('warning')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                DeleteAction::make()
                    ->iconButton()
                    ->color('danger')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                Action::make('live_stream')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->size('xl')
                    ->iconButton()
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ])
                    ->url(fn ($record) => \App\Filament\Resources\Cctvs\CctvResource::getUrl('live-stream', ['record' => $record])),
            ])
            ->toolbarActions([
                Action::make('export')
                    ->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $cctvs = \App\Models\Cctv::with(['building', 'room'])->get();

                        return Excel::download(new CctvExport($cctvs), 'cctvs-'.now()->format('Y-m-d-H-i-s').'.xlsx');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
