# SproutPHP Configuration Guide

This document outlines all available configuration options for SproutPHP framework.

## Environment Variables (.env file)

### Application Configuration
```env
APP_NAME=SproutPHP
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:9090
APP_TIMEZONE=UTC
APP_LOCALE=en
APP_KEY=your-secret-key-here
```

### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=sprout
DB_USER=root
DB_PASS=
DB_PREFIX=
```

### Security Configuration
```env
# CSRF Protection
CSRF_ENABLED=true
CSRF_TOKEN_NAME=_token
CSRF_EXPIRE=3600

# XSS Protection
XSS_PROTECTION=true
XSS_MODE=block  # 'block', 'sanitize', or '0' to disable

# Content Security Policy
CSP_ENABLED=true
CSP_REPORT_ONLY=false
CSP_REPORT_URI=

# CORS
CORS_ENABLED=false
CORS_ALLOWED_ORIGINS=*
CORS_ALLOWED_METHODS=GET,POST,PUT,DELETE
CORS_ALLOWED_HEADERS=Content-Type,Authorization
```

### Mail Configuration
```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sproutphp.com
MAIL_FROM_NAME=SproutPHP
```

### Cache Configuration
```env
CACHE_DRIVER=file
CACHE_PATH=/storage/cache
```

### Session Configuration
```env
SESSION_NAME=sprout_session
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_PATH=/storage/sessions
```

### Logging Configuration
```env
LOG_DRIVER=file
LOG_LEVEL=debug
LOG_PATH=/storage/logs
```

### View Configuration
```env
VIEW_ENGINE=twig
TWIG_CACHE=false
TWIG_DEBUG=true
TWIG_AUTO_RELOAD=true
TWIG_STRICT_VARIABLES=false
```

### Framework Configuration
```env
SPROUT_REPO=SproutPHP/framework
SPROUT_USER_AGENT=sproutphp-app
```

## Configuration Usage

### In PHP Code
```php
// Get app name
$appName = config('app.name');

// Get database host
$dbHost = config('database.connections.mysql.host');

// Check if XSS protection is enabled
$xssEnabled = config('security.xss.enabled');

// Get CSP mode
$cspMode = config('security.csp.report_only');
```

### In Twig Templates
```twig
{# Get app name #}
<h1>{{ config('app.name') }}</h1>

{# Check environment #}
{% if config('app.env') == 'local' %}
    <div class="debug-info">Development Mode</div>
{% endif %}
```

## Security Features

### XSS Protection
The framework automatically adds XSS protection headers based on your configuration:

- **Development**: Relaxed CSP policy allowing inline styles and external images
- **Production**: Strict CSP policy for maximum security

### CSRF Protection
CSRF tokens are automatically generated and validated for state-changing requests (POST, PUT, PATCH, DELETE).

### Content Security Policy
CSP headers are automatically set based on your environment:
- **Local/Debug**: Allows `unsafe-inline` for styles and external images
- **Production**: Strict policy with no unsafe directives

## Environment-Specific Behavior

### Local Environment
- Debug information displayed
- Relaxed security policies
- Detailed error messages
- HTMX debug indicator

### Production Environment
- No debug information
- Strict security policies
- Generic error pages
- Optimized performance settings 