<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class AuditService
{
    protected string $logChannel = 'audit';

    protected bool $storeInDatabase = true;

    protected bool $storeInFile = true;

    public function log(string $action, string $model, $modelId, array $changes = [], ?string $description = null): void
    {
        $user = Auth::user();

        $auditData = [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'changes' => $changes,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        // Log to file
        if ($this->storeInFile) {
            Log::channel($this->logChannel)->info("{$action} {$model}", $auditData);
        }

        // Store in database (you would typically have an audit_logs table)
        if ($this->storeInDatabase) {
            // This would typically save to a database table
            // For now, we'll just log that we would save to database
            Log::channel($this->logChannel)->info("Would save to database: {$action} {$model}", $auditData);
        }
    }

    public function logCreate(string $model, $modelId, array $data, ?string $description = null): void
    {
        $this->log('create', $model, $modelId, ['created' => $data], $description);
    }

    public function logUpdate(string $model, $modelId, array $oldData, array $newData, ?string $description = null): void
    {
        $changes = [];
        foreach ($newData as $key => $value) {
            if (! isset($oldData[$key]) || $oldData[$key] !== $value) {
                $changes[$key] = [
                    'old' => $oldData[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        $this->log('update', $model, $modelId, $changes, $description);
    }

    public function logDelete(string $model, $modelId, array $data, ?string $description = null): void
    {
        $this->log('delete', $model, $modelId, ['deleted' => $data], $description);
    }

    public function logView(string $model, $modelId, ?string $description = null): void
    {
        $this->log('view', $model, $modelId, [], $description);
    }

    public function logLogin(string $userId, string $userName, string $ipAddress, string $userAgent): void
    {
        $auditData = [
            'user_id' => $userId,
            'user_name' => $userName,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'timestamp' => now()->toISOString(),
        ];

        if ($this->storeInFile) {
            Log::channel($this->logChannel)->info('User login', $auditData);
        }

        if ($this->storeInDatabase) {
            Log::channel($this->logChannel)->info('Would save login to database', $auditData);
        }
    }

    public function logLogout(string $userId, string $userName): void
    {
        $auditData = [
            'user_id' => $userId,
            'user_name' => $userName,
            'timestamp' => now()->toISOString(),
        ];

        if ($this->storeInFile) {
            Log::channel($this->logChannel)->info('User logout', $auditData);
        }

        if ($this->storeInDatabase) {
            Log::channel($this->logChannel)->info('Would save logout to database', $auditData);
        }
    }

    public function logFailedLogin(string $email, string $ipAddress, string $userAgent): void
    {
        $auditData = [
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'timestamp' => now()->toISOString(),
        ];

        if ($this->storeInFile) {
            Log::channel($this->logChannel)->warning('Failed login attempt', $auditData);
        }

        if ($this->storeInDatabase) {
            Log::channel($this->logChannel)->warning('Would save failed login to database', $auditData);
        }
    }

    public function logPermissionCheck(string $permission, bool $allowed, ?User $user = null): void
    {
        $auditData = [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'permission' => $permission,
            'allowed' => $allowed,
            'ip_address' => Request::ip(),
            'timestamp' => now()->toISOString(),
        ];

        if ($this->storeInFile) {
            $logLevel = $allowed ? 'info' : 'warning';
            Log::channel($this->logChannel)->{$logLevel}("Permission check: {$permission}", $auditData);
        }

        if ($this->storeInDatabase) {
            Log::channel($this->logChannel)->info('Would save permission check to database', $auditData);
        }
    }

    public function logRoleAssignment(string $role, User $user, ?string $assignedBy = null): void
    {
        $auditData = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'role' => $role,
            'assigned_by' => $assignedBy,
            'ip_address' => Request::ip(),
            'timestamp' => now()->toISOString(),
        ];

        if ($this->storeInFile) {
            Log::channel($this->logChannel)->info("Role assigned: {$role}", $auditData);
        }

        if ($this->storeInDatabase) {
            Log::channel($this->logChannel)->info('Would save role assignment to database', $auditData);
        }
    }

    public function getAuditLogs(int $limit = 100): array
    {
        // This would typically retrieve logs from database
        // For now, we'll return an empty array
        return [];
    }

    public function searchAuditLogs(string $query, int $limit = 100): array
    {
        // This would typically search logs in database
        // For now, we'll return an empty array
        return [];
    }

    public function exportAuditLogs(?string $startDate = null, ?string $endDate = null): string
    {
        // This would typically export logs to a file
        // For now, we'll create a mock export
        $filename = 'audit_logs_'.now()->format('Y-m-d_H-i-s').'.csv';
        $filepath = storage_path('app/exports/'.$filename);

        // Create exports directory if it doesn't exist
        if (! Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        // Create a simple CSV export
        $content = "Timestamp,User,Action,Model,Model ID,Description\n";
        $content .= now()->toISOString().",System,Audit,Service,0,Audit logs exported\n";

        Storage::put('exports/'.$filename, $content);

        return $filepath;
    }
}
