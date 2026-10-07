#!/bin/sh
# Development entrypoint: write the container environment file, make sure
# composer dependencies (including dev) are installed, then start Apache.
set -e

: "${ZENDER_CC_ENV:=/tmp/zender-cc_env.inc}"
: "${DB_HOST:=db}"
: "${DB_PORT:=3306}"
: "${DB_NAME:=zender}"
: "${DB_USER:=root}"
: "${DB_PASS:=root}"
: "${ZENDER_SYSTEM_TOKEN:=dev-token}"

cat > "${ZENDER_CC_ENV}" <<EOF
dbhost<=>${DB_HOST}
dbport<=>${DB_PORT}
dbname<=>${DB_NAME}
dbuser<=>${DB_USER}
dbpass<=>${DB_PASS}
systoken<=>${ZENDER_SYSTEM_TOKEN}
installed<=>1
EOF

if [ -f composer.json ]; then
    # Install only when the lock file changed (keeps startup fast and avoids
    # churning the bind-mounted vendor/ directory)
    if [ ! -f vendor/composer/installed.json ] || [ composer.lock -nt vendor/composer/installed.json ]; then
        composer install --no-interaction --prefer-dist --no-progress
        # Container runs as root; keep files owned by the host user
        chown -R "$(stat -c '%u:%g' composer.json)" vendor 2>/dev/null || true
    fi
fi

# Bind-mounted sources are owned by the host user; open them up for www-data
chmod -R a+rwX uploads system/storage 2>/dev/null || true

exec apache2-foreground
