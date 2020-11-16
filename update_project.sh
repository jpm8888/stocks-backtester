#!/usr/bin/env bash
git status
git stash
git pull
composer install --no-dev
php artisan migrate
yarn install
npm run production
echo "all done for now..."
