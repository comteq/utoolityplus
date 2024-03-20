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
                $unit->Status = '1'; // Update Lights column to '1'
                $unit->save();
                $this->info('Unit Status for Lights columns updated to 1 successfully.');
                
                // Sending Data to Arduino for Lights
                $arduinoIp = '192.168.1.110'; // Replace with your Arduino's IP address
                $arduinoPort = 50003; // Replace with your Arduino's port
                $dataType = 'AC: ';
                $dataToSend = $dataType . '0'; // Sending 'Lights: 1'

                try {
                    $socket = fsockopen($arduinoIp, $arduinoPort, $errno, $errstr, 10);
                    if ($socket) {
                        fwrite($socket, $dataToSend);
                        fclose($socket);
                        $this->info('Data sent to Arduino for Lights.');
                    } else {
                        $this->error('Failed to connect to Arduino for Lights.');
                    }
                } catch (\Exception $e) {
                    $this->error('Failed to connect to Arduino for Lights.');
                }
            } else {
                $this->error('Unit with ID 1 not found for Lights Status.');
            }

            $unit = unit::find(2); // Assuming the target row ID is 1
            if ($unit) {
                $unit->Status = '1'; // Update Lights column to '1'
                $unit->save();
                $this->info('Unit Status for Lights columns updated to 1 successfully.');
                
                // Sending Data to Arduino for Lights
                $arduinoIp = '192.168.1.110'; // Replace with your Arduino's IP address
                $arduinoPort = 50003; // Replace with your Arduino's port
                $dataType = 'Lights: ';
                $dataToSend = $dataType . '0'; // Sending 'Lights: 1'

                try {
                    $socket = fsockopen($arduinoIp, $arduinoPort, $errno, $errstr, 10);
                    if ($socket) {
                        fwrite($socket, $dataToSend);
                        fclose($socket);
                        $this->info('Data sent to Arduino for Lights.');
                    } else {
                        $this->error('Failed to connect to Arduino for Lights.');
                    }
                } catch (\Exception $e) {
                    $this->error('Failed to connect to Arduino for Lights.');
                }
            } else {
                $this->error('Unit with ID 1 not found for Lights Status.');
            }
    
            // // Update unit table when the schedule is active
            // $unit = unit::find(1); // Assuming the target row ID is 1
            // if ($unit) {
            //     //$unit->AC = '1'; // Update AC column to '1' (make sure it's the correct ENUM format)
            //     $unit->Status = '1'; // Update Lights column to '1' (make sure it's the correct ENUM format)
            //     $unit->save();
            //     $this->info('Unit Status for AC columns updated to 1 successfully.');
            // } else {
            //     $this->error('Unit with ID 1 not found for AC Status.');
            // }

            
            // $unit = unit::find(2); // Assuming the target row ID is 1
            // if ($unit) {
            //     //$unit->AC = '1'; // Update AC column to '1' (make sure it's the correct ENUM format)
            //     $unit->Status = '1'; // Update Lights column to '1' (make sure it's the correct ENUM format)
            //     $unit->save();
            //     $this->info('Unit Status for Lights columns updated to 1 successfully.');
            // } else {
            //     $this->error('Unit with ID 1 not found for Lights Status.');
            // }

            }


            ///////////////////////////////////////////
            // Check if the current time matches 'event_datetime_off' in the schedules table and 'status' column is 'Processing' after a 30-second delay
            $dateMatchingSchedules = schedules::where('status', 'Processing')
            ->where('event_datetime_off', '<=', now()->addSeconds(5))
            ->get();
    
            foreach ($dateMatchingSchedules as $schedule) {

                $unit = unit::find(1); // Assuming the target row ID is 1
                if ($unit) {
                    $unit->Status = '0'; // Update Lights column to '1'
                    $unit->save();
                    $this->info('Unit Status for Lights columns updated to 1 successfully.');
                    
                    // Sending Data to Arduino for Lights
                    $arduinoIp = '192.168.1.110'; // Replace with your Arduino's IP address
                    $arduinoPort = 50003; // Replace with your Arduino's port
                    $dataType = 'AC: ';
                    $dataToSend = $dataType . '1'; // Sending 'Lights: 1'
    
                    try {
                        $socket = fsockopen($arduinoIp, $arduinoPort, $errno, $errstr, 10);
                        if ($socket) {
                            fwrite($socket, $dataToSend);
                            fclose($socket);
                            $this->info('Data sent to Arduino for Lights.');
                        } else {
                            $this->error('Failed to connect to Arduino for Lights.');
                        }
                    } catch (\Exception $e) {
                        $this->error('Failed to connect to Arduino for Lights.');
                    }
                } else {
                    $this->error('Unit with ID 1 not found for Lights Status.');
                }


                $unit = unit::find(2); // Assuming the target row ID is 1
                if ($unit) {
                    $unit->Status = '0'; // Update Lights column to '1'
                    $unit->save();
                    $this->info('Unit Status for Lights columns updated to 1 successfully.');
                    
                    // Sending Data to Arduino for Lights
                    $arduinoIp = '192.168.1.110'; // Replace with your Arduino's IP address
                    $arduinoPort = 50003; // Replace with your Arduino's port
                    $dataType = 'Lights: ';
                    $dataToSend = $dataType . '1'; // Sending 'Lights: 1'
    
                    try {
                        $socket = fsockopen($arduinoIp, $arduinoPort, $errno, $errstr, 10);
                        if ($socket) {
                            fwrite($socket, $dataToSend);
                            fclose($socket);
                            $this->info('Data sent to Arduino for Lights.');
                        } else {
                            $this->error('Failed to connect to Arduino for Lights.');
                        }
                    } catch (\Exception $e) {
                        $this->error('Failed to connect to Arduino for Lights.');
                    }
                } else {
                    $this->error('Unit with ID 1 not found for Lights Status.');
                }

            // $unit = unit::find(1); // Assuming the target row ID is 1
            // if ($unit) {
            //     $unit->Status = '0'; // Update AC column to '0' (make sure it's the correct ENUM format)
            //     //$unit->Lights = '0'; // Update Lights column to '0' (make sure it's the correct ENUM format)
            //     $unit->save();
            //     $this->info('Unit table AC and Lights columns updated to 0 successfully.');
            // } else {
            //     $this->error('Unit with ID 1 not found for AC and Lights.');
            // }
            // $unit = unit::find(2);
            // if ($unit) {
            //     $unit->Status = '0'; // Update AC column to '0' (make sure it's the correct ENUM format)
            //     //$unit->Lights = '0'; // Update Lights column to '0' (make sure it's the correct ENUM format)
            //     $unit->save();
            //     $this->info('Unit table AC and Lights columns updated to 0 successfully.');
            // } else {
            //     $this->error('Unit with ID 1 not found for AC and Lights.');
            // }
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
