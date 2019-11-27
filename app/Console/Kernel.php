<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\DailyUpdate::class,
        Commands\DownloadBhavCopyCombined::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('daily:update')->dailyAt('08:30');
        $schedule->command('download:bhavcopy_combined')->dailyAt('03:00');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
