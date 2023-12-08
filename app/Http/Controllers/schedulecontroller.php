<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\schedules; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Events\UpdateSchedulesEvent;
use App\Models\Activity;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule');
    }

    public function store(Request $request)
    {
        // Validation messages
        $customMessages = [
            'event_datetime.required' => 'From: Date & Time: field is required.',
            'event_datetime_off.required' => 'To: Date & Time: field is required.',
            'description.required' => 'Action field is required.',
            'same_day' => 'Cannot make a schedule that covers multiple days.',
        ];

        if ($request->has('custom_schedule')) {
            $validationRules = [
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
            ];

            $request->validate($validationRules, $customMessages);

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

            $existingSchedulesCount = schedules::where('event_datetime', $request->input('event_datetime'))->count();
            if ($existingSchedulesCount >= 2) {
                // Display an error message and set the state to 'Inactive'
                $schedule->state = 'In-Active';
                $schedule->status = 'Pending';
                return redirect()->route('schedule.index')->with('error', 'Cannot insert more than 2 schedule with the same Start Time!');
            }
        
            if ($overlappingSchedule) {
                // Overlapping schedule, set the state to 'Inactive'
                $schedule->state = 'In-Active';
                $schedule->status = 'Pending';
                $schedule->save(); // Save the schedule to the database
                return redirect()->route('schedule.index')->with('success', 'Schedule created successfully but it is overlaping with an active schedule');
            }
            $schedule->save(); // Save the schedule to the database
        }

        else {
            $validationRules = [
                'description' => 'required',
                'yearmonth' => 'required',
                'day' => 'required',
                'fromtime' => 'required',
                'totime' => 'required',
            ];

            $request->validate($validationRules, $customMessages);
            $yearMonth = Carbon::createFromFormat('m-Y', $request->input('yearmonth'));

            // $formattedTime = Carbon::createFromFormat('h:i A', $request->input('fromtime'));
            // $formattedTime2 = Carbon::createFromFormat('h:i A', $request->input('totime'));
            $formattedTime = Carbon::parse($request->input('fromtime'));
            $formattedTime2 = Carbon::parse($request->input('totime'));
            $failedSchedules = [];

            // Create a new Schedule instance for each day in the selected month
            for ($day = 1; $day <= $yearMonth->daysInMonth; $day++) {
                $currentDay = $yearMonth->setDay($day)->format('l'); // Get the day name (e.g., Sunday)
            
                if (strtolower($currentDay) == strtolower($request->day)) {
                    $newEventStart = $yearMonth->setDay($day)
                        ->setTime($formattedTime->hour, $formattedTime->minute, 0) // Set seconds to 0 for precision
                        ->copy(); // Create a copy to avoid referencing the same instance
                    $newEventEnd = $yearMonth->setDay($day)
                        ->setTime($formattedTime2->hour, $formattedTime2->minute, 0) // Set seconds to 0 for precision
                        ->copy();

                    // Check for overlap with existing schedules
                    $overlap = schedules::where(function ($query) use ($newEventStart, $newEventEnd) {
                        $query->where(function ($q) use ($newEventStart, $newEventEnd) {
                            $q->where('event_datetime', '<=', $newEventEnd)
                                ->where('event_datetime_off', '>=', $newEventStart)
                                ->where('state', '=', 'Active');

                        })->orWhere(function ($q) use ($newEventStart, $newEventEnd) {
                            $q->where('event_datetime', '>=', $newEventStart)
                                ->where('event_datetime', '<=', $newEventEnd)
                                ->where('state', '=', 'Active');

                        });
                    })->exists();
            
                    // If there's an overlap, add the schedule to the failedSchedules array
                    if ($overlap) {
                        $failedSchedules[] = [
                            'start' => $newEventStart,
                            'end' => $newEventEnd,
                        ];
                        continue;
                    }
            
                    // If no overlap, save the new schedule
                    $schedule = new schedules();
                    $schedule->event_datetime = $newEventStart;
                    $schedule->event_datetime_off = $newEventEnd; // Use the formattedTime2 for the end time
                    $schedule->description = $request->input('description');
                    $schedule->save();
                }
            }
            
            // If there were failed schedules, pass the error message and the failed schedules to the view
            if (!empty($failedSchedules)) {
                $errorMessage = 'Some schedules overlap with existing events.';
                return view('schedule', compact('errorMessage', 'failedSchedules'));
            }

        }// else end

        return redirect()->route('schedule.index')->with('success', 'Schedule created successfully!');
    }

//-------------------------------------------------------User & ADMIN----------------------------------------------------------------//
    private function getScheduleState($event_datetime, $event_datetime_off)
    {
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
            return 'Active';
        } else {
            // Schedule is in the past
            return 'Active';
        }
    }//user/admin
    
    public function getRelatedData1(Request $request)
    {
        $eventDatetime = $request->input('event_datetime');
        $description = $request->input('description');
    
        $query = schedules::query();
        
        if ($eventDatetime) {
            $query->where('event_datetime', '=', $eventDatetime);
        }

        if ($description) {
            $query->where('description', '=', $description);
        }
    
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 2);

        $offset = ($page - 1) * $perPage;
        $relatedData1 = $query->skip($offset)->take($perPage)->get();

        $relatedData1 = $query->get(['id','event_datetime', 'event_datetime_off', 'description', 'state']);
    
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
            
            $relatedData1->each(function ($item) {
                $item->event_datetime_off_time = date('h:i A', strtotime($item->event_datetime_off));
                $item->event_datetime_off_date = date('Y-m-d', strtotime($item->event_datetime_off));
            });

        return response()->json([
            'relatedData1' => $relatedData1
        ]);
    }//user/admin

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
    }//user/admin

    public function updateRelatedSchedulesadmin(Request $request)
    {
        $description = $request->input('description');
        $yearmonth = $request->input('yearmonth');
        $day = $request->input('day');
        $fromtime = $request->input('fromtime');
        $totime = $request->input('totime');
        $query = schedules::query();
    
        if ($description) {
            $query->where('description', $description);
        }
    
        if ($yearmonth) {
            $parsedDate = Carbon::createFromFormat('m-Y', $yearmonth);
            $query->whereYear('event_datetime', $parsedDate->year)
                ->whereMonth('event_datetime', $parsedDate->month);
        }
    
        if ($day) {
            $query->whereRaw('DAYNAME(event_datetime) = ?', [$day]);
        }
        
        if ($fromtime) {
            $formattedFromtime = Carbon::createFromFormat('H:i', $fromtime)->format('H:i');
            $query->whereRaw("TIME(event_datetime) = ?", [$formattedFromtime]);
        }
            
        if ($totime) {
            $formattedTotime = Carbon::createFromFormat('H:i', $totime)->format('H:i');
            $query->whereRaw("TIME(event_datetime_off) = ?", [$formattedTotime]);
        }
        
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 2);
    
        $offset = ($page - 1) * $perPage;
        $relatedData3 = $query->skip($offset)->take($perPage)->get();
        
        $relatedData3 = $query->get(['id','event_datetime', 'event_datetime_off', 'description', 'state']);
        

        $relatedData3->each(function ($item) {
            $item->event_datetime_time = date('h:i A', strtotime($item->event_datetime));
            $item->event_datetime_date = date('Y-m-d', strtotime($item->event_datetime));
        });
        
        $relatedData3->each(function ($item) {
            $item->event_datetime_off_time = date('h:i A', strtotime($item->event_datetime_off));
            $item->event_datetime_off_date = date('Y-m-d', strtotime($item->event_datetime_off));
        });

        
        return response()->json(['relatedData3' => $relatedData3]);
    }//user/admin

    public function checkOverlap(Request $request)
    {
        $fromDateTime = $request->input('fromDateTime');
        $toDateTime = $request->input('toDateTime');
    
        // Check for overlapping schedules
        $overlappingSchedule = schedules::where(function ($query) use ($fromDateTime, $toDateTime) {
            $query->where('state', 'Active')
                ->where(function ($query) use ($fromDateTime, $toDateTime) {
                    $query->whereBetween('event_datetime', [$fromDateTime, $toDateTime])
                        ->orWhereBetween('event_datetime_off', [$fromDateTime, $toDateTime])
                        ->orWhere(function ($query) use ($fromDateTime, $toDateTime) {
                            $query->where('event_datetime', '<', $fromDateTime)
                                ->where('event_datetime_off', '>', $toDateTime);
                        });
                });
        })->exists();
    
        return response()->json(['overlap' => $overlappingSchedule]);
    }//automatic detection for to: date and time and from: date and time

    public function checkForActionOverlap(Request $request)
    {
        $fromDateTime = $request->input('fromDateTime');
        $toDateTime = $request->input('toDateTime');
        $description = $request->input('description');

        // Check for overlapping schedules based on description
        // Adjust the logic accordingly based on your requirements
        $overlappingSchedule = schedules::where(function ($query) use ($fromDateTime, $toDateTime, $description) {
            $query->where(function ($q) use ($fromDateTime, $toDateTime, $description) {
                $q->where('event_datetime', '<=', $toDateTime)
                    ->where('event_datetime_off', '>=', $fromDateTime)
                    ->where('state', '=', 'Active')
                    ->where('description', '=', $description);
            })->orWhere(function ($q) use ($fromDateTime, $toDateTime, $description) {
                $q->where('event_datetime', '>=', $fromDateTime)
                    ->where('event_datetime', '<=', $toDateTime)
                    ->where('state', '=', 'Active')
                    ->where('description', '=', $description);
            });
        })->exists();

        return response()->json(['overlap' => $overlappingSchedule]);
    }//automatic detection for action overlap

    public function checkExistingSchedules(Request $request)
    {
        $eventDateTime = $request->input('event_datetime');
        $existingSchedulesCount = schedules::where('event_datetime', $eventDateTime)->count();
    
        if ($existingSchedulesCount >= 2) {
            return response()->json(['error' => 'Cannot insert more than 2 schedules with the same Start Time']);
        }
    
        return response()->json(['success' => true]);
    }//automatic detection for from: date and time

//-------------------------------------------------------ADMIN----------------------------------------------------------------//

    public function indexadmin()
    {
        return view('admin.schedule');
    }

    public function storeadmin(Request $request)
    {
        // Validation messages
        $customMessages = [
            'event_datetime.required' => 'From: Date & Time: field is required.',
            'event_datetime_off.required' => 'To: Date & Time: field is required.',
            'description.required' => 'Action field is required.',
            'same_day' => 'Cannot make a schedule that covers multiple days.',
        ];

        if ($request->has('custom_schedule')) {
        $validationRules = [
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
            ];

            $request->validate($validationRules, $customMessages);

            $schedule = new schedules();
            $schedule->event_datetime = $request->input('event_datetime');
            $schedule->event_datetime_off = $request->input('event_datetime_off');
            $schedule->description = $request->input('description');

            // Set the schedule state based on the date and time
            $schedule->state = $this->getScheduleState($request->input('event_datetime'), $request->input('event_datetime_off'));

            $existingSchedulesCount = schedules::where('event_datetime', $request->input('event_datetime'))->count();
            if ($existingSchedulesCount >= 2) {
                // Display an error message and redirect back
                return redirect()->route('scheduleadmin.index')->with('error', 'Cannot insert more than 2 schedules with the same Start Time!');
            }

            $overlappingSchedule = schedules::where(function ($query) use ($request) {// Check if the new schedule overlaps with existing schedules
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
                return redirect()->route('scheduleadmin.index')->with('error', 'Schedule overlaps with an active schedule!');
            }
            $schedule->save(); // Save the schedule to the database

        } else {
            $validationRules = [
                'description' => 'required',
                'yearmonth' => 'required',
                'day' => 'required',
                'fromtime' => 'required',
                'totime' => 'required',
            ];

            $request->validate($validationRules, $customMessages);
            $yearMonth = Carbon::createFromFormat('m-Y', $request->input('yearmonth'));

            $formattedTime = Carbon::parse($request->input('fromtime'));
            $formattedTime2 = Carbon::parse($request->input('totime'));
            $failedSchedules = [];

            // Create a new Schedule instance for each day in the selected month
            for ($day = 1; $day <= $yearMonth->daysInMonth; $day++) {
                $currentDay = $yearMonth->setDay($day)->format('l'); // Get the day name (e.g., Sunday)
            
                if (strtolower($currentDay) == strtolower($request->day)) {
                    $newEventStart = $yearMonth->setDay($day)
                        ->setTime($formattedTime->hour, $formattedTime->minute, 0) // Set seconds to 0 for precision
                        ->copy(); // Create a copy to avoid referencing the same instance
                    $newEventEnd = $yearMonth->setDay($day)
                        ->setTime($formattedTime2->hour, $formattedTime2->minute, 0) // Set seconds to 0 for precision
                        ->copy();

                    // Check for overlap with existing schedules
                    $overlap = schedules::where(function ($query) use ($newEventStart, $newEventEnd) {
                        $query->where(function ($q) use ($newEventStart, $newEventEnd) {
                            $q->where('event_datetime', '<=', $newEventEnd)
                                ->where('event_datetime_off', '>=', $newEventStart)
                                ->where('state', '=', 'Active');

                        })->orWhere(function ($q) use ($newEventStart, $newEventEnd) {
                            $q->where('event_datetime', '>=', $newEventStart)
                                ->where('event_datetime', '<=', $newEventEnd)
                                ->where('state', '=', 'Active');

                        });
                    })->exists();
            
                    // If there's an overlap, add the schedule to the failedSchedules array
                    if ($overlap) {
                        $failedSchedules[] = [
                            'start' => $newEventStart,
                            'end' => $newEventEnd,
                        ];
                        continue;
                    }
            
                    // If no overlap, save the new schedule
                    $schedule = new schedules();
                    $schedule->event_datetime = $newEventStart;
                    $schedule->event_datetime_off = $newEventEnd; // Use the formattedTime2 for the end time
                    $schedule->description = $request->input('description');
                    $schedule->save();
                }
            }
            
            // If there were failed schedules, pass the error message and the failed schedules to the view
            if (!empty($failedSchedules)) {
                $errorMessage = 'Some schedules overlap with existing events.';
                return view('schedule', compact('errorMessage', 'failedSchedules'));
            }

        }// else end
        return redirect()->route('scheduleadmin.index')->with('success', 'Schedule created successfully!');
    }

    public function updateState($itemId)
    {
        if (!$itemId) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }
    
        $item = schedules::find($itemId);
    
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        $newState = $item->state === 'Active' ? 'In-Active' : 'Active'; // Toggle the state

        // Log the activity
        Activity::create([
            'user_id' => auth()->id(),
            'activity' => 'Update Schedule State',
            'message' => 'User updated the state of schedule at ' . $item->event_datetime . ' from ' . $item->state . ' to ' . $newState,
            'created_at' => now(),
    ]);
    
        $item->state = $item->state === 'Active' ? 'In-Active' : 'Active'; // Toggle the state
        $item->save();
    
        schedules::where('event_datetime', '=', $item->event_datetime)
            ->where('id', '!=', $item->id)
            ->update(['state' => 'In-Active']);
    
        $updatedSchedules = schedules::where('event_datetime', '=', $item->event_datetime)->get();
        return response()->json(['success' => true, 'schedules' => $updatedSchedules]);
    }
    
    public function deleteSchedule($itemId)
    {
        $schedule = schedules::find($itemId);
        if ($schedule) {
            $eventTimeFrom = $schedule->event_datetime; // Replace 'from' with your actual attribute name
            $eventTimeTo = $schedule->event_datetime_off;     // Replace 'to' with your actual attribute name
    
            $schedule->delete();
    
            Activity::create([
                'user_id' => auth()->id(),
                'activity' => 'Delete Schedule',
                'message' => 'User deleted a schedule from ' . $eventTimeFrom . ' to ' . $eventTimeTo . '.',
                'created_at' => now(),
            ]);

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
            'description' => 'required|in:ON,OFF',
        ]);

        $schedule = schedules::findOrFail($id);

        // Get the original event times before the update
        $oldEventDatetime = $schedule->event_datetime;
        $oldEventDatetimeOff = $schedule->event_datetime_off;

        $schedule->update([
            'event_datetime' => $request->input('event_datetime'),
            'event_datetime_off' => $request->input('event_datetime_off'),
            'description' => $request->input('description'),
        ]);

        // Get the updated event times after the update
        $newEventDatetime = $schedule->event_datetime;
        $newEventDatetimeOff = $schedule->event_datetime_off;

        // Log the activity
        Activity::create([
            'user_id' => auth()->id(),
            'activity' => 'Update Schedule',
            'message' => 'User updated schedule eventime ' . $oldEventDatetime . ' to ' . $newEventDatetime . ' and ' . $oldEventDatetimeOff . ' to ' . $newEventDatetimeOff,
            'created_at' => now(),
    ]);
        $updatedSchedule = schedules::findOrFail($id);
        return response()->json([
            'message' => 'Schedule updated successfully',
            'updatedSchedule' => $updatedSchedule,
        ]);
    }

}//controller end
