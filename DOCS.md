# SproutPHP Documentation

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
- After installing dependencies, you'll be prompted to select a PicoCSS build:
  0. Default Sprout Layout (Minimal PicoCSS) — just press Enter or choose 0 for the default
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
| Use Case | Choose This Option |
|----------|-------------------|
| Default Sprout layout, minimal PicoCSS | 0 (or press Enter) |
| Simple blog, no layout classes | Classless |
| Full control, grid, utilities | Minimal (Standard) |
| Themed look + classless | Classless + Color Theme |
| Toggle light/dark with JS | Conditional or Conditional + Color Theme |
| Full-width layout, no classes | Fluid Classless |
| Define your own classes | Color Palette Only |

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

