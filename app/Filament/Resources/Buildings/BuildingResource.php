<?php

namespace App\Filament\Resources\Buildings;

use App\Filament\Resources\Buildings\Pages\ManageBuildings;
use App\Models\Building;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BuildingResource extends Resource
{
    protected static ?string $model = Building::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Playlist And Maps';

    protected static ?string $navigationLabel = 'Building';

    protected static ?string $modelLabel = 'Building';

    protected static ?string $pluralModelLabel = 'Building';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('latitude')
                    ->required(),
                TextInput::make('longitude')
                    ->required(),
                TextInput::make('marker_icon')
                    ->label('Marker Icon URL (opsional)')
                    ->placeholder('https://.../icon.png')
                    ->default('https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-blue.png')
                    ->helperText('Kosongkan untuk pakai default pin peta. Jika diisi, gunakan URL ikon kustom.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('marker_icon')
                    ->searchable()
                    ->toggleable(),
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
            'index' => ManageBuildings::route('/'),
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
        return 'The number of buildings';
    }
}
