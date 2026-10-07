# Zender

Zender — Android Mobile Devices as SMS Gateway (SaaS Platform).
Custom PHP MVC framework by KhulnaSoft.

## Requirements

- PHP 7.2–8.x (legacy dependency pins; composer.json pins platform PHP to 7.4 for resolution)
- MySQL/MariaDB
- Composer

## Setup

```bash
composer install
```

1. Import `install.sql` into your database.
2. Configure `system/configurations/mvc_database.php` and `system/configurations/cc_env.inc`.
3. Point your web server document root at this directory (see `.htaccess` / `.nginx.conf`).
4. Ensure `uploads/` and `system/storage/{cache,smarty,temporary}` are writable.

## Structure

```
index.php            Front controller
install.sql          Database schema
system/              MVC framework, controllers, models, plugins, config
templates/           Smarty templates and assets
uploads/             User-uploaded content (gitignored)
system/storage/      Cache/compiled/temporary files (gitignored)
vendor/              Composer dependencies (gitignored)
docs/       HTML documentation
```

## Notes

- Dependencies are pinned to the versions recorded in the original `vendor/` tree; some have known security advisories (`composer audit`). Upgrading them is recommended but may require code changes.
- `mysql-import` in vendor was a git submodule and is reinstallable via Composer.
