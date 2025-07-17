# SproutPHP Documentation

## [v0.1.7-beta.1] - 2024-06-09

### New Features & Improvements

- **Dynamic Route Parameters:** Define routes with parameters (e.g., `/user/{id}`, `/blog/{slug}`) for flexible CRUD and API endpoints.
- **Robust CSRF Protection:** Middleware-based CSRF protection for all state-changing requests (forms, AJAX, HTMX). Use `{{ csrf_field()|raw }}` in forms and `{{ csrf_token() }}` for AJAX/HTMX headers. All tokens use the `_csrf_token` session key.
- **SPA-like Interactivity with HTMX:** Use HTMX attributes for seamless, partial updates (e.g., form submissions, file uploads) and request indicators (spinners). Example:
  ```html
  <form
    hx-post="/validation-test"
    hx-target="#form-container"
    hx-swap="innerHTML"
    hx-indicator="#spinner"
    hx-trigger="submit delay:500ms"
  >
    ...
  </form>
  ```
- **Private File Handling:** Upload files to private storage (not web-accessible). Download private files only via internal controller methods (no direct links). Example:
  ```php
  // Upload
  Storage::put($file, '', 'private');
  // Download (controller)
  $path = Storage::path($filename, 'private');
  ```
- **Storage Improvements:** Storage paths are now always resolved relative to the project root. Public/private separation, symlink support for serving public files, and compatibility with the PHP built-in server for downloads.
- **UI/UX:** Two-column grid for validation and file upload forms, spinner/indicator support, and SPA feel for user interactions.

---

## Included by Default

- **HTMX** for modern, interactive UIs (already loaded in your base template)
- **PicoCSS** for beautiful, minimal styling (already loaded in your base template)
- **Twig** for templating
- **CLI** for scaffolding and project management
- **Error pages** and basic debug tools

## Example: Hello World Route

```php
// routes/web.php
Route::get('/home', 'HomeController@index');
```

```php
// app/Controllers/HomeController.php
class HomeController {
    public function index() {
        return view('home', ['title' => 'Hello, World!']);
    }
}
```

```twig
{# app/Views/home.twig #}
<h1>{{ title }}</h1>
```

## Why Minimal?

- **Faster response times**
- **Lower memory usage**
- **Easier to understand and debug**
- **No vendor lock-in**
- **You are in control**

## What This Framework Is NOT

- Not a Laravel, Symfony, CodeIgniter, or Slim clone
- Not a full-stack SPA framework
- Not for those who want everything done for them

## What's Included

- Minimal MVC structure
- Simple routing
- Twig templating (optional to swap for PHP)
- Essential CLI tools (scaffolding)
- Custom error handling
- **Centralized configuration system** with `config()` helper
- **Enhanced security** with configurable XSS and CSP protection
- **HTMX and PicoCSS pre-installed**

## Features & Improvements Coming Soon

| Feature/Improvement                        | Status           | Notes/Suggestions                            |
| ------------------------------------------ | ---------------- | -------------------------------------------- |
| [ ] Event System                           | In Consideration | Event/listener system                        |
| [ ] Localization (i18n)                    | In Consideration | Translation files and helpers                |
| [ ] Caching (Redis, Memcached, etc.)       | In Consideration | Cache abstraction, Redis/Memcached support   |
| [ ] Testing Utilities                      | In Consideration | PHPUnit integration and helpers              |
| [x] File Uploads & Storage                 | In Consideration | File upload and storage abstraction          |
| [ ] Command Bus/CQRS                       | In Consideration | Command handler system                       |
| [ ] Form Builder                           | In Consideration | Dynamic form generation and validation       |
| [ ] API Support (JWT, rate limiting, etc.) | In Consideration | API middleware, JWT, transformers            |
| [ ] ORM/Query Builder                      | In Consideration | Query builder/ORM for easier DB access       |
| [ ] Model Relationships                    | In Consideration | hasOne, hasMany, belongsTo, etc.             |
| [ ] Package Installation System            | In Consideration | Install and manage reusable packages/plugins |

## Optional Packages

SproutPHP is designed to be lightweight. The following features are (or will be) available as optional packages, so you can install only what you need:

- Authentication & Authorization
- Admin Panel/CRUD
- Advanced Error Handling
- API Support (JWT, rate limiting, etc.)
- File Uploads & Storage
- Queue/Job System
- Event System
- Localization (i18n)
- Caching (Redis, Memcached, etc.)
- Testing Utilities
- Asset Pipeline
- Social Login
- Notifications
- Payment Integration
- SEO Tools
- Form Builder
- User Profile/Avatar
- Two-Factor Authentication
- Command Bus/CQRS
- GraphQL Support

## What's NOT Included

- No asset pipeline or `node_modules`
- No heavy ORM (use Models for direct DB access)
- No built-in authentication (add your own as needed)
- No complex middleware (keep it simple)

## Configuration System

SproutPHP now includes a powerful configuration system:

```php
// Access configuration values
$appName = config('app.name');
$dbHost = config('database.connections.mysql.host');
$xssEnabled = config('security.xss.enabled');
```

### Configuration Files

- `config/app.php` - Application settings and global middleware
- `config/database.php` - Database connections
- `config/security.php` - Security settings (CSRF, XSS, CSP)
- `config/view.php` - View engine settings
- `config/cache.php` - Cache configuration
- `config/mail.php` - Mail configuration

See `CONFIGURATION.md` for complete documentation.

## Validation System (NEW in v0.1.6-alpha.1)

SproutPHP now includes a minimal, extensible validation system for validating user input in controllers and forms.

### Overview

- Validate associative arrays (e.g., $\_POST, custom data)
- Supports common rules: required, email, min, max, numeric, string, in, etc.
- Collects and returns error messages
- Easily display errors in Twig views
- Extensible: add your own rules as needed

### Example Usage in a Controller

```php
use Core\Support\Validator;

public function handleForm()
{
    $data = $_POST;
    $validator = new Validator($data, [
        'email' => 'required|email',
        'name' => 'required|min:3',
        'age' => 'required|numeric|min:18',
        'role' => 'in:admin,user,guest',
    ]);

    if ($validator->fails()) {
        // Pass errors and old input to the view
        return view('your-form-view', [
            'errors' => $validator->errors(),
            'old' => $data,
        ]);
    }

    // Proceed with valid data
}
```

### Available Rules

- `required` — Field must not be empty
- `email` — Must be a valid email address
- `min:N` — Minimum length N
- `max:N` — Maximum length N
- `numeric` — Must be a number
- `integer` — Must be an integer
- `string` — Must be a string
- `boolean` — Must be a boolean value
- `array` — Must be an array
- `in:val1,val2,...` — Value must be one of the listed options
- `not_in:val1,val2,...` — Value must NOT be one of the listed options
- `same:field` — Must match another field
- `different:field` — Must be different from another field
- `confirmed` — Must have a matching {field}\_confirmation value
- `regex:pattern` — Must match a regex pattern
- `url` — Must be a valid URL
- `ip` — Must be a valid IP address
- `date` — Must be a valid date
- `before:date` — Must be a date before the given date
- `after:date` — Must be a date after the given date
- `nullable` — Field is allowed to be null (affects other rules)
- `present` — Field must be present in the input (even if empty)
- `digits:N` — Must be exactly N digits
- `digits_between:min,max` — Must be between min and max digits
- `size:N` — Must be exactly N characters (for strings) or N value (for numbers/arrays)
- `starts_with:val1,val2,...` — Must start with one of the given values
- `ends_with:val1,val2,...` — Must end with one of the given values
- `uuid` — Must be a valid UUID

You can add more rules by extending the Validator class.

### Displaying Errors in Twig

```twig
<form method="POST">
    <input type="text" name="name" value="{{ old.name|e }}">
    {% if errors.name %}
        <div class="error">{{ errors.name }}</div>
    {% endif %}
    <input type="email" name="email" value="{{ old.email|e }}">
    {% if errors.email %}
        <div class="error">{{ errors.email }}</div>
    {% endif %}
    <button type="submit">Submit</button>
</form>
```

### Notes

- Use the `validate()` helper for a shortcut: `validate($data, $rules)`
- See the Validator class in `core/Support/Validator.php` for more details and to add custom rules.

## Dark/Light Mode Support

SproutPHP supports instant dark and light mode switching using PicoCSS's built-in color schemes. The framework provides an optional sun/moon icon button in the navbar to toggle the theme.

- PicoCSS automatically applies dark or light styles based on the `data-theme` attribute on `<html>`.
- The toggle button updates `data-theme` to `dark` or `light` and saves the preference in localStorage.
- No extra CSS is needed for the color scheme itself—PicoCSS handles all color changes.

### Example Usage

Add this to your navbar:

```html
<button
  id="theme-toggle-btn"
  aria-label="Toggle dark/light mode"
  style="background:none;border:none;cursor:pointer;font-size:1.5rem;"
>
  <span id="theme-icon">☀️</span>
</button>
```

Add this script (in your layout or navbar):

```html
<script>
  (function () {
    const themeBtn = document.getElementById("theme-toggle-btn");
    const themeIcon = document.getElementById("theme-icon");
    const html = document.documentElement;
    if (!themeBtn || !themeIcon) return;
    function setInitialTheme() {
      let theme = localStorage.getItem("theme");
      if (!theme) {
        theme = window.matchMedia("(prefers-color-scheme: dark)").matches
          ? "dark"
          : "light";
      }
      html.setAttribute("data-theme", theme);
      themeIcon.textContent = theme === "dark" ? "🌙" : "☀️";
    }
    setInitialTheme();
    themeBtn.addEventListener("click", function () {
      const currentTheme = html.getAttribute("data-theme");
      const newTheme = currentTheme === "dark" ? "light" : "dark";
      html.setAttribute("data-theme", newTheme);
      localStorage.setItem("theme", newTheme);
      themeIcon.textContent = newTheme === "dark" ? "🌙" : "☀️";
    });
  })();
</script>
```

**Result:**

- The icon (☀️ or 🌙) is shown in the navbar.
- Clicking the icon toggles the theme and updates the icon instantly.
- The theme preference is saved in `localStorage`.
- PicoCSS automatically applies the correct color scheme.

## Using HTMX and PicoCSS

You do **not** need to install or include HTMX or PicoCSS yourself—they are already downloaded and loaded in your base template:

```html
<link rel="stylesheet" href="{{ assets('css/sprout.min.css') }}" />
<script src="{{ assets('js/sprout.min.js') }}"></script>
```

## Twig Helper Functions

SproutPHP uses a **hybrid system** for making PHP helper functions available in your Twig templates:

- **Automatic Registration:** All user-defined functions in `core/Support/helpers.php` are automatically registered as Twig helpers. Just add your function to `helpers.php` and it will be available in your Twig views.
- **Explicit Registration (Optional):** You can also explicitly list additional helpers in the `twig_helpers` array in `config/view.php`. This is useful if you want to expose helpers from other files or override the default set.
- **Both lists are merged and deduplicated.**

### Usage

1. **Add a helper to `helpers.php`:**

   ```php
   // core/Support/helpers.php
   if (!function_exists('my_custom_helper')) {
       function my_custom_helper($arg) {
           return strtoupper($arg);
       }
   }
   ```

   Now you can use it in Twig:

   ```twig
   {{ my_custom_helper('hello') }}
   ```

2. **(Optional) Add a helper to config:**
   ```php
   // config/view.php
   'twig_helpers' => [
       'my_other_helper',
   ],
   ```

### How it works

- All helpers in `helpers.php` are auto-registered.
- Any helpers listed in `twig_helpers` are also registered (even if not in `helpers.php`).
- If a helper exists in both, it is only registered once.

**This means most of the time, you just add your helper to `helpers.php` and it works in Twig!**

### Note on the `view()` Helper

- The `view()` helper now supports a third parameter `$return` (default: `false`).
- If `$return` is `true`, it returns the rendered string instead of echoing it. This is used by the generic fragment helper to inject fragments into layouts.

## HTMX/AJAX Fragment Rendering: Two Approaches

SproutPHP supports two ways to handle routes that should return either a fragment (for HTMX/AJAX) or a full page (for normal requests):

### 1. Generic Helper (Recommended)

Use the `render_fragment_or_full` helper in your route. This will automatically detect if the request is HTMX/AJAX and return just the fragment, or wrap it in your layout for normal requests.

By default, the fragment is injected into the `content` block of `layouts/base.twig`, so direct URL access always returns a full, styled page (navbar, footer, etc.).

```php
Route::get('/my-fragment', function () {
    $data = [/* ... */];
    render_fragment_or_full('partials/my-fragment', $data); // uses layouts/base.twig by default
});
```

- **Best for most use cases.**
- Keeps your code DRY and consistent.
- Ensures direct URL access always returns a full page.
- You can customize the layout or block by passing additional arguments to the helper.
- **Note:** The default layout path is `layouts/base` (not just `base`).

### 2. Manual Fragment Detection (Advanced)

You can manually check for HTMX/AJAX requests and echo the fragment or full layout as needed:

```php
Route::get('/my-fragment', function () {
    $data = [/* ... */];
    if (is_htmx_request() || is_ajax_request()) {
        echo view('partials/my-fragment', $data);
    } else {
        echo view('home', ['main_content' => view('partials/my-fragment', $data, true)]);
    }
});
```

- **Use when you need custom logic per route.**
- Useful for advanced scenarios or when you want to handle fragments differently.

**Tip:** For most routes, use the generic helper. Use the manual method only if you need special handling.

## Preventing HTMX from Handling Certain Links

Sometimes you want a link to always trigger a full page reload (for example, your home link to "/"), rather than being handled by HTMX as a fragment swap. To do this, use one of the following approaches:

- Add `hx-boost="false"` to the `<a>` tag:
  ```html
  <a href="/" hx-boost="false">Home</a>
  ```
- Or, add `target="_self"`:
  ```html
  <a href="/" target="_self">Home</a>
  ```
- Or, add `rel="external"`:
  ```html
  <a href="/" rel="external">Home</a>
  ```

**Best Practice:**

- Use these attributes for any link that should always reload the full page, such as your site home or links to external sites.
- This prevents issues where the page loses its CSS/JS context due to HTMX fragment swaps.

## CORS Middleware

SproutPHP includes a built-in CORS middleware, registered globally by default but disabled in config/security.php:

- To enable CORS, set `'enabled' => true` in the `'cors'` section of `config/security.php` or set `CORS_ENABLED=true` in your `.env` file.
- Configure allowed origins, methods, and headers in the same config file or via environment variables.
- The middleware will automatically set the appropriate CORS headers and handle preflight (OPTIONS) requests.
- By default, CORS is **disabled** for security. Enable only if you need cross-origin requests (e.g., for APIs or frontend apps).

**Example config/security.php:**

```php
'cors' => [
    'enabled' => env('CORS_ENABLED', false),
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
    'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE')),
    'allowed_headers' => explode(',', env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization')),
],
```

**Security Note:**

- Only enable CORS for trusted origins in production. Use `*` for development only.

## CLI Reference

Run `php sprout` for all available commands, including:

- `grow` — Start local dev server
- `make:controller`, `make:model`, `make:view`, `make:route`, `make:component`, `make:migration`, `migrate`, etc.
- `env` — Set environment
- `logs` — View error logs
- `info` — Show framework info

## 🌿 Contributing & Future Growth

SproutPHP is a living, growing project—just like its name! Contributions, ideas, and feedback are welcome. Here’s how you can help this sprout grow:

1. **Fork the repo and clone it locally**
2. **Create a new branch** for your feature or fix
3. **Make your changes** (keep them minimal and in line with the project philosophy)
4. **Submit a pull request**
5. **Discuss and improve** with the community

## PicoCSS Installer (Post-Install Script)

SproutPHP includes a post-install script that lets you choose your preferred PicoCSS build right after running `composer install`.

### How it Works

- After installing dependencies, you'll be prompted to select a PicoCSS build: 0. Default Sprout Layout (Minimal PicoCSS) — just press Enter or choose 0 for the default
  1. Minimal (Standard)
  2. Classless
  3. Conditional
  4. Fluid Classless
  5. Color Theme (choose a color)
  6. Classless + Color Theme
  7. Conditional + Color Theme
  8. Fluid + Classless + Conditional + Color Theme
  9. Color Palette Only
- If you choose a color theme, you'll be prompted for the color (amber, blue, cyan, fuchsia, green, grey, indigo, jade, lime, orange, pink, pumpkin, purple, red, sand, slate, violet, yellow, zinc).
- You'll also be asked if you want the minified version (recommended for production).
- The script will download the latest PicoCSS file from the CDN and save it as `public/assets/css/sprout.min.css`.

### Use Cases

| Use Case                               | Choose This Option                       |
| -------------------------------------- | ---------------------------------------- |
| Default Sprout layout, minimal PicoCSS | 0 (or press Enter)                       |
| Simple blog, no layout classes         | Classless                                |
| Full control, grid, utilities          | Minimal (Standard)                       |
| Themed look + classless                | Classless + Color Theme                  |
| Toggle light/dark with JS              | Conditional or Conditional + Color Theme |
| Full-width layout, no classes          | Fluid Classless                          |
| Define your own classes                | Color Palette Only                       |

### Changing PicoCSS Later

- You can re-run the post-install script at any time:
  ```bash
  php core/Console/PostInstall.php
  ```
- Or, use the CLI command to update PicoCSS interactively:
  ```bash
  php sprout install:pico
  ```
- Or, manually download your preferred PicoCSS file from [jsdelivr PicoCSS CDN](https://cdn.jsdelivr.net/npm/@picocss/pico@latest/css/) and place it in `public/assets/css/sprout.min.css`.

### Advanced

- All PicoCSS builds and color themes are available. See the [PicoCSS documentation](https://picocss.com/docs/) for more details on each build type and theme.

## Production Build (bloom Command)

To prepare your SproutPHP app for production, use the `bloom` command:

```bash
php sprout bloom
```

This will run the production build process (minifies, strips dev code, precompiles, etc.).

- The old `build` command is now replaced by `bloom` for clarity and branding.
- Use this command before deploying your app to production.

## File Upload & Storage (v0.1.7+)

SproutPHP now includes a robust Storage helper for file uploads and URL generation, saving files in `storage/app/public` for web accessibility via a symlink.

### Usage Example

```php
use Core\Support\Storage;

// In your controller
if ($request->hasFile('avatar')) {
    $path = Storage::put($request->file('avatar'), 'avatars');
    $url = Storage::url($path); // /storage/avatars/filename.jpg
}
```

- Files are saved in `storage/app/public/{subdir}`.
- URLs are generated as `/storage/{subdir}/{filename}`.
- The `public/storage` directory is a symlink (or junction on Windows) to `storage/app/public`.

### Storage Configuration

In `config/storage.php`:

```php
'public' => [
    'root' => env('STORAGE_PUBLIC_ROOT', 'storage/app/public'),
    'url' => env('STORAGE_PUBLIC_LINK', '/storage'),
    'visibility' => 'public',
],
```

### Creating the Symlink

To make uploaded files accessible via the web, create a symlink:

```bash
php sprout symlink:create
```

- This links `public/storage` to `storage/app/public`.
- On Windows, a directory junction is created for compatibility.

### Folder Structure

```
project-root/
├── public/
│   ├── assets/
│   ├── index.php
│   └── storage/  # symlink → ../storage/app/public
├── storage/
│   └── app/
│       └── public/
│           └── avatars/
│               └── uploadedfile.jpg
```

### Accessing Uploaded Files

- After upload, files are accessible at `/storage/avatars/filename.jpg`.
- The `Storage::url($path)` helper generates the correct public URL.

### Example Controller Snippet

```php
if ($request->hasFile('avatar')) {
    $file = $request->file('avatar');
    $path = Storage::put($file, 'avatars');
    $avatarUrl = Storage::url($path); // Use in your views
}
```

### Notes

- Always use the `Storage` helper for uploads and URLs.
- The storage root is now absolute for reliability.
- No need to set or override the storage root in `.env` unless you have a custom setup.
- The CLI symlink command ensures public access to uploaded files.

## HTMX File Upload with

You can use your main form for file upload:

```twig
<form
    id="validation-form"
    hx-post="/validation-test"
    hx-target="#form-container"
    hx-swap="innerHTML"
    hx-encoding="multipart/form-data"
    hx-indicator="#form-progress"
    method="POST"
    enctype="multipart/form-data"
    autocomplete="off"
>
    <!-- ...other fields... -->
    <div>
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" id="avatar" accept="image/*">
        {% if errors.avatar %}
            <div class="error" for="avatar" style="color: red;">{{ errors.avatar }}</div>
        {% endif %}
    </div>
    <button type="submit">Submit</button>
    <progress id="form-progress" value="0" max="100"></progress>
</form>
```

- On success, your server can return a fragment with the uploaded avatar URL and preview.

---

## Error Clearing Script

A generic script clears error messages for any field when focused:

```html
<script>
  document.addEventListener("focusin", function (e) {
    if (e.target.form && e.target.name) {
      const error = e.target.form.querySelector(
        `.error[for="${e.target.name}"]`
      );
      if (error) error.remove();
    }
  });
</script>
```

- Works for all fields with `.error[for="fieldname"]`.

---

See the rest of this documentation for more on validation, request handling, and UI best practices.
