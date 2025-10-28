<?php

// Example usage of the InvoicePaid notification in the context of
// ATCS CCTV system for PT Kilang Pertamina Internasional Refinery Unit VI Balongan

use App\Models\User;
use App\Notifications\InvoicePaid;

// Example 1: CCTV Offline Notification
$cctvOfflineDetails = [
    'type' => 'cctv_offline',
    'cctv_name' => 'CAMERA_PROCESSING_01',
    'location' => 'Processing Unit Area',
    'building' => 'Main Processing Building',
    'room' => 'Control Room 3',
    'offline_time' => now()->format('Y-m-d H:i:s'),
];

$operator = User::where('role', 'operator')->first();
$operator->notify(new InvoicePaid($cctvOfflineDetails));

echo "CCTV offline notification sent to operator for CAMERA_PROCESSING_01\n";

// Example 2: Maintenance Completed Notification
$maintenanceDetails = [
    'type' => 'maintenance_completed',
    'cctv_name' => 'CAMERA_TANK_FARM_05',
    'location' => 'Refinery Unit VI Tank Farm',
    'technician' => 'Budi Santoso',
    'completed_at' => now()->format('Y-m-d H:i:s'),
    'status' => 'Completed Successfully',
];

$supervisor = User::where('role', 'supervisor')->first();
$supervisor->notify(new InvoicePaid($maintenanceDetails));

echo "Maintenance completion notification sent to supervisor for CAMERA_TANK_FARM_05\n";

// Example 3: System Alert Notification
$alertDetails = [
    'type' => 'system_alert',
    'alert_type' => 'Network Disruption',
    'message' => 'Network connectivity issue detected in Building A, multiple CCTVs affected',
    'severity' => 'High',
    'timestamp' => now()->format('Y-m-d H:i:s'),
];

$admin = User::where('role', 'admin')->first();
$admin->notify(new InvoicePaid($alertDetails));

echo "System alert notification sent to admin for network disruption\n";

echo "All ATCS notifications sent successfully for PT Kilang Pertamina Internasional Refinery Unit VI Balongan\n";
