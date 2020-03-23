<?php

namespace App\Console;

use App\Console\Commands\DeleteTempFiles;
use App\Console\Commands\ImportIndicesData;
use App\Console\Commands\ImportVixData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\DailyUpdate::class,
        Commands\DownloadBhavCopyCombined::class,
        Commands\DownloadBhavCopyCM::class,
        Commands\DownloadBhavCopyFO::class,
        Commands\DownloadSecurityWiseDelvPos::class,
        Commands\SendLogsViaMail::class,
        Commands\DataProcessing\v1\ProcessBhavcopyCMV01::class,
        DeleteTempFiles::class,
        ImportVixData::class,
        ImportIndicesData::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('download:bhavcopy_cm')->weekdays()->at('18:30');
        $schedule->command('download:delv_wise_positions')->weekdays()->at('18:35');
        $schedule->command('download:bhavcopy_fo')->weekdays()->at('18:40');

        $schedule->command('import:vix_data')->weekdays()->at('21:00');
        $schedule->command('import:indices')->weekdays()->at('21:00');

        $schedule->command('process:bhavcopy_v1')->weekdays()->at('18:50');
        $schedule->command('send_mail:logs')->weekdays()->at('19:30');


        $schedule->command('delete:temp')->dailyAt('00:00');
        $schedule->command('delete:temporary_files')->dailyAt('00:00');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
