<?php

namespace App\Filament\Resources\Cctvs;


use App\Filament\Resources\Cctvs\Pages\CreateCctv;
use App\Filament\Resources\Cctvs\Pages\EditCctv;
use App\Filament\Resources\Cctvs\Pages\ListCctvs;
use App\Filament\Resources\Cctvs\Pages\LiveStream;
use App\Filament\Resources\Cctvs\Pages\ViewCctv;
use App\Filament\Resources\Cctvs\Schemas\CctvForm;
use App\Filament\Resources\Cctvs\Schemas\CctvInfolist;
use App\Filament\Resources\Cctvs\Tables\CctvsTable;
use App\Models\Cctv;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CctvResource extends Resource
{
    protected static ?string $model = Cctv::class;

    protected static string|UnitEnum|null $navigationGroup = 'Location And Maps';

    protected static ?string $navigationLabel = 'CCTV';

    protected static ?string $modelLabel = 'CCTV';

    protected static ?string $pluralModelLabel = 'CCTV';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CctvForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CctvInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CctvsTable::configure($table);
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
            'index' => ListCctvs::route('/'),
            'create' => CreateCctv::route('/create'),
            'view' => ViewCctv::route('/{record}'),
            'edit' => EditCctv::route('/{record}/edit'),
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
        return 'The number of CCTVs';
    }
}
