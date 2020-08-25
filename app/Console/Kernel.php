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
        // $schedule->command('download:bhavcopy_cm')->weekdays()->at('08:30');
        // $schedule->command('download:bhavcopy_nse_cm')->weekdays()->at('08:31');
        // $schedule->command('make:stocks_cm')->weekdays()->at('08:32');
        // $schedule->command('download:delv_wise_positions')->weekdays()->at('08:33');
        // $schedule->command('download:bhavcopy_fo')->weekdays()->at('08:34');

        // $schedule->command('process:bhavcopy_v1')->weekdays()->at('08:35');

        // $schedule->command('import:vix_indices_data')->weekdays()->at('08:36');
        // $schedule->command('process:bhavcopy_indices_v1')->weekdays()->at('08:37');

        // $schedule->command('send_mail:logs')->weekdays()->at('10:10');

        // $schedule->command('delete:temp')->dailyAt('11:00');
        // $schedule->command('delete:temporary_files')->dailyAt('11:00');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
