# Changelog

All notable changes to this project are documented in this file.

This project uses [Semantic Versioning](https://semver.org/) beginning with version `v0.1.7-alpha.2` (or `v0.1.7-beta.1`).  
Earlier releases (`v0.1.0-alpha.1` to `v0.1.7-alpha.1`) were experimental and do not strictly follow SemVer conventions.

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
