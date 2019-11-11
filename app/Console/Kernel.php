<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\DailyUpdate::class,
        Commands\DownloadBhavCopy::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('daily:update')->dailyAt('08:30');
        $schedule->command('download:bhavcopy')->dailyAt('03:00');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
