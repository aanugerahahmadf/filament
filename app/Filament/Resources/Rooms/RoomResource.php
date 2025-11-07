<?php

namespace App\Filament\Resources\Rooms;

use App\Filament\Resources\Rooms\Pages\ManageRooms;
use App\Models\Room;
use App\Models\Building;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

     protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Playlist And Maps';

    protected static ?string $navigationLabel = 'Room';

    protected static ?string $modelLabel = 'Room';

    protected static ?string $pluralModelLabel = 'Room';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('building_id')
                    ->label('Building')
                    ->options(Building::pluck('name', 'id')->unique())
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->searchPrompt('Search Building...')
                    ->required()
                    ->live(),
                TextInput::make('name')
                    ->required(),
                Grid::make()
                    ->schema([
                        TextInput::make('latitude')
                            ->required(),
                        TextInput::make('longitude')
                            ->required(),
                    ])
                    ->columns(2),
                TextInput::make('marker_icon')
                    ->label('Marker Icon URL (opsional)')
                    ->placeholder('https://.../icon.png')
                    ->default('https://cdn.jsdelivr.net/gh/atisawd/boxicons/svg/solid/bxs-cctv.svg')
                    ->helperText('Kosongkan untuk pakai ikon CCTV bulat bawaan. Jika diisi, gunakan URL ikon kustom.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('building.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('longitude')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('marker_icon')
                    ->searchable()
                    ->toggleable(),
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
                    ->size('lg'),
                EditAction::make()
                    ->button()
                    ->color('warning')
                    ->size('lg'),
                DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->size('lg'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRooms::route('/'),
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
        return 'The number of rooms';
    }
}
