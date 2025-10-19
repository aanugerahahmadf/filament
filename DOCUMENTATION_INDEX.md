# Documentation Index

## Project Overview
- [README.md](README.md) - Main project documentation
- [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - Comprehensive project summary
- [USAGE_GUIDE.md](USAGE_GUIDE.md) - How to use the system

## Technical Documentation
- [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md) - Complete system architecture
- [MODELS_SUMMARY.md](MODELS_SUMMARY.md) - Detailed model documentation
- [CONTROLLERS_API_SUMMARY.md](CONTROLLERS_API_SUMMARY.md) - Controllers and API endpoints
- [SERVICES_REPOSITORIES_SUMMARY.md](SERVICES_REPOSITORIES_SUMMARY.md) - Services and repositories

## Source Code Files

### Models (10)
1. [app/Models/User.php](app/Models/User.php)
2. [app/Models/Building.php](app/Models/Building.php)
3. [app/Models/Room.php](app/Models/Room.php)
4. [app/Models/Cctv.php](app/Models/Cctv.php)
5. [app/Models/Contact.php](app/Models/Contact.php)
6. [app/Models/Message.php](app/Models/Message.php)
7. [app/Models/Maintenance.php](app/Models/Maintenance.php)
8. [app/Models/Alert.php](app/Models/Alert.php)
9. [app/Models/Recording.php](app/Models/Recording.php)
10. [app/Models/Setting.php](app/Models/Setting.php)

### Controllers (13)
1. [app/Http/Controllers/DashboardController.php](app/Http/Controllers/DashboardController.php)
2. [app/Http/Controllers/CctvController.php](app/Http/Controllers/CctvController.php)
3. [app/Http/Controllers/MaintenanceController.php](app/Http/Controllers/MaintenanceController.php)
4. [app/Http/Controllers/AlertController.php](app/Http/Controllers/AlertController.php)
5. [app/Http/Controllers/RecordingController.php](app/Http/Controllers/RecordingController.php)
6. [app/Http/Controllers/MessageController.php](app/Http/Controllers/MessageController.php)
7. [app/Http/Controllers/StreamController.php](app/Http/Controllers/StreamController.php)
8. [app/Http/Controllers/MapController.php](app/Http/Controllers/MapController.php)
9. [app/Http/Controllers/ExportController.php](app/Http/Controllers/ExportController.php)
10. [app/Http/Controllers/SearchController.php](app/Http/Controllers/SearchController.php)
11. [app/Http/Controllers/ReportController.php](app/Http/Controllers/ReportController.php)
12. [app/Http/Controllers/SettingsController.php](app/Http/Controllers/SettingsController.php)
13. [app/Http/Controllers/HealthCheckController.php](app/Http/Controllers/HealthCheckController.php)

### API Controllers (5)
1. [app/Http/Controllers/Api/CctvApiController.php](app/Http/Controllers/Api/CctvApiController.php)
2. [app/Http/Controllers/Api/BuildingApiController.php](app/Http/Controllers/Api/BuildingApiController.php)
3. [app/Http/Controllers/Api/RoomApiController.php](app/Http/Controllers/Api/RoomApiController.php)
4. [app/Http/Controllers/Api/MaintenanceApiController.php](app/Http/Controllers/Api/MaintenanceApiController.php)
5. [app/Http/Controllers/Api/AlertApiController.php](app/Http/Controllers/Api/AlertApiController.php)

### Services (19)
1. [app/Services/CctvService.php](app/Services/CctvService.php)
2. [app/Services/DashboardService.php](app/Services/DashboardService.php)
3. [app/Services/DashboardWidgetService.php](app/Services/DashboardWidgetService.php)
4. [app/Services/ExportService.php](app/Services/ExportService.php)
5. [app/Services/FfmpegStreamService.php](app/Services/FfmpegStreamService.php)
6. [app/Services/FileStorageService.php](app/Services/FileStorageService.php)
7. [app/Services/HealthCheckService.php](app/Services/HealthCheckService.php)
8. [app/Services/NotificationService.php](app/Services/NotificationService.php)
9. [app/Services/ReportService.php](app/Services/ReportService.php)
10. [app/Services/SearchService.php](app/Services/SearchService.php)
11. [app/Services/SettingsService.php](app/Services/SettingsService.php)
12. [app/Services/SystemMonitoringService.php](app/Services/SystemMonitoringService.php)
13. [app/Services/ValidationService.php](app/Services/ValidationService.php)
14. [app/Services/ApiResponseService.php](app/Services/ApiResponseService.php)
15. [app/Services/AuditService.php](app/Services/AuditService.php)
16. [app/Services/BackupService.php](app/Services/BackupService.php)
17. [app/Services/CacheService.php](app/Services/CacheService.php)
18. [app/Services/EventService.php](app/Services/EventService.php)
19. [app/Services/LoggingService.php](app/Services/LoggingService.php)

### Repositories (10)
1. [app/Repositories/BaseRepository.php](app/Repositories/BaseRepository.php)
2. [app/Repositories/CctvRepository.php](app/Repositories/CctvRepository.php)
3. [app/Repositories/BuildingRepository.php](app/Repositories/BuildingRepository.php)
4. [app/Repositories/RoomRepository.php](app/Repositories/RoomRepository.php)
5. [app/Repositories/UserRepository.php](app/Repositories/UserRepository.php)
6. [app/Repositories/ContactRepository.php](app/Repositories/ContactRepository.php)
7. [app/Repositories/MessageRepository.php](app/Repositories/MessageRepository.php)
8. [app/Repositories/MaintenanceRepository.php](app/Repositories/MaintenanceRepository.php)
9. [app/Repositories/AlertRepository.php](app/Repositories/AlertRepository.php)
10. [app/Repositories/RecordingRepository.php](app/Repositories/RecordingRepository.php)

### Middleware
1. [app/Http/Middleware/EnsureSuperAdmin.php](app/Http/Middleware/EnsureSuperAdmin.php)

### Providers
1. [app/Providers/AppServicesServiceProvider.php](app/Providers/AppServicesServiceProvider.php)
2. [app/Providers/Filament/AdminPanelProvider.php](app/Providers/Filament/AdminPanelProvider.php)

### Seeders (5)
1. [database/seeders/BuildingSeeder.php](database/seeders/BuildingSeeder.php)
2. [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)
3. [database/seeders/RolePermissionSeeder.php](database/seeders/RolePermissionSeeder.php)
4. [database/seeders/RoleSeeder.php](database/seeders/RoleSeeder.php)
5. [database/seeders/SettingsSeeder.php](database/seeders/SettingsSeeder.php)
6. [database/seeders/SuperAdminSeeder.php](database/seeders/SuperAdminSeeder.php)

### Factories (9)
1. [database/factories/BuildingFactory.php](database/factories/BuildingFactory.php)
2. [database/factories/RoomFactory.php](database/factories/RoomFactory.php)
3. [database/factories/CctvFactory.php](database/factories/CctvFactory.php)
4. [database/factories/ContactFactory.php](database/factories/ContactFactory.php)
5. [database/factories/MessageFactory.php](database/factories/MessageFactory.php)
6. [database/factories/MaintenanceFactory.php](database/factories/MaintenanceFactory.php)
7. [database/factories/AlertFactory.php](database/factories/AlertFactory.php)
8. [database/factories/RecordingFactory.php](database/factories/RecordingFactory.php)
9. [database/factories/UserFactory.php](database/factories/UserFactory.php)

### Filament Resources (6)
1. [app/Filament/Resources/Buildings/BuildingResource.php](app/Filament/Resources/Buildings/BuildingResource.php)
2. [app/Filament/Resources/Cctvs/CctvResource.php](app/Filament/Resources/Cctvs/CctvResource.php)
3. [app/Filament/Resources/Contacts/ContactResource.php](app/Filament/Resources/Contacts/ContactResource.php)
4. [app/Filament/Resources/Messages/MessageResource.php](app/Filament/Resources/Messages/MessageResource.php)
5. [app/Filament/Resources/Rooms/RoomResource.php](app/Filament/Resources/Rooms/RoomResource.php)
6. [app/Filament/Resources/Users/UserResource.php](app/Filament/Resources/Users/UserResource.php)

### Filament Widgets (4)
1. [app/Filament/Widgets/DashboardStats.php](app/Filament/Widgets/DashboardStats.php)
2. [app/Filament/Widgets/CctvStatusChart.php](app/Filament/Widgets/CctvStatusChart.php)
3. [app/Filament/Widgets/CctvOperationalTable.php](app/Filament/Widgets/CctvOperationalTable.php)
4. [app/Filament/Widgets/OfflineAlerts.php](app/Filament/Widgets/OfflineAlerts.php)

### Tests
1. [tests/Feature/BackendComprehensiveTest.php](tests/Feature/BackendComprehensiveTest.php)

### Routes
1. [routes/web.php](routes/web.php)
2. [routes/api.php](routes/api.php)

## Database Migrations
All migrations are located in [database/migrations/](database/migrations/)

## Configuration Files
- [.env.example](.env.example) - Environment configuration template
- [config/](config/) - Laravel configuration files

## Frontend Assets
- [resources/](resources/) - Views and frontend assets
- [public/](public/) - Publicly accessible files

## Package Management
- [composer.json](composer.json) - PHP dependencies
- [package.json](package.json) - JavaScript dependencies

## Testing
- [phpunit.xml](phpunit.xml) - PHPUnit configuration
- [tests/](tests/) - Test suite

## Development Tools
- [.editorconfig](.editorconfig) - Editor configuration
- [.gitignore](.gitignore) - Git ignore rules
- [vite.config.js](vite.config.js) - Vite configuration
