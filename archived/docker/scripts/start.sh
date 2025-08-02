#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until nc -z mysql 3306; do
    echo "MySQL is not ready yet. Waiting..."
    sleep 5
done
echo "MySQL is ready!"

# Navigate to the application directory
cd /var/www/html

# Set up Laravel
echo "Setting up Laravel..."

# Generate application key if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Generate application key
php artisan key:generate --no-interaction

# Cache configuration
php artisan config:cache

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Seed the database (optional)
# php artisan db:seed --force

# Clear and cache routes
php artisan route:cache

# Clear and cache views
php artisan view:cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "Laravel setup complete!"

# Start supervisor
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf 