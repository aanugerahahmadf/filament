<?php

namespace App\Filament\Resources\Contacts\Tables;

use App\Exports\ContactExport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('instagram')
                    ->searchable(),
                TextColumn::make('facebook')
                    ->searchable(),
                TextColumn::make('linkedin')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
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
                        $contacts = \App\Models\Contact::all();

                        return Excel::download(new ContactExport($contacts), 'contacts-'.now()->format('Y-m-d-H-i-s').'.xlsx');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
