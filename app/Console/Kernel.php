<?php

namespace App\Console;

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
    ];


    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('daily:update')->dailyAt('08:30');
//        $schedule->command('download:bhavcopy_combined')->dailyAt('03:00');
        $schedule->command('download:bhavcopy_cm')->weekdays()->at('18:30');
        $schedule->command('download:delv_wise_positions')->weekdays()->at('18:45');
        $schedule->command('download:bhavcopy_fo')->weekdays()->at('19:00');
        $schedule->command('delete:temp')->dailyAt('00:00');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
