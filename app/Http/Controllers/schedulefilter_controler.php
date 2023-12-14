<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\schedules;
use App\Models\activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class schedulefilter_controler extends Controller
{
    public function index(){
        return view('sched_list');
    }
    
    public function filter(Request $request)
    {
        $uniqueYears = schedules::distinct()
            ->orderBy('event_datetime')
            ->pluck(DB::raw('YEAR(event_datetime) as year'))
            ->toArray();

        $status = $request->input('status');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $week = $request->input('week');
        $hour = $request->input('hour'); 
        $description = $request->input('description');
        $direction = $request->input('direction'); 
        $filterWeek = $request->has('week');
        $state = $request->input('state');
        $dayOfWeek = $request->input('dayOfWeek');

        $query = schedules::query();
        if ($year) {
            $query->whereYear('event_datetime', $year);
        }
    
        if ($month) {
            $query->whereMonth('event_datetime', $month);
        }

        if ($day) {
            $query->whereDay('event_datetime', $day);
        }

        if ($dayOfWeek) {
            $adjustedDayOfWeek = ($dayOfWeek == 7) ? 1 : ($dayOfWeek + 1);
            $query->whereRaw('DAYOFWEEK(event_datetime) = ?', [$adjustedDayOfWeek]);
        }

        if ($hour) {
            $formattedHour = Carbon::createFromFormat('g A', $hour)->format('H');
            $query->whereRaw("HOUR(event_datetime) = ?", [$formattedHour]);
        }

        if ($description && in_array(strtoupper($description), ['ON', 'OFF'])) {
            $query->where('description', strtoupper($description));
        }

        if ($state && in_array($state, ['Active', 'In-Active'])) {
            $query->where('state', $state);
        }

        if ($status && in_array($status, ['In-Progress','Pending','Processing' ,'Finished'])) {
            $query->where('status', $status);
        }
        
        if ($week) {
            $selectedYear = $request->input('year', now()->year);
            $selectedMonth = $request->input('month', now()->month);
        
            $firstDayOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $totalWeeksInMonth = $firstDayOfMonth->copy()->endOfMonth()->weekOfMonth;
        
            $selectedWeek = $request->input('week', Carbon::now()->weekOfMonth);
        
            $startDate = $firstDayOfMonth->copy()->startOfWeek()->addWeeks($selectedWeek - 1)->format('Y-m-d');
            $endDate = $firstDayOfMonth->copy()->startOfWeek()->addWeeks($selectedWeek)->format('Y-m-d');
        
            $query->whereBetween('event_datetime', [$startDate, $endDate]);
        }
        
        
        $query->orderBy('event_datetime');
        $uniqueMonths = $query->distinct()
            ->orderBy('event_datetime')
            ->pluck(DB::raw('MONTH(event_datetime) as month'))
            ->toArray();
    
        $uniqueDays = $query->distinct()
            ->orderBy('event_datetime')
            ->pluck(DB::raw('DAY(event_datetime) as day'))
            ->toArray();

        $scheduless = $query->get();

        $currentYear = Carbon::now()->format('Y');

        $currentMonth = !$month ? Carbon::now()->format('m') : $month;

        $currentWeek = $week ?: Carbon::now()->weekOfMonth;

        $currentDay = Carbon::now()->format('d');

        if ($direction === 'next') {
            $query->where('event_datetime', '>', Carbon::now());
        } elseif ($direction === 'previous') {
            $query->where('event_datetime', '<', Carbon::now());
        }
        
        $dateString = ($year ? $year . '/' : '--/') . ($month ? $month . '/' : '--/') . ($day ? $day . '' : '--');
        $filterText = $dateString ? $dateString : '--/--/--';

        return view('sched_list', compact('scheduless','uniqueYears', 'uniqueMonths', 'uniqueDays', 'filterText', 'currentYear','currentMonth', 'currentWeek', 'currentDay'));
    } 
 
    public function indexadmin(){
        $this->updateSchedulesManually();
        return view('admin.sched_list');
    }

    public function filteradmin(Request $request)
    {
        $uniqueYears = schedules::distinct()
            ->orderBy('event_datetime')
            ->pluck(DB::raw('YEAR(event_datetime) as year'))
            ->toArray();
    
        $status = $request->input('status');
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $week = $request->input('week');
        $hour = $request->input('hour'); 
        $description = $request->input('description');
        $direction = $request->input('direction'); 
        $filterWeek = $request->has('week');
        $state = $request->input('state');
        $dayOfWeek = $request->input('dayOfWeek');
        

        $query = schedules::query();
        if ($year) {
            $query->whereYear('event_datetime', $year);
        }
    
        if ($month) {
            $query->whereMonth('event_datetime', $month);
        }

        if ($day) {
            $query->whereDay('event_datetime', $day);
        }

        if ($dayOfWeek) {
            $adjustedDayOfWeek = ($dayOfWeek == 7) ? 1 : ($dayOfWeek + 1);
            $query->whereRaw('DAYOFWEEK(event_datetime) = ?', [$adjustedDayOfWeek]);
        }

        if ($hour) {
            $formattedHour = Carbon::createFromFormat('g A', $hour)->format('H');
            $query->whereRaw("HOUR(event_datetime) = ?", [$formattedHour]);
        }

        if ($description && in_array(strtoupper($description), ['ON', 'OFF'])) {
            $query->where('description', strtoupper($description));
        }

        if ($state && in_array($state, ['Active', 'In-Active'])) {
            $query->where('state', $state);
        }

        if ($status && in_array($status, ['In-Progress','Pending','Processing' ,'Finished'])) {
            $query->where('status', $status);
        }
        
        if ($week) {
            $selectedYear = $request->input('year', now()->year);
            $selectedMonth = $request->input('month', now()->month);
        
            $firstDayOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $totalWeeksInMonth = $firstDayOfMonth->copy()->endOfMonth()->weekOfMonth;
        
            $selectedWeek = $request->input('week', Carbon::now()->weekOfMonth);
        
            $startDate = $firstDayOfMonth->copy()->startOfWeek()->addWeeks($selectedWeek - 1)->format('Y-m-d');
            $endDate = $firstDayOfMonth->copy()->startOfWeek()->addWeeks($selectedWeek)->format('Y-m-d');
        
            $query->whereBetween('event_datetime', [$startDate, $endDate]);
        }
        
        
        $query->orderBy('event_datetime');
        $uniqueMonths = $query->distinct()
            ->orderBy('event_datetime')
            ->pluck(DB::raw('MONTH(event_datetime) as month'))
            ->toArray();
    
        $uniqueDays = $query->distinct()
            ->orderBy('event_datetime')
            ->pluck(DB::raw('DAY(event_datetime) as day'))
            ->toArray();
    
        $scheduless = $query->get();
    
        $currentYear = Carbon::now()->format('Y');

        $currentMonth = !$month ? Carbon::now()->format('m') : $month;

        $currentWeek = $week ?: Carbon::now()->weekOfMonth;

        $currentDay = Carbon::now()->format('d');

        if ($direction === 'next') {
            $query->where('event_datetime', '>', Carbon::now());
        } elseif ($direction === 'previous') {
            $query->where('event_datetime', '<', Carbon::now());
        }
        
        $dateString = ($year ? $year . '/' : '--/') . ($month ? $month . '/' : '--/') . ($day ? $day . '' : '--');
        $filterText = $dateString ? $dateString : '--/--/--';

        return view('admin.sched_list', compact('scheduless','uniqueYears', 'uniqueMonths', 'uniqueDays', 'filterText', 'currentYear','currentMonth', 'currentWeek', 'currentDay'));
    
    } 

    public function destroy($id)
    {
        // Find the schedule by ID
        $schedule = schedules::findOrFail($id);

        $eventTimeFrom = $schedule->event_datetime;
        $eventTimeTo = $schedule->event_datetime_off; 

        // Log the activity before deletion
        Activity::create([
            'user_id' => auth()->id(),
            'activity' => 'Delete Schedule',
            'message' => 'User deleted a schedule with event time ' . $eventTimeFrom . ' to ' . $eventTimeTo, 
            'created_at' => now(),
        ]);

        // Delete the schedule
        $schedule->delete();

        // Return a response if needed
        return response()->json(['message' => 'Schedule deleted successfully']);
    }

    public function updateSchedulesManually()
    {
        // Get all schedules where the status is not 'Finished' and the event end time has passed
        $schedulesToUpdate = schedules::where('status', '!=', 'Finished')
            ->where('event_datetime_off', '<', now())  
            ->get();
    
        // Update the status for each schedule
        foreach ($schedulesToUpdate as $schedule) {
            $schedule->update(['status' => 'Finished']);
        }
        return response()->json($schedulesToUpdate);
    }
    
    

}

?>