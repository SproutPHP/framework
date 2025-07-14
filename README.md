# SproutPHP — Minimalist, Batteries-Included PHP Framework

## 🌱 Why "Sprout"?

SproutPHP is a **growing and budding framework**—a tiny sprout with the ambition to become a solid tree. It starts small, fast, and minimal, but is designed to evolve and grow with the needs of its community. The name "Sprout" reflects this progressive, ever-improving philosophy. If you want to be part of something that starts simple and grows strong, you’re in the right place!

## Philosophy

SproutPHP is for developers who know PHP, HTML, and CSS, and want to build fast, modern web applications without the bloat of heavy JavaScript frameworks or large dependency trees. It is minimal, but **batteries-included**: everything you need for a beautiful, interactive web app is ready out of the box.

- **No heavy JavaScript**: Use [HTMX](https://htmx.org/) for interactivity, not SPA frameworks.
- **Minimal dependencies**: Only essential Composer packages, no `node_modules` or asset pipeline.
- **MVC structure**: Clear separation of Controllers, Models, and Views.
- **Twig templating**: Clean, secure, and fast rendering (optional to swap for native PHP if desired).

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

## Learn More

- **Full documentation, features, and advanced usage:** See [DOCS.md](./DOCS.md)
- **Contributing:** See [CONTRIBUTING.md](./CONTRIBUTING.md) (if available)

**Star this repo** to show your support and follow future updates!

**Sponsor development:** [![Sponsor](https://img.shields.io/badge/Sponsor-%E2%9D%A4%EF%B8%8F-pink?logo=githubsponsors&style=flat-square)](https://github.com/sponsors/yanikkumar) or [![Buy Me a Tea](https://img.shields.io/badge/Buy%20Me%20a%20Tea-ffdd00?logo=buymeacoffee&logoColor=black&style=flat-square)](https://buymeacoffee.com/yanikkumar)

>SproutPHP will continue to grow with new features, improvements, and community input. Stay tuned for updates, and help shape the future of this framework! 

## License

MIT
