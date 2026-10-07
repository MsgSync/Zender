#!/bin/sh
# Run the full local verification pass: PHP syntax lint, coding standard
# (new code), and the PHPUnit suite.
set -e

cd "$(dirname "$0")/.."

echo "==> PHP syntax lint"
composer lint

echo "==> Coding standard (phpcs)"
vendor/bin/phpcs

echo "==> PHPUnit"
XDEBUG_MODE=off vendor/bin/phpunit

echo "All checks passed."
