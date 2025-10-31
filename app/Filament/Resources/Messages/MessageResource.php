<?php

namespace App\Filament\Resources\Messages;

use App\Filament\Resources\Messages\Pages\ManageMessages;
use App\Filament\Resources\Messages\Pages\ChatInterface;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;
class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $recordTitleAttribute = 'subject';

    protected static string|UnitEnum|null $navigationGroup = 'Communication';

    protected static ?string $navigationLabel = 'Message';

    protected static ?string $modelLabel = 'Message';

    protected static ?string $pluralModelLabel = 'Message';

    protected static ?int $navigationSort = 2;


    public static function form(Schema $schema): Schema
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
                MarkdownEditor::make('body')
                    ->required()
                    ->columnSpanFull()
                    ->label('Message Content'),
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

    public static function table(Table $table): Table
    {
        return $table
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
                    ->markdown()
                    ->label('Message'),
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
                ForceDeleteAction::make()
                     ->successNotification(null),
                RestoreAction::make()
                     ->successNotification(null),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMessages::route('/'),
             'chat' => ChatInterface::route('/chat'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

        public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

        public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of messages';
    }
}
