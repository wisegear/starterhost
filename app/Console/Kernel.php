<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule database backup daily
        $schedule->command('backup:run --only-db')->dailyAt('01:00');

        // Schedule full backup daily
        $schedule->command('backup:run')->dailyAt('02:00');

        // Temporarily schedule the backup to run every minute
        $schedule->command('backup:run')->everyMinute();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}