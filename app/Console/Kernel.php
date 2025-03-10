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
        // Import BMX events daily at 1:00 AM
        $schedule->command('import:bmx-events')
                 ->dailyAt('01:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/bmx-events-import.log'));
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