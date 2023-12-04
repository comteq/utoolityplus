<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateScheduleStatus;

class Kernel extends ConsoleKernel
{

    protected $commands = [
       
    ];
    
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:schedules')->everyMinute()->runInBackground();
    }
    
    
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
