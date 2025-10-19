<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Cctvs\CctvResource;
use App\Models\Cctv;
use App\Services\FfmpegStreamService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class CctvOperationalTable extends BaseWidget
{
    protected static ?string $heading = 'Operational – CCTV Status & Controls';

    protected ?string $pollingInterval = '5s';

    protected int | string | array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Cctv::query()->with(['building:id,name', 'room:id,name'])
                    ->latest('updated_at')
            )
            ->columns([
                TextColumn::make('building.name')->label('Building')->searchable(),
                TextColumn::make('room.name')->label('Room')->searchable(),
                TextColumn::make('name')->label('CCTV')->searchable(),
                TextColumn::make('status')->badge()->colors([
                    'success' => 'online',
                    'danger' => 'offline',
                    'warning' => 'maintenance',
                ]),
                TextColumn::make('last_seen_at')->dateTime()->label('Last Seen')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                        'maintenance' => 'Maintenance',
                    ]),
            ])
            ->actions([
                Action::make('start')
                    ->label('Start Stream')
                    ->color('success')
                    ->visible(fn (Cctv $record) => $record->status !== Cctv::STATUS_ONLINE)
                    ->action(function (Cctv $record, FfmpegStreamService $service) {
                        try {
                            $result = $service->startStream($record);
                            if (str_contains($result, 'Failed to connect')) {
                                Notification::make()
                                    ->title('Stream Failed')
                                    ->body('Failed to connect to CCTV stream. Please check the IP address and network connection.')
                                    ->danger()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Stream Started')
                                    ->body('CCTV stream started successfully.')
                                    ->success()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Stream Error')
                                ->body('Error starting stream: '.$e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->icon('heroicon-o-play')
                    ->size('sm'),

                Action::make('stop')
                    ->label('Stop Stream')
                    ->color('danger')
                    ->visible(fn (Cctv $record) => $record->status === Cctv::STATUS_ONLINE)
                    ->requiresConfirmation()
                    ->action(function (Cctv $record, FfmpegStreamService $service) {
                        $service->stopStream($record);
                    })
                    ->icon('heroicon-o-stop')
                    ->size('sm'),

                Action::make('open')
                    ->label('Open HLS')
                    ->color('info')
                    ->url(fn (Cctv $record) => CctvResource::getUrl('live-stream', ['record' => $record]), true)
                    ->icon('heroicon-o-video-camera')
                    ->size('sm'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
