#!/usr/bin/env bash
cd public/trading_view_lib
git checkout unstable
git branch
git pull
cp -r charting_library ../
cp -r datafeeds ../
cp -r charting_library ../resources/js/


#git status
#git stash
#git pull
#composer install --no-dev
#composer dump-autoload
#php artisan migrate
#php artisan cache:clear
#php artisan route:cache
#php artisan view:clear
#php artisan config:cache
#php artisan config:clear
#npm install
#npm run production
#echo "all done for now..."
