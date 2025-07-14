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

