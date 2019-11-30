#!/usr/bin/env bash
git status
git stash
git pull
composer install --no-dev
php artisan cache:clear
php artisan route:cache
php artisan view:clear
php artisan config:cache
php artisan config:clear
npm install
npm run production
echo "all done for now..."