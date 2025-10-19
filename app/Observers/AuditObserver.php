<?php

namespace App\Observers;

use App\Services\AuditService;
use Illuminate\Support\Str;

class AuditObserver
{
    protected AuditService $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function created($model): void
    {
        $this->auditService->logCreate(
            $this->getModelName($model),
            $model->id,
            $model->toArray(),
            "Created {$this->getModelName($model)}: {$model->id}"
        );
    }

    public function updated($model): void
    {
        // Get the original attributes before the update
        $original = $model->getOriginal();
        $changes = $model->getChanges();

        // Remove timestamps from changes
        unset($changes['created_at'], $changes['updated_at']);

        // If there are actual changes, log them
        if (! empty($changes)) {
            $this->auditService->logUpdate(
                $this->getModelName($model),
                $model->id,
                $original,
                $model->toArray(),
                "Updated {$this->getModelName($model)}: {$model->id}"
            );
        }
    }

    public function deleted($model): void
    {
        $this->auditService->logDelete(
            $this->getModelName($model),
            $model->id,
            $model->toArray(),
            "Deleted {$this->getModelName($model)}: {$model->id}"
        );
    }

    protected function getModelName($model): string
    {
        return Str::afterLast(get_class($model), '\\');
    }
}
