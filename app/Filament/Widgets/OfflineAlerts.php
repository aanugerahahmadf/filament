<?php

namespace App\Filament\Widgets;

use App\Models\Cctv;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class OfflineAlerts extends BaseWidget
{
    protected static ?string $heading = 'Offline Alerts';

    protected ?string $pollingInterval = '5s';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Cctv::query()->with(['building:id,name', 'room:id,name'])
                    ->where('status', 'offline')
                    ->latest('updated_at')
            )
            ->columns([
                TextColumn::make('building.name')->label('Building')
                    ->searchable(),
                TextColumn::make('room.name')->label('Room')
                    ->searchable(),
                TextColumn::make('ip_rtsp')->label('CCTV IP')   
                    ->searchable(),
                TextColumn::make('last_seen_at')->dateTime()->label('Last Seen')
                    ->searchable(),
            ])
            ->paginated([5, 10, 25, 50]);
    }
}
