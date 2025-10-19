<?php

namespace App\Filament\Resources\Buildings\Tables;

use App\Exports\BuildingExport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class BuildingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),

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
                        $buildings = \App\Models\Building::all();

                        return Excel::download(new BuildingExport($buildings), 'buildings-'.now()->format('Y-m-d-H-i-s').'.xlsx');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
