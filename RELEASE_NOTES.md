# SproutPHP v0.1.6-alpha.1 Release Notes

## 🎉 New Features & Improvements

### Validation System

- **New Validator class**: Minimal, extensible validation for forms and data.
- **Validation helper**: Easy to use in controllers and views.
- **Twig integration**: Display validation errors in templates.

### Session Handling

- **Configurable session name**: Set session cookie name via config/app.php and .env.
- **Session start improvements**: Session is started after Composer autoload, ensuring config() is available.

### Other Improvements

- **CSRF fixes**: More robust CSRF token handling for HTMX and forms.
- **Bug fixes**: Typos and namespace fixes in Validator and helpers.
- **Documentation updates**: Improved setup and usage instructions for new features.

## 🛠️ Upgrade Guide

- Update your config/app.php and .env for the new session name option if desired.
- Use the new Validator in your controllers for form validation.
- See DOCS.md for usage examples.

## 📅 Release Date

June 2024

## 📦 Framework Version

v0.1.6-alpha.1

---

**Release Date**: June 2024  
**Framework Version**: v0.1.6-alpha.1  
**PHP Version**: 8.1+  
**Composer**: 2.0+
