#!/usr/bin/env bash
php artisan download:bhavcopy_cm
php artisan download:bhavcopy_nse_cm
php artisan make:stocks_cm
php artisan download:delv_wise_positions
php artisan download:bhavcopy_fo
php artisan process:bhavcopy_v1
php artisan import:vix_indices_data
php artisan process:bhavcopy_indices_v1
php artisan delete:temp
php artisan delete:temporary_files
