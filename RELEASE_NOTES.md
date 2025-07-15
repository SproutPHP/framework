# SproutPHP v0.1.7-alpha.3 Release Notes

## 🎉 New Features & Improvements

- **Absolute Storage Path:** Storage root is now set to an absolute path by default for reliability; no need to set in .env for most use cases.
- **Updated Storage Helper:** Improved documentation and usage for file uploads and URL generation.
- **Symlink Command:** Enhanced for better cross-platform compatibility (Windows junctions, Linux/macOS symlinks).
- **Documentation:** DOCS.md updated to reflect new storage system, usage, and best practices.
- **Bugfix:** Prevented duplicate/nested storage paths in uploads.
- **General Improvements:** Codebase and documentation refinements.

## 🛠️ Upgrade Guide

- Use the new absolute storage root (no .env override needed).
- Run `php sprout symlink:create` to ensure correct symlink/junction for uploads.
- See DOCS.md for updated usage and examples.

## 📅 Release Date

2024-06-13

## 📦 Framework Version

v0.1.7-alpha.3

---

**Release Date**: 2024-06-13  
**Framework Version**: v0.1.7-alpha.3  
**PHP Version**: 8.1+  
**Composer**: 2.0+
