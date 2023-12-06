<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\schedules; 

class NotificationController extends Controller
{
    public function getPendingSchedules()
    {
        $count = schedules::where('status', 'Pending')->count();
        $schedules = schedules::where('status', 'Pending')->get();

        return response()->json([
            'count' => $count,
            'schedules' => $schedules,
        ]);
    }
}
