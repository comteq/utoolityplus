<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\schedules;
use App\Models\activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
 
}

?>