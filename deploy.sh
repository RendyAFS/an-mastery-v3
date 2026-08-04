#!/bin/bash

# Exit on error
set -e

echo "🚀 Updating AN Mastery V3 (Local Environment)..."

# 1. Tarik kode terbaru dari repository
echo "📥 Pulling latest code from origin main..."
git pull origin main

# 2. Update dependensi Composer
echo "📦 Installing Composer dependencies..."
composer install

# 3. Jalankan migrasi database
echo "🗄️ Running database migrations..."
php artisan migrate

# 4. Update & Build frontend assets
if [ -f "package.json" ]; then
    echo "⚡ Building frontend assets..."
    npm install
    npm run build
fi

# 5. Membersihkan cache aplikasi
echo "🧹 Clearing application caches..."
php artisan optimize:clear

echo "✅ Local update completed successfully!"

