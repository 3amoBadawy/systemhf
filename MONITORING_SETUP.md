# Monitoring & Debugging Packages Setup

This document summarizes the installation and configuration of monitoring and debugging packages for the SystemHF Laravel application.

## 📦 **Installed Packages**

### 1. **Sentry Integration** (`sentry/sentry-laravel`)
- **Purpose**: Error tracking, performance monitoring, and crash reporting
- **Version**: 4.15.1
- **Configuration**: `config/sentry.php`
- **Features**:
  - Automatic error capture and reporting
  - Performance monitoring with traces and profiles
  - Environment-based configuration
  - Customizable sample rates for traces and profiles

### 2. **Laravel Telescope** (`laravel/telescope`)
- **Purpose**: Application debugging and monitoring (dev-only)
- **Version**: 5.11.3
- **Configuration**: `app/Providers/TelescopeServiceProvider.php`
- **Features**:
  - Request/response monitoring
  - Database query logging
  - Job execution tracking
  - Mail and notification logging
  - Gated to local/staging environments only

### 3. **Log Viewer** (`opcodesio/log-viewer`)
- **Purpose**: Web-based log file viewing and analysis
- **Version**: 3.19.0
- **Configuration**: Published from vendor package
- **Features**:
  - Browse and search log files
  - Filter by log levels
  - Download log files
  - Protected by authentication and permissions

### 4. **Slack Logging**
- **Feature**: Real-time log notifications to Slack
- **Configuration**: Integrated with Laravel's logging system
- **Status**: Already configured in `config/logging.php`

## 🔧 **Configuration Changes**

### **Service Providers** (`bootstrap/providers.php`)
```php
// Sentry for error tracking and monitoring
\Sentry\Laravel\ServiceProvider::class,

// Telescope for debugging (dev-only)
...(in_array(config('app.env'), ['local', 'staging']) ? [
    \Laravel\Telescope\TelescopeServiceProvider::class,
    \App\Providers\TelescopeServiceProvider::class,
] : []),

// Log Viewer for viewing application logs
\Opcodes\LogViewer\LogViewerServiceProvider::class,
```

### **Telescope Service Provider** (`app/Providers/TelescopeServiceProvider.php`)
- Gates access to local and staging environments only
- Configures sensitive data hiding for production
- Implements custom filtering for non-local environments

### **Sentry Configuration** (`config/sentry.php`)
- Environment-based configuration
- Traces and profiles sample rates
- Performance monitoring settings
- Error handling callbacks

### **Logging Configuration** (`config/logging.php`)
- Updated stack to include Slack channel by default
- Slack channel already configured with webhook support

### **Routes** (`routes/web.php`)
```php
// Log Viewer - محمي بالصلاحيات
Route::get('/logs', function () {
    return app(\Opcodes\LogViewer\Http\Controllers\IndexController::class)();
})->name('logs.index')->middleware('permission:system.logs');

// Test route for error tracking - محمي بالصلاحيات
Route::get('/oops', function () {
    throw new RuntimeException('This is a test error for Sentry, Slack logging, and error tracking systems.');
})->name('oops')->middleware('permission:system.logs');
```

### **Permissions** (`database/seeders/PermissionSeeder.php`)
- Added `system.logs` permission for accessing monitoring tools
- Permission assigned to admin role

## 🌐 **Access URLs**

### **Log Viewer**
- **URL**: `/logs`
- **Access**: Requires authentication + `system.logs` permission
- **Purpose**: View and analyze application logs

### **Telescope Dashboard**
- **URL**: `/telescope`
- **Access**: Dev-only (local/staging environments)
- **Purpose**: Application debugging and monitoring

### **Test Error Route**
- **URL**: `/oops`
- **Access**: Requires authentication + `system.logs` permission
- **Purpose**: Test error tracking systems

## 🔑 **Environment Variables**

### **Required Variables**
```bash
# Sentry Configuration
SENTRY_DSN=your_sentry_dsn_here
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_PROFILES_SAMPLE_RATE=0.1

# Slack Logging
LOG_SLACK_WEBHOOK_URL=your_slack_webhook_url_here
LOG_SLACK_USERNAME="Laravel Log"
LOG_SLACK_EMOJI=:boom:

# Log Configuration
LOG_CHANNEL=stack
LOG_STACK=single,slack
LOG_LEVEL=critical
```

### **Optional Variables**
```bash
SENTRY_RELEASE=1.0.0
SENTRY_ENVIRONMENT=production
```

## 🚀 **Installation Commands**

### **Package Installation**
```bash
# Install monitoring packages
composer require sentry/sentry-laravel laravel/telescope opcodesio/log-viewer

# Publish Telescope configuration
php artisan telescope:install

# Publish Log Viewer configuration
php artisan vendor:publish --provider="Opcodes\LogViewer\LogViewerServiceProvider"

# Run migrations
php artisan migrate
```

### **Permission Setup**
```bash
# Run permission seeder to add system.logs permission
php artisan db:seed --class=PermissionSeeder
```

## 🔍 **Verification Steps**

### **1. Test Error Tracking Route**
1. Visit `/oops` (requires authentication and `system.logs` permission)
2. Verify error is captured by Sentry
3. Check Slack channel for error notification
4. Verify error appears in application logs

### **2. Access Log Viewer**
1. Visit `/logs` (requires authentication and `system.logs` permission)
2. Browse application logs
3. Test search and filter functionality
4. Verify log download capability

### **3. Telescope Dashboard**
1. Visit `/telescope` (dev-only, local/staging environments)
2. Monitor requests and responses
3. View database queries
4. Track job executions
5. Monitor mail and notifications

### **4. Verify Sentry Integration**
1. Set `SENTRY_DSN` in your `.env` file
2. Visit `/oops` to trigger a test error
3. Check Sentry dashboard for captured error
4. Verify performance monitoring data

### **5. Verify Slack Logging**
1. Set `LOG_SLACK_WEBHOOK_URL` in your `.env` file
2. Visit `/oops` to trigger a test error
3. Check Slack channel for error notification
4. Verify log level configuration in `config/logging.php`

## 📁 **Files Modified/Created**

### **New Files**
- `app/Providers/TelescopeServiceProvider.php`
- `config/sentry.php`
- `MONITORING_SETUP.md` (this file)

### **Modified Files**
- `bootstrap/providers.php` - Added service providers
- `config/logging.php` - Updated stack to include Slack
- `routes/web.php` - Added monitoring routes
- `database/seeders/PermissionSeeder.php` - Added system.logs permission
- `README.md` - Added comprehensive documentation

### **Published Files**
- Telescope configuration and migrations
- Log Viewer configuration and views

## 🛡️ **Security Features**

### **Permission-Based Access**
- All monitoring routes protected by `system.logs` permission
- Only users with appropriate permissions can access monitoring tools

### **Environment Gating**
- Telescope only available in local/staging environments
- Production environment protection for sensitive debugging tools

### **Authentication Required**
- All monitoring routes require user authentication
- No anonymous access to monitoring tools

## 🔧 **Troubleshooting**

### **Common Issues**
1. **Sentry Configuration Errors**: Ensure all required environment variables are set
2. **Permission Denied**: Verify user has `system.logs` permission
3. **Telescope Not Accessible**: Check environment is local/staging
4. **Slack Notifications**: Verify webhook URL and log level configuration

### **Debug Commands**
```bash
# Clear caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Check routes
php artisan route:list | grep -E "(logs|oops|telescope)"

# Verify permissions
php artisan tinker --execute="echo App\Models\Permission::where('name', 'system.logs')->exists() ? 'EXISTS' : 'NOT FOUND';"
```

## 📚 **Additional Resources**

- [Sentry Laravel Documentation](https://docs.sentry.io/platforms/php/guides/laravel/)
- [Laravel Telescope Documentation](https://laravel.com/docs/telescope)
- [Log Viewer Documentation](https://github.com/opcodesio/log-viewer)
- [Laravel Logging Documentation](https://laravel.com/docs/logging)

---

**Note**: This setup provides comprehensive monitoring and debugging capabilities while maintaining security through proper authentication and permission controls. All tools are production-ready and follow Laravel best practices.
