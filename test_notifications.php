<?php

/**
 * Test script to create sample notifications
 * Run: php test_notifications.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Notification;
use App\Models\User;

// Get user ID 1 (Super Admin)
$user = User::find(1);

if (!$user) {
    echo "User tidak ditemukan!\n";
    exit(1);
}

// Delete old test notifications
Notification::where('user_id', $user->id)->delete();

// Create test notifications
$notifications = [
    [
        'type' => 'success',
        'data' => [
            'message' => 'Sistem CCTV berhasil terhubung',
            'location' => 'Gedung A',
            'status' => 'online'
        ]
    ],
    [
        'type' => 'error',
        'data' => [
            'message' => 'CCTV Camera offline',
            'location' => 'Gedung B',
            'status' => 'offline'
        ]
    ],
    [
        'type' => 'info',
        'data' => [
            'message' => 'Maintenance scheduled',
            'location' => 'Server Room',
            'status' => 'maintenance'
        ]
    ],
    [
        'type' => 'warning',
        'data' => [
            'message' => 'Battery backup rendah',
            'location' => 'UPS Room',
            'status' => 'warning'
        ]
    ],
    [
        'type' => 'message',
        'data' => [
            'message' => 'Ada pesan baru dari Security',
            'from' => 'Security Team',
            'priority' => 'high'
        ],
        'read_at' => now() // Mark sebagai read
    ]
];

foreach ($notifications as $notif) {
    Notification::create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'user_id' => $user->id,
        'type' => $notif['type'],
        'data' => $notif['data'],
        'read_at' => $notif['read_at'] ?? null,
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

echo "✅ Berhasil membuat " . count($notifications) . " test notifications untuk user: {$user->name}\n";
echo "📧 Email: {$user->email}\n\n";
echo "🔔 Refresh browser Anda di halaman /notifications untuk melihat notifications!\n";

