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
        $schedulesToUpdate = schedules::where('status', '!=', 'Finished')
            ->where('event_datetime_off', '<', now())  
            ->get();

        $processing = schedules::where('status', '!=', 'Processing')
            ->where('event_datetime', '>=', now())
            ->where('event_datetime', '<', now()->addSeconds(10))  
            ->get();

            foreach ($processing as $schedule) {
                $isOn = ($schedule->description === 'ON');
                $updateData = [
                    'status' => 'Processing',
                    'state' => 'Active',
                    'ACStat' => $isOn ? 'Active' : 'In-Active',
                    'LightStat' => $isOn ? 'Active' : 'In-Active',
                ];
                $schedule->update($updateData);
            }
            
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
