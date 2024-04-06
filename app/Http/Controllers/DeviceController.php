<?php

namespace App\Http\Controllers;
use App\Models\device;
use App\Models\activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\unit;


class DeviceController extends Controller
{
     public function index()
    {   // Fetch Arduino IP from the database
       
        // Retrieve the device settings from the database (assuming only one record exists)
        $deviceSettings = device::first();
        // Get the Device_Name from the retrieved settings
        $deviceName = $deviceSettings->Device_Name;
        $units = unit::all();

        return view('device', compact('deviceSettings', 'deviceName', 'units'));
    }

    public function updateSettings(Request $request)
    {   


        // Validate the form data
        $validatedData = $request->validate([
            'Pin_Number' => 'required|integer', // Add Pin_Number to the validation rules
            'Device_IP' => 'required|string',
        ]);

        $arduinoIp = device::where('Device_Name', 'Utoolityplus')->value('Device_IP');

        // Check if Arduino's IP address is fetched successfully
        if (!$arduinoIp) {
            return redirect()->back()->with('error', 'Arduino IP address not found in the database')->with('showAlert', true);
        }

        // Code to send "IP Changed" to the Arduino
        $arduinoPort = 50003; // Replace with your Arduino's port
        $dataType = 'IP';

        // Attempt to send data to Arduino
        try {
            $socket = fsockopen($arduinoIp, $arduinoPort, $errno, $errstr, 10);
            if ($socket) {
                fwrite($socket, $dataType);
                fclose($socket);

                // Log the activity or perform any other action
            } else {
                // Handle connection error
                //return redirect()->back()->with('error', 'Failed to connect to Arduino')->with('showAlert', true);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during connection
            //return redirect()->back()->with('error', 'Failed to connect to Arduino')->with('showAlert', true);
        }



    
        // Retrieve the existing device settings from the database (assuming only one record exists)
        $existingSettings = device::first();
    
        // Check if the new values are different from the existing settings
        if (
            $existingSettings->Pin_Number != (int)$validatedData['Pin_Number'] ||
            $existingSettings->Device_IP != $validatedData['Device_IP']
        ) {
            // Update the device settings only if there are changes
            $existingSettings->update([
                'Pin_Number' => $validatedData['Pin_Number'],
                'Device_IP' => $validatedData['Device_IP'],
            ]);
    
            // Log the activity
            activity::create([
                'user_id' => Auth::id(),
                'activity' => 'Update Device Settings',
                'message' => 'Update Device Setting Successful',
                'created_at' => now(),
            ]);
    
            // Redirect back to the device page
            return redirect()->route('device')->with('success', 'Settings updated successfully.');
        }
    
        // If there are no changes, redirect back to the device page without updating
        return redirect()->route('device')->with('info', 'No changes detected.');
    }    

    public function getPinData(Request $request)
    {
        // Get the selected number of pins from the request
        $numPins = $request->input('numPins');

        // Fetch pin data from the unit table based on the selected number of pins
        $pinData = unit::take($numPins)->select('id', 'Pin_Num', 'Pin_Name', 'Status')->get();

        // Return the pin data as JSON response
        return response()->json($pinData);
    }

}