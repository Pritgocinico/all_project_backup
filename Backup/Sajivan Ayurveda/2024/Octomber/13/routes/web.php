<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Common\CustomerController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Common\DepartmentController;
use App\Http\Controllers\Common\CategoryController;
use App\Http\Controllers\Common\ProductController;
use App\Http\Controllers\Common\RoleController;
use App\Http\Controllers\Common\SettingController;
use App\Http\Controllers\Common\UserController;
use App\Http\Controllers\Common\DesignationController;
use App\Http\Controllers\Common\HolidayController;
use App\Http\Controllers\Common\CertificateController;
use App\Http\Controllers\Common\InfoSheetController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\ServicePreferenceController;
use App\Http\Controllers\Common\LeaveController;
use App\Http\Controllers\Common\AttendanceController;
use App\Http\Controllers\Common\FollowUpController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\Common\SalarySlipController;
use App\Http\Controllers\Common\ShiftTimeController;
use App\Http\Controllers\Common\LeadController;
use App\Http\Controllers\Common\NotificationController;
use App\Http\Controllers\Common\DiseaseController;
use App\Http\Controllers\Common\PushLeadController;
use App\Http\Controllers\employee\EmployeeDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\hr\DashboardController as HrDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes([
    'register' => false,
    'verify' => false,
    'reset' => false
]);
// Route::get('/', [LoginController::class,'loginPage']);
Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('state-by-country', [UserController::class, 'getStateByCountry'])->name('state-by-country');
Route::get('city-by-state', [UserController::class, 'getCityByState'])->name('city-by-state');


Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/employee-dashboard', [EmployeeDashboardController::class, 'index'])->name('emp-dashboard');
    Route::get('/hr-dashboard', [HrDashboardController::class, 'index'])->name('hr-dashboard');

    Route::get('/dashboard-setting', [SettingController::class, 'index'])->name('setting');
    Route::post('/setting-update', [SettingController::class, 'settingUpdate'])->name('setting-update');
    
    Route::resource('role',RoleController::class);
    Route::resource('user',UserController::class);
    Route::get('remove-card-users', [UserController::class, 'removeUsersCard'])->name('remove.card.users');
    
    // department
    Route::resource('department',DepartmentController::class);
    Route::get('department-ajax',[DepartmentController::class,'departmentAjaxList'])->name('department-ajax');
    Route::get('department-export', [DepartmentController::class,'exportFile'])->name('department-export');

    // category
    Route::resource('category',CategoryController::class);
    Route::get('category-ajax',[CategoryController::class,'categoryAjaxList'])->name('category-ajax');
    Route::get('category-export', [CategoryController::class,'exportFile'])->name('category-export');

    // product
    Route::resource('product',ProductController::class);
    Route::get('product-ajax',[ProductController::class,'productAjaxList'])->name('product-ajax');
    Route::get('product-export', [ProductController::class,'exportFile'])->name('product-export');
    Route::get('remove-product-image', [ProductController::class,'removeProductImage'])->name('remove.product.image');
    
    Route::resource('designation',DesignationController::class);
    Route::resource('holiday',HolidayController::class);
    Route::resource('certificate',CertificateController::class);
    Route::resource('incentive',InfoSheetController::class);
    Route::resource('salary-slip',SalarySlipController::class);
    Route::resource('leads',LeadController::class);
    Route::Post('add-customer', [LeadController::class, 'addCustomerForm'])->name('customer.add');
    Route::Post('lead-remark',[LeadController::class,'leadRemarks'])->name('lead-remark');

    Route::resource('pushleads',PushLeadController::class);
    Route::get('push-lead-ajax',[PushLeadController::class,'pushleadAjaxList'])->name('push-lead-ajax');
    Route::get('push-lead-export', [PushLeadController::class,'pushexportFile'])->name('push-lead-export');
    
    Route::Post('generate-salary-slip', [SalarySlipController::class, 'generateSalarySlip'])->name('generate-salary-slip');
    Route::get('deduction-delete', [SalarySlipController::class, 'deductionDelete'])->name('deduction.delete');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance-ajax', [AttendanceController::class, 'ajaxList'])->name('attendance.ajax');
    Route::get('daily_attendance', [AttendanceController::class, 'dailyAttendance'])->name('daily_attendance');
    Route::get('designation-by-department',[DepartmentController::class,'getDesignationByDepartment'])->name('designation-by-department');
    
    Route::get('designation-ajax',[DesignationController::class,'designationAjaxList'])->name('designation-ajax');
    Route::get('designation-export', [DesignationController::class,'exportFile'])->name('designation-export');
    Route::get('role-ajax',[RoleController::class,'roleAjaxList'])->name('role-ajax');
    Route::post('/change-status/{id}', [RoleController::class, 'changeStatus'])->name('changeStatus');

    Route::get('employee-ajax',[UserController::class,'employeeAjaxList'])->name('employee-ajax');
    Route::get('employee-export', [UserController::class,'exportFile'])->name('employee-export');
    Route::get('infosheet-ajax',[InfoSheetController::class,'infosheetAjaxList'])->name('infosheet-ajax');
    Route::get('infosheet-export', [InfoSheetController::class,'exportFile'])->name('infosheet-export');
    Route::get('holiday-ajax',[HolidayController::class,'holidayAjaxList'])->name('holiday-ajax');
    Route::get('holiday-export', [HolidayController::class,'exportFile'])->name('holiday-export');
    Route::get('leave-ajax',[LeaveController::class,'leaveAjaxList'])->name('leave-ajax');
    Route::get('leave-export', [LeaveController::class,'exportFile'])->name('leave-export');
    Route::get('help-ajax',[TicketController::class,'ticketAjaxList'])->name('help-ajax');
    Route::get('help-export', [TicketController::class,'exportFile'])->name('help-export');
    Route::get('salaryslip-ajax',[SalarySlipController::class,'salaryslipAjaxList'])->name('salaryslip-ajax');
    Route::get('salaryslip-export', [SalarySlipController::class,'exportFile'])->name('salaryslip-export');
    Route::get('certificate-ajax',[CertificateController::class,'certificateAjaxList'])->name('certificate-ajax');
    Route::get('certificate-export', [CertificateController::class,'exportFile'])->name('certificate-export');
    Route::get('lead-ajax',[LeadController::class,'leadAjaxList'])->name('lead-ajax');
    Route::get('lead-export', [LeadController::class,'exportFile'])->name('lead-export');
    Route::get('followup-ajax-list',[FollowUpController::class,'followupAjaxList'])->name('followup-ajax-list');
    Route::get('followup-export', [FollowUpController::class,'exportFile'])->name('followup-export');

    Route::get('business-setting',[SettingController::class,'businessSetting'])->name('business-setting');
    Route::get('business-setting-view/{id}',[SettingController::class,'businessSettingForm'])->name('business.setting.view');
    Route::Post('business-setting-view/{id}',[SettingController::class,'businessSettingUpdate'])->name('business.setting-update');
    Route::get('/all-logs', [SettingController::class, 'AllLogs'])->name('all-log');
    Route::get('log-ajax',[SettingController::class,'logAjaxList'])->name('log-ajax');
    
    Route::post('/menu-items/reorder', [MenuController::class, 'reorder']);
    
    // customer
    Route::resource('customer',CustomerController::class);
    Route::get('customer-ajax',[CustomerController::class,'customerAjaxList'])->name('customer-ajax');
    Route::get('customer-export', [CustomerController::class,'exportFile'])->name('customer-export');
    Route::get('general-insurance-customer', [CustomerController::class,'generalInsuranceCustomer'])->name('general-insurance-customer');
    Route::get('travel-customer', [CustomerController::class,'travelCustomer'])->name('travel-customer');
    Route::get('get-department-customer', [CustomerController::class,'departmentCustomer'])->name('get-department-customer');
    Route::get('get-customer-detail',[CustomerController::class,'getCustomerDetail'])->name('customer.get');
    Route::get('customer-profile/{id}',[CustomerController::class,'customerProfileChange'])->name('customer.change-profile');
    Route::POST('customer-change-profile',[CustomerController::class,'customerProfileUpdate'])->name('customer.update-profile');
    Route::get('customer-view-profile/{id}',[CustomerController::class,'customerProfileView'])->name('customer.view-profile');
    Route::get('service-preference-tag-delete', [CustomerController::class,'removeCustomerTag'])->name('service-preference-tag-delete');
    Route::get('alt-num-delete', [CustomerController::class, 'alt_numDelete'])->name('alt_num.delete');
    Route::get('address-delete', [CustomerController::class, 'addressDelete'])->name('address-delete');
    Route::get('customer-search-ajax',[CustomerController::class,'customerAjaxSearch'])->name('customer-search-ajax');
    Route::get('pin-code-ajax',[CustomerController::class,'pincodeDetails'])->name('pin-code-ajax');

    Route::resource('leave',LeaveController::class);
    Route::get('leave-status', [LeaveController::class, 'changeLeaveStatus'])->name('leave.status');

    Route::resource('help',TicketController::class);
    Route::POST('help-comment-store',[TicketController::class,'addCommentTicket'])->name('help-comment-store');
    Route::get('help-status/{id}',[TicketController::class,'changeTicketStatus'])->name('help.status');

    // Break
    Route::get('employee-break-time-start',[DashboardController::class,'break'])->name('employee-break-time-start');
    Route::get('break-count', [DashboardController::class, 'sumTime'])->name('break_time');
    Route::get('complete-break',[DashboardController::class,'completeBreak'])->name('complete_break');
    Route::get('profile-view',[DashboardController::class,'profileView'])->name('profile-view');
    Route::POST('update-profile',[DashboardController::class,'updateProfile'])->name('update.profile');

    Route::get('calendar-detail/{id}',[UserController::class,'calendar'])->name('calendar-detail');
    Route::get('get-attendance-by-date', [UserController::class, 'attendanceByDate'])->name('attendance_by_date');
    Route::get('get-break-time', [UserController::class, 'breakTime'])->name('break_time_count');
    Route::get('get-log-detail', [UserController::class, 'logDetail'])->name('get-log-detail');
    Route::get('office-num-delete', [UserController::class, 'office_numDelete'])->name('office_num.delete');

    // Shift Detail
    Route::resource('shift-time',ShiftTimeController::class);
    Route::get('shift-ajax',[ShiftTimeController::class,'ajaxList'])->name('shift-ajax');
    Route::get('shift-export', [ShiftTimeController::class,'exportFile'])->name('shift-export');
    
    // Follow Up
    Route::resource('follow-up',FollowUpController::class);
    Route::get('followup-ajax', [FollowUpController::class, 'followUpEventAjax'])->name('followup-ajax');
    Route::POST('followup-comment-store', [FollowUpController::class, 'followCommentStore'])->name('follow-comment-store');

    // Bulk Import
    Route::POST('bulk-holiday-import', [HolidayController::class, 'importExcel'])->name('bulk-holiday-import');
    Route::post('/import-excel', [HolidayController::class, 'import'])->name('import.excel');

    Route::get('change-password',[DashboardController::class,'changePassword'])->name('change.password');
    Route::POST('update-password',[DashboardController::class,'updatePassword'])->name('update.password');


    Route::get('attendance-export', [AttendanceController::class, 'attendanceExport'])->name('attendance-export');
    Route::get('daily-attendance-export', [AttendanceController::class, 'dailyAttendanceExport'])->name('daily-attendance-export');

    Route::get('mark-as-read', function(){
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('mark-as-read');
    Route::post('notification/mark-as-read', [NotificationController::class, 'readNotification'])->name('notification.mark_as_read');
    Route::post('notification/mark_all_as_read', [NotificationController::class, 'readAllNotifications'])->name('notification.mark_all_as_read');

    // Employee
    Route::get('emp-leave-detail', [EmployeeDashboardController::class, 'leaveDataAjax'])->name('emp-leave-detail');
    Route::get('lead-status',[LeadController::class,'leadStatusChange'])->name('lead.status');

    // Service Preference
    Route::resource('service-preference',ServicePreferenceController::class);
    Route::get('service-preference-ajax',[ServicePreferenceController::class,'ajaxList'])->name('service-preference-ajax');
    Route::get('service-preference-export', [ServicePreferenceController::class,'exportFile'])->name('service-preference-export');

    // Disease
    Route::resource('disease',DiseaseController::class);
    Route::get('disease-ajax',[DiseaseController::class,'diseaseAjaxList'])->name('disease-ajax');
    Route::get('disease-export', [DiseaseController::class,'exportFile'])->name('disease-export');

    Route::POST('customer-lead-update',[LeadController::class,'updateLeadCustomerData'])->name('customer_lead_update');
    Route::get('alt_num.check',[CustomerController::class,'checkNumber'])->name('alt_num.check');
    Route::get('check.number.lead',[CustomerController::class,'checkNumberLead'])->name('check.number.lead');
    Route::get('lead-view-edit/{id}',[LeadController::class,'viewEdit'])->name('lead-view-edit');
});