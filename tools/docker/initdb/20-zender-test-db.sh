#!/bin/sh
# Runs once on first container start: create and seed the isolated test
# database used by PHPUnit (tests/config/cc_env.inc defaults to it).
set -e

mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" <<'SQL'
CREATE DATABASE IF NOT EXISTS zender_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SQL

mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" zender_test < /docker-entrypoint-initdb.d/10-zender.sql
mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" zender_test < /docker-entrypoint-initdb.d/30-dev-admin.sql

echo "zender_test database created and seeded (dev admin: admin@zender.test)."
