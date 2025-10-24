<?php

namespace App\Filament\Exports;

use App\Models\Cctv;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CctvExporter extends Exporter
{
    protected static ?string $model = Cctv::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('building_id'),
            ExportColumn::make('room_id'),
            ExportColumn::make('name'),
            ExportColumn::make('ip_rtsp'),
            ExportColumn::make('port'),
            ExportColumn::make('connection_type'),
            ExportColumn::make('status'),
            ExportColumn::make('recording'),
            ExportColumn::make('last_seen_at'),
            ExportColumn::make('stream_username'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your cctv export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
