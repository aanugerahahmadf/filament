# Controllers and API Endpoints Summary

## Web Controllers

### 1. DashboardController
**File**: `app/Http/Controllers/DashboardController.php`
**Routes**: `/dashboard`
**Methods**:
- `index()`: Display the main dashboard with statistics

### 2. CctvController
**File**: `app/Http/Controllers/CctvController.php`
**Routes**: `/cctvs`
**Methods**:
- `index()`: List all CCTVs with pagination
- `create()`: Show form to create new CCTV
- `store()`: Store new CCTV in database
- `show()`: Display specific CCTV details
- `edit()`: Show form to edit CCTV
- `update()`: Update existing CCTV
- `destroy()`: Delete CCTV
- `startStream()`: Start HLS streaming for CCTV
- `stopStream()`: Stop HLS streaming for CCTV
- `checkStatus()`: Check CCTV connectivity status
- `statistics()`: Get CCTV status statistics
- `mapData()`: Get geolocation data for map display

### 3. MaintenanceController
**File**: `app/Http/Controllers/MaintenanceController.php`
**Routes**: `/maintenances`
**Methods**:
- `index()`: List all maintenance records
- `create()`: Show form to create new maintenance record
- `store()`: Store new maintenance record
- `show()`: Display specific maintenance details
- `edit()`: Show form to edit maintenance record
- `update()`: Update existing maintenance record
- `destroy()`: Delete maintenance record
- `start()`: Mark maintenance as in-progress
- `complete()`: Mark maintenance as completed
- `cancel()`: Cancel maintenance record
- `statistics()`: Get maintenance statistics

### 4. AlertController
**File**: `app/Http/Controllers/AlertController.php`
**Routes**: `/alerts`
**Methods**:
- `index()`: List all alerts
- `create()`: Show form to create new alert
- `store()`: Store new alert
- `show()`: Display specific alert details
- `edit()`: Show form to edit alert
- `update()`: Update existing alert
- `destroy()`: Delete alert
- `acknowledge()`: Mark alert as acknowledged
- `resolve()`: Mark alert as resolved
- `suppress()`: Suppress alert notifications
- `statistics()`: Get alert statistics

### 5. RecordingController
**File**: `app/Http/Controllers/RecordingController.php`
**Routes**: `/recordings`
**Methods**:
- `index()`: List all recordings
- `create()`: Show form to create new recording
- `store()`: Store new recording
- `show()`: Display specific recording details
- `edit()`: Show form to edit recording
- `update()`: Update existing recording
- `destroy()`: Delete recording
- `archive()`: Archive recording
- `restore()`: Restore archived recording
- `download()`: Download recording file
- `statistics()`: Get recording statistics

### 6. MessageController
**File**: `app/Http/Controllers/MessageController.php`
**Routes**: `/messages`
**Methods**:
- `index()`: List user messages
- `store()`: Send new message

### 7. StreamController
**File**: `app/Http/Controllers/StreamController.php`
**Routes**: `/stream/{cctv}`
**Methods**:
- `start()`: Start streaming for CCTV
- `stop()`: Stop streaming for CCTV

### 8. MapController
**File**: `app/Http/Controllers/MapController.php`
**Routes**: `/maps`, `/map-data`, `/location-data`
**Methods**:
- `data()`: Get map data
- `locationData()`: Get location data

### 9. ExportController
**File**: `app/Http/Controllers/ExportController.php`
**Routes**: `/export/*`
**Methods**:
- `buildings()`: Export buildings data
- `rooms()`: Export rooms data
- `cctvs()`: Export CCTVs data
- `users()`: Export users data
- `contacts()`: Export contacts data
- `stats()`: Export statistics

### 10. SearchController
**File**: `app/Http/Controllers/SearchController.php`
**Routes**: `/search`, `/api/search`, `/api/global-search`
**Methods**:
- `index()`: Display search interface
- `apiSearch()`: API endpoint for search
- `globalSearch()`: Global search across all entities

### 11. ReportController
**File**: `app/Http/Controllers/ReportController.php`
**Routes**: `/reports/*`
**Methods**:
- `index()`: Display reports dashboard
- `cctvStatus()`: CCTV status report
- `maintenance()`: Maintenance report
- `alerts()`: Alerts report
- `infrastructure()`: Infrastructure report

### 12. SettingsController
**File**: `app/Http/Controllers/SettingsController.php`
**Routes**: `/settings/*`
**Methods**:
- `index()`: Display settings interface
- `updateSystem()`: Update system settings
- `updateCctv()`: Update CCTV settings
- `updateNotifications()`: Update notification settings
- `updateMaintenance()`: Update maintenance settings
- `updateStorage()`: Update storage settings

### 13. HealthCheckController
**File**: `app/Http/Controllers/HealthCheckController.php`
**Routes**: `/api/health/*`
**Methods**:
- `index()`: System health overview
- `alerts()`: Active alerts health check

## API Controllers

### 1. CctvApiController
**File**: `app/Http/Controllers/Api/CctvApiController.php`
**Routes**: `/api/cctvs/*`
**Methods**:
- `index()`: Get all CCTVs
- `show()`: Get specific CCTV
- `checkStatus()`: Check CCTV status
- `startStream()`: Start streaming
- `stopStream()`: Stop streaming
- `statistics()`: Get statistics
- `mapData()`: Get map data

### 2. BuildingApiController
**File**: `app/Http/Controllers/Api/BuildingApiController.php`
**Routes**: `/api/buildings/*`
**Methods**:
- `index()`: Get all buildings
- `show()`: Get specific building
- `statistics()`: Get building statistics

### 3. RoomApiController
**File**: `app/Http/Controllers/Api/RoomApiController.php`
**Routes**: `/api/rooms/*`
**Methods**:
- `index()`: Get all rooms
- `show()`: Get specific room
- `statistics()`: Get room statistics

### 4. MaintenanceApiController
**File**: `app/Http/Controllers/Api/MaintenanceApiController.php`
**Routes**: `/api/maintenances/*`
**Methods**:
- `index()`: Get all maintenances
- `show()`: Get specific maintenance
- `statistics()`: Get maintenance statistics

### 5. AlertApiController
**File**: `app/Http/Controllers/Api/AlertApiController.php`
**Routes**: `/api/alerts/*`
**Methods**:
- `index()`: Get all alerts
- `show()`: Get specific alert
- `statistics()`: Get alert statistics

## API Endpoints

### Authentication
- `POST /api/login`: User login
- `POST /api/logout`: User logout
- `POST /api/register`: User registration
- `POST /api/forgot-password`: Password reset request
- `POST /api/reset-password`: Password reset

### Dashboard
- `GET /api/dashboard`: Get dashboard data
- `GET /api/dashboard/statistics`: Get system statistics

### CCTVs
- `GET /api/cctvs`: Get all CCTVs
- `GET /api/cctvs/{cctv}`: Get specific CCTV
- `POST /api/cctvs`: Create new CCTV
- `PUT /api/cctvs/{cctv}`: Update CCTV
- `DELETE /api/cctvs/{cctv}`: Delete CCTV
- `GET /api/cctvs/{cctv}/check-status`: Check CCTV status
- `POST /api/cctvs/{cctv}/start-stream`: Start streaming
- `POST /api/cctvs/{cctv}/stop-stream`: Stop streaming
- `GET /api/cctvs/statistics`: Get CCTV statistics
- `GET /api/cctvs/map-data`: Get map data

### Buildings
- `GET /api/buildings`: Get all buildings
- `GET /api/buildings/{building}`: Get specific building
- `POST /api/buildings`: Create new building
- `PUT /api/buildings/{building}`: Update building
- `DELETE /api/buildings/{building}`: Delete building
- `GET /api/buildings/{building}/statistics`: Get building statistics

### Rooms
- `GET /api/rooms`: Get all rooms
- `GET /api/rooms/{room}`: Get specific room
- `POST /api/rooms`: Create new room
- `PUT /api/rooms/{room}`: Update room
- `DELETE /api/rooms/{room}`: Delete room
- `GET /api/rooms/{room}/statistics`: Get room statistics

### Maintenances
- `GET /api/maintenances`: Get all maintenances
- `GET /api/maintenances/{maintenance}`: Get specific maintenance
- `POST /api/maintenances`: Create new maintenance
- `PUT /api/maintenances/{maintenance}`: Update maintenance
- `DELETE /api/maintenances/{maintenance}`: Delete maintenance
- `POST /api/maintenances/{maintenance}/start`: Start maintenance
- `POST /api/maintenances/{maintenance}/complete`: Complete maintenance
- `POST /api/maintenances/{maintenance}/cancel`: Cancel maintenance
- `GET /api/maintenances/statistics`: Get maintenance statistics

### Alerts
- `GET /api/alerts`: Get all alerts
- `GET /api/alerts/{alert}`: Get specific alert
- `POST /api/alerts`: Create new alert
- `PUT /api/alerts/{alert}`: Update alert
- `DELETE /api/alerts/{alert}`: Delete alert
- `POST /api/alerts/{alert}/acknowledge`: Acknowledge alert
- `POST /api/alerts/{alert}/resolve`: Resolve alert
- `POST /api/alerts/{alert}/suppress`: Suppress alert
- `GET /api/alerts/statistics`: Get alert statistics

### Recordings
- `GET /api/recordings`: Get all recordings
- `GET /api/recordings/{recording}`: Get specific recording
- `POST /api/recordings`: Create new recording
- `PUT /api/recordings/{recording}`: Update recording
- `DELETE /api/recordings/{recording}`: Delete recording
- `POST /api/recordings/{recording}/archive`: Archive recording
- `POST /api/recordings/{recording}/restore`: Restore recording
- `GET /api/recordings/{recording}/download`: Download recording
- `GET /api/recordings/statistics`: Get recording statistics

### Messages
- `GET /api/messages`: Get user messages
- `POST /api/messages`: Send new message
- `PUT /api/messages/{message}`: Update message
- `DELETE /api/messages/{message}`: Delete message
- `POST /api/messages/{message}/read`: Mark message as read
- `POST /api/messages/{message}/unread`: Mark message as unread

### Contacts
- `GET /api/contacts`: Get all contacts
- `GET /api/contacts/{contact}`: Get specific contact
- `POST /api/contacts`: Create new contact
- `PUT /api/contacts/{contact}`: Update contact
- `DELETE /api/contacts/{contact}`: Delete contact

### Users
- `GET /api/users`: Get all users (admin only)
- `GET /api/users/{user}`: Get specific user
- `POST /api/users`: Create new user (admin only)
- `PUT /api/users/{user}`: Update user
- `DELETE /api/users/{user}`: Delete user (admin only)
- `GET /api/users/{user}/permissions`: Get user permissions
- `POST /api/users/{user}/permissions`: Update user permissions (admin only)

### Reports
- `GET /api/reports/cctv-status`: Get CCTV status report
- `GET /api/reports/maintenance`: Get maintenance report
- `GET /api/reports/alerts`: Get alerts report
- `GET /api/reports/infrastructure`: Get infrastructure report
- `GET /api/reports/export`: Export report data

### Settings
- `GET /api/settings`: Get system settings
- `PUT /api/settings`: Update system settings (admin only)
- `GET /api/settings/{group}`: Get specific settings group
- `PUT /api/settings/{group}`: Update specific settings group

### Health Check
- `GET /api/health`: Get system health status
- `GET /api/health/alerts`: Get active alerts
- `GET /api/health/services`: Get service status
- `GET /api/health/database`: Get database status

### Search
- `GET /api/search`: Search across all entities
- `GET /api/search/{entity}`: Search specific entity

## Middleware

### Authentication Middleware
- `auth`: Ensure user is authenticated
- `auth:sanctum`: API token authentication
- `verified`: Ensure email is verified

### Role-Based Access Control
- `role:admin`: Admin-only access
- `role:technician`: Technician access
- `role:operator`: Operator access
- `role:viewer`: Viewer access
- `permission:view cctvs`: Specific permission check

### Custom Middleware
- `EnsureSuperAdmin`: Restrict access to super admin users only

## Resource Routes

All controllers that extend the base Controller class follow RESTful conventions:
- `index`: GET /
- `create`: GET /create
- `store`: POST /
- `show`: GET /{id}
- `edit`: GET /{id}/edit
- `update`: PUT/PATCH /{id}
- `destroy`: DELETE /{id}

## API Response Format

All API endpoints return consistent JSON responses:
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {},
  "meta": {}
}
```

Error responses:
```json
{
  "success": false,
  "message": "Error description",
  "errors": {}
}
```

## Validation

All controllers implement comprehensive validation:
- Required fields validation
- Data type validation
- Format validation (email, IP, etc.)
- Relationship validation
- Custom validation rules

## Error Handling

Controllers implement proper error handling:
- 404 for not found resources
- 403 for forbidden access
- 422 for validation errors
- 500 for server errors
- Custom error messages for better UX
