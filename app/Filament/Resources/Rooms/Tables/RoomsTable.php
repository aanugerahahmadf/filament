<?php

namespace App\Filament\Resources\Rooms\Tables;

use App\Exports\RoomExport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('building.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('floor')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state))
                    ->sortable(),
                TextColumn::make('capacity')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state))
                    ->sortable(),
                TextColumn::make('latitude')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 6))
                    ->sortable(),
                TextColumn::make('longitude')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state, 6))
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
                    ->size('lg'),
                EditAction::make()
                    ->iconButton()
                    ->color('warning')
                    ->size('lg'),
                DeleteAction::make()
                    ->iconButton()
                    ->color('danger')
                    ->size('lg'),
            ])
            ->toolbarActions([
                Action::make('export')
                    ->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $rooms = \App\Models\Room::with('building')->get();

                        return Excel::download(new RoomExport($rooms), 'rooms-'.now()->format('Y-m-d-H-i-s').'.xlsx');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
