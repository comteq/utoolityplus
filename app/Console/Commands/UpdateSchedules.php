<?php

namespace App\Console\Commands;
use App\Models\schedules;
use App\Models\unit;
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
        // Fetch schedules that are active within the given time frame
            $activeSchedules = schedules::where('status', '!=', 'Processing')
            ->where('event_datetime', '<=', now())
            ->where('event_datetime_off', '>=', now())
            ->get();
    
            foreach ($activeSchedules as $schedule) {
            // Update schedule table
            $schedule->update([
                'status' => 'Processing',
                'state' => 'Active',
                'ACStat' => 'Active',
                'LightStat' => 'Active',
            ]);
    
            // Update unit table when the schedule is active
            $unit = unit::find(1); // Assuming the target row ID is 1
            if ($unit) {
                $unit->AC = '1'; // Update AC column to '1' (make sure it's the correct ENUM format)
                $unit->Lights = '1'; // Update Lights column to '1' (make sure it's the correct ENUM format)
                $unit->save();
                $this->info('Unit table AC and Lights columns updated to 1 successfully.');
            } else {
                $this->error('Unit with ID 1 not found for AC and Lights.');
            }
            }
    
            // Check if the current time matches 'event_datetime_off' in the schedules table and 'status' column is 'Processing' after a 30-second delay
            $dateMatchingSchedules = schedules::where('status', 'Processing')
            ->where('event_datetime_off', '<=', now()->addSeconds(5))
            ->get();
    
            foreach ($dateMatchingSchedules as $schedule) {
            $unit = unit::find(1); // Assuming the target row ID is 1
            if ($unit) {
                $unit->AC = '0'; // Update AC column to '0' (make sure it's the correct ENUM format)
                $unit->Lights = '0'; // Update Lights column to '0' (make sure it's the correct ENUM format)
                $unit->save();
                $this->info('Unit table AC and Lights columns updated to 0 successfully.');
            } else {
                $this->error('Unit with ID 1 not found for AC and Lights.');
            }
            }
    
            // Update schedules that are not active anymore
            $schedulesToUpdate = schedules::where('status', '!=', 'Finished')
            ->where('event_datetime_off', '<', now())
            ->get();
    
            foreach ($schedulesToUpdate as $schedule) {
            $schedule->update([
                'status' => 'Finished',
                'state' => 'In-Active',
                'ACStat' => 'In-Active',
                'LightStat' => 'In-Active',
            ]);
            }
            $this->info('Schedules updated successfully.');
    
        }
    
}
