# CCTV Monitoring System

A comprehensive backend system for CCTV monitoring with SUPER ADMIN PANEL using Filament starter kits.

## Features

### Infrastructure Management
- **Buildings**: Manage building information with location data
- **Rooms**: Organize spaces within buildings
- **CCTVs**: Comprehensive camera management with RTSP streaming
- **Contacts**: Maintain contact information

### Monitoring & Operations
- **Real-time Streaming**: HLS streaming for low-latency viewing
- **Status Monitoring**: Automatic online/offline detection
- **Maintenance Management**: Schedule and track maintenance
- **Alert System**: Critical issue notifications
- **Recording Management**: Storage and retrieval of footage

### Communication
- **Messaging System**: Internal communication
- **Notifications**: Real-time system notifications

### User Management
- **Role-Based Access Control**: Admin, Technician, Operator, Viewer
- **Authentication**: Secure login with 2FA support
- **User Profiles**: Detailed user information

### Reporting & Analytics
- **Dashboard Statistics**: Real-time system overview
- **Infrastructure Reports**: Building and room analytics
- **Performance Reports**: Camera uptime tracking
- **Maintenance Reports**: Work order tracking
- **Alert Reports**: Issue tracking

## Technical Architecture

### Core Technologies
- **Laravel 12**: Modern PHP framework
- **Filament**: Beautiful admin panel with Bxs icons
- **Spatie Permissions**: Advanced role-based access
- **Repository Pattern**: Clean data access layer
- **Service Layer**: Business logic organization
- **Event-Driven**: Observers for automatic actions
- **RESTful API**: External integration support

### Database Structure
- Users, Buildings, Rooms, CCTVs, Contacts
- Messages, Maintenances, Alerts, Recordings
- Settings, Roles, Permissions

### Security Features
- Role-Based Permissions
- Secure Authentication
- Two-Factor Authentication
- Input Validation
- CSRF Protection

## Installation

### System Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- Database (MySQL/PostgreSQL/SQLite)
- FFmpeg (for streaming)

### Setup
```bash
# Clone repository
git clone <repository-url>
cd atcs-kpi

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite

# Run migrations and seeders
php artisan migrate --seed

# Create super admin user
php artisan db:seed --class=SuperAdminSeeder

# Start development server
php artisan serve
```

### Default Credentials
- Email: admin@example.com
- Password: password

## API Documentation

### Authentication
```bash
# Login
POST /api/login
{
  "email": "admin@example.com",
  "password": "password"
}

# Get authenticated user
GET /api/user
Authorization: Bearer {token}
```

### CCTVs
```bash
# Get all CCTVs
GET /api/cctvs

# Get specific CCTV
GET /api/cctvs/{id}

# Start streaming
POST /api/cctvs/{id}/start-stream

# Check status
GET /api/cctvs/{id}/check-status

# Get statistics
GET /api/cctvs/statistics
```

### Buildings
```bash
# Get all buildings
GET /api/buildings

# Get building statistics
GET /api/buildings/{id}/statistics
```

### Maintenances
```bash
# Get all maintenances
GET /api/maintenances

# Get maintenance statistics
GET /api/maintenances/statistics
```

### Alerts
```bash
# Get all alerts
GET /api/alerts

# Get alert statistics
GET /api/alerts/statistics
```

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter BackendComprehensiveTest

# Run tests in parallel
php artisan test --parallel
```

## Documentation

- [System Documentation](SYSTEM_DOCUMENTATION.md)
- [Models Summary](MODELS_SUMMARY.md)
- [Controllers & API Summary](CONTROLLERS_API_SUMMARY.md)
- [Services & Repositories](SERVICES_REPOSITORIES_SUMMARY.md)

## License

This project is proprietary and confidential. All rights reserved.

## Support

For support, please contact the development team.
