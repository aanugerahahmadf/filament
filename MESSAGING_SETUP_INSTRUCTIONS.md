# Enhanced Messaging System Setup Instructions

## Overview
This document provides instructions for setting up and running the enhanced messaging system with typing indicators, read receipts, and real-time features.

## Prerequisites
1. PHP 8.1 or higher
2. MySQL or MariaDB database server
3. Composer
4. Node.js and NPM
5. Laravel Reverb server

## Database Setup

### 1. Start MySQL Server
Make sure your MySQL server is running:
- If using XAMPP: Start MySQL from the XAMPP Control Panel
- If using WAMP: Start MySQL from the WAMP interface
- If using standalone MySQL: Start the service with `net start mysql` (using the correct service name)

### 2. Create Database
Create the database specified in your `.env` file:
```sql
CREATE DATABASE Kilang_pertamina_international_db;
```

### 3. Run Migrations
After the database is running, execute:
```bash
php artisan migrate
```

## Configuration

### 1. Environment Variables
Ensure your `.env` file has the correct database configuration:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Kilang_pertamina_international_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2. Broadcasting Configuration
Set up broadcasting in your `.env` file:
```env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST="localhost"
REVERB_PORT=8080
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Starting Services

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Compile Assets
```bash
npm run dev
```

### 3. Start Reverb Server
```bash
php artisan reverb:start
```

### 4. Start Development Server
In a separate terminal:
```bash
php artisan serve
```

## Features Implemented

### Typing Indicators
- Real-time typing notifications
- Visual feedback with animated dots
- Automatic timeout handling

### Read Receipts
- Message delivery confirmation
- Read status tracking
- Visual indicators for all message statuses

### Enhanced Message Deletion
- Improved delete functionality
- Better confirmation dialogs
- Proper authorization checks

### Real-time Updates
- Instant message delivery
- Live status updates
- Seamless user experience

## Testing the Features

### 1. Typing Indicators
1. Open two browser windows with different user accounts
2. Navigate to the messaging page
3. Start typing in one window
4. Observe the typing indicator in the other window

### 2. Read Receipts
1. Send a message from one user to another
2. Observe the "Sent" status
3. Open the conversation as the recipient
4. Observe the "Read" status on the sender's side

### 3. Message Deletion
1. Send a message
2. Click the delete button
3. Confirm deletion
4. Verify the message is removed

## Troubleshooting

### Database Connection Issues
1. Verify MySQL service is running
2. Check database credentials in `.env`
3. Ensure the database exists
4. Verify firewall settings

### Real-time Features Not Working
1. Confirm Reverb server is running
2. Check broadcasting configuration
3. Verify JavaScript is loading correctly
4. Check browser console for errors

### Common Solutions
1. Clear cache: `php artisan cache:clear`
2. Clear configuration cache: `php artisan config:clear`
3. Clear view cache: `php artisan view:clear`
4. Restart all services

## File Structure
```
app/
├── Events/
│   ├── UserTyping.php
│   ├── MessageDelivered.php
│   ├── MessageReadReceipt.php
│   └── MessageSent.php
├── Http/Controllers/
│   └── MessageController.php
├── Models/
│   └── Message.php
database/
└── migrations/
    └── 2025_10_12_152139_add_typing_and_read_receipts_to_messages_table.php
resources/
├── js/
│   └── app.js
└── views/
    └── messages.blade.php
routes/
└── web.php
```

## Additional Documentation
- [ENHANCED_MESSAGING_FEATURES.md](ENHANCED_MESSAGING_FEATURES.md) - Detailed feature documentation
- [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) - Implementation overview
- [USER_GUIDE_MESSAGING.md](USER_GUIDE_MESSAGING.md) - User guide

## Support
For issues with the messaging system, please check:
1. Database connectivity
2. Broadcasting configuration
3. Service status (MySQL, Reverb)
4. File permissions
