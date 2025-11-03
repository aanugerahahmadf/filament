<?php

namespace App\Filament\Resources\Cctvs;

use App\Filament\Resources\Cctvs\Pages\ManageCctvs;
use App\Models\Cctv;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Cctvs\Pages\LiveStream;
use UnitEnum;

class CctvResource extends Resource
{
    protected static ?string $model = Cctv::class;

    protected static string|UnitEnum|null $navigationGroup = 'Playlist And Maps';

    protected static ?string $navigationLabel = 'Cctv';

    protected static ?string $modelLabel = 'Cctv';

    protected static ?string $pluralModelLabel = 'Cctv';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('building_id')
                    ->relationship('building', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->searchPrompt('Search Building...')
                    ->required()
                    ->live(),
                Select::make('room_id')
                    ->relationship('room', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->searchPrompt('Search Room...')
                    ->required()
                    ->live(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('stream_username')
                    ->default('admin'),
                TextInput::make('stream_password')
                    ->password()
                    ->default('password.123')
                    ->revealable(),
                TextInput::make('ip_rtsp')
                    ->required(),
                TextInput::make('port')
                    ->required()
                    ->numeric()
                    ->default(554),
                Select::make('connection_type')
                    ->options([
                        'wired' => 'Wired (LAN Cable)',
                        'wireless' => 'Wireless (Wi-Fi)'
                    ])
                    ->default('wired')
                    ->required(),
                Select::make('status')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                        'maintenance' => 'Maintenance'
                    ])
                    ->default('offline')
                    ->required()
                    ->disabled(),
                //DateTimePicker::make('last_seen_at')
                    //->disabled(), // Make it disabled since it should be automatically updated
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('building.name')
                    ->searchable(),
                TextColumn::make('room.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stream_username')
                    ->searchable(),
                TextColumn::make('ip_rtsp')
                    ->searchable(),
                TextColumn::make('port')
                    ->formatStateUsing(fn (string $state): string => number_format((float) $state)),
                    //->sortable(),
                TextColumn::make('connection_type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'wired' ? 'success' : 'info'),
                TextColumn::make('status')
                    ->badge(),
                //TextColumn::make('last_seen_at')
                    //->dateTime()
                    //->sortable(),
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
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                EditAction::make()
                    ->button()
                    ->color('warning')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->size('xl')
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ]),
                Action::make('live_stream')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->size('xl')
                    ->button()
                    ->extraAttributes([
                        'class' => 'shadow-lg',
                    ])
                    ->url(fn ($record) => \App\Filament\Resources\Cctvs\CctvResource::getUrl('live-stream', ['record' => $record])),
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
            'index' => ManageCctvs::route('/'),
            'live-stream' => LiveStream::route('/{record}/live-stream'),
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
        return 'The number of CCTV';
    }

}
