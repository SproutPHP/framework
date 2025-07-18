# Changelog

All notable changes to this project are documented in this file.

This project uses [Semantic Versioning](https://semver.org/) beginning with version `v0.1.7-alpha.2` (or `v0.1.7`).  
Earlier releases (`v0.1.0-alpha.1` to `v0.1.7-alpha.1`) were experimental and do not strictly follow SemVer conventions.

---

## [v0.1.7-alpha.3] - 2024-06-13

### Added
- Storage root is now set to an absolute path by default for reliability (no .env needed)
- Improved Storage helper documentation and usage
- Enhanced symlink command for better cross-platform compatibility
- Updated documentation for new storage system and best practices

### Fixed
- Prevented duplicate/nested storage paths in uploads
- General codebase and documentation improvements

---

## [v0.1.7-alpha.2] - 2025-07-15

### Added
- Storage helper for file uploads, saving to `public/uploads` and generating URLs.
- Modern file access in controllers: `$request->file('avatar')`, `$request->hasFile('avatar')`.
- Unified request data: merges `$_GET`, `$_POST`, and JSON body.
- `mimes` and `image` validation rules for secure file uploads.
- HTMX-powered file upload with progress bar in the main form (no JS required).
- Generic error-clearing script for all form fields.

### Changed
- File uploads are now web-accessible by default.
- Improved documentation for file upload, validation, and request handling.

### Fixed
- No more duplicate `/uploads/uploads/...` in file URLs.

---

## Legacy Experimental Releases

These were single-shot development releases with no progressive alpha/beta cycle.

| Version Tag      | Notes                                     |
| ---------------- | ----------------------------------------- |
| `v0.1.0-alpha.1` | Initial experimental release              |
| `v0.1.1-alpha.1` | Feature/bug updates without SemVer phases |
| `v0.1.2-alpha.1` | Same as above                             |
| `v0.1.3-alpha.1` | —                                         |
| `v0.1.4-alpha.1` | —                                         |
| `v0.1.5-alpha.1` | —                                         |
| `v0.1.6-alpha.1` | —                                         |
| `v0.1.7-alpha.1` | Final experimental/unstable release       |

---

**From v0.1.7-alpha.2 onward, all releases will follow a structured, progressive SemVer pre-release cycle.**

## [v0.1.7] - 2025-07-18

### Added
- Dynamic route parameter support (e.g., `/user/{id}`, `/blog/{slug}`) for CRUD and flexible routing
- Robust CSRF protection via middleware and helpers (works for forms, AJAX, and HTMX)
- SPA-like file upload and form handling with HTMX (including indicators and grid UI)
- Secure private file upload/download (no direct links, internal access only)
- Consistent CSRF token management (single session key, helpers, and middleware)

### Improved
- UI/UX for validation and file upload forms (two-column grid, spinner, SPA feel)
- Path resolution for storage (public/private separation, symlink support)
- Code structure: CSRF logic moved to helpers/middleware, no raw PHP in entry

### Fixed
- Issues with file download on PHP built-in server (now uses query param for compatibility)
- Consistency in CSRF token usage across the framework

### Removed
- Exposed raw CSRF logic from entry point

---
