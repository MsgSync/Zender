#!/bin/sh
# One-shot local setup for contributors (run outside Docker).
set -e

cd "$(dirname "$0")/.."

command -v composer >/dev/null 2>&1 || { echo "composer is required" >&2; exit 1; }
command -v npm >/dev/null 2>&1 || { echo "npm is required" >&2; exit 1; }

composer install --no-interaction --prefer-dist
npm ci

if [ ! -f system/configurations/cc_env.inc ]; then
    cp system/configurations/cc_env.inc.example system/configurations/cc_env.inc
    echo "Created system/configurations/cc_env.inc from the example - edit the credentials."
fi

cat <<'EOF'

Setup complete. Next steps:

  make dev     # start the Docker development stack (app + db, auto-seeded)
  make test    # run the PHPUnit suite inside the app container
  make lint    # PHP + JS lint
  make build   # compile theme SCSS
EOF
