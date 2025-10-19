# CCTV Monitoring System - Backend Documentation

## Overview
This is a comprehensive backend system for a CCTV monitoring system with a SUPER ADMIN PANEL using Filament starter kits. The system provides complete infrastructure management, user interface with CRUD operations, and real-time monitoring capabilities.

## Key Features

### 1. Infrastructure Management
- **Buildings**: Manage building information with location data, contact details, and statistics
- **Rooms**: Organize spaces within buildings with capacity and location tracking
- **CCTVs**: Comprehensive camera management with RTSP streaming, status monitoring, and maintenance tracking
- **Contacts**: Maintain contact information for personnel and stakeholders

### 2. Monitoring & Operations
- **Real-time Streaming**: HLS streaming for low-latency CCTV viewing
- **Status Monitoring**: Automatic online/offline detection with alerts
- **Maintenance Management**: Schedule and track maintenance activities
- **Alert System**: Critical issue notifications with severity levels
- **Recording Management**: Storage and retrieval of recorded footage

### 3. Communication
- **Messaging System**: Internal communication between users
- **Notifications**: Real-time system notifications and updates

### 4. User Management
- **Role-Based Access Control**: Admin, Technician, Operator, and Viewer roles
- **Authentication**: Secure login with optional two-factor authentication
- **User Profiles**: Detailed user information with department and position tracking

### 5. Reporting & Analytics
- **Dashboard Statistics**: Real-time overview of system health
- **Infrastructure Reports**: Building and room analytics
- **CCTV Performance Reports**: Camera uptime and status tracking
- **Maintenance Reports**: Work order tracking and completion statistics
- **Alert Reports**: Issue tracking and resolution metrics

## Technical Architecture

### Core Components
1. **Laravel 12 Framework**: Modern PHP framework with robust features
2. **Filament Admin Panel**: Beautiful admin interface with Bxs icons and navigation groups
3. **Spatie Permissions**: Advanced role-based access control
4. **Repository Pattern**: Clean data access layer abstraction
5. **Service Layer**: Business logic separation and organization
6. **Event-Driven System**: Observers and listeners for automatic actions
7. **RESTful API**: Comprehensive API for external integrations

### Database Structure
- **Users**: Authentication and profile management
- **Buildings**: Physical infrastructure tracking
- **Rooms**: Space organization within buildings
- **CCTVs**: Camera devices with streaming capabilities
- **Contacts**: Personnel and stakeholder information
- **Messages**: Internal communication system
- **Maintenances**: Work order and service tracking
- **Alerts**: System notifications and issue tracking
- **Recordings**: Stored video footage management
- **Settings**: System configuration options

### API Endpoints
- **CCTV API**: Camera management and streaming control
- **Building API**: Infrastructure data access
- **Room API**: Space management endpoints
- **Maintenance API**: Service scheduling and tracking
- **Alert API**: Notification system integration
- **Health Check API**: System status monitoring

### Security Features
- **Role-Based Permissions**: Granular access control for all operations
- **Secure Authentication**: Password hashing and session management
- **Two-Factor Authentication**: Optional enhanced security
- **Input Validation**: Comprehensive data validation and sanitization
- **CSRF Protection**: Prevention of cross-site request forgery attacks

## Super Admin Panel Features

### Navigation Groups
1. **Infrastructure**: Buildings, Rooms, CCTVs, Contacts
2. **Operations**: Maintenances, Alerts, Recordings
3. **Communication**: Messages
4. **Administration**: Users, Roles, Permissions
5. **Reports**: System analytics and statistics

### Icon System
All Filament resources use Bxs icons for consistent visual navigation:
- Buildings: `bx-building`
- Rooms: `bx-home`
- CCTVs: `bx-video`
- Contacts: `bx-user`
- Maintenances: `bx-wrench`
- Alerts: `bx-error`
- Recordings: `bx-video-recording`
- Messages: `bx-message`
- Users: `bx-user`
- Reports: `bx-bar-chart-alt-2`

## Installation & Setup

### Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- Database (MySQL/PostgreSQL/SQLite)
- FFmpeg (for streaming capabilities)

### Installation Steps
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure database settings
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Run `php artisan serve`
7. Access the admin panel at `/admin`

### Default Credentials
- Email: admin@example.com
- Password: password

## Testing
Comprehensive test suite covering:
- CRUD operations for all entities
- Role-based access control validation
- API endpoint functionality
- Dashboard statistics generation
- System health checks

## Customization
The system is designed for easy extension:
- Add new infrastructure types
- Create custom reports and dashboards
- Implement additional notification channels
- Extend role-based permissions
- Add new API endpoints

## Maintenance
- Regular database backups
- FFmpeg process monitoring
- Log file rotation
- Performance optimization
- Security updates
