<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\schedulecontroller; 
use App\Http\Controllers\schedulefilter_controler; 
use App\Http\Controllers\ArduinoController;




Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});
 
// Routes for authenticated users
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home'); // Rename to 'home' route
    Route::get('/acu', [HomeController::class, 'acu'])->name('acu');

    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/users', UserController::class)->except(['show']);
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    Route::get('/schedule', [schedulecontroller::class, 'index'])->name('schedule.index');

    Route::get('/get-related-data1', [schedulecontroller::class, 'getRelatedData1'])->name('get.related.data1');
    Route::get('/get-related-data', [schedulecontroller::class, 'getRelatedData'])->name('get.related.data');

    Route::post('/update-state1/{itemId}', [schedulecontroller::class, 'updateState1']);
    Route::post('/update-state/{itemId}', [schedulecontroller::class, 'updateState']);
    Route::delete('/delete-schedule/{itemId}', [schedulecontroller::class, 'deleteSchedule']);

    Route::put('/update-schedule/{id}', [schedulecontroller::class, 'updateSchedule']);

    Route::post('/schedule', [schedulecontroller::class, 'store'])->name('store.schedule');
    Route::get('/schedule-List?year=&month=&day=', function () { return view('sched_list'); });
    Route::get('/schedule-List', [schedulefilter_controler::class, 'filter'])->name('schedule.filter');
    Route::post('/log-activity', [ActivityLogController::class, 'logActivity']);
});

// Routes for admin users
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Additional admin routes can be added here
    // Example: Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', UserController::class)->except(['show']);
});




Route::post('/send-data-to-arduino', [ArduinoController::class, 'sendData'])->name('send.data.to.arduino');
