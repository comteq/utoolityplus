<?php

namespace App\Http\Controllers;
use App\Models\device;
use App\Models\activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function index()
    {
        // Retrieve the device settings from the database (assuming only one record exists)
        $deviceSettings = Device::first();

        return view('device', compact('deviceSettings'));
    }

    public function updateSettings(Request $request)
{
    // Validate the form data
    $validatedData = $request->validate([
        'acNumPins' => 'required|integer|min:1|max:8',
        'lightsNumPins' => 'required|integer|min:1|max:8',
        'Device_IP' => 'required|string',
    ]);

    // Retrieve the existing device settings from the database (assuming only one record exists)
    $existingSettings = Device::first();

    // Check if the new values are different from the existing settings
    if (
        $existingSettings->acNumPins != (int)$validatedData['acNumPins'] ||
        $existingSettings->lightsNumPins != (int)$validatedData['lightsNumPins'] ||
        $existingSettings->Device_IP != $validatedData['Device_IP']
    ) {
        // Update the device settings only if there are changes
        $existingSettings->update([
            'acNumPins' => $validatedData['acNumPins'],
            'lightsNumPins' => $validatedData['lightsNumPins'],
            'Device_IP' => $validatedData['Device_IP'],
        ]);

        // Log the activity
        Activity::create([
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

}