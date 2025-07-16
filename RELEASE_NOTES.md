# Release Notes: v0.1.7-beta.1 (2024-06-09)

## Highlights

- **First Beta Release!** SproutPHP is now feature-complete and ready for broader testing and feedback.
- **Dynamic Routing:** Support for route parameters (e.g., `/user/{id}`, `/file/{filename:.+}`) enables full CRUD and flexible APIs.
- **CSRF Protection:** Robust, middleware-based CSRF protection for all state-changing requests (forms, AJAX, HTMX).
- **SPA-like UX:** HTMX-powered forms and file uploads for a modern, seamless user experience.
- **Private File Handling:** Secure upload/download of private files, accessible only via internal methods.
- **Cleaner Codebase:** All CSRF logic is now in helpers/middleware, not exposed in entry scripts.

## Upgrade Notes

- All CSRF tokens now use the `_csrf_token` session key. Update any custom code to use the new helpers.
- File downloads now use a query parameter (`?file=...`) for compatibility with the PHP built-in server.
- If you use custom routes, you can now use `{param}` and `{param:regex}` patterns.

## What's New

- Two-column grid UI for validation and file upload forms
- SPA feel with HTMX indicators and partial updates
- Consistent and secure CSRF handling everywhere
- Improved storage path resolution and symlink support

## Thank You!

Thank you for testing and contributing to SproutPHP. Please report any issues or feedback as we move toward a stable release.
