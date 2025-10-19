# Usage Guide: CCTV Monitoring System

## Getting Started

### 1. Starting the Application
```bash
# Navigate to project directory
cd d:\skrtee\atcs-kpi

# Start the development server
php artisan serve
```

### 2. Accessing the System
- **Frontend**: http://127.0.0.1:8000
- **Admin Panel**: http://127.0.0.1:8000/admin

### 3. Default Login Credentials
- **Email**: admin@example.com
- **Password**: password

## Admin Panel Navigation

### Infrastructure Management
1. **Buildings**
   - View all buildings
   - Create new buildings
   - Edit building details
   - Delete buildings

2. **Rooms**
   - Organize spaces within buildings
   - Track room capacity and location

3. **CCTVs**
   - Manage camera devices
   - Configure streaming settings
   - Monitor camera status
   - Start/stop streaming

4. **Contacts**
   - Maintain personnel directory
   - Store contact information

### Operations
1. **Maintenances**
   - Schedule maintenance work
   - Track work order status
   - Assign technicians

2. **Alerts**
   - Monitor system alerts
   - Acknowledge issues
   - Resolve problems

3. **Recordings**
   - Manage stored footage
   - Download recordings
   - Archive old footage

### Communication
1. **Messages**
   - Internal messaging system
   - Send/receive communications

### Administration
1. **Users**
   - Manage user accounts
   - Assign roles and permissions
   - Track user activity

2. **Roles & Permissions**
   - Configure access control
   - Manage role assignments

### Reports
1. **Dashboard**
   - View system statistics
   - Monitor infrastructure health
   - Track performance metrics

## Key Features Usage

### Real-Time Streaming
1. Navigate to CCTVs section
2. Select a camera
3. Click "Start Stream" button
4. View live footage in HLS format

### Status Monitoring
1. Dashboard shows real-time statistics
2. Offline cameras are highlighted
3. Alerts notify of system issues
4. Automatic status checks every 30 seconds

### Maintenance Management
1. Create maintenance records for CCTVs
2. Assign technicians to work orders
3. Track maintenance schedule
4. Update status as work progresses

### Alert System
1. System automatically generates alerts
2. Critical issues are prioritized
3. Alerts can be acknowledged and resolved
4. Notification system keeps users informed

## API Usage Examples

### Authentication
```bash
# Login to get access token
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

### Get CCTV List
```bash
# Get all CCTVs
curl -X GET http://127.0.0.1:8000/api/cctvs \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Start Streaming
```bash
# Start streaming for specific CCTV
curl -X POST http://127.0.0.1:8000/api/cctvs/1/start-stream \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Check Status
```bash
# Check CCTV status
curl -X GET http://127.0.0.1:8000/api/cctvs/1/check-status \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

## User Roles and Permissions

### Admin
- Full access to all features
- User management
- System configuration
- Role assignment

### Technician
- Manage maintenances
- Start/complete work orders
- Acknowledge alerts
- Access recordings

### Operator
- View CCTVs
- Start/stop streaming
- View alerts
- Access recordings

### Viewer
- View-only access
- Monitor system status
- View reports

## Troubleshooting

### Common Issues

1. **Streaming Not Working**
   - Check FFmpeg installation
   - Verify RTSP URL configuration
   - Ensure camera is accessible

2. **Login Issues**
   - Verify credentials
   - Check database connection
   - Reset password if needed

3. **Permission Errors**
   - Verify user role assignment
   - Check role permissions
   - Contact administrator

### System Maintenance

1. **Database Backups**
   ```bash
   php artisan backup:run
   ```

2. **Clear Cache**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Run Tests**
   ```bash
   php artisan test
   ```

## Customization

### Adding New Features
1. Create new model: `php artisan make:model Models/NewEntity -mf`
2. Create repository: `php artisan make:repository NewEntity`
3. Create service: `php artisan make:service NewEntityService`
4. Create controller: `php artisan make:controller NewEntityController`
5. Register service in AppServicesServiceProvider
6. Add routes in web.php and api.php
7. Create Filament resource if needed

### Modifying Permissions
1. Update RolePermissionSeeder
2. Run: `php artisan db:seed --class=RolePermissionSeeder`
3. Clear cache: `php artisan cache:clear`

## Performance Optimization

### Caching
- Enable caching in .env: `CACHE_DRIVER=redis`
- Configure cache settings in config/cache.php

### Database Optimization
- Add database indexes for frequently queried columns
- Use eager loading to prevent N+1 queries
- Implement pagination for large datasets

### Streaming Optimization
- Configure FFmpeg settings in config/services.php
- Optimize HLS segment size and duration
- Use CDN for streaming content delivery

## Security Best Practices

1. **Regular Updates**
   - Keep Laravel updated
   - Update dependencies regularly
   - Apply security patches

2. **Access Control**
   - Regularly review user permissions
   - Remove unused accounts
   - Implement least privilege principle

3. **Data Protection**
   - Encrypt sensitive data
   - Regular backups
   - Secure file storage

4. **Monitoring**
   - Enable audit logging
   - Monitor system logs
   - Set up alerting for suspicious activity

## Support

For issues or questions:
1. Check documentation files in the project root
2. Review error logs: `storage/logs/laravel.log`
3. Run tests to identify issues: `php artisan test`
4. Contact development team for assistance
