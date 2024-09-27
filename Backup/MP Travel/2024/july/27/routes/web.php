<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Common\CustomerController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Common\DepartmentController;
use App\Http\Controllers\Common\RoleController;
use App\Http\Controllers\Common\SettingController;
use App\Http\Controllers\Common\UserController;
use App\Http\Controllers\Common\DesignationController;
use App\Http\Controllers\Common\HolidayController;
use App\Http\Controllers\Common\CertificateController;
use App\Http\Controllers\Common\InfoSheetController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\Common\LeaveController;
use App\Http\Controllers\Common\AttendanceController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\Common\SalarySlipController;
use App\Http\Controllers\Common\ShiftTimeController;
use App\Http\Controllers\Common\LeadController;
use App\Http\Controllers\common\NotificationController;
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
    Route::resource('holiday',HolidayController::class);
    Route::resource('certificate',CertificateController::class);
    Route::resource('info_sheet',InfoSheetController::class);
    Route::resource('salary-slip',SalarySlipController::class);
    Route::resource('leads',LeadController::class);
    
    Route::Post('generate-salary-slip', [SalarySlipController::class, 'generateSalarySlip'])->name('generate-salary-slip');
    Route::get('deduction-delete', [SalarySlipController::class, 'deductionDelete'])->name('deduction.delete');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance-ajax', [AttendanceController::class, 'ajaxList'])->name('attendance.ajax');
    Route::get('attendance/daily_attendance', [AttendanceController::class, 'dailyAttendance'])->name('attendance.daily_attendance');
    Route::get('designation-by-department',[DepartmentController::class,'getDesignationByDepartment'])->name('designation-by-department');

    Route::get('business-setting',[SettingController::class,'businessSetting'])->name('business-setting');
    Route::get('business-setting-view/{id}',[SettingController::class,'businessSettingForm'])->name('business.setting.view');
    Route::Post('business-setting-view/{id}',[SettingController::class,'businessSettingUpdate'])->name('business.setting-update');
    Route::get('/all-logs', [SettingController::class, 'AllLogs'])->name('all-log');
    
    Route::post('/menu-items/reorder', [MenuController::class, 'reorder']);
    
    Route::resource('customer',CustomerController::class);
    Route::resource('leave',LeaveController::class);
    Route::get('leave-status', [LeaveController::class, 'changeLeaveStatus'])->name('leave.status');

    Route::resource('ticket',TicketController::class);
    Route::POST('ticket-comment-store',[TicketController::class,'addCommentTicket'])->name('ticket-comment-store');

    // Break
    Route::get('employee-break-time-start',[DashboardController::class,'break'])->name('employee-break-time-start');
    Route::get('break-count', [DashboardController::class, 'sumTime'])->name('break_time');
    Route::get('complete-break',[DashboardController::class,'completeBreak'])->name('complete_break');

    Route::get('calendar-detail/{id}',[UserController::class,'calendar'])->name('calendar-detail');
    Route::get('get-attendance-by-date', [UserController::class, 'attendanceByDate'])->name('attendance_by_date');
    Route::get('get-break-time', [UserController::class, 'breakTime'])->name('break_time_count');
    Route::get('get-log-detail', [UserController::class, 'logDetail'])->name('get-log-detail');

    // Shift Detail
    Route::resource('shift-time',ShiftTimeController::class);

    Route::get('mark-as-read', function(){
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('mark-as-read');
    Route::post('notification/mark-as-read', [NotificationController::class, 'readNotification'])->name('notification.mark_as_read');
    Route::post('notification/mark_all_as_read', [NotificationController::class, 'readAllNotifications'])->name('notification.mark_all_as_read');
});
