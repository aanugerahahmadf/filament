<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    protected string $backupPath;

    protected string $databaseBackupPath;

    protected string $filesBackupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->databaseBackupPath = $this->backupPath.'/database';
        $this->filesBackupPath = $this->backupPath.'/files';

        // Create backup directories if they don't exist
        if (! File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        if (! File::exists($this->databaseBackupPath)) {
            File::makeDirectory($this->databaseBackupPath, 0755, true);
        }

        if (! File::exists($this->filesBackupPath)) {
            File::makeDirectory($this->filesBackupPath, 0755, true);
        }
    }

    public function backupDatabase(): bool
    {
        try {
            $filename = 'database_backup_'.now()->format('Y-m-d_H-i-s').'.sql';
            $filepath = $this->databaseBackupPath.'/'.$filename;

            // For SQLite, we can just copy the database file
            if (config('database.default') === 'sqlite') {
                $databasePath = config('database.connections.sqlite.database');
                if (File::exists($databasePath)) {
                    File::copy($databasePath, $filepath);
                    Log::info("Database backup created: {$filepath}");

                    return true;
                }
            }

            // For other databases, you would use appropriate commands
            // For example, for MySQL:
            // mysqldump -u username -p password database_name > $filepath

            Log::warning('Database backup not implemented for current database driver');

            return false;
        } catch (Exception $e) {
            Log::error('Database backup failed: '.$e->getMessage());

            return false;
        }
    }

    public function backupFiles(): bool
    {
        try {
            $filename = 'files_backup_'.now()->format('Y-m-d_H-i-s').'.zip';
            $filepath = $this->filesBackupPath.'/'.$filename;

            // Create a zip archive of the storage directory
            $zip = new \ZipArchive;
            if ($zip->open($filepath, \ZipArchive::CREATE) === true) {
                $this->addFilesToZip($zip, storage_path('app'), 'app');
                $zip->close();
                Log::info("Files backup created: {$filepath}");

                return true;
            }

            Log::error('Failed to create zip archive for files backup');

            return false;
        } catch (Exception $e) {
            Log::error('Files backup failed: '.$e->getMessage());

            return false;
        }
    }

    protected function addFilesToZip(\ZipArchive $zip, string $folder, string $localName): void
    {
        $files = File::allFiles($folder);

        foreach ($files as $file) {
            $relativePath = str_replace($folder, '', $file->getPathname());
            $zip->addFile($file->getPathname(), $localName.$relativePath);
        }
    }

    public function backupAll(): array
    {
        $results = [
            'database' => $this->backupDatabase(),
            'files' => $this->backupFiles(),
        ];

        return $results;
    }

    public function getBackupList(): array
    {
        $backups = [];

        // Get database backups
        if (File::exists($this->databaseBackupPath)) {
            $databaseBackups = File::files($this->databaseBackupPath);
            foreach ($databaseBackups as $backup) {
                $backups[] = [
                    'type' => 'database',
                    'name' => $backup->getFilename(),
                    'path' => $backup->getPathname(),
                    'size' => $backup->getSize(),
                    'created_at' => $backup->getMTime(),
                ];
            }
        }

        // Get files backups
        if (File::exists($this->filesBackupPath)) {
            $filesBackups = File::files($this->filesBackupPath);
            foreach ($filesBackups as $backup) {
                $backups[] = [
                    'type' => 'files',
                    'name' => $backup->getFilename(),
                    'path' => $backup->getPathname(),
                    'size' => $backup->getSize(),
                    'created_at' => $backup->getMTime(),
                ];
            }
        }

        // Sort by creation time (newest first)
        usort($backups, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return $backups;
    }

    public function deleteBackup(string $filename, string $type): bool
    {
        try {
            $path = $this->backupPath.'/'.$type.'/'.$filename;

            if (File::exists($path)) {
                File::delete($path);
                Log::info("Backup deleted: {$path}");

                return true;
            }

            Log::warning("Backup not found: {$path}");

            return false;
        } catch (Exception $e) {
            Log::error('Failed to delete backup: '.$e->getMessage());

            return false;
        }
    }

    public function cleanupOldBackups(int $days = 30): int
    {
        try {
            $deletedCount = 0;
            $cutoffDate = now()->subDays($days);

            $backups = $this->getBackupList();

            foreach ($backups as $backup) {
                $backupDate = \DateTime::createFromFormat('U', $backup['created_at']);

                if ($backupDate < $cutoffDate) {
                    if ($this->deleteBackup($backup['name'], $backup['type'])) {
                        $deletedCount++;
                    }
                }
            }

            return $deletedCount;
        } catch (Exception $e) {
            Log::error('Failed to cleanup old backups: '.$e->getMessage());

            return 0;
        }
    }

    public function getBackupStats(): array
    {
        $backups = $this->getBackupList();

        $totalBackups = count($backups);
        $totalSize = array_sum(array_column($backups, 'size'));

        $databaseBackups = array_filter($backups, fn ($backup) => $backup['type'] === 'database');
        $filesBackups = array_filter($backups, fn ($backup) => $backup['type'] === 'files');

        return [
            'total_backups' => $totalBackups,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'database_backups' => count($databaseBackups),
            'files_backups' => count($filesBackups),
            'latest_backup' => $backups[0] ?? null,
        ];
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
