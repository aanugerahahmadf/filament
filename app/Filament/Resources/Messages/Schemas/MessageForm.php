<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\User;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('from_user_id')
                    ->relationship('fromUser', 'name')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->label('From User'),
                Select::make('to_user_id')
                    ->relationship('toUser', 'name')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->label('To User'),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull()
                    ->label('Message Content'),
                TextInput::make('subject')
                    ->label('Subject'),
                TextInput::make('type')
                    ->required()
                    ->default('message')
                    ->label('Message Type'),
                TextInput::make('priority')
                    ->required()
                    ->default('medium')
                    ->label('Priority'),
                DateTimePicker::make('read_at')
                    ->label('Read At'),
                DateTimePicker::make('delivered_at')
                    ->label('Delivered At'),
                DateTimePicker::make('archived_at')
                    ->label('Archived At'),
            ]);
    }
}
