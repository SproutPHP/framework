# SproutPHP v0.1.7-alpha.1 Release Notes

## 🎉 New Features & Improvements

### Validation System

- **Expanded validation rules**: Now supports numeric, integer, string, boolean, array, in, not_in, same, different, confirmed, regex, url, ip, date, before, after, nullable, present, digits, digits_between, size, starts_with, ends_with, uuid, and more.
- **Improved documentation**: DOCS.md now lists all available rules and usage examples.
- **Better error clearing**: Validation errors are cleared on input focus for a smoother UX.

### Dark/Light Mode Support

- **PicoCSS dark/light mode toggle**: Optional sun/moon icon button in the navbar for instant theme switching.
- **Theme preference**: Saved in localStorage and applied instantly.
- **Post-install option**: Users can choose to auto-include the toggle during installation.

### Other Improvements

- **Post-install script**: Now prompts for dark/light mode toggle inclusion.
- **Code cleanup and bug fixes**: Heredoc indentation, improved scripts, and UI polish.
- **Documentation updates**: More examples and clearer instructions for new features.

## 🛠️ Upgrade Guide

- Use the new validation rules in your controllers and forms.
- To add the dark/light mode toggle, re-run the post-install script or add the button and script as shown in DOCS.md.
- See DOCS.md for updated usage examples.

## 📅 Release Date

July 2024

## 📦 Framework Version

v0.1.7-alpha.1

---

**Release Date**: July 2024  
**Framework Version**: v0.1.7-alpha.1  
**PHP Version**: 8.1+  
**Composer**: 2.0+
