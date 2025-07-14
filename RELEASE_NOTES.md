# SproutPHP v0.1.4-alpha.1 Release Notes

## 🎉 New Features

### Configuration System
- **Centralized Configuration**: New `config/` directory with organized configuration files
- **Config Helper**: New `config()` helper function for easy access to configuration values
- **Environment-Based Settings**: Automatic configuration based on environment (local/production)
- **Multiple Config Files**: Separate config files for app, database, security, view, cache, and mail

### Enhanced Security
- **Configurable XSS Protection**: Can be enabled/disabled and mode configured
- **Configurable Content Security Policy**: Environment-based CSP policies
- **Improved CSRF Protection**: Better token handling and validation
- **Security Configuration**: All security settings now configurable via config files

### Framework Improvements
- **Database Configuration**: Support for multiple database connections
- **View Configuration**: Configurable Twig settings
- **Error Handling**: Environment-aware error handling
- **Middleware System**: Global middleware configuration
- **CLI Enhancements**: Better error handling and validation

## 🔧 Technical Improvements

### Configuration Files
- `config/app.php` - Application settings, framework info, global middleware
- `config/database.php` - Database connections (MySQL, SQLite, PostgreSQL)
- `config/security.php` - Security settings (CSRF, XSS, CSP, CORS)
- `config/view.php` - View engine settings (Twig configuration)
- `config/cache.php` - Cache configuration
- `config/mail.php` - Mail configuration

### Updated Components
- **Database Layer**: Now uses `config('database.*')` instead of direct env calls
- **View Engine**: Twig settings from `config('view.*')`
- **Error Handler**: Uses `config('app.debug')` and `config('app.env')`
- **Middleware**: Global middleware loaded from `config('app.global_middleware')`
- **Templates**: Using `config('app.name')` and `config('app.env')`

### New Helper Functions
- `config($key, $default = null)` - Access configuration values
- Enhanced error handling and validation in middleware system

## 🚀 Breaking Changes

### Configuration Access
- Replace direct `env()` calls with `config()` helper where appropriate
- Update any hardcoded values to use configuration system

### Middleware Configuration
- Global middleware now configured in `config/app.php`
- Middleware classes must implement `MiddlewareInterface`

## 📚 Documentation

### New Files
- `CONFIGURATION.md` - Comprehensive configuration guide
- `RELEASE_NOTES.md` - This file

### Updated Documentation
- Enhanced README with configuration examples
- Better CLI documentation

## 🧪 Testing

### New Test Routes
- `/config-test` - Shows configuration values
- `/debug-config` - Shows debug information
- `/security-test` - Tests security configuration

## 🔒 Security Features

### XSS Protection
- Configurable XSS protection headers
- Environment-based CSP policies
- Development: Relaxed policy allowing inline styles and external images
- Production: Strict policy blocking unsafe directives

### CSRF Protection
- Automatic token generation and validation
- Configurable token settings
- Protection for all state-changing requests

## 📦 Installation

```bash
# Clone the repository
git clone https://github.com/SproutPHP/framework.git

# Install dependencies
composer install

# Start development server
php sprout grow
```

## 🎯 Migration Guide

### From v0.1.3-alpha.1

1. **Update Configuration Access**:
   ```php
   // Old way
   $debug = env('APP_DEBUG', true);
   
   // New way
   $debug = config('app.debug', true);
   ```

2. **Update Database Configuration**:
   ```php
   // Old way
   $host = env('DB_HOST', 'localhost');
   
   // New way
   $host = config('database.connections.mysql.host');
   ```

3. **Update Security Settings**:
   ```php
   // Old way
   $xssEnabled = env('XSS_PROTECTION', true);
   
   // New way
   $xssEnabled = config('security.xss.enabled', true);
   ```

## 🐛 Bug Fixes

- Fixed middleware instantiation issues
- Improved error handling in configuration loading
- Better validation of middleware classes
- Fixed CSP header conflicts in development mode

## 🔮 Future Roadmap

- Authentication system
- Validation library
- Testing framework integration
- API response helpers
- Asset management system
- Deployment tools

## 📄 License

MIT License - see LICENSE file for details

---

**Release Date**: December 2024  
**Framework Version**: v0.1.4-alpha.1  
**PHP Version**: 8.1+  
**Composer**: 2.0+ 