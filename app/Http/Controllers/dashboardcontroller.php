<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\unit;
use App\Models\activity;
use App\Models\device;

class dashboardcontroller extends Controller
{
    public function index()
    {
        $deviceSettings = device::first();

        return view('roomControls', compact('deviceSettings'));

    }

    public function checkUpdates()
    {
        $lastUpdatedAt = cache()->get('last_updated_at', null); // Get the last recorded updated_at timestamp
    
        $latestUpdatedAt = unit::max('updated_at'); // Get the latest updated_at timestamp from the database
    
        if ($latestUpdatedAt != $lastUpdatedAt) {
            cache()->put('last_updated_at', $latestUpdatedAt); // Update the recorded timestamp
            return response()->json(['hasUpdates' => true]);
        }
    
        return response()->json(['hasUpdates' => false]);
    }




    public function updateAC(Request $request, $id)
    {
        ///////////////////////////////////////////////////////////////
        // Sending Data to Arduino

        // Retrieve the value to be sent to Arduino (1 or 0) from the form
        $acValue = $request->input('acValue');

        // Code to send $acValue to the Arduino
        $arduinoIp = '192.168.1.110'; // Replace with your Arduino's IP address
        $arduinoPort = 50003; // Replace with your Arduino's port
        $dataType = ''; // Variable to identify the type of data being sent

        if ($request->has('acValue')) {
            $acValue = $request->input('acValue');
            $dataType = 'AC: ';
            $dataToSend = $dataType . ' ' . $acValue;
        } 

        try {
            $socket = fsockopen($arduinoIp, $arduinoPort, $errno, $errstr, 10);
            if ($socket) {
                fwrite($socket, $dataToSend);
                fclose($socket);

                $unit = Unit::find($id);

                if (!$unit) {
                    return response()->json(['error' => 'Unit not found'], 404);
                }
        
                // Get the current AC state before the update
                $oldState = $unit->Status;/////////////
        
                // Toggle the 'AC' state
                $newState = $unit->Status === '1' ? '0' : '1';
                $unit->update(['Status' => $newState]);
        
                // Log the activity with updated message based on AC state
                $logMessage = 'User turned ' . ($newState === '1' ? 'ON' : 'OFF') . ' the Air Condition Unit';
        
                activity::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Power ' . ($newState === '1' ? 'ON' : 'OFF') . ' ACU',
                    'message' => $logMessage,
                    'created_at' => now(),
                ]);
        
                // Successful data send
            } else {
                // Handle connection error
                return redirect()->back()->with('error', 'Failed to connect to Arduino')->with('showAlert', true);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during connection
            return redirect()->back()->with('error', 'Failed to connect to Arduino')->with('showAlert', true);

        }

        return redirect()->route('room-controls')->with('success', 'ACU updated successfully');
    }

    
    
    
    public function updateLights(Request $request, $id)
    {
        ///////////////////////////////////////////////////////////////
        // Sending Data to Arduino

        // Retrieve the value to be sent to Arduino (1 or 0) from the form
        $lightsValue = $request->input('lightsValue');

        // Code to send $lightsValue to the Arduino
        $arduinoIp = '192.168.1.110'; // Replace with your Arduino's IP address
        $arduinoPort = 50003; // Replace with your Arduino's port

        if ($request->has('lightsValue')) {
            $lightsValue = $request->input('lightsValue');
            $dataType = 'Lights: ';
            $dataToSend = $dataType . ' ' . $lightsValue;
        }

        try {
            $socket = fsockopen($arduinoIp, $arduinoPort, $errno, $errstr, 10);
            if ($socket) {
                fwrite($socket, $dataToSend);
                fclose($socket);

                $unit = unit::find($id);

                if (!$unit) {
                    return response()->json(['error' => 'Unit not found'], 404);
                }

                // Get the current Lights state before the update
                $oldState = $unit->Status;

                // Toggle the 'Status' state
                $newState = $unit->Status === '1' ? '0' : '1';
                $unit->update(['Status' => $newState]);

                // Log the activity with updated message based on Lights state
                $logMessage = 'User turned ' . ($newState === '1' ? 'ON' : 'OFF') . ' the Lights';

                activity::create([
                    'user_id' => auth()->id(),
                    'activity' => 'Power ' . ($newState === '1' ? 'ON' : 'OFF') . ' Lights',
                    'message' => $logMessage,
                    'created_at' => now(),
                ]);

                // Successful data send
            } else {
                // Handle connection error
                return redirect()->back()->with('error', 'Failed to connect to Arduino')->with('showAlert', true);
            }


        } catch (\Exception $e) {
            // Handle any exceptions that occur during connection
            return redirect()->back()->with('error', 'Failed to connect to Arduino')->with('showAlert', true);
        }

        return redirect()->route('room-controls')->with('success', 'Lights updated successfully');
    }





    
}