#!/usr/bin/env bash
git status
git stash
git pull
composer install --no-dev
composer dump-autoload
php artisan migrate
php artisan cache:clear
php artisan route:cache
php artisan view:clear
php artisan config:cache
php artisan config:clear
yarn install
yarn run production
echo "all done for now..."
