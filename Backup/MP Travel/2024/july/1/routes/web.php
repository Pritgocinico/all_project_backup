<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Common\DashboardController;
use App\Http\Controllers\Common\DepartmentController;
use App\Http\Controllers\Common\RoleController;
use App\Http\Controllers\Common\SettingController;
use App\Http\Controllers\Common\UserController;
use App\Http\Controllers\Common\DesignationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    'register' => false,
    'verify' => false,
    'reset' => false
]);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('state-by-country', [UserController::class, 'getStateByCountry'])->name('state-by-country');
Route::get('city-by-state', [UserController::class, 'getCityByState'])->name('city-by-state');


Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard-setting', [SettingController::class, 'index'])->name('setting');
    Route::post('/setting-update', [SettingController::class, 'settingUpdate'])->name('setting-update');
    
    Route::resource('role',RoleController::class);
    Route::resource('user',UserController::class);
    Route::resource('department',DepartmentController::class);
    Route::resource('designation',DesignationController::class);
    
    Route::get('/all-logs', [SettingController::class, 'AllLogs'])->name('all-log');
});
