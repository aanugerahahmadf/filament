<?php

namespace App\Exports;

use App\Models\User;

class UserExport extends BaseExport
{
    /**
     * Get the headings for the export
     */
    protected function getHeadings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Status',
            'Phone',
            'Department',
            'Position',
            'Avatar',
            'Last Seen At',
            'Email Verified At',
            'Two Factor Confirmed At',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Map a row to an array for export
     *
     * @param  User  $user
     */
    protected function mapRow($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->status,
            $user->phone,
            $user->department,
            $user->position,
            $user->avatar,
            $user->last_seen_at?->format('Y-m-d H:i:s') ?? '',
            $user->email_verified_at?->format('Y-m-d H:i:s') ?? '',
            $user->two_factor_confirmed_at?->format('Y-m-d H:i:s') ?? '',
            $user->created_at?->format('Y-m-d H:i:s') ?? '',
            $user->updated_at?->format('Y-m-d H:i:s') ?? '',
        ];
    }
}
