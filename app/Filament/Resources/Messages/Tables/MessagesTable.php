<?php

namespace App\Filament\Resources\Messages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->columns([
                TextColumn::make('fromUser.name')
                    ->searchable()
                    ->label('From'),
                TextColumn::make('toUser.name')
                    ->searchable()
                    ->label('To'),
                TextColumn::make('body')
                    ->limit(50)
                    ->searchable()
                    ->label('Message'),
                TextColumn::make('subject')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('priority')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('read_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Read At'),
                TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Delivered At'),
                IconColumn::make('is_read')
                    ->boolean()
                    ->label('Read')
                    ->toggleable(isToggledHiddenByDefault: false),
                IconColumn::make('is_delivered')
                    ->boolean()
                    ->label('Delivered')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('from_user_id')
                    ->relationship('fromUser', 'name')
                    ->label('From User')
                    ->searchable(),
                SelectFilter::make('to_user_id')
                    ->relationship('toUser', 'name')
                    ->label('To User')
                    ->searchable(),
                SelectFilter::make('type')
                    ->options([
                        'message' => 'Message',
                        'notification' => 'Notification',
                        'alert' => 'Alert',
                    ]),
                SelectFilter::make('read')
                    ->options([
                        '1' => 'Read',
                        '0' => 'Unread',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === '1') {
                            return $query->whereNotNull('read_at');
                        } elseif ($data['value'] === '0') {
                            return $query->whereNull('read_at');
                        }

                        return $query;
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->button()
                    ->color('info')
                    ->size('lg'),
                EditAction::make()
                    ->button()
                    ->color('warning')
                    ->size('lg'),
                DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->size('lg'),
                Action::make('chatInterface')
                    ->label('Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->size('lg')
                    ->button()
                    ->url(fn ($record) => route('messages.conversation', $record->id)),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
