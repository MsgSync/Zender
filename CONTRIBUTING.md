# Contributing to Zender

## Development environment

Quick start (Docker, recommended):

```bash
make dev          # build + start app and MySQL (auto-seeded on first run)
make test         # run the full PHPUnit suite inside the app container
make lint         # PHP syntax lint + ESLint
make build        # compile theme SCSS
```

The stack is available at http://localhost:8080 (override with
`make dev HTTP_PORT=8090`). On first boot the databases are seeded from
`install.sql`, including a development administrator:

| | |
|---|---|
| URL | http://localhost:8080 |
| Email | `admin@zender.test` |
| Password | `password` |

Two databases are created: `zender` (the application) and `zender_test`
(used by PHPUnit, never touched by the app). `make reset` wipes both
volumes; they re-seed on the next `make dev`.

### Host-only setup

If you prefer running tools directly on the host:

```bash
./tools/setup.sh   # composer install + npm ci + sample env config
```

Note: integration tests require `pdo_mysql` and a reachable MySQL. Without
them they skip cleanly — run `make test` for the full suite.

## Testing

```bash
make test                    # in Docker (unit + integration)
composer test:unit           # host, no database needed
composer test:integration    # host, requires pdo_mysql + seeded test DB
vendor/bin/phpunit --filter HelperFunctionsTest   # single class
```

- `tests/Unit` — pure logic, no database.
- `tests/Integration` — runs against `zender_test`; skips when the database
  is unreachable or unseeded.
- `tests/bootstrap.php` loads the framework without dispatching a request
  (`MVC_NO_DISPATCH`), using a generated `tests/config/cc_env.inc` so your
  real `system/configurations/cc_env.inc` is never read or written.

New behaviour should come with tests; keep `composer test` green.

## Coding standards

```bash
composer phpcs         # blocking: PSR-12 for index.php, tests/ (run by CI)
composer phpcbf        # auto-fix what can be auto-fixed
composer phpcs:legacy  # informational PSR-12 scan of system/
npm run lint           # ESLint (Gruntfile and other first-party JS)
```

The legacy `system/` tree is scanned by `phpcs:legacy` for visibility but is
not a merge blocker; new code in `system/` should follow PSR-12 where
practical. JavaScript outside `templates/` (vendored assets are exempt) must
pass ESLint.

## Frontend

- Source SCSS: `templates/_scss/{default,dashboard}.scss`
- `make build` (or `npm run build`) compiles to
  `templates/*/assets/css/style.min.css`, mirroring the runtime compilation
  the admin theme picker performs; `npm run watch` rebuilds on change.
- Node version is pinned in `.nvmrc`.

## Debugging

The dev container ships Xdebug 3 (`XDEBUG_MODE=debug` is set in
`docker-compose.dev.yml`). Trigger a session via the `XDEBUG_TRIGGER`
parameter or cookie from your IDE/browser extension; the client listens on
`host.docker.internal:9003`. Set `XDEBUG_MODE=off make dev` for maximum
performance.

## Pull requests

- One change per PR; include tests for bug fixes and new behaviour.
- CI must pass: PHP lint + phpcs + unit tests, integration tests against
  MySQL, frontend lint + build, and the Docker image build.
- Do not commit credentials (`cc_env.inc`), `vendor/`, `node_modules/`, or
  generated files listed in `.gitignore`.
