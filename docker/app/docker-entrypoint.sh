#!/bin/sh
set -e

host="$DB_HOST"
port="$DB_PORT"

FLAG_FILE="/var/www/.setup_complete"

echo "Waiting for database at $host:$port..."
while ! nc -z "$host" "$port"; do
  sleep 1
done
echo "Database is ready!"

chown -R www-data:www-data storage bootstrap/cache

if [ ! -f "$FLAG_FILE" ]; then
    echo "First time setup. Running key generation, migrations and seeding..."

    if grep -q 'APP_KEY=' .env; then
        php artisan key:generate
        set -a && . ./.env && set +a
    fi

    php artisan migrate --force

    touch "$FLAG_FILE"
    echo "First time setup is complete."
else
    echo "Setup has already been completed. Skipping migrations and seeding."
fi

if [ ! -L "public/storage" ]; then
    php artisan storage:link
fi

exec "$@"