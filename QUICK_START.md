# Quick Start Guide

## 🚀 Get Up and Running in 5 Minutes

### Step 1: Start the Development Server
```bash
cd d:\skrtee\atcs-kpi
php artisan serve
```

### Step 2: Access the Application
- **Frontend**: http://127.0.0.1:8000
- **Admin Panel**: http://127.0.0.1:8000/admin

### Step 3: Login with Default Credentials
- **Email**: admin@example.com
- **Password**: password

## 🎯 Key Features to Explore

### Admin Panel Navigation
1. **Infrastructure** → Buildings, Rooms, CCTVs, Contacts
2. **Operations** → Maintenances, Alerts, Recordings
3. **Communication** → Messages
4. **Administration** → Users, Roles
5. **Reports** → Dashboard statistics

### Real-Time Features
- **Live Streaming**: Click any CCTV → "Start Stream"
- **Status Monitoring**: Dashboard shows real-time stats
- **Alert System**: Automatic notifications for issues

## 🛠️ Common Tasks

### Add New CCTV Camera
1. Go to Admin Panel → Infrastructure → CCTVs
2. Click "Create"
3. Fill in camera details (RTSP URL, credentials)
4. Save and test connection

### Schedule Maintenance
1. Go to Admin Panel → Operations → Maintenances
2. Click "Create"
3. Select CCTV and assign technician
4. Set schedule date and description

### View Reports
1. Go to Admin Panel → Reports
2. View dashboard statistics
3. Export data if needed

## 🔧 System Commands

### Useful Artisan Commands
```bash
# Start development server
php artisan serve

# Run tests
php artisan test

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run database migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create new model
php artisan make:model Models/Entity -mf

# Create new controller
php artisan make:controller EntityController
```

## 📚 Documentation

### Essential Reading
1. [USAGE_GUIDE.md](USAGE_GUIDE.md) - How to use the system
2. [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md) - Complete architecture
3. [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - What we built
4. [README.md](README.md) - Main documentation

## 🆘 Need Help?

### Quick Troubleshooting
1. **Can't login**: Check credentials (admin@example.com/password)
2. **Streaming not working**: Verify FFmpeg installation and RTSP URL
3. **Permission errors**: Check user roles in Admin Panel
4. **Database issues**: Run `php artisan migrate:fresh --seed`

### Support Resources
- Check documentation files in project root
- Review error logs: `storage/logs/laravel.log`
- Run tests: `php artisan test`
- Contact development team

## ✅ System Status

- **Database**: ✅ Connected and seeded
- **Admin User**: ✅ Created (admin@example.com)
- **API**: ✅ Available at /api/*
- **Streaming**: ✅ Ready (requires FFmpeg)
- **Testing**: ✅ All tests passing

## 🎉 You're Ready!

The complete CCTV monitoring system is now running. Explore the admin panel to manage your infrastructure, monitor cameras, schedule maintenance, and view real-time statistics.

For detailed information, refer to the comprehensive documentation in the project root directory.
