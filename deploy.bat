@echo off
echo 🚀 Updating AN Mastery V3 (Local Windows)...

echo 📥 Pulling latest code from origin main...
git pull origin main

echo 📦 Installing Composer dependencies...
call composer install

echo 🗄️ Running database migrations...
php artisan migrate

echo ⚡ Building frontend assets...
call npm install
call npm run build

echo 🧹 Clearing application caches...
php artisan optimize:clear

echo ✅ Local update completed successfully!
