<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\schedulecontroller; 
use App\Http\Controllers\schedulefilter_controler; 
use App\Http\Controllers\dashboardcontroller;
use App\Http\Controllers\NotificationController; 
use App\Http\Controllers\LightsController;
use App\Http\Controllers\DeviceController;



Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
    
});
 
// Routes for authenticated users
Route::group(['middleware' => 'auth'], function () {
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/users', UserController::class)->except(['show']);
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile-password', [UserController::class, 'updatePassword'])->name('password.update');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    Route::get('/schedule', [schedulecontroller::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [schedulecontroller::class, 'store'])->name('store.schedule');

    Route::post('/update-related-schedules', [schedulecontroller::class, 'updateRelatedSchedulesadmin']);
    Route::get('/get-existing-data-user', [schedulecontroller::class, 'getRelatedData1']);
    Route::get('/get-related-data-user', [schedulecontroller::class, 'getRelatedData']);

    Route::get('/check-overlap-user', [schedulecontroller::class, 'checkOverlap']);
    Route::get('/check-existing-schedules-user', [schedulecontroller::class, 'checkExistingSchedules']);

    Route::get('/schedule-List?year=&month=&day=', function () { return view('sched_list'); });
    Route::get('/schedule-List', [schedulefilter_controler::class, 'filter'])->name('schedule.filter');

    Route::get('/room-controls', [dashboardcontroller::class, 'index'])->name('room-controls');

    Route::post('/update-ac/{id}', [DashboardController::class, 'updateAC'])->name('update-ac');
    Route::post('/update-lights/{id}', [DashboardController::class, 'updatelights'])->name('update-lights');

    Route::post('/get-other-related-data-user', [schedulecontroller::class, 'getotherRelatedSchedules']);//default related sched
    Route::post('/validate-date-user', [schedulecontroller::class, 'validateDate']);
    Route::post('/validate-time-user', [schedulecontroller::class, 'validateTime']);
    Route::post('/validate-event-time-user', [schedulecontroller::class, 'validateEventTime']);
    Route::post('/validate-datetime-user', [schedulecontroller::class, 'validateDateTime']);
    Route::post('/validate-dates-user', [schedulecontroller::class, 'validateDates']);//check for schedule that cover multiple days

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/users', UserController::class)->except(['show']);

    Route::get('/schedule-admin', [schedulecontroller::class, 'indexadmin'])->name('scheduleadmin.index');
    Route::post('/schedule-admin', [schedulecontroller::class, 'storeadmin'])->name('storeadmin.schedule');

    Route::get('/check-existing-schedules', [schedulecontroller::class, 'checkExistingSchedules']);
    Route::get('/check-overlap', [schedulecontroller::class, 'checkOverlap']);

    // Route::get('/check-for-action-overlap', [schedulecontroller::class, 'checkForActionOverlap'])->name('checkForActionOverlap');

    Route::get('/get-related-data1', [schedulecontroller::class, 'getRelatedData1'])->name('get.related.data1');
    Route::get('/get-related-data', [schedulecontroller::class, 'getRelatedData'])->name('get.related.data');
    Route::put('/update-schedule/{id}', [schedulecontroller::class, 'updateSchedule']);
    
    Route::post('/update-state1/{itemId}', [schedulecontroller::class, 'updateState1']);
    Route::post('/update-state/{itemId}', [schedulecontroller::class, 'updateState']);
    Route::get('/get-state/{itemId}', [schedulecontroller::class, 'getState']);
    
    Route::delete('/delete-schedule/{itemId}', [schedulecontroller::class, 'deleteSchedule']);
    Route::delete('/forcedelete-schedule/{itemId}', [schedulecontroller::class, 'forcedeleteSchedule']);

    Route::post('/update-related-schedules-admin', [schedulecontroller::class, 'updateRelatedSchedulesadmin']);

    Route::get('/schedule-List-admin?year=&month=&day=', function () { return view('admin.sched_list'); });
    Route::get('/schedule-List-admin', [schedulefilter_controler::class, 'filteradmin'])->name('schedule-admin.filter');
    Route::get('/schedule-list-admin', [schedulefilter_controler::class, 'indexadmin']);
    Route::delete('/schedule/{id}', [schedulefilter_controler::class, 'destroy'])->name('schedule.destroy');
    Route::post('/update-schedules-status', [schedulefilter_controler::class, 'updateSchedulesManually'])->name('update-schedules-status');

    Route::get('/get-pending-schedule-count', [NotificationController::class, 'getPendingSchedules']);
    Route::post('/log-activity', [ActivityLogController::class, 'logActivity']);

    Route::post('/get-other-related-data', [schedulecontroller::class, 'getotherRelatedSchedules']);//default related sched

    // automatic detect
    Route::post('/validate-date', [schedulecontroller::class, 'validateDate']);
    Route::post('/validate-time', [schedulecontroller::class, 'validateTime']);
    Route::post('/modal-validate-datetime', [schedulecontroller::class, 'modalvaliddatetime']);
    Route::get('/get-schedules-count', [schedulecontroller::class, 'getScheduleCount']);
    Route::post('/check-overlapping-schedule', [schedulecontroller::class, 'checkOverlappingSchedule']);
    Route::post('/validate-event-time', [schedulecontroller::class, 'validateEventTime']);
    Route::post('/validate-datetime', [schedulecontroller::class, 'validateDateTime']);

    Route::get('/device', [DeviceController::class, 'index'])->name('device');
    Route::post('/device', [DeviceController::class, 'updateSettings'])->name('update-device-settings');

    Route::post('/validate-dates', [schedulecontroller::class, 'validateDates']);//check for schedule that cover multiple days
});

Route::get('/check-updates', [DashboardController::class, 'checkUpdates'])->name('check-updates');