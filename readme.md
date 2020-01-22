Details:

`php artisan download:bhavcopy_cm` - to download cash market copy

`php artisan download:bhavcopy_fo` - to download future market copy

`php artisan download:devl_wise_position` - to download delivery wise positions copy

`./vendor/bin/phpunit --testdox tests/Unit/DataProcessing/V1ProcessingOptionsTest.php`

to run test cases : use `./vendor/bin/phpunit --testdox`


###MySQL Partitions:

partitions are date wise and for 2030 done in following tables:
1. `bhavcopy_cm`
2. `bhavcopy_fo`
3. `bhavcopy_processed`
4. `bhavcopy_delv_position`


