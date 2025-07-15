# SproutPHP v0.1.7-alpha.2 Release Notes

## 🎉 New Features & Improvements

- **File Upload & Storage:** New Storage helper for easy file uploads and URL generation, saving files in `public/uploads`.
- **Modern Request API:** Access uploaded files via `$request->file('avatar')` and `$request->hasFile('avatar')`.
- **Unified Input:** Request data now merges `$_GET`, `$_POST`, and JSON body for easier access.
- **Validation:** Added `mimes` and `image` rules for secure file validation.
- **HTMX File Upload:** File upload with progress bar using only HTMX, no custom JS required.
- **Error Handling:** Generic script to clear errors on focus for all fields.
- **Docs:** Updated with new usage examples and best practices.

## 🛠️ Upgrade Guide

- Use the new Storage helper and request methods for file uploads.
- Update your forms to use the new validation rules and error-clearing script.
- See DOCS.md for updated usage and examples.

## 📅 Release Date

2025-07-15

## 📦 Framework Version

v0.1.7-alpha.2

---

**Release Date**: 2025-07-15  
**Framework Version**: v0.1.7-alpha.2  
**PHP Version**: 8.1+  
**Composer**: 2.0+
