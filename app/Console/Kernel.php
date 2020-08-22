<?php

namespace App\Console;

use App\Console\Commands\DeleteTemp;
use App\Console\Commands\DeleteTempFiles;
use App\Console\Commands\ImportVixIndexData;
use App\Console\Commands\MakeStocksCMList;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\DailyUpdate::class,
        Commands\DownloadBhavCopyCombined::class,
        Commands\DownloadBhavCopyCM::class,
        Commands\DownloadBhavCopyNseCM::class,
        Commands\DownloadBhavCopyFO::class,
        Commands\DownloadSecurityWiseDelvPos::class,
        Commands\SendLogsViaMail::class,
        Commands\DataProcessing\v1\ProcessBhavcopyCMV01::class,
        Commands\DataProcessing\v1\ProcessBhavcopyIndices::class,
        DeleteTempFiles::class,
        DeleteTemp::class,
        MakeStocksCMList::class,
        ImportVixIndexData::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('download:bhavcopy_cm')->weekdays()->at('09:20');
        $schedule->command('download:bhavcopy_nse_cm')->weekdays()->at('09:25');
        $schedule->command('make:stocks_cm')->weekdays()->at('09:32');
        $schedule->command('download:delv_wise_positions')->weekdays()->at('09:35');
        $schedule->command('download:bhavcopy_fo')->weekdays()->at('09:40');

        $schedule->command('process:bhavcopy_v1')->weekdays()->at('09:50');

        $schedule->command('import:vix_indices_data')->weekdays()->at('09:55');
        $schedule->command('process:bhavcopy_indices_v1')->weekdays()->at('10:00');

        $schedule->command('send_mail:logs')->weekdays()->at('10:10');

        $schedule->command('delete:temp')->dailyAt('10:20');
        $schedule->command('delete:temporary_files')->dailyAt('10:20');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
