# BookEasy (Backend)

A BookEasy built with Laravel 12, designed to streamline appointment scheduling for service-based businesses.

## 🚀 Features

### Core Functionality
- **Advanced Booking System**: Create, manage, and track appointments with real-time availability
- **Flexible Working Hours**: Configure different working hours for each day of the week
- **Slot Management**: Automatic time slot generation with configurable durations
- **Client Management**: Find or create client profiles automatically

### Technical Features
- **RESTful API**: Clean API endpoints with versioning (`/api/v1/`)
- **Email Notifications**: Automated booking confirmations with professional templates
- **Queue Processing**: Async email delivery and background job processing
- **Database Transactions**: Data integrity with atomic operations
- **Comprehensive Validation**: Form request validation with detailed error messages

### Security & Performance
- **Input Validation**: Comprehensive validation for all endpoints
- **Rate Limiting**: Built-in throttling to prevent abuse

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your system:

### Required
- **PHP 8.3 or higher**
- **Composer 2.8 or higher**

### Optional (Recommended for Development)
- **Mailpit** - Local email testing server (for testing email notifications)
- **SQLite** - Default database (file-based, no additional setup required)

## 🛠️ Installation & Setup

### 1. Project Setup
```bash
composer setup
```
Composer setup script will perform following
- Install dependancy
- Create .env file from .env.example
- Create SQLite database
- Generate application key
- Migration and seeding of database

### 2. Configure Environment Variables

Edit your `.env` file and update the following values:

```env
# Application URL
APP_URL=http://127.0.0.1:8000

# API Token
APP_TOKEN=your-secure-api-token-here

# Maximum days in advance for bookings
MAX_BOOKING_DAYS_IN_FUTURE=60
```

> **⚠️ Important**: The `APP_TOKEN` value must be identical in both backend and frontend applications for API communication to work properly.

### 3. Start the Development Server
```bash
# Start Laravel development server
php artisan serve

# The application will be available at: http://127.0.0.1:8000
```

### 4. (Optional) Start Background Services
```bash
# Start queue worker for email processing
php artisan queue:work --tries=1
```

## 📧 Email Setup

Application use "failover" driver for email configuration which use mailpit and log driver internally.
If you have mailpit installed on local kindly access mailpit at http://localhost:8025
Otherwise mail will be printed in log file

## 🧪 Testing

To run the test kindly run following command

```bash

php vendor/bin/pest
```

## 📚 API Documentation

### Authentication
All API requests require the `X-Api-Token` in the header:
```
X-Api-Token: your-secure-api-token-here
```

### Available Endpoints

#### Bookings
- `GET /api/v1/booking` - List date wise booking count
- `POST /api/v1/booking` - Create new booking


#### Working Hours
- `GET /api/v1/working-hour` - Get working hours
- `POST /api/v1/working-hour` - Save or update working hours

#### Slots
- `GET /api/v1/slot` - Get available time slots for the day
```

## 🏗️ Project Structure

```
├── app/
│   ├── Contracts/          # Interface definitions
│   ├── Events/             # Event classes
│   ├── Http/Controllers/Api   # API controllers
│   └── Http/Requests/         # API Validations
│   ├── Listeners/          # Event listeners
│   ├── Mail/              # Email templates
│   ├── Models/            # Eloquent models
│   ├── Notifications/     # Notification classes
│   ├── Services/          # Business logic services
│   └── Providers/         # Service providers
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   └── views/emails/      # Email templates
├── routes/v1
│   └── api.php           # API routes
└── tests/                # Test files
```
