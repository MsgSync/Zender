#!/bin/sh
# Import install.sql into a database through the running compose db service.
#
# Usage:
#   tools/seed-db.sh            # seed `zender` (the application database)
#   tools/seed-db.sh zender_test  # seed the PHPUnit test database
set -e

DB_NAME="${1:-zender}"
ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"

if ! command -v docker >/dev/null 2>&1; then
    echo "docker is required to reach the compose database" >&2
    exit 1
fi

echo "Seeding database '${DB_NAME}' from install.sql..."
docker compose exec -T db sh -c \
    "mysql -uroot -p\"\$MYSQL_ROOT_PASSWORD\" -e 'CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4;'" \
    && docker compose exec -T db sh -c \
    "mysql -uroot -p\"\$MYSQL_ROOT_PASSWORD\" '${DB_NAME}'" < "${ROOT_DIR}/install.sql" \
    && docker compose exec -T db sh -c \
    "mysql -uroot -p\"\$MYSQL_ROOT_PASSWORD\" '${DB_NAME}'" < "${ROOT_DIR}/tools/dev-admin.sql"

echo "Done (dev admin: admin@zender.test / password)."
