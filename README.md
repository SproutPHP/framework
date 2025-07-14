# SproutPHP — Minimalist, Batteries-Included PHP Framework

## 🌱 Why "Sprout"?

SproutPHP is a **growing and budding framework**—a tiny sprout with the ambition to become a solid tree. It starts small, fast, and minimal, but is designed to evolve and grow with the needs of its community. The name "Sprout" reflects this progressive, ever-improving philosophy. If you want to be part of something that starts simple and grows strong, you’re in the right place!

## Philosophy

SproutPHP is for developers who know PHP, HTML, and CSS, and want to build fast, modern web applications without the bloat of heavy JavaScript frameworks or large dependency trees. It is minimal, but **batteries-included**: everything you need for a beautiful, interactive web app is ready out of the box.

- **No heavy JavaScript**: Use [HTMX](https://htmx.org/) for interactivity, not SPA frameworks.
- **Minimal dependencies**: Only essential Composer packages, no `node_modules` or asset pipeline.
- **MVC structure**: Clear separation of Controllers, Models, and Views.
- **Twig templating**: Clean, secure, and fast rendering (optional to swap for native PHP if desired).
- **Simple routing**: Easy-to-understand routing system.
- **Powerful CLI**: Scaffold controllers, models, migrations, views (with HTMX demo), resources, routes, and components with the `sprout` CLI—no manual file creation needed.
- **Batteries included**: HTMX and PicoCSS are pre-installed and loaded in your base template. Your app is modern and beautiful from the start.
- **Direct SQL or minimal DB abstraction**: Use the Model layer for direct database access, not a heavy ORM.
- **Custom error handling**: Lean error handler with dev/prod toggle.
- **Security helpers**: Basic CSRF and XSS protection (documented, not forced).

## Getting Started

1. **Clone the repository**
2. **Install dependencies**
   ```bash
   composer install
   ```
3. **Set up your web server** to point to the `public/` directory.
4. **Use the CLI to scaffold files**
   ```bash
   php sprout make:controller HomeController
   php sprout make:model Post
   php sprout make:view home
   php sprout make:route posts
   # ...and more (run `php sprout` for all commands)
   ```
5. **Edit routes in** `routes/web.php`.
6. **Create controllers in** `app/Controllers/`.
7. **Create views in** `app/Views/` (Twig by default).
8. **Create models in** `app/Models/` for database access.

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

**Star this repo** to show your support and follow future updates!

**Sponsor development:** [![Sponsor](https://img.shields.io/badge/Sponsor-%E2%9D%A4%EF%B8%8F-pink?logo=githubsponsors&style=flat-square)](https://github.com/sponsors/yanikkumar) or [![Buy Me a Tea](https://img.shields.io/badge/Buy%20Me%20a%20Tea-ffdd00?logo=buymeacoffee&logoColor=black&style=flat-square)](https://buymeacoffee.com/yanikkumar)

> SproutPHP will continue to grow with new features, improvements, and community input. Stay tuned for updates, and help shape the future of this framework!

## License

MIT
