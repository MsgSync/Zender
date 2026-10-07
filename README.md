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

## Docker

The image is based on `php:8.4-apache` with `pdo_mysql`, `zip` and `mod_rewrite` enabled. Composer dependencies are installed during the build.

### Quick start (Docker Compose)

1. Point the app at the `db` service by editing `system/configurations/cc_env.inc`:

   ```
   dbhost<=>db
   dbport<=>3306
   dbname<=>zender
   dbuser<=>root
   dbpass<=>root
   ```

2. Build and start the stack:

   ```bash
   docker compose up -d --build
   ```

3. Import the database schema:

   ```bash
   docker compose exec -T db mysql -uroot -proot zender < install.sql
   ```

4. Open http://localhost:8080.

To publish on a different host port, set `HTTP_PORT` (e.g. `HTTP_PORT=8090 docker compose up -d`).

The compose file mounts `uploads/` and `system/storage/` from the host so data persists across rebuilds. MySQL is only reachable from the `app` service over the compose network (the app waits for it to pass a healthcheck before starting).

Useful commands:

```bash
docker compose logs -f app     # follow app logs
docker compose down            # stop containers
docker compose down -v         # stop and remove volumes
```

### Build the image manually

```bash
docker build -t zender .
docker run -d -p 8080:80 --name zender zender
```

When running standalone, set `dbhost` in `system/configurations/cc_env.inc` to a MySQL host reachable from the container (rebuild the image after changing it, or bind-mount the file).

### Prebuilt image (GHCR)

Pushes to `main` and `v*` tags publish an image via `.github/workflows/docker.yml`:

```bash
docker pull ghcr.io/msgsync/zender:latest
docker run -d -p 8080:80 \
  -v "$PWD/system/configurations/cc_env.inc:/var/www/html/system/configurations/cc_env.inc" \
  ghcr.io/msgsync/zender:latest
```

Available tags: `latest` (default branch), `sha-<commit>`, and release tags (e.g. `v1.0.0`).

> Change the default MySQL credentials and `systoken` before deploying to production.

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
