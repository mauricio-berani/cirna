#!/usr/bin/env sh
set -eu

cd /var/www

umask 0002

mkdir -p \
  storage/app/public \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/testing \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache || true
find storage bootstrap/cache -type d -exec chmod 2775 {} \; || true
find storage bootstrap/cache -type f -exec chmod 664 {} \; || true

if [ -f composer.json ] && [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ -f artisan ] && [ -d public ] && [ ! -L "public/storage" ]; then
  php artisan storage:link || ln -s /var/www/storage/app/public /var/www/public/storage || true
fi

exec docker-php-entrypoint "$@"
