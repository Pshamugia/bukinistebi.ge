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
        // $schedule->command('searchkeywords:clear-old')->monthly();
        $schedule->command('search:cleanup')->monthly();
        $schedule->command('user-preferences:clean')->monthlyOn(1, '2:00');
        $schedule->command('auctions:activate-approved')->everyMinute();
        $schedule->command('auctions:close-expired')->everyMinute();
        $schedule->command('queue:work --stop-when-empty')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
    protected $commands = [
        \App\Console\Commands\GenerateSitemap::class,
        \App\Console\Commands\DeleteOldUserPreferences::class, // ✅ Add this line
    ];
}
