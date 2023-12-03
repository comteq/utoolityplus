<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\schedules; // Adjust this based on your actual model namespace

class UpdateScheduleStatus extends Command
{
    protected $signature = 'update:schedule-status';
    protected $description = 'Update schedule status based on event datetime';

    public function handle()
    {
        $schedules = schedules::all();

        foreach ($schedules as $schedule) {
            $eventDateTime = Carbon::parse($schedule->event_datetime);
            $eventDateTimeOff = Carbon::parse($schedule->event_datetime_off);

            if ((Carbon::now() > $eventDateTime || Carbon::now() > $eventDateTimeOff) && $schedule->status != 'Finished') {
                $schedule->update(['status' => 'Finished']);
            }
        }

        $this->info('Schedule status updated successfully!');
    }
}
