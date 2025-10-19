<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    public function storeRecording(string $filename, string $content, string $disk = 'local'): bool
    {
        try {
            $path = 'recordings/'.$filename;
            Storage::disk($disk)->put($path, $content);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to store recording: '.$e->getMessage());

            return false;
        }
    }

    public function getRecording(string $filename, string $disk = 'local'): ?string
    {
        try {
            $path = 'recordings/'.$filename;
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->get($path);
            }

            return null;
        } catch (Exception $e) {
            Log::error('Failed to retrieve recording: '.$e->getMessage());

            return null;
        }
    }

    public function deleteRecording(string $filename, string $disk = 'local'): bool
    {
        try {
            $path = 'recordings/'.$filename;
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }

            return false;
        } catch (Exception $e) {
            Log::error('Failed to delete recording: '.$e->getMessage());

            return false;
        }
    }

    public function storeImage(UploadedFile $file, string $directory = 'images', string $disk = 'public'): string
    {
        try {
            return $file->store($directory, $disk);
        } catch (Exception $e) {
            Log::error('Failed to store image: '.$e->getMessage());
            throw $e;
        }
    }

    public function storeDocument(UploadedFile $file, string $directory = 'documents', string $disk = 'public'): string
    {
        try {
            return $file->store($directory, $disk);
        } catch (Exception $e) {
            Log::error('Failed to store document: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteFile(string $path, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }

            return false;
        } catch (Exception $e) {
            Log::error('Failed to delete file: '.$e->getMessage());

            return false;
        }
    }

    public function getFileUrl(string $path, string $disk = 'public'): string
    {
        // For public disk, we can use the url helper
        if ($disk === 'public') {
            return Storage::url($path);
        }

        // For other disks, return a fallback
        return '/storage/'.$path;
    }

    public function getStorageUsage(string $disk = 'local'): array
    {
        try {
            $files = Storage::disk($disk)->allFiles();
            $totalSize = 0;

            foreach ($files as $file) {
                $totalSize += Storage::disk($disk)->size($file);
            }

            return [
                'total_files' => count($files),
                'total_size' => $totalSize,
                'total_size_formatted' => $this->formatBytes($totalSize),
            ];
        } catch (Exception $e) {
            Log::error('Failed to get storage usage: '.$e->getMessage());

            return [
                'total_files' => 0,
                'total_size' => 0,
                'total_size_formatted' => '0 B',
            ];
        }
    }

    public function cleanupOldRecordings(int $days = 30, string $disk = 'local'): int
    {
        try {
            $deletedCount = 0;
            $files = Storage::disk($disk)->allFiles('recordings');
            $cutoffDate = now()->subDays($days);

            foreach ($files as $file) {
                $lastModified = Storage::disk($disk)->lastModified($file);
                $fileDate = \DateTime::createFromFormat('U', $lastModified);

                if ($fileDate < $cutoffDate) {
                    if (Storage::disk($disk)->delete($file)) {
                        $deletedCount++;
                    }
                }
            }

            return $deletedCount;
        } catch (Exception $e) {
            Log::error('Failed to cleanup old recordings: '.$e->getMessage());

            return 0;
        }
    }

    protected function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision).' '.$units[$i];
    }
}
