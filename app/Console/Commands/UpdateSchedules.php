<?php

namespace App\Console\Commands;
use App\Models\schedules;
use Illuminate\Console\Command;

class UpdateSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:schedules';
    protected $description = 'Update schedules automatically';

    /**
     * The console command description.
     *
     * @var string
     */
  

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schedulesToUpdate = schedules::where('status', '!=', 'Finished')
            ->where('event_datetime_off', '<', now())  
            ->get();

            foreach ($schedulesToUpdate as $schedule) {
                $schedule->update([
                    'status' => 'Finished',
                    'state' => 'In-Active', // Assuming 'state' is the column to update
                ]);
            }
            
        $this->info('Schedules updated successfully.');
    }
}
