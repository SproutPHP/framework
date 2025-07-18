# SproutPHP Documentation

> ⚠️ **Security Warning:**
>
> For security, you **must** set your web server's document root to the `public/` directory only. Never expose the project root or any directory above `public/` to the web. If misconfigured, sensitive files (like `.env`, `storage/`, `config/`, etc.) could be publicly accessible and compromise your application.
>
> See [Server Configuration](#server-configuration-apache--nginx) for deployment details.

## Server Configuration (Apache & Nginx)

### Apache (.htaccess)

```apache
# Place this in your project root (not public/)
# Redirect all requests to public/
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L]

# Deny access to sensitive files everywhere
<FilesMatch "^(\.env|\.git|composer\.(json|lock)|config\.php)$">
  Order allow,deny
  Deny from all
</FilesMatch>
```

### Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;

    # Set the root to the public directory
    root /path/to/your/project/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Deny access to sensitive files
    location ~ /\.(env|git|htaccess) {
        deny all;
    }
    location ~* /(composer\.json|composer\.lock|config\.php) {
        deny all;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

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

## Routing

SproutPHP supports flexible route parameters (like most modern frameworks):

| Pattern            | Matches | Example URI              | Notes                       |
| ------------------ | ------- | ------------------------ | --------------------------- |
| `/user/{id}`       | Yes     | `/user/123`              | `{id}` = 123                |
| `/user/{id?}`      | Yes     | `/user/123`, `/user`     | `{id}` = 123 or null        |
| `/file/{path:.+}`  | Yes     | `/file/foo/bar`          | `{path}` = foo/bar          |
| `/file/{path?:.+}` | Yes     | `/file`, `/file/foo/bar` | `{path}` = foo/bar or null  |
| `/blog/{slug}`     | Yes     | `/blog/hello-world`      | `{slug}` = hello-world      |
| `/blog/{slug}`     | No      | `/blog/hello/world`      | `{slug}` does not match `/` |

- <code>{param}</code> — required parameter (matches anything except <code>/</code>)
- <code>{param?}</code> — optional parameter (trailing slash and parameter are optional)
- <code>{param:regex}</code> — required with custom regex
- <code>{param?:regex}</code> — optional with custom regex

Optional parameters are passed as <code>null</code> if missing. For catch-all (wildcard) parameters, use a custom regex like <code>{path:.+}</code>.

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

```

```
