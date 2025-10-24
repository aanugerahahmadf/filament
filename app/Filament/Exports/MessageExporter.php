<?php

namespace App\Filament\Exports;

use App\Models\Message;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class MessageExporter extends Exporter
{
    protected static ?string $model = Message::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('from_user_id'),
            ExportColumn::make('to_user_id'),
            ExportColumn::make('subject'),
            ExportColumn::make('message'),
            ExportColumn::make('body'),
            ExportColumn::make('read_at'),
            ExportColumn::make('priority'),
            ExportColumn::make('type'),
            ExportColumn::make('delivered_at'),
            ExportColumn::make('archived_at'),
            ExportColumn::make('reply_to_message_id'),
            ExportColumn::make('forwarded_from_user_id'),
            ExportColumn::make('is_edited'),
            ExportColumn::make('edited_at'),
            ExportColumn::make('message_type'),
            ExportColumn::make('attachment_path'),
            ExportColumn::make('attachment_name'),
            ExportColumn::make('attachment_size'),
            ExportColumn::make('is_pinned'),
            ExportColumn::make('pinned_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your message export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
