<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Test task: log every minute
        $schedule->call(function () {
            \Log::info('Test scheduler is running at: ' . now());
        })->everyMinute();
    
        // Full backup every day at 2 AM
        $schedule->command('backup:run')->dailyAt('02:00');
    
        // Database backup every day at 1 AM
        $schedule->command('backup:run --only-db')->dailyAt('01:00');
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