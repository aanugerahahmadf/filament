<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('from_user_id')
                    ->relationship('fromUser', 'name')
                    ->required()
                    ->searchable()
                    ->label('From User')
                    ->visible(fn () => Auth::user() && Auth::user()->hasRole('Super Admin'))
                    ->default(fn () => Auth::user() && Auth::user()->hasRole('Super Admin') ? Auth::id() : null),
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
                    ->label('Message Type')
                    ->readOnly(),
                TextInput::make('priority')
                    ->required()
                    ->default('medium')
                    ->label('Priority')
                    ->readOnly(),
                DateTimePicker::make('read_at')
                    ->label('Read At')
                    ->readOnly(),
                DateTimePicker::make('delivered_at')
                    ->label('Delivered At')
                    ->default(fn () => now())
                    ->readOnly(),
                DateTimePicker::make('archived_at')
                    ->label('Archived At')
                    ->readOnly(),
            ]);
    }
}
