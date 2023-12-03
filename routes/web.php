<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\schedulecontroller; 
use App\Http\Controllers\schedulefilter_controler; 

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});
 
// Routes for authenticated users
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home'); // Rename to 'home' route
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/users', UserController::class)->except(['show']);
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    Route::get('/schedule', [schedulecontroller::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [schedulecontroller::class, 'store'])->name('store.schedule');
    Route::post('/update-related-schedules', [schedulecontroller::class, 'updateRelatedSchedulesadmin']);
    Route::get('/get-related-data1', [schedulecontroller::class, 'getRelatedData1'])->name('get.related.data1');
    Route::get('/get-related-data', [schedulecontroller::class, 'getRelatedData'])->name('get.related.data');

    Route::get('/schedule-List?year=&month=&day=', function () { return view('sched_list'); });
    Route::get('/schedule-List', [schedulefilter_controler::class, 'filter'])->name('schedule.filter');
    
    
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/acu', [HomeController::class, 'acu'])->name('acu');
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
    Route::post('/update-related-schedules-admin', [schedulecontroller::class, 'updateRelatedSchedulesadmin']);

    Route::get('/schedule-List-admin?year=&month=&day=', function () { return view('admin.sched_list'); });
    Route::get('/schedule-List-admin', [schedulefilter_controler::class, 'filteradmin'])->name('schedule-admin.filter');
    Route::get('/schedule-list-admin', [schedulefilter_controler::class, 'indexadmin']);
    Route::delete('/schedule/{id}', [schedulefilter_controler::class, 'destroy'])->name('schedule.destroy');
    Route::post('/update-schedules-status', [schedulefilter_controler::class, 'updateSchedulesManually'])->name('update-schedules-status');


    
});