<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\unit;

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
    
        // Toggle the 'AC' state
        $newState = $unit->AC === '1' ? '0' : '1';
        $unit->update(['AC' => $newState]);
    
        return redirect()->route('dashboard')->with('success', 'AC updated successfully');
    }
    
    public function updatelights(Request $request, $id)
    {
        $unit = Unit::find($id);
    
        if (!$unit) {
            return response()->json(['error' => 'Unit not found'], 404);
        }
    
        // Toggle the 'Lights' state
        $newState = $unit->Lights === '1' ? '0' : '1';
        $unit->update(['Lights' => $newState]);
    
        return redirect()->route('dashboard')->with('success', 'Lights updated successfully');
    }
    
}
