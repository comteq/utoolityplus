<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\schedules; 
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule');
    }

    public function store(Request $request)
    {
        $customMessages = [
            'event_datetime.required' => 'From: Date & Time: field is required.',
            'event_datetime_off.required' => 'To: Date & Time: field is required.',
            'description.required' => 'Action field is required.',
            'same_day' => 'Cannot make a schedule that covers multiple days.',
        ];
    
        // Validate the form data
        $request->validate([
            'event_datetime' => 'required|date',
            'event_datetime_off' => [
                'required',
                'date',
                'after:event_datetime',
                function ($attribute, $value, $fail) use ($request) {
                    // Custom validation rule to check if event_datetime_off is on the same day as event_datetime
                    $startDateTime = new \DateTime($request->input('event_datetime'));
                    $endDateTime = new \DateTime($value);
    
                    if ($startDateTime->format('Y-m-d') !== $endDateTime->format('Y-m-d')) {
                        $fail('Cannot make a schedule that covers multiple days.');
                    }
                },
            ],
            'description' => 'required|string',
        ], $customMessages);
    
        // Create a new Schedule instance
        $schedule = new schedules();
        $schedule->event_datetime = $request->input('event_datetime');
        $schedule->event_datetime_off = $request->input('event_datetime_off');
        $schedule->description = $request->input('description');
    
        // Set the schedule state based on the date and time
        $schedule->state = $this->getScheduleState($request->input('event_datetime'), $request->input('event_datetime_off'));
    
        // Check if the new schedule overlaps with existing schedules
        $overlappingSchedule = schedules::where(function ($query) use ($request) {
            $query->where('state', 'Active')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('event_datetime', [$request->input('event_datetime'), $request->input('event_datetime_off')])
                        ->orWhereBetween('event_datetime_off', [$request->input('event_datetime'), $request->input('event_datetime_off')])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('event_datetime', '<', $request->input('event_datetime'))
                                ->where('event_datetime_off', '>', $request->input('event_datetime_off'));
                        });
                });
        })->first();
    
        if ($overlappingSchedule) {
            // Overlapping schedule, display an error message and redirect back
            return redirect()->route('schedule.index')->with('error', 'Schedule overlaps with an active schedule!');
        }
    
        // Save the schedule to the database
        $schedule->save();
        // Redirect back to the form with a success message
        return redirect()->route('schedule.index')->with('success', 'Schedule set successfully!');
    }
    
    
    private function getScheduleState($event_datetime, $event_datetime_off)
    {
        // Convert the input date and time strings to Carbon instances
        $startDateTime = Carbon::parse($event_datetime);
        $endDateTime = Carbon::parse($event_datetime_off);
    
        // Get the current date and time
        $now = Carbon::now();
    
        // Check if the current date and time are within the schedule's start and end date and time
        if ($now->isBefore($startDateTime)) {
            // Schedule is in the future
            return 'Active';
        } elseif ($now->isBetween($startDateTime, $endDateTime)) {
            // Schedule is ongoing
            return 'In-Active';
        } else {
            // Schedule is in the past
            return 'Active';
        }
    }
    

    public function getRelatedData1(Request $request)
    {
        // Retrieve specific columns from related data
        $eventDatetime = $request->input('event_datetime');
        $relatedData1 = schedules::whereDate('event_datetime', '=', \Carbon\Carbon::parse($eventDatetime)->toDateString())
            ->where('event_datetime', '=', $eventDatetime)
            ->get(['id','event_datetime', 'event_datetime_off', 'description', 'state']);

        $activeSchedules = $relatedData1->where('state', 'Active');

        if ($activeSchedules->count() > 0) {
            $relatedData1->whereNotIn('id', $activeSchedules->pluck('id')->toArray())->each(function ($item) {
                $item->state = 'In-Active';
            });
        }

            $relatedData1->each(function ($item) {
                $item->event_datetime_time = date('h:i A', strtotime($item->event_datetime));
                $item->event_datetime_date = date('Y-m-d', strtotime($item->event_datetime));
            });
        
            // Format time for event_datetime_off
            $relatedData1->each(function ($item) {
                $item->event_datetime_off_time = date('h:i A', strtotime($item->event_datetime_off));
                $item->event_datetime_off_date = date('Y-m-d', strtotime($item->event_datetime_off));
            });

        return response()->json([
            'relatedData1' => $relatedData1
        ]);
    }

    public function getRelatedData(Request $request)
    {
        // Retrieve specific columns from related data
        $eventDatetime = $request->input('event_datetime');
        $relatedData = schedules::whereDate('event_datetime', '=', \Carbon\Carbon::parse($eventDatetime)->toDateString())
            ->where('event_datetime', '!=', $eventDatetime)
            ->get(['id','event_datetime', 'event_datetime_off', 'description', 'state']);

            $relatedData->each(function ($item) {
                $item->event_datetime_time = date('h:i A', strtotime($item->event_datetime));
                $item->event_datetime_date = date('Y-m-d', strtotime($item->event_datetime));
            });
        
            // Format time for event_datetime_off
            $relatedData->each(function ($item) {
                $item->event_datetime_off_time = date('h:i A', strtotime($item->event_datetime_off));
                $item->event_datetime_off_date = date('Y-m-d', strtotime($item->event_datetime_off));
            });

        return response()->json([
            'relatedData' => $relatedData
        ]);
    }

    public function updateState($itemId)
    {
        // $itemId is now the parameter from the URL
    
        if (!$itemId) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }
    
        $item = schedules::find($itemId);
    
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }
        schedules::where('event_datetime', '=', $item->event_datetime)
        ->where('id', '!=', $item->id)
        ->update(['state' => 'In-Active']);
        
        $item->state = $item->state === 'Active' ? 'In-Active' : 'Active'; // Toggle the state
        $item->save();
    
        return response()->json(['success' => true, 'message' => 'State updated successfully']);
    }
    
    public function deleteSchedule($itemId)
    {
        $schedule = schedules::find($itemId);
        if ($schedule) {
            $schedule->delete();
            return response()->json(['message' => 'Schedule deleted successfully']);
        } else {
            return response()->json(['error' => 'Schedule not found'], 404);
        }
    }

    public function updateSchedule(Request $request, $id)
    {
        // Validate the request data as needed
        $request->validate([
            'event_datetime' => 'required',
            'event_datetime_off' => 'required',
            'state' => 'required|in:Active,In-Active',
            'description' => 'required|in:ON,OFF',
        ]);

        // Find the schedule item by ID
        $schedule = schedules::findOrFail($id);

        $schedule->update([
            'event_datetime' => $request->input('event_datetime'),
            'event_datetime_off' => $request->input('event_datetime_off'),
            'state' => $request->input('state'),
            'description' => $request->input('description'),
        ]);

        // You can return a response if needed
        return response()->json(['message' => 'Schedule updated successfully']);
    }

}
