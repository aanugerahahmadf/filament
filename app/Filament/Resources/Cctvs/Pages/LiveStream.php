<?php

namespace App\Filament\Resources\Cctvs\Pages;

use App\Filament\Resources\Cctvs\CctvResource;
use App\Models\Cctv;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class LiveStream extends Page
{
    protected static string $resource = CctvResource::class;

    protected static ?string $title = 'Live Stream CCTV';

    protected string $view = 'filament.resources.cctvs.pages.live-stream';

    public ?Cctv $cctv = null;

    public string $hlsUrl = '';

    public array $recordings = [];

    public function mount(int $record): void
    {
        $this->cctv = Cctv::findOrFail($record);

        // Auto-start stream if not already online
        if ($this->cctv->status !== Cctv::STATUS_ONLINE) {
            $this->startStream();
        } else if ($this->cctv->hls_path) {
            $this->hlsUrl = url($this->cctv->hls_path);
        }
    }

    public function startStream(): void
    {
        try {
            $service = app(\App\Services\FfmpegStreamService::class);
            $hlsPath = $service->startStream($this->cctv);

            $this->hlsUrl = url($hlsPath);
            $this->cctv->refresh();

            Notification::make()
                ->title('Stream Started')
                ->body('The live stream has been started successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to start the stream: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function stopStream(): void
    {
        try {
            $service = app(\App\Services\FfmpegStreamService::class);
            $service->stopStream($this->cctv);
            $this->cctv->refresh();

            Notification::make()
                ->title('Stream Stopped')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to stop the stream: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function takeScreenshot(): void
    {
        try {
            // Auto start stream if not already online
            if ($this->cctv->status !== Cctv::STATUS_ONLINE) {
                $this->startStream();
            }

            $service = app(\App\Services\FfmpegStreamService::class);
            $imagePath = $service->takeSnapshot($this->cctv);

            // Add to recordings list
            $this->recordings[] = [
                'type' => 'screenshot',
                'title' => 'Screenshot taken at ' . now()->format('H:i:s'),
                'path' => $imagePath,
                'url' => url($imagePath),
                'timestamp' => now(),
            ];

            Notification::make()
                ->title('Screenshot Taken')
                ->body('Screenshot has been saved successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to take screenshot: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function recordVideo(int $duration = 30): void
    {
        try {
            // Auto start stream if not already online
            if ($this->cctv->status !== Cctv::STATUS_ONLINE) {
                $this->startStream();
            }

            $service = app(\App\Services\FfmpegStreamService::class);
            $videoPath = $service->recordClip($this->cctv, $duration);

            // Add to recordings list
            $this->recordings[] = [
                'type' => 'video',
                'title' => "Video recording ({$duration}s) at " . now()->format('H:i:s'),
                'path' => $videoPath,
                'url' => url($videoPath),
                'timestamp' => now(),
            ];

            Notification::make()
                ->title('Video Recorded')
                ->body("Video has been recorded for {$duration} seconds.")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to record video: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to CCTV')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            Action::make('start_stream')
                ->label('Start Stream')
                ->color('success')
                ->visible(fn () => $this->cctv && $this->cctv->status !== \App\Models\Cctv::STATUS_ONLINE)
                ->action('startStream'),

            Action::make('stop_stream')
                ->label('Stop Stream')
                ->color('danger')
                ->visible(fn () => $this->cctv && $this->cctv->status === \App\Models\Cctv::STATUS_ONLINE)
                ->requiresConfirmation()
                ->action('stopStream'),

            Action::make('take_screenshot')
                ->label('Take Screenshot')
                ->color('info')
                ->visible(fn () => $this->cctv !== null)
                ->action('takeScreenshot'),

            Action::make('record_video')
                ->label('Record Video')
                ->color('warning')
                ->visible(fn () => $this->cctv !== null)
                ->action('recordVideo'),
        ];
    }
}
