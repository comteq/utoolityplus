<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateScheduleStatus;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        UpdateScheduleStatus::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('update:schedule-status')->daily();
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
