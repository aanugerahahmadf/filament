# Services and Repositories Summary

## Service Layer

The service layer implements business logic and acts as an intermediary between controllers and repositories. All services are registered as singletons in the AppServicesServiceProvider.

### 1. CctvService
**File**: `app/Services/CctvService.php`
**Dependencies**: FfmpegStreamService
**Key Methods**:
- `startStream()`: Start HLS streaming for a CCTV
- `stopStream()`: Stop HLS streaming for a CCTV
- `checkCctvStatus()`: Check connectivity status of a CCTV
- `scheduleMaintenance()`: Schedule maintenance for a CCTV
- `startRecording()`: Start recording for a CCTV
- `stopRecording()`: Stop recording for a CCTV
- `getOnlineCctvs()`: Get all online CCTVs
- `getOfflineCctvs()`: Get all offline CCTVs
- `getMaintenanceCctvs()`: Get all CCTVs in maintenance
- `getStatusStatistics()`: Get CCTV status statistics

### 2. DashboardService
**File**: `app/Services/DashboardService.php`
**Dependencies**: CctvRepository, BuildingRepository, RoomRepository, MaintenanceRepository, AlertRepository
**Key Methods**:
- `getDashboardData()`: Get all dashboard data
- `getStatistics()`: Get system statistics
- `getRecentAlerts()`: Get recent alerts
- `getUpcomingMaintenance()`: Get upcoming maintenance
- `getOfflineCctvs()`: Get offline CCTVs
- `getMapData()`: Get map data for visualization

### 3. DashboardWidgetService
**File**: `app/Services/DashboardWidgetService.php`
**Dependencies**: CctvRepository, BuildingRepository, RoomRepository, MaintenanceRepository, AlertRepository
**Key Methods**:
- `getCctvStatusData()`: Get CCTV status chart data
- `getMaintenanceData()`: Get maintenance statistics
- `getAlertData()`: Get alert statistics
- `getInfrastructureData()`: Get infrastructure statistics

### 4. ExportService
**File**: `app/Services/ExportService.php`
**Dependencies**: CctvRepository, BuildingRepository, RoomRepository, UserRepository, ContactRepository
**Key Methods**:
- `exportBuildings()`: Export buildings data
- `exportRooms()`: Export rooms data
- `exportCctvs()`: Export CCTVs data
- `exportUsers()`: Export users data
- `exportContacts()`: Export contacts data
- `exportStatistics()`: Export system statistics
- `generateReport()`: Generate comprehensive reports

### 5. FfmpegStreamService
**File**: `app/Services/FfmpegStreamService.php`
**Dependencies**: None
**Key Methods**:
- `startStream()`: Start FFmpeg HLS streaming process
- `stopStream()`: Stop FFmpeg streaming process

### 6. FileStorageService
**File**: `app/Services/FileStorageService.php`
**Dependencies**: None
**Key Methods**:
- `storeRecording()`: Store recording file
- `deleteRecording()`: Delete recording file
- `archiveRecording()`: Archive recording file
- `getRecordingPath()`: Get recording file path
- `getFileSize()`: Get file size information

### 7. HealthCheckService
**File**: `app/Services/HealthCheckService.php`
**Dependencies**: CctvRepository, AlertRepository
**Key Methods**:
- `getSystemHealth()`: Get overall system health
- `getCctvHealth()`: Get CCTV system health
- `getDatabaseHealth()`: Get database health
- `getStorageHealth()`: Get storage health
- `getServiceHealth()`: Get service health status

### 8. NotificationService
**File**: `app/Services/NotificationService.php`
**Dependencies**: None
**Key Methods**:
- `sendAlertNotification()`: Send alert notifications
- `sendMaintenanceNotification()`: Send maintenance notifications
- `sendSystemNotification()`: Send system notifications
- `broadcastNotification()`: Broadcast notifications to users
- `getEmailTemplate()`: Get email notification template

### 9. ReportService
**File**: `app/Services/ReportService.php`
**Dependencies**: CctvRepository, BuildingRepository, RoomRepository, MaintenanceRepository, AlertRepository
**Key Methods**:
- `generateCctvStatusReport()`: Generate CCTV status report
- `generateMaintenanceReport()`: Generate maintenance report
- `generateAlertReport()`: Generate alert report
- `generateInfrastructureReport()`: Generate infrastructure report
- `exportReport()`: Export report in various formats

### 10. SearchService
**File**: `app/Services/SearchService.php`
**Dependencies**: CctvRepository, BuildingRepository, RoomRepository, UserRepository, ContactRepository
**Key Methods**:
- `searchAll()`: Search across all entities
- `searchCctvs()`: Search CCTVs
- `searchBuildings()`: Search buildings
- `searchRooms()`: Search rooms
- `searchUsers()`: Search users
- `searchContacts()`: Search contacts

### 11. SettingsService
**File**: `app/Services/SettingsService.php`
**Dependencies**: None
**Key Methods**:
- `getSettings()`: Get system settings
- `updateSettings()`: Update system settings
- `getSetting()`: Get specific setting
- `setSetting()`: Set specific setting
- `resetSettings()`: Reset settings to default

### 12. SystemMonitoringService
**File**: `app/Services/SystemMonitoringService.php`
**Dependencies**: CctvRepository, AlertRepository
**Key Methods**:
- `getCpuUsage()`: Get CPU usage statistics
- `getMemoryUsage()`: Get memory usage statistics
- `getDiskUsage()`: Get disk usage statistics
- `getNetworkUsage()`: Get network usage statistics
- `getPerformanceMetrics()`: Get overall performance metrics

### 13. ValidationService
**File**: `app/Services/ValidationService.php`
**Dependencies**: None
**Key Methods**:
- `validateCctvData()`: Validate CCTV data
- `validateBuildingData()`: Validate building data
- `validateRoomData()`: Validate room data
- `validateUserData()`: Validate user data
- `validateMaintenanceData()`: Validate maintenance data
- `validateAlertData()`: Validate alert data

### 14. ApiResponseService
**File**: `app/Services/ApiResponseService.php`
**Dependencies**: None
**Key Methods**:
- `success()`: Generate successful response
- `error()`: Generate error response
- `notFound()`: Generate not found response
- `unauthorized()`: Generate unauthorized response
- `validationError()`: Generate validation error response

### 15. AuditService
**File**: `app/Services/AuditService.php`
**Dependencies**: None
**Key Methods**:
- `logActivity()`: Log user activity
- `logModelChange()`: Log model changes
- `logSystemEvent()`: Log system events
- `getAuditTrail()`: Get audit trail
- `exportAuditLog()`: Export audit log

### 16. BackupService
**File**: `app/Services/BackupService.php`
**Dependencies**: None
**Key Methods**:
- `createBackup()`: Create database backup
- `restoreBackup()`: Restore database backup
- `listBackups()`: List available backups
- `deleteBackup()`: Delete backup
- `scheduleBackup()`: Schedule automatic backups

### 17. CacheService
**File**: `app/Services/CacheService.php`
**Dependencies**: None
**Key Methods**:
- `remember()`: Cache data with expiration
- `forget()`: Remove cached data
- `flush()`: Clear all cache
- `getStats()`: Get cache statistics
- `isEnabled()`: Check if caching is enabled

### 18. EventService
**File**: `app/Services/EventService.php`
**Dependencies**: None
**Key Methods**:
- `dispatch()`: Dispatch events
- `listen()`: Register event listeners
- `subscribe()`: Subscribe to events
- `getListeners()`: Get registered listeners

### 19. LoggingService
**File**: `app/Services/LoggingService.php`
**Dependencies**: None
**Key Methods**:
- `info()`: Log info messages
- `error()`: Log error messages
- `warning()`: Log warning messages
- `debug()`: Log debug messages
- `logException()`: Log exceptions

## Repository Layer

The repository layer provides a clean abstraction for data access and implements the repository pattern.

### 1. BaseRepository
**File**: `app/Repositories/BaseRepository.php`
**Key Methods**:
- `all()`: Get all records
- `paginate()`: Get paginated records
- `find()`: Find record by ID
- `findOrFail()`: Find record or throw exception
- `where()`: Query by condition
- `create()`: Create new record
- `update()`: Update existing record
- `delete()`: Delete record
- `firstOrCreate()`: Find or create record
- `updateOrCreate()`: Update or create record
- `count()`: Get record count
- `latest()`: Get latest records
- `oldest()`: Get oldest records

### 2. CctvRepository
**File**: `app/Repositories/CctvRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `online()`: Get online CCTVs
- `offline()`: Get offline CCTVs
- `maintenance()`: Get CCTVs in maintenance
- `byBuilding()`: Get CCTVs by building
- `byRoom()`: Get CCTVs by room
- `search()`: Search CCTVs
- `getStatusStatistics()`: Get status statistics

### 3. BuildingRepository
**File**: `app/Repositories/BuildingRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `withRoomsAndCctvs()`: Get buildings with relationships
- `search()`: Search buildings
- `getStatistics()`: Get building statistics

### 4. RoomRepository
**File**: `app/Repositories/RoomRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `withBuilding()`: Get rooms with building
- `byBuilding()`: Get rooms by building
- `search()`: Search rooms
- `getStatusStatistics()`: Get status statistics

### 5. UserRepository
**File**: `app/Repositories/UserRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `online()`: Get online users
- `offline()`: Get offline users
- `byRole()`: Get users by role
- `search()`: Search users
- `getStatusStatistics()`: Get status statistics

### 6. ContactRepository
**File**: `app/Repositories/ContactRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `search()`: Search contacts
- `byBuilding()`: Get contacts by building
- `byRoom()`: Get contacts by room

### 7. MessageRepository
**File**: `app/Repositories/MessageRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `byUser()`: Get messages by user
- `unread()`: Get unread messages
- `read()`: Get read messages
- `byType()`: Get messages by type
- `byPriority()`: Get messages by priority

### 8. MaintenanceRepository
**File**: `app/Repositories/MaintenanceRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `byCctv()`: Get maintenance by CCTV
- `byTechnician()`: Get maintenance by technician
- `scheduled()`: Get scheduled maintenance
- `inProgress()`: Get in-progress maintenance
- `completed()`: Get completed maintenance
- `cancelled()`: Get cancelled maintenance
- `upcoming()`: Get upcoming maintenance
- `getStatusStatistics()`: Get status statistics

### 9. AlertRepository
**File**: `app/Repositories/AlertRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `bySeverity()`: Get alerts by severity
- `byCategory()`: Get alerts by category
- `triggered()`: Get triggered alerts
- `acknowledged()`: Get acknowledged alerts
- `resolved()`: Get resolved alerts
- `suppressed()`: Get suppressed alerts
- `recent()`: Get recent alerts
- `getStatusStatistics()`: Get status statistics

### 10. RecordingRepository
**File**: `app/Repositories/RecordingRepository.php`
**Extends**: BaseRepository
**Key Methods**:
- `byCctv()`: Get recordings by CCTV
- `active()`: Get active recordings
- `archived()`: Get archived recordings
- `deleted()`: Get deleted recordings
- `byDateRange()`: Get recordings by date range

## Service Registration

All services are registered as singletons in the AppServicesServiceProvider:

**File**: `app/Providers/AppServicesServiceProvider.php`

```php
$this->app->singleton(CctvService::class, function ($app) {
    return new CctvService($app->make(FfmpegStreamService::class));
});

$this->app->singleton(DashboardService::class, function ($app) {
    return new DashboardService(
        $app->make(CctvRepository::class),
        $app->make(BuildingRepository::class),
        $app->make(RoomRepository::class),
        $app->make(MaintenanceRepository::class),
        $app->make(AlertRepository::class)
    );
});
// ... other service registrations
```

## Dependency Injection

Services are injected into controllers through constructor injection:

```php
class CctvController extends Controller
{
    protected CctvService $cctvService;

    public function __construct(CctvService $cctvService)
    {
        $this->cctvService = $cctvService;
    }
}
```

## Usage Examples

### Using Services in Controllers
```php
public function startStream(Cctv $cctv): JsonResponse
{
    $success = $this->cctvService->startStream($cctv);
    
    if ($success) {
        return response()->json([
            'message' => 'Stream started successfully',
            'hls_path' => $cctv->hls_path
        ]);
    } else {
        return response()->json([
            'message' => 'Failed to start stream'
        ], 500);
    }
}
```

### Using Repositories in Services
```php
public function getStatusStatistics(): array
{
    return [
        'total' => $this->cctvRepository->count(),
        'online' => $this->cctvRepository->online()->count(),
        'offline' => $this->cctvRepository->offline()->count(),
        'maintenance' => $this->cctvRepository->maintenance()->count(),
    ];
}
```

## Benefits of This Architecture

1. **Separation of Concerns**: Business logic is separated from presentation and data access
2. **Testability**: Services and repositories can be easily mocked for testing
3. **Maintainability**: Changes to business logic or data access don't affect other layers
4. **Reusability**: Services can be used across multiple controllers
5. **Scalability**: Easy to add new features or modify existing ones
6. **Flexibility**: Can switch data sources or modify business rules without major changes
