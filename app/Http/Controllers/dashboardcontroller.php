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

        // Log the activity
        activity::create([
        'user_id' => auth()->id(),
        'activity' => 'Update AC State',
        'message' => 'User updated the AC state of unit from ' . $oldState . ' to ' . $newState,
        'created_at' => now(),
    ]);
    
        return redirect()->route('dashboard')->with('success', 'AC updated successfully');
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

        // Log the activity
        Activity::create([
        'user_id' => auth()->id(),
        'activity' => 'Update Lights State',
        'message' => 'User updated the Lights state of unit from ' . $oldState . ' to ' . $newState,
        'created_at' => now(),
    ]);
    
        return redirect()->route('dashboard')->with('success', 'Lights updated successfully');
    }
    
}
