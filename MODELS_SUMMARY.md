# Models Summary

## Core Models

### 1. User
**File**: `app/Models/User.php`
**Table**: users
**Key Features**:
- Authentication and profile management
- Role-based access control integration
- Online/offline status tracking
- Department and position information
- Last seen timestamp
- Avatar support

### 2. Building
**File**: `app/Models/Building.php`
**Table**: buildings
**Key Features**:
- Name, description, and address
- Geolocation coordinates (latitude/longitude)
- Contact information (person, phone, email)
- Relationship counts for rooms and CCTVs
- Infrastructure statistics

### 3. Room
**File**: `app/Models/Room.php`
**Table**: rooms
**Key Features**:
- Building relationship
- Name, description, and floor information
- Capacity tracking
- Geolocation coordinates
- CCTV status statistics

### 4. Cctv
**File**: `app/Models/Cctv.php`
**Table**: cctvs
**Key Features**:
- Building and room relationships
- Comprehensive device information (model, serial, firmware)
- Streaming configuration (RTSP URL, username, password)
- Technical specifications (resolution, FPS, port)
- Status management (online, offline, maintenance)
- Geolocation tracking
- Recording schedule
- HLS streaming path
- Status badge classes

### 5. Contact
**File**: `app/Models/Contact.php`
**Table**: contacts
**Key Features**:
- Personal information (name, email, phone)
- Social media profiles (WhatsApp, Instagram, Facebook, LinkedIn)
- Professional details (position, department)
- Address information
- Avatar support

### 6. Message
**File**: `app/Models/Message.php`
**Table**: messages
**Key Features**:
- Sender and recipient relationships (User)
- Message content (body, subject)
- Categorization (type, priority)
- Status tracking (read, archived)
- Timestamp management

### 7. Maintenance
**File**: `app/Models/Maintenance.php`
**Table**: maintenances
**Key Features**:
- CCTV relationship
- Technician assignment
- Scheduling (scheduled, started, completed timestamps)
- Status tracking (scheduled, in-progress, completed, cancelled)
- Type classification (preventive, corrective, emergency)
- Cost tracking
- Description and notes

### 8. Alert
**File**: `app/Models/Alert.php`
**Table**: alerts
**Key Features**:
- Polymorphic relationship (alertable: CCTV, Maintenance, etc.)
- Title and detailed message
- Severity levels (critical, high, medium, low, info)
- Category classification (hardware, network, system, maintenance)
- Source tracking
- Status management (triggered, acknowledged, resolved, suppressed)
- Timestamp tracking for all status changes
- Additional data storage (JSON)

### 9. Recording
**File**: `app/Models/Recording.php`
**Table**: recordings
**Key Features**:
- CCTV relationship
- File information (filename, filepath, size)
- Technical details (duration, format, resolution)
- Status tracking (active, archived, deleted)
- Timestamp management (started, ended)
- Storage path

### 10. Setting
**File**: `app/Models/Setting.php`
**Table**: settings
**Key Features**:
- Key-value storage for system configuration
- Group organization
- Data type support (string, integer, boolean, array)
- Description field for documentation

## Relationship Mapping

### User
- Has many: Messages (sent)
- Has many: Messages (received)
- Has many: Maintenances (as technician)
- Has many: Alerts (through polymorphic relationship)
- Has many: Recordings (through CCTV)

### Building
- Has many: Rooms
- Has many: CCTVs
- Has many: Contacts (through relationships)

### Room
- Belongs to: Building
- Has many: CCTVs

### Cctv
- Belongs to: Building
- Belongs to: Room (nullable)
- Has many: Maintenances
- Has many: Alerts
- Has many: Recordings
- Has many: Messages (through relationships)

### Contact
- Belongs to: Building (nullable)
- Belongs to: Room (nullable)

### Message
- Belongs to: User (sender)
- Belongs to: User (recipient)

### Maintenance
- Belongs to: CCTV
- Belongs to: User (technician)
- Has many: Alerts

### Alert
- Morph to: Alertable (CCTV, Maintenance, etc.)
- Belongs to: User (acknowledged by)

### Recording
- Belongs to: CCTV

## Scopes and Helper Methods

### User
- Online/Offline scopes
- Status checking methods
- Role and permission helpers

### Building
- Search scope
- Infrastructure statistics

### Room
- Search scope
- Building filter
- CCTV status statistics

### Cctv
- Status scopes (online, offline, maintenance)
- Search functionality
- Streaming helpers (RTSP URL, HLS path)
- Status badge methods

### Contact
- Search scope
- Building/Room filters

### Message
- Read/Unread scopes
- Archived/Unarchived scopes
- Type and priority filters

### Maintenance
- Status scopes
- Type filters
- Date range filters

### Alert
- Severity scopes
- Category filters
- Status filters
- Date range filters

### Recording
- Status scopes
- Date range filters

## Computed Properties

### User
- Initials
- Full name with position
- Status badge class and text

### Building
- Total rooms count
- Total CCTVs count
- Online CCTVs count
- Offline CCTVs count
- Maintenance CCTVs count

### Room
- Total CCTVs count
- Online CCTVs count
- Offline CCTVs count
- Maintenance CCTVs count

### Cctv
- Full RTSP URL
- HLS URL
- Status badge class
- Status text
- Online status check

### Message
- Priority badge class
- Type badge class

### Alert
- Severity badge class
- Status badge class
- Category badge class

## Observers

### AuditObserver
- Automatically logs all model changes
- Tracks creation, updates, and deletions
- Stores user information when available

## Factories

Each model has a corresponding factory for testing:
- BuildingFactory
- RoomFactory
- CctvFactory
- ContactFactory
- MessageFactory
- MaintenanceFactory
- AlertFactory
- RecordingFactory
- UserFactory

## Repositories

Each model has a corresponding repository for data access:
- BuildingRepository
- RoomRepository
- CctvRepository
- ContactRepository
- MessageRepository
- MaintenanceRepository
- AlertRepository
- RecordingRepository
- UserRepository

## Services

Comprehensive service layer for business logic:
- CctvService: Streaming, status checking, maintenance scheduling
- DashboardService: Statistics and overview data
- ExportService: Data export functionality
- FileStorageService: File management
- HealthCheckService: System health monitoring
- NotificationService: Alert and notification management
- ReportService: Reporting capabilities
- SearchService: Global search functionality
- SettingsService: Configuration management
- SystemMonitoringService: Performance monitoring
- ValidationService: Data validation
- ApiResponseService: Standardized API responses
- AuditService: Logging and auditing
- BackupService: Data backup operations
- CacheService: Caching operations
- EventService: Event management
- FfmpegStreamService: FFmpeg streaming operations
- LoggingService: Advanced logging
