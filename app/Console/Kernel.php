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
    ];


    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('daily:update')->dailyAt('08:30');
//        $schedule->command('download:bhavcopy_combined')->dailyAt('03:00');
        $schedule->command('download:bhavcopy_cm')->dailyAt('08:30');
        $schedule->command('download:bhavcopy_fo')->dailyAt('08:30');
        $schedule->command('delete:temp')->dailyAt('00:00');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
