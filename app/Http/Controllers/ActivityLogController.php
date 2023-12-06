<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class ActivityLogController extends Controller
{
    public function index()
    {
        $activityLogs = activity::all(); // Fetch all activity logs
        return view('activity-logs.index', compact('activityLogs'));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function logActivity(Request $request)
    {
        $action = $request->input('action');

        // Log the activity
        Activity::create([
            'user_id' => auth()->id(),
            'activity' => $action,
            'message' => 'User performed the ' . $action . ' action.',
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Activity logged successfully']);
    }
}
