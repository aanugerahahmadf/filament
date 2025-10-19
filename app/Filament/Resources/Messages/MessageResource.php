<?php

namespace App\Filament\Resources\Messages;


use App\Filament\Resources\Messages\Pages\ChatInterface;
use App\Filament\Resources\Messages\Pages\CreateMessage;
use App\Filament\Resources\Messages\Pages\EditMessage;
use App\Filament\Resources\Messages\Pages\ListMessages;
use App\Filament\Resources\Messages\Pages\ViewMessage;
use App\Filament\Resources\Messages\Schemas\MessageForm;
use App\Filament\Resources\Messages\Schemas\MessageInfolist;
use App\Filament\Resources\Messages\Tables\MessagesTable;
use App\Models\Message;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
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
        return MessageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MessagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMessages::route('/'),
            'create' => CreateMessage::route('/create'),
            'view' => ViewMessage::route('/{record}'),
            'edit' => EditMessage::route('/{record}/edit'),
            'chat' => ChatInterface::route('/chat'),
        ];
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
