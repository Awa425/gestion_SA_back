<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:absence-insertion')->dailyAt('16:00');
        $schedule->command('app:send-event-notifications')->everyMinute();
    }
    
    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->call(function () {
    //         // Créer une nouvelle table de présence ici
    //     })->daily();
    // }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
