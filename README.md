# SystemHF - Laravel Business Management System

[![Quality Check & CI/CD](https://github.com/3amoBadawy/systemhf/actions/workflows/quality.yml/badge.svg)](https://github.com/3amoBadawy/systemhf/actions/workflows/quality.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg)](https://phpstan.org/)
[![Laravel Pint](https://img.shields.io/badge/Laravel%20Pint-PSR--12%20Compliant-brightgreen.svg)](https://laravel.com/docs/pint)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)

## 🚀 **Project Overview**

SystemHF is a comprehensive Laravel 12 business management system designed for Arabic-speaking markets with multi-branch support. The system includes complete user management, product management, customer relationships, invoicing, payments, employee management, and comprehensive reporting.

## ✨ **Features**

- **🔐 User Management**: Role-based access control with granular permissions
- **🏢 Multi-Branch Support**: Manage multiple business locations
- **📦 Product Management**: Complete inventory and product catalog
- **👥 Customer Management**: CRM with customer notifications
- **🧾 Invoicing System**: Professional invoice generation and management
- **💳 Payment Processing**: Multiple payment methods and tracking
- **👨‍💼 Employee Management**: Attendance, salaries, and commissions
- **📊 Financial Management**: Accounts, transactions, and expense tracking
- **📱 Media Management**: Advanced file and image handling
- **📈 Reporting**: Comprehensive business analytics and reports

## 🛠️ **Technology Stack**

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: MySQL 8.0+ with comprehensive migrations
- **Frontend**: Blade templates, Tailwind CSS 4.0, Alpine.js
- **Quality Tools**: PHPStan (Level 8), Laravel Pint, Psalm, PHPMD
- **CI/CD**: GitHub Actions, Pre-commit hooks, Automated quality gates
- **Testing**: PHPUnit with comprehensive test coverage

## 📦 **Monitoring & Debugging Packages**

### **Sentry Integration**
- **Package**: `sentry/sentry-laravel`
- **Purpose**: Error tracking, performance monitoring, and crash reporting
- **Features**: 
  - Automatic error capture and reporting
  - Performance monitoring with traces and profiles
  - Environment-based configuration
  - Customizable sample rates for traces and profiles

### **Laravel Telescope**
- **Package**: `laravel/telescope`
- **Purpose**: Application debugging and monitoring (dev-only)
- **Features**:
  - Request/response monitoring
  - Database query logging
  - Job execution tracking
  - Mail and notification logging
  - Gated to local/staging environments only

### **Log Viewer**
- **Package**: `opcodesio/log-viewer`
- **Purpose**: Web-based log file viewing and analysis
- **Features**:
  - Browse and search log files
  - Filter by log levels
  - Download log files
  - Protected by authentication and permissions

### **Slack Logging**
- **Feature**: Real-time log notifications to Slack
- **Configuration**: Integrated with Laravel's logging system
- **Usage**: Critical errors and important events sent to Slack channels

## 📊 **Current Status**

### ✅ **Completed**
- **Database Design**: 30+ tables with proper relationships
- **Models**: 25+ Eloquent models with relationships
- **Migrations**: All database migrations implemented
- **Seeders**: Comprehensive data seeding for testing
- **Routes**: Complete routing structure for all features
- **Views**: Blade templates for all major features
- **CI/CD Setup**: Complete GitHub Actions workflow
- **Quality Tools**: All tools configured and enforced

### 🔧 **In Progress**
- **Quality Issues**: Resolving 656 PHPStan errors
- **Missing Methods**: Implementing missing service methods
- **Interface Compatibility**: Fixing interface mismatches
- **Test Coverage**: Expanding test suite coverage

### 📋 **Next Steps**
- Complete quality issue resolution
- Implement missing controller methods
- Add comprehensive test coverage
- Deploy to production environment

## 🚀 **Quick Start**

### **Prerequisites**
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Composer 2.0+
- Git

### **Installation**
```bash
# Clone repository
git clone https://github.com/3amoBadawy/systemhf.git
cd systemhf

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Add required environment variables for monitoring packages
echo "SENTRY_DSN=" >> .env
echo "SENTRY_TRACES_SAMPLE_RATE=0.1" >> .env
echo "SENTRY_PROFILES_SAMPLE_RATE=0.1" >> .env
echo "LOG_SLACK_WEBHOOK_URL=" >> .env

# Database setup
php artisan migrate
php artisan db:seed

# Verify installation
composer quality

## 🔧 **Environment Variables**

### **Required for Monitoring Packages**
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

### **Optional Sentry Settings**
```bash
SENTRY_RELEASE=1.0.0
SENTRY_ENVIRONMENT=production
```

## 🔍 **Package Verification**

### **Test Error Tracking Route**
Visit `/oops` (requires authentication and `system.logs` permission) to test:
- **Sentry**: Error capture and reporting
- **Slack**: Log notification delivery
- **Log Viewer**: Error logging and viewing

### **Access Log Viewer**
Visit `/logs` (requires authentication and `system.logs` permission) to:
- Browse application logs
- Search and filter log entries
- Download log files

### **Telescope Dashboard**
Visit `/telescope` (dev-only, local/staging environments) to:
- Monitor requests and responses
- View database queries
- Track job executions
- Monitor mail and notifications

### **Verify Sentry Integration**
1. Set `SENTRY_DSN` in your `.env` file
2. Visit `/oops` to trigger a test error
3. Check Sentry dashboard for captured error
4. Verify performance monitoring data

### **Verify Slack Logging**
1. Set `LOG_SLACK_WEBHOOK_URL` in your `.env` file
2. Visit `/oops` to trigger a test error
3. Check Slack channel for error notification
4. Verify log level configuration in `config/logging.php`

## 🚀 **Installation Commands**

### **Install Required Packages**
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

### **Update Permissions**
```bash
# Run permission seeder to add system.logs permission
php artisan db:seed --class=PermissionSeeder
```

### **Development Server**
```bash
# Start development server
php artisan serve

# Start Vite for frontend
npm run dev

# Run all services (recommended)
composer dev
```

## 🔧 **Quality Tools & CI/CD**

### **Local Quality Checks**
```bash
# Code style
./vendor/bin/pint                    # Fix code style
./vendor/bin/pint --test            # Check code style

# Static analysis
./vendor/bin/phpstan analyse        # Run PHPStan (Level 8)
./vendor/bin/psalm                  # Run Psalm analysis

# Testing
php artisan test                     # Run all tests
php artisan test --coverage         # Run with coverage

# Comprehensive check
composer quality                     # Run all quality checks
```

### **Pre-commit Hook (ENFORCED)**
Quality checks run automatically before each commit:
1. **Laravel Pint**: Code style validation
2. **PHPStan**: Static analysis (Level 8)
3. **Tests**: Full test suite execution

**Commits will fail** if any quality check fails.

### **GitHub Actions**
Automated CI/CD pipeline runs on every push and pull request:
- **Quality Checks**: Code style, static analysis, tests
- **Security Checks**: Dependency scanning, vulnerability detection
- **Build Process**: Asset compilation and optimization
- **Quality Gates**: All checks must pass before merge

## 📁 **Project Structure**

```
systemhf/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   ├── Repositories/        # Data access layer
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/            # Data seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Tailwind CSS
│   └── js/                 # Alpine.js components
├── routes/                  # Application routes
├── tests/                   # PHPUnit tests
└── .github/workflows/       # GitHub Actions CI/CD
```

## 🧪 **Testing**

### **Run Tests**
```bash
# All tests
php artisan test

# Specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# With coverage
php artisan test --coverage

# Specific test
php artisan test --filter=UserTest
```

### **Test Coverage Requirements**
- **Minimum 80% coverage** required
- **All public methods** must have tests
- **Edge cases** must be covered
- **Error conditions** must be tested

## 📚 **Documentation**

- **[System Documentation](SYSTEM_DOCUMENTATION.md)**: Complete system overview
- **[Development Guide](DEVELOPMENT.md)**: Development workflow and standards
- **[GitHub Setup](GITHUB_SETUP.md)**: CI/CD setup and GitHub integration
- **[Tools Guide](TOOLS.md)**: Quality tools usage and configuration
- **[Project Rules](RULES.md)**: Coding standards and project rules

## 🔍 **Quality Standards**

### **Code Quality Requirements**
- **PHPStan Level 8**: Maximum static analysis
- **Laravel Pint**: PSR-12 code style compliance
- **Test Coverage**: Minimum 80% coverage
- **Documentation**: PHPDoc for all public methods

### **Quality Gates**
- **Pre-commit**: All quality checks must pass
- **GitHub Actions**: Automated quality validation
- **Branch Protection**: Quality checks required before merge
- **Code Review**: Required for all changes

## 🚨 **Current Issues & Solutions**

### **Quality Issues (656 PHPStan Errors)**
- **Missing Generic Types**: Add type specifications for Eloquent relationships
- **Missing Methods**: Implement missing service and model methods
- **Interface Compatibility**: Fix interface mismatches
- **Property Access**: Fix undefined property access

### **Quick Fixes**
```bash
# Fix code style issues
./vendor/bin/pint

# Check static analysis
./vendor/bin/phpstan analyse --level=8

# Run tests
php artisan test
```

## 🤝 **Contributing**

### **Development Workflow**
1. **Fork** the repository
2. **Create** feature branch (`feature/your-feature`)
3. **Implement** your changes following quality standards
4. **Run quality checks** locally (`composer quality`)
5. **Commit** your changes (quality checks run automatically)
6. **Push** and create pull request
7. **Wait for CI checks** to pass
8. **Get code review** and approval

### **Quality Requirements**
- All quality checks must pass
- Tests must cover new functionality
- Code must follow PSR-12 standards
- Static analysis must pass Level 8

## 📞 **Support & Issues**

- **GitHub Issues**: [Report bugs and feature requests](https://github.com/3amoBadawy/systemhf/issues)
- **GitHub Actions**: Check CI/CD status in Actions tab
- **Quality Issues**: Run `composer quality` locally for detailed error information
- **Documentation**: Check project documentation files

## 📈 **Roadmap**

### **Phase 1: Quality & Stability** (Current)
- ✅ Complete CI/CD setup
- 🔧 Resolve quality issues
- 🔧 Implement missing methods
- 🔧 Expand test coverage

### **Phase 2: Feature Completion** (Next 2-4 weeks)
- 🔧 Complete controller implementations
- 🔧 Add model factories
- 🔧 Implement authorization policies
- 🔧 API development

### **Phase 3: Enhancement** (Next 2-3 months)
- 🔧 Frontend improvements
- 🔧 Performance optimization
- 🔧 Security hardening
- 🔧 Advanced analytics

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 **Acknowledgments**

- **Laravel Team** for the amazing framework
- **Quality Tool Maintainers** for excellent development tools
- **Open Source Community** for contributions and feedback

---

**Last Updated**: January 2025  
**Version**: 1.0.0  
**Status**: Development (Quality Issues Being Resolved)  
**CI/CD**: ✅ Fully Configured and Enforced
