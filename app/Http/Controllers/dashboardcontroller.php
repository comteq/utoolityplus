<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\unit;
use App\Models\activity;

class dashboardcontroller extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function checkUpdates()
    {
        $lastUpdatedAt = cache()->get('last_updated_at', null); // Get the last recorded updated_at timestamp

        $latestUpdatedAt = Unit::max('updated_at'); // Get the latest updated_at timestamp from the database

        if ($latestUpdatedAt != $lastUpdatedAt) {
            cache()->put('last_updated_at', $latestUpdatedAt); // Update the recorded timestamp
            return response()->json(['hasUpdates' => true]);
        }

        return response()->json(['hasUpdates' => false]);
    }

    public function updateAC(Request $request, $id)
    {
        $unit = Unit::find($id);
    
        if (!$unit) {
            return response()->json(['error' => 'Unit not found'], 404);
        }
    
        // Get the current AC state before the update
        $oldState = $unit->AC;
    
        // Toggle the 'AC' state
        $newState = $unit->AC === '1' ? '0' : '1';
        $unit->update(['AC' => $newState]);
    
        // Log the activity with updated message based on AC state
        $logMessage = 'User turned ' . ($newState === '1' ? 'ON' : 'OFF') . ' the Air Condition Unit ';
    
        activity::create([
            'user_id' => auth()->id(),
            'activity' => 'Power ' . ($newState === '1' ? 'ON' : 'OFF') . ' ACU',
            'message' => $logMessage,
            'created_at' => now(),
        ]);
    
        return redirect()->route('dashboard')->with('success', 'ACU updated successfully');
    }
    
    
    public function updatelights(Request $request, $id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json(['error' => 'Unit not found'], 404);
        }

        // Get the current Lights state before the update
        $oldState = $unit->Lights;

        // Toggle the 'Lights' state
        $newState = $unit->Lights === '1' ? '0' : '1';
        $unit->update(['Lights' => $newState]);

        // Log the activity with updated message based on Lights state
        $logMessage = 'User turned ' . ($newState === '1' ? 'ON' : 'OFF') . ' the Lights';

        Activity::create([
            'user_id' => auth()->id(),
            'activity' => 'Power ' . ($newState === '1' ? 'ON' : 'OFF') . ' Lights',
            'message' => $logMessage,
            'created_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Lights updated successfully');
    }

    
}