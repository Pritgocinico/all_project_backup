<?php

use App\Http\Controllers\admin\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AfterSaleController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ConfirmationDepartmentController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\HolidayController;
use App\Http\Controllers\admin\HrManagerController;
use App\Http\Controllers\admin\InfoSheetsController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\SchemeController;
use App\Http\Controllers\employee\DashboardController;
use App\Http\Controllers\admin\SystemEngineerController;
use App\Http\Controllers\admin\TeamController;
use App\Http\Controllers\admin\TicketController as AdminTicketController;
use App\Http\Controllers\admin\TransportController;
use App\Http\Controllers\admin\UserLogController;
use App\Http\Controllers\admin\WarehouseController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\employee\EmployeeCategoryController;
use App\Http\Controllers\employee\EmployeeHolidayController;
use App\Http\Controllers\employee\EmployeeInfoSheetController;
use App\Http\Controllers\employee\EmployeeLeadController;
use App\Http\Controllers\employee\EmployeeLeaveController;
use App\Http\Controllers\employee\EmployeeOrdersController;
use App\Http\Controllers\employee\TicketController;
use App\Http\Controllers\hr\HrDashboardController;
use App\Http\Controllers\hr\HrInfoSheetController;
use App\Http\Controllers\hr\SalarySlipController;
use App\Http\Controllers\confirm\ConfirmationDepartmentController as ConfirmController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\driver\DriverController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\salemanager\SalesManagerController;
use App\Http\Controllers\sale_service\AfterSaleServiceController;
use App\Http\Controllers\system\SystemEngineerController as RoleSystemEngineerController;
use App\Http\Controllers\transport\TransportDepartmentController;
use App\Http\Controllers\warehouse\WarehouseManagerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[LoginController::class,'index']);
Route::get('login',[LoginController::class,'index'])->name('login');
Route::post('sign-in',[LoginController::class,'login'])->name('sign-in');
Route::get('logout',[LoginController::class,'logout'])->name('logout');
Route::post('update-profile',[LoginController::class,'updateProfile'])->name('update-profile');
Route::get('check_attendance',[AttendanceController::class,'checkAttendance'])->name('check_attendance');
Route::get('first-check-half-day',[CronJobController::class,'getFirstShiftUserAttendance'])->name('first-check-half-day');
Route::get('second-check-half-day',[CronJobController::class,'getSecondShiftUserAttendance'])->name('second-check-half-day');
Route::get('third-check-half-day',[CronJobController::class,'getThirdShiftUserAttendance'])->name('third-check-half-day');



// Admin Route
Route::group(['prefix' => 'admin'], function(){
    Route::middleware('auth','role:admin','autoLogout')->group(function () {
        Route::get('dashboard',[AdminDashboardController::class,'dashboard'])->name('dashboard');
        Route::get('admin-dashboard-order-ajax',[AdminDashboardController::class,'dashboardOrderAjax'])->name('admin-dashboard-order-ajax');
        Route::get('dashboard-batch-ajax',[AdminDashboardController::class,'dashboardBatchAjax'])->name('dashboard-batch-ajax');
        Route::get('dashboard-team-ajax',[AdminDashboardController::class,'dashboardTeamAjax'])->name('dashboard-team-ajax');

        // Employee
        Route::resource('employees', EmployeeController::class);
        Route::get('employee-ajax', [EmployeeController::class,'ajaxList'])->name('employee-ajax');
        Route::get('employee-export', [EmployeeController::class,'exportFile'])->name('employee-export');
        Route::get('edit-profile',[LoginController::class,'edit'])->name('edit-profile');
        Route::post('update-permission',[EmployeeController::class,'updateUserPermission'])->name('update-permission');
        Route::get('calendar-detail/{id}',[EmployeeController::class,'calendar'])->name('calendar-detail');
        Route::get('get-break-time', [EmployeeController::class, 'breakTime'])->name('break_time_count');
        Route::get('vip-customer-list', [EmployeeController::class, 'vipCustomerList'])->name('vip-customer-list');
        Route::get('generate-offer-letter', [EmployeeController::class, 'offerLetter'])->name('generate-offer-letter');

        // Category
        Route::resource('category', CategoryController::class);
        Route::get('category-ajax', [CategoryController::class,'ajaxList'])->name('category-ajax');

        //System Engineer
        Route::resource('systemengineer', SystemEngineerController::class);
        Route::get('systemengineer-ajax', [SystemEngineerController::class,'ajaxList'])->name('systemengineer-ajax');

        // Product
        Route::resource('product', ProductController::class);
        Route::get('product-ajax', [ProductController::class,'ajaxList'])->name('product-ajax');
        Route::get('product-export', [ProductController::class,'exportFile'])->name('product-export');
        Route::get('product-variant-delete', [ProductController::class,'productVariantDelete'])->name('product-variant-delete');

        // Department
        Route::resource('department', DepartmentController::class);
        Route::get('department-ajax', [DepartmentController::class,'ajaxList'])->name('department-ajax');

        // Orders
        Route::resource('orders',OrderController::class);
        Route::get('get-subdistricts',[OrderController::class,'getSubDistrict'])->name('get-subdistricts');
        Route::get('get-villages',[OrderController::class,'getVillage'])->name('get-villages');
        Route::get('get-category',[OrderController::class,'getAllCategory'])->name('get-category');
        Route::get('get-product-list',[OrderController::class,'getProductByCategory'])->name('get-product-list');
        Route::get('get-product-variant-list',[OrderController::class,'getProductVariantDetail'])->name('get-product-variant-list');
        Route::get('get_variant',[OrderController::class,'getVariantDetailById'])->name('get_variant');
        Route::get('orders-ajax',[OrderController::class,'ajaxList'])->name('orders-ajax');
        Route::get('orders-list',[OrderController::class,'ordersList'])->name('orders-list');
        Route::get('orders-export',[OrderController::class,'exportFile'])->name('orders-export');
        Route::get('confirm-order',[OrderController::class,'confirmOrder'])->name('all-confirm-order');
        Route::get('confirm-order-list',[OrderController::class,'confirmAjaxList'])->name('confirm-order-list');
        Route::get('cancel-order',[OrderController::class,'cancelOrder'])->name('all-cancel-order');
        Route::get('cancel-order-list',[OrderController::class,'cancelAjaxList'])->name('cancel-order-list');
        Route::get('deliver-order-list',[OrderController::class,'deliveredAjaxList'])->name('deliver-order-list');
        Route::get('deliver-order',[OrderController::class,'deliverOrder'])->name('all-deliver-order');
        Route::get('confirm-order-export',[OrderController::class,'confirmExportCSV'])->name('confirm-order-export');
        Route::get('confirm-order-invoice-pdf',[OrderController::class,'generateOrderInvoice'])->name('confirm-order-invoice-pdf');
        Route::get('on-delivery-order',[OrderController::class,'onDeliverOrder'])->name('on-delivery-order');
        Route::get('confirm-order-query',[OrderController::class,'confirmOrderQuery'])->name('confirm-order-query');
        Route::post('update-order-status',[OrderController::class,'updateOrderStatus'])->name('update-order-status');

        
        // Warehouse route
        Route::resource('warehouse',WarehouseController::class);
        Route::get('bill-download',[WarehouseController::class,'billDownload'])->name('bill-download');
        Route::get('bill-download-ajax',[WarehouseController::class,'billDownloadAjax'])->name('bill-download-ajax');
        Route::get('generate-batch-invoice-pdf/{id}',[TransportDepartmentController::class,'generateInvoice'])->name('generate-batch-invoice-pdf');
        
        // HR Manager
        Route::resource('human-resource/holiday-list',HolidayController::class);
        Route::get('human-resource/holiday-list-ajax',[HolidayController::class,'ajaxList'])->name('holiday-list-ajax');
        Route::get('leave-list',[HrManagerController::class,'leaveList'])->name('leave-list');
        Route::get('admin-leave-ajax',[HrManagerController::class,'leaveajaxList'])->name('admin-leave-ajax');
        Route::get('admin-leave-status-update',[HrManagerController::class,'leavestatusUpdate'])->name('admin-leave-status-update');
        Route::get('certificate-list',[HrManagerController::class,'certificateList'])->name('certificate-list');
        Route::get('certificate-list',[HrManagerController::class,'certificateList'])->name('certificate-list');
        Route::get('admin-generate-certificate/{winnerNumber}/{empName}',[HrManagerController::class,'GenerateCertificate'])->name('admin-generate-certificate');
        Route::get('generate-certificate',[HrManagerController::class,'autoGenerateCertificate'])->name('generate-certificate');

        Route::get('ticket-list',[AdminTicketController::class,'ticketList'])->name('ticket-list');
        Route::get('ticket-list-ajax',[AdminTicketController::class,'ticketListAjax'])->name('ticket-list-ajax');
        Route::get('ticket-show/{id}',[AdminTicketController::class,'show'])->name('ticket-show');
        Route::post('ticket-comment-store',[AdminTicketController::class,'addCommentTicket'])->name('admin-ticket-comment-store');

        // Attendance
        Route::get('attendance-list',[AttendanceController::class,'attendanceList'])->name('attendance-list');
        Route::get('attendance-ajax',[AttendanceController::class,'attendanceAjaxList'])->name('attendance-ajax');
        Route::get('get-attendance-by-date', [AttendanceController::class, 'attendanceByDate'])->name('attendance_by_date');
        Route::get('get-log-detail', [AttendanceController::class, 'logDetail'])->name('get-log-detail');
        Route::get('daily-attendance', [AttendanceController::class, 'dailyAttendance'])->name('daily-attendance');

        // Report
        Route::get('order_report',[ReportController::class,'orderReport'])->name('order_report');
        Route::get('order-report-ajax',[ReportController::class,'orderReportAjax'])->name('order-report-ajax');
        Route::get('order-report-export',[ReportController::class,'orderReportExport'])->name('order-report-export');

        Route::get('product-order-report',[ReportController::class,'productOrderReport'])->name('product-order-report');
        Route::get('product-order-report-ajax',[ReportController::class,'productOrderReportAjax'])->name('product-order-report-ajax');
        Route::get('product-order-report-export',[ReportController::class,'productOrderReportExport'])->name('product-order-report-export');

        Route::get('sales-report',[ReportController::class,'salesReport'])->name('sales-report');
        Route::get('sales-report-ajax',[ReportController::class,'salesReportAjax'])->name('sales-report-ajax');
        Route::get('sales-report-export',[ReportController::class,'salesReportExport'])->name('sales-report-export');

        Route::get('staff-order-report',[ReportController::class,'staffOrderReport'])->name('staff-order-report');
        Route::get('staff-order-report-ajax',[ReportController::class,'staffOrderReportAjax'])->name('staff-order-report-ajax');
        Route::get('staff-order-report-export',[ReportController::class,'staffOrderReportExport'])->name('staff-order-report-export');
        
        // Stock
        Route::get('admin-in-out-stock',[TransportDepartmentController::class,'stockList'])->name('admin-in-out-stock');
        Route::get('admin-stock-list',[WarehouseManagerController::class,'stockList'])->name('admin-stock-list');
        
        
        Route::get('manual-order',[ConfirmationDepartmentController::class,'index'])->name('manual-order');
        Route::get('manual-orders-ajax',[ConfirmationDepartmentController::class,'ajaxList'])->name('manual-orders-ajax');
        
        Route::post('manual-orders-confirm',[ConfirmationDepartmentController::class,'confirmOrderRequest'])->name('manual-orders-confirm');
        Route::post('manual-orders-cancel',[ConfirmationDepartmentController::class,'cancelOrderRequest'])->name('manual-orders-cancel');

        Route::get('return-order',[ConfirmationDepartmentController::class,'returnOrder'])->name('return-order');
        Route::get('return-order-ajax',[ConfirmationDepartmentController::class,'returnajaxList'])->name('return-order-ajax');
        Route::get('divert-transport',[ConfirmationDepartmentController::class,'divertTransport'])->name('divert-transport');
        
        Route::get('batch-transport',[ConfirmationDepartmentController::class,'batchTransport'])->name('batch-transport');

        Route::get('batch-manually',[TransportController::class,'index'])->name('batch-manually');
        Route::get('assign-driver',[TransportController::class,'assignDriver'])->name('assign-driver');
        Route::get('driver',[TransportController::class,'driverManagement'])->name('driver');
        Route::get('driver-management-ajax',[TransportController::class,'driverManagementAjax'])->name('driver-management-ajax');
        Route::get('print_bill',[TransportController::class,'printBill'])->name('print_bill');

        Route::get('complete-order',[AfterSaleController::class,'index'])->name('complete-order');
        Route::get('order-report',[AfterSaleController::class,'orderReport'])->name('order-report');
        Route::get('sale-product',[AfterSaleController::class,'product'])->name('sale-product');
        Route::get('feedback',[AfterSaleController::class,'feedback'])->name('feedback');
        
        // Account
        Route::get('order-deliver',[AccountController::class,'index'])->name('order-deliver');
        Route::get('hr-salary-slip',[AccountController::class,'salarySlip'])->name('hr-salary-slip');
        Route::get('all-stock',[AccountController::class,'allStock'])->name('all-stock');
        
        // Info Sheet
        Route::resource('info-sheet',InfoSheetsController::class);
        Route::get('info-ajax',[InfoSheetsController::class,'ajaxList'])->name('info-ajax');

        // User Logs
        Route::get('admin-all-logs',[UserLogController::class,'index'])->name('admin-all-logs');
        
        Route::resource('scheme',SchemeController::class);
        Route::get('discount-type-form/{id}',[SchemeController::class,'discountTypeForm'])->name('discount-type-form');
        Route::get('scheme-ajax',[SchemeController::class,'ajaxList'])->name('scheme-ajax');
        
        Route::get('order-feedback-list',[AfterSaleServiceController::class,'feedbackList'])->name('order-feedback-list');
        Route::get('order-feedback-ajax',[AfterSaleServiceController::class,'feedbackAjaxList'])->name('order-feedback-ajax');
        
        Route::resource('team', TeamController::class);
        Route::get('admin-batch-view/{id}',[TransportDepartmentController::class,'batchView'])->name('admin-batch-view');
        Route::get('admin-confirm-order',[TransportDepartmentController::class,'orderList'])->name('admin-confirm-order');
        Route::get('admin-batch-list',[TransportDepartmentController::class,'batchList'])->name('admin-batch-list');
        Route::get('all-attendance-date-ajax',[EmployeeController::class,'attendanceDateAjax'])->name('all-attendance-date-ajax');
        Route::get('top-order-product',[SalesManagerController::class,'getTopProductSale'])->name('top-order-product');
        Route::get('top-order-product-ajax',[SalesManagerController::class,'getTopProductSaleAjax'])->name('top-order-product-ajax');

        Route::get('admin-pending-order-item',[ConfirmationDepartmentController::class,'getAllPendingOrderItem'])->name('admin-pending-order-item');
        Route::get('admin-generate-pending-order-item',[ConfirmationDepartmentController::class,'generatePendingOrderItemPDF'])->name('admin-generate-pending-order-item');

        Route::get('all-confirm-order-list',[ConfirmationDepartmentController::class,'allConfirmOrderList'])->name('all-confirm-order-list');
        Route::get('all-confirm-order-ajax',[ConfirmationDepartmentController::class,'allConfirmOrderAjax'])->name('all-confirm-order-ajax');
        Route::get('admin-top-five-confirm-order', [ConfirmController::class, 'topFiveConfirmOrder'])->name('admin-top-five-confirm-order');
        Route::get('setting', [SettingController::class, 'setting'])->name('setting');
        Route::post('setting', [SettingController::class, 'update'])->name('setting');
        Route::get('admin-show-winner-list',[ConfirmController::class,'winnerList'])->name('admin-show-winner-list');
        Route::get('admin-show-winner-ajax',[ConfirmController::class,'winnerListAjax'])->name('admin-show-winner-ajax');
        Route::get('admin-show-winner-export',[ConfirmController::class,'winnerListExport'])->name('admin-show-winner-export');
        Route::get('admin-confirm-order-item',[ConfirmController::class,'confirmOrderItem'])->name('admin-confirm-order-item');
        Route::get('admin-generate-confirm-order-item',[ConfirmController::class,'generateConfirmOrderItemPDF'])->name('admin-generate-confirm-order-item');
    });
    Route::get('delete-order-item',[OrderController::class,'removeOrderItem'])->name('delete-order-item');
    Route::get('get-sub-district-order',[OrderController::class,'getSubDistrictInOrder'])->name('get-sub-district-order');
    Route::get('divert-transport-ajax',[ConfirmationDepartmentController::class,'divertTransportajaxList'])->name('divert-transport-ajax');
});

// Employee Route
Route::group(['prefix' => 'employee'], function(){
    Route::middleware(['auth','role:employee','autoLogout'])->group(function () {
        Route::get('employee-dashboard',[DashboardController::class,'employeeDashboard'])->name('employee-dashboard');
        Route::get('chart-ajax-data',[DashboardController::class,'chartDataAjax'])->name('chart-ajax-data');
        Route::get('order-ajax-dashboard',[DashboardController::class,'orderAjax'])->name('order-ajax-dashboard');
        Route::get('leave-ajax-dashboard',[DashboardController::class,'leaveAjax'])->name('leave-ajax-dashboard');
        Route::get('winner-ajax-dashboard',[DashboardController::class,'winnerAjax'])->name('winner-ajax-dashboard');
        Route::get('user-edit-profile',[LoginController::class,'userEdit'])->name('user-edit-profile');

        Route::get('employee-category',[EmployeeCategoryController::class,'category'])->name('employee-category');
        Route::get('employee-category-ajax',[EmployeeCategoryController::class,'ajaxList'])->name('employee-category-ajax');
        Route::get('employee-category-export',[EmployeeCategoryController::class,'exportCategoryCSV'])->name('employee-category-export');

        Route::get('employee-product',[EmployeeCategoryController::class,'product'])->name('employee-product');
        Route::get('employee-product-ajax',[EmployeeCategoryController::class,'productAjaxList'])->name('employee-product-ajax');
        Route::get('employee-product-export',[EmployeeCategoryController::class,'exportProductCSV'])->name('employee-product-export');
        Route::get('employee-product-view/{id}',[EmployeeCategoryController::class,'productView'])->name('employee-product-view');

        // Order
        Route::resource('employee-orders',EmployeeOrdersController::class);
        Route::get('employee-orders-ajax',[EmployeeOrdersController::class,'OrderajaxList'])->name('employee-orders-ajax');
        Route::get('employee-pending-orderlist',[EmployeeOrdersController::class,'pendingOrderList'])->name('employee-pending-orderlist');
        Route::get('employee-pending-order',[EmployeeOrdersController::class,'pendingOrder'])->name('employee-pending-order');
        Route::get('employee-cancel-order',[EmployeeOrdersController::class,'cancelOrder'])->name('employee-cancel-order');
        Route::get('employee-cancel-orderlist',[EmployeeOrdersController::class,'cancelOrderList'])->name('employee-cancel-orderlist');
        Route::get('employee-return-order',[EmployeeOrdersController::class,'returnOrder'])->name('employee-return-order');
        Route::get('employee-return-orderlist',[EmployeeOrdersController::class,'returnOrderList'])->name('employee-return-orderlist');
        Route::get('employee-completed-order',[EmployeeOrdersController::class,'completedOrder'])->name('employee-completed-order');
        Route::get('employee-completed-orderlist',[EmployeeOrdersController::class,'completedOrderList'])->name('employee-completed-orderlist');
        Route::get('employee-get-subdistricts',[EmployeeOrdersController::class,'getSubDistrict'])->name('employee-get-subdistricts');
        Route::get('employee-get-villages',[EmployeeOrdersController::class,'getVillage'])->name('employee-get-villages');
        Route::get('employee-get-category',[EmployeeOrdersController::class,'getAllCategory'])->name('employee-get-category');
        Route::get('employee-get-product-list',[EmployeeOrdersController::class,'getProductByCategory'])->name('employee-get-product-list');
        Route::get('employee-get-product-variant-list',[EmployeeOrdersController::class,'getProductVariantDetail'])->name('employee-get-product-variant-list');
        Route::get('employee-get_variant',[EmployeeOrdersController::class,'getVariantDetailById'])->name('employee-get_variant');
        Route::get('employee-orders-list',[EmployeeOrdersController::class,'ordersList'])->name('employee-orders-list');
        Route::get('employee-orders-export',[EmployeeOrdersController::class,'exportCSV'])->name('employee-orders-export');
        Route::get('employee-confirm-order',[EmployeeOrdersController::class,'confirmOrder'])->name('employee-confirm-order');
        Route::get('employee-confirm-order-list',[EmployeeOrdersController::class,'confirmOrderList'])->name('employee-confirm-order-list');

        Route::resource('employee-info-sheet', EmployeeInfoSheetController::class);;
        Route::get('employee-info-ajax',[EmployeeInfoSheetController::class,'ajaxList'])->name('employee-info-ajax');
        Route::get('employee-info-export',[EmployeeInfoSheetController::class,'exportCSV'])->name('employee-info-export');
        
        Route::get('employee-certificate',[DashboardController::class,'certificate'])->name('employee-certificate');
        Route::get('attendance',[DashboardController::class,'attendance'])->name('attendance');

        Route::get('holiday',[EmployeeHolidayController::class,'index'])->name('holiday');
        Route::get('holiday-ajax',[EmployeeHolidayController::class,'ajaxList'])->name('holiday-ajax');
        Route::get('holiday-export',[EmployeeHolidayController::class,'exportCSV'])->name('holiday-export');
        
        // Ticket
        Route::resource('employee-ticket',TicketController::class);
        Route::get('employee-ticket-ajax',[TicketController::class,'ajaxList'])->name('employee-ticket-ajax');
        Route::get('employee-ticket-ajax-export',[TicketController::class,'exportCSV'])->name('employee-ticket-ajax-export');
        Route::post('employee-ticket-comment',[TicketController::class,'ticketComment'])->name('employee-ticket-comment');
        
        // Leave
        Route::resource('employee-leave',EmployeeLeaveController::class);
        Route::get('employee-leave-ajax',[EmployeeLeaveController::class,'ajaxList'])->name('employee-leave-ajax');
        Route::get('employee-leave-export',[EmployeeLeaveController::class,'exportCSV'])->name('employee-leave-export');
        
        // Lead
        Route::resource('employee-lead',EmployeeLeadController::class);
        Route::get('employee-lead-ajax',[EmployeeLeadController::class,'ajaxList'])->name('employee-lead-ajax');
        Route::get('employee-add-lead-ajax',[EmployeeLeadController::class,'getLeadDetail'])->name('employee-add-lead-ajax');
        Route::get('employee-lead-export',[EmployeeLeadController::class,'exportCSV'])->name('employee-lead-export');
        Route::get('convert-lead-order',[EmployeeLeadController::class,'convertLeadToOrder'])->name('convert-lead-order');

        Route::get('employee-break-time-start',[DashboardController::class,'break'])->name('employee-break-time-start');
        Route::get('break-count', [DashboardController::class, 'sumTime'])->name('break_time');
        Route::get('complete-break',[DashboardController::class,'completeBreak'])->name('complete_break');
        
        Route::get('get-attendance-by-date', [AttendanceController::class, 'attendanceByDate'])->name('employee-attendance_by_date');
        Route::get('get-log-detail', [AttendanceController::class, 'logDetail'])->name('employee-get-log-detail');
        Route::get('attendance-date-ajax',[DashboardController::class,'attendanceDateAjax'])->name('attendance-date-ajax');

        Route::get('employee-scheme-detail-code',[SchemeController::class,'schemeDetail'])->name('employee-scheme-detail-code');
        Route::get('employee-scheme-detail-product',[SchemeController::class,'schemeDetailProduct'])->name('employee-scheme-detail-product');
        Route::get('employee-team-show/{id}',[TeamController::class,'show'])->name('emp-team.show');
        Route::get('employee-all-logs',[UserLogController::class,'index'])->name('employee-all-logs');
    });
});

Route::group(['prefix' => 'hr'], function(){
    Route::middleware(['auth','role:Human Resource','autoLogout'])->group(function () {
        Route::get('hr-dashboard',[HrDashboardController::class,'index'])->name('hr-dashboard');
        
        Route::get('certificate',[HrDashboardController::class,'certificate'])->name('certificate');
        
        Route::get('hr-get-log-detail', [AttendanceController::class, 'logDetail'])->name('hr-get-log-detail');
        // employee
        Route::get('employees',[HrInfoSheetController::class,'employee'])->name('hr-employee');
        Route::get('hr-employees-add',[HrInfoSheetController::class,'employeeAdd'])->name('hr-employees-add');
        Route::post('hr-employees-add-post',[HrInfoSheetController::class,'employeeAddpost'])->name('hr-employees-add-post');
        Route::get('hr-employee-ajax',[HrInfoSheetController::class,'employeeajaxList'])->name('hr-employee-ajax');
        Route::get('hr-employee-export', [HrInfoSheetController::class,'exportFile'])->name('hr-employee-export');
        Route::get('hr-edit-profile/{id}',[HrInfoSheetController::class,'employeeEdit'])->name('hr-edit-profile');
        Route::post('hr-update-permission',[HrInfoSheetController::class,'updateUserPermission'])->name('hr-update-permission');
        Route::get('hr-calendar-detail/{id}',[HrInfoSheetController::class,'calendar'])->name('hr-calendar-detail');
        
        Route::resource('hr', HrInfoSheetController::class);
        
        // info sheet
        Route::resource('hr-info-sheet', HrInfoSheetController::class);
        Route::post('hr-info-sheet-update/{id}', [HrInfoSheetController::class,'updateInfoSheet'])->name('hr-info-sheet-update');
        Route::post('hr-info-sheet-delete/{id}', [HrInfoSheetController::class,'deleteInfoSheet'])->name('hr-info-sheet-delete');
        Route::get('hr-info-sheet-ajax',[HrInfoSheetController::class,'ajaxList'])->name('hr-info-sheet-ajax');

        // Holiday
        Route::get('hr-holiday',[HrInfoSheetController::class,'holiday'])->name('hr-holiday');
        Route::post('hr-holiday-create',[HrInfoSheetController::class,'holidayCreate'])->name('hr-holiday-create');
        Route::get('hr-holiday-ajax',[HrInfoSheetController::class,'holidayAjaxList'])->name('hr-holiday-ajax');
        Route::get('hr-holiday-export',[HrInfoSheetController::class,'exportCSV'])->name('hr-holiday-export');


        // Leave
        Route::get('hr-leave',[HrInfoSheetController::class,'leave'])->name('hr-leave');
        Route::get('hr-leave-ajax',[HrInfoSheetController::class,'leaveAjaxList'])->name('hr-leave-ajax');
        Route::get('hr-leave-status-update',[HrInfoSheetController::class,'leaveStatusUpdate'])->name('hr-leave-status-update');
        Route::get('hr-leave-export',[HrInfoSheetController::class,'exportLeaveCSV'])->name('hr-leave-export');

        // Ticket
        Route::get('hr-ticket',[HrInfoSheetController::class,'ticket'])->name('hr-ticket');
        Route::get('hr-ticket-ajax-list',[HrInfoSheetController::class,'ticketAjaxList'])->name('hr-ticket-ajax-list');
        Route::get('hr-ticket-export',[HrInfoSheetController::class,'exportTicketCSV'])->name('hr-ticket-export');
        Route::get('hr-ticket-view/{id}',[HrInfoSheetController::class,'ticketShow'])->name('hr-ticket-view');
        Route::post('hr-ticket-comment',[HrInfoSheetController::class,'addCommentTicket'])->name('hr-ticket-comment');

        // Attendance
        Route::get('hr-attendance',[HrInfoSheetController::class,'attendance'])->name('hr-attendance');
        Route::get('hr-attendance-ajax',[HrInfoSheetController::class,'attendanceAjaxList'])->name('hr-attendance-ajax');
        Route::get('hr-employee-view/{id}',[HrInfoSheetController::class,'employeeView'])->name('hr-employee-view');
        Route::get('hr-calendar-detail/{id}',[EmployeeController::class,'calendar'])->name('hr-calendar-detail');
        Route::get('hr-get-attendance-by-date', [AttendanceController::class, 'attendanceByDate'])->name('hr_attendance_by_date');
        Route::post('hr-update-permission',[HrInfoSheetController::class,'updateUserPermission'])->name('hr-update-permission');

        // Salary Slip
        Route::resource('hr-salaryslip',SalarySlipController::class);
        Route::get('hr-salary-slip-ajax',[SalarySlipController::class,'ajaxList'])->name('hr-salary-slip-ajax');
        Route::get('calculate-salary',[SalarySlipController::class,'calculateSalarySlip'])->name('hr-calculate-salary');
        Route::get('generate-salary-pdf',[SalarySlipController::class,'generateSalaryPDF'])->name('generate-salary-pdf');
        Route::get('hr-attendance-date-ajax',[HrDashboardController::class,'attendanceDateAjax'])->name('hr-attendance-date-ajax');
        Route::get('hr-team-show/{id}',[TeamController::class,'show'])->name('hr-team.show');
        Route::get('hr-daily-attendance', [AttendanceController::class, 'dailyAttendance'])->name('hr-daily-attendance');

        Route::get('hr-all-logs',[UserLogController::class,'index'])->name('hr-all-logs');
    });
    Route::get('get-break-time', [EmployeeController::class, 'breakTime'])->name('break_time_count');
    Route::get('daily-attendance-ajax', [AttendanceController::class, 'dailyAttendanceAjax'])->name('daily-attendance-ajax');
});
Route::group(['prefix' => 'confirm'], function(){
    Route::middleware(['auth','role:Confirmation Department','autoLogout'])->group(function () {
        Route::get('confirm-dashboard',[ConfirmController::class,'index'])->name('confirm-dashboard');
        Route::get('chart-dashboard-ajax',[ConfirmController::class,'chartAjax'])->name('chart-dashboard-ajax');
        Route::get('order-dashboard-ajax',[ConfirmController::class,'orderAjax'])->name('order-dashboard-ajax');
        Route::get('confirm-winner-ajax-dashboard',[DashboardController::class,'winnerAjax'])->name('confirm-winner-ajax-dashboard');
        Route::get('confirm-all-order-list',[ConfirmController::class,'pendingOrderList'])->name('confirm-all-order-list');
        Route::get('confirm-all-order-ajax',[ConfirmController::class,'pendingOrderAjax'])->name('confirm-all-order-ajax');
        Route::get('confirm-order-list',[ConfirmController::class,'confirmOrderList'])->name('confirm-order-list');
        Route::get('confirm-order-ajax',[ConfirmController::class,'confirmOrderAjax'])->name('confirm-order-ajax');
        Route::get('assign-driver',[ConfirmController::class,'assignDriver'])->name('assign-driver');
        Route::get('confirm-vip-customer-list', [EmployeeController::class, 'vipCustomerList'])->name('confirm-vip-customer-list');
        Route::get('top-five-confirm-order', [ConfirmController::class, 'topFiveConfirmOrder'])->name('top-five-confirm-order');
        Route::get('confirm-team-show/{id}',[TeamController::class,'show'])->name('confirm-team.show');
        Route::resource('confirm-leave',EmployeeLeaveController::class);
        Route::get('confirm-leave-ajax',[EmployeeLeaveController::class,'ajaxList'])->name('confirm-leave-ajax');
        Route::get('confirm-leave-export',[EmployeeLeaveController::class,'exportCSV'])->name('confirm-leave-export');
        
        Route::resource('confirm-ticket',TicketController::class);
        Route::get('confirm-ticket-ajax',[TicketController::class,'ajaxList'])->name('confirm-ticket-ajax');
        Route::get('confirm-ticket-ajax-export',[TicketController::class,'exportCSV'])->name('confirm-ticket-ajax-export');
        Route::post('confirm-ticket-comment',[TicketController::class,'ticketComment'])->name('confirm-ticket-comment');
        
        Route::get('confirm-holiday',[EmployeeHolidayController::class,'index'])->name('confirm-holiday');
        Route::get('confirm-holiday-ajax',[EmployeeHolidayController::class,'ajaxList'])->name('confirm-holiday-ajax');
        Route::get('confirm-holiday-export',[EmployeeHolidayController::class,'exportCSV'])->name('confirm-holiday-export');
        Route::get('confirm-all-logs',[UserLogController::class,'index'])->name('confirm-all-logs');
        
        Route::get('confirm-pending-order-item',[ConfirmationDepartmentController::class,'getAllPendingOrderItem'])->name('confirm-pending-order-item');
        Route::get('confirm-generate-pending-order-item',[ConfirmationDepartmentController::class,'generatePendingOrderItemPDF'])->name('confirm-generate-pending-order-item');

        // Route::get('confirmation-order-list',[ConfirmationDepartmentController::class,'allConfirmOrderList'])->name('confirmation-order-list');
        Route::get('confirmation-order-ajax',[ConfirmationDepartmentController::class,'allConfirmOrderAjax'])->name('confirmation-order-ajax');
        Route::get('confirmation-order-list',[OrderController::class,'confirmOrderQuery'])->name('confirmation-order-list');

        Route::get('confirm-product',[EmployeeCategoryController::class,'product'])->name('confirm-product');
        Route::get('confirm-product-ajax',[EmployeeCategoryController::class,'productAjaxList'])->name('confirm-product-ajax');
        Route::get('confirm-product-export',[EmployeeCategoryController::class,'exportProductCSV'])->name('confirm-product-export');
        Route::get('confirm-product-view/{id}',[EmployeeCategoryController::class,'productView'])->name('confirm-product-view');

        Route::get('show-winner-list',[ConfirmController::class,'winnerList'])->name('show-winner-list');
        Route::get('show-winner-ajax',[ConfirmController::class,'winnerListAjax'])->name('show-winner-ajax');
        Route::get('show-winner-export',[ConfirmController::class,'winnerListExport'])->name('show-winner-export');
        Route::get('confirm-on-delivery-order',[OrderController::class,'onDeliverOrder'])->name('confirm-on-delivery-order');
        Route::get('confirm-transport-ajax',[ConfirmationDepartmentController::class,'divertTransportajaxList'])->name('confirm-transport-ajax');
        Route::get('confirm-order-item',[ConfirmController::class,'confirmOrderItem'])->name('confirm-order-item');
        Route::get('confirm-generate-confirm-order-item',[ConfirmController::class,'generateConfirmOrderItemPDF'])->name('confirm-generate-confirm-order-item');
    });

    // Orders
    Route::get('top-five-confirm-order-ajax', [ConfirmController::class, 'topFiveConfirmOrderAjax'])->name('top-five-confirm-order-ajax');
        Route::resource('confirm-orders',OrderController::class);
        Route::get('get-subdistricts',[OrderController::class,'getSubDistrict'])->name('get-subdistricts');
        Route::get('get-villages',[OrderController::class,'getVillage'])->name('get-villages');
        Route::get('get-category',[OrderController::class,'getAllCategory'])->name('get-category');
        Route::get('get-product-list',[OrderController::class,'getProductByCategory'])->name('get-product-list');
        Route::get('get-product-variant-list',[OrderController::class,'getProductVariantDetail'])->name('get-product-variant-list');
        Route::get('get_variant',[OrderController::class,'getVariantDetailById'])->name('get_variant');
        Route::get('orders-ajax',[OrderController::class,'ajaxList'])->name('orders-ajax');
        Route::get('orders-list',[OrderController::class,'ordersList'])->name('orders-list');
        Route::get('orders-export',[OrderController::class,'exportFile'])->name('orders-export');
        Route::get('confirm-order',[OrderController::class,'confirmOrder'])->name('confirm-order');
        Route::get('confirm-order-list',[OrderController::class,'confirmAjaxList'])->name('confirm-order-list');
        Route::get('cancel-order',[OrderController::class,'cancelOrder'])->name('cancel-order');
        Route::get('cancel-order-list',[OrderController::class,'cancelAjaxList'])->name('cancel-order-list');
        Route::get('deliver-order-list',[OrderController::class,'deliveredAjaxList'])->name('deliver-order-list');
        Route::get('deliver-order',[OrderController::class,'deliverOrder'])->name('deliver-order');
        Route::get('confirm-order-export',[OrderController::class,'confirmExportCSV'])->name('confirm-order-export');
        Route::get('confirm-order-invoice-pdf',[OrderController::class,'generateOrderInvoice'])->name('confirm-order-invoice-pdf');
        Route::get('confirm-manual-order',[ConfirmationDepartmentController::class,'index'])->name('confirm-manual-order');
        Route::get('manual-orders-ajax',[ConfirmationDepartmentController::class,'ajaxList'])->name('manual-orders-ajax');
        Route::post('manual-orders-confirm',[ConfirmationDepartmentController::class,'confirmOrderRequest'])->name('confirm-department-manual-orders-confirm');
        Route::post('manual-orders-cancel',[ConfirmationDepartmentController::class,'cancelOrderRequest'])->name('confirm-department-manual-orders-cancel');
        Route::get('confirm-return-order',[ConfirmationDepartmentController::class,'returnOrder'])->name('confirm-return-order');
        Route::get('return-order-ajax',[ConfirmationDepartmentController::class,'returnajaxList'])->name('return-order-ajax');
        Route::get('ajax-logs',[UserLogController::class,'ajaxList'])->name('ajax-logs');
        Route::get('generate-invoice-pdf/{id}',[OrderController::class,'generateSingleOrderInvoice'])->name('generate-invoice-pdf');
        Route::get('vip-customer-ajax', [EmployeeController::class, 'vipCustomerAjaxList'])->name('vip-customer-ajax');
        Route::get('team-list-ajax',[TeamController::class,'teamAjaxList'])->name('team-list-ajax');
        Route::get('team-employee-list',[TeamController::class,'employeeList'])->name('team-employee-list');
        Route::post('update-team-employee',[TeamController::class,'updateTeamEmployee'])->name('update-team-employee');
        Route::get('team-view-ajax',[TeamController::class,'viewTeamEmployeeAjax'])->name('team-view-ajax');
        Route::get('remove-team-member',[TeamController::class,'removeTeamMember'])->name('remove-team-member');
        Route::get('vip-customer-export', [EmployeeController::class, 'vipCustomerExport'])->name('vip-customer-export');
        Route::get('team-export',[TeamController::class,'exportCSV'])->name('team-export');
        Route::get('manual-orders-export',[ConfirmationDepartmentController::class,'exportFile'])->name('manual-orders-export');
});
Route::group(['prefix' => 'driver'], function(){
    Route::middleware(['auth','role:driver','autoLogout'])->group(function () {
        Route::get('driver-dashboard',[DriverController::class,'index'])->name('driver-dashboard');
        Route::get('delivery-order-list',[DriverController::class,'deliveryOrderList'])->name('delivery-order-list');
        Route::get('order-status-update',[DriverController::class,'statusUpdate'])->name('order-status-update');
        Route::get('driver-team-show/{id}',[TeamController::class,'show'])->name('driver-team.show');
    });
});

Route::group(['prefix' => 'system-engineer'], function(){
    Route::middleware(['auth','role:System Engineer','autoLogout'])->group(function () {
        Route::get('system-engineer-dashboard',[RoleSystemEngineerController::class,'index'])->name('system-engineer-dashboard');
        Route::get('ticket-count',[RoleSystemEngineerController::class,'ticketCountAjax'])->name('ticket-count');
        Route::get('engineer-ticket/{id?}',[RoleSystemEngineerController::class,'ticketList'])->name('engineer-ticket');
        Route::get('engineer-ticket-ajax',[RoleSystemEngineerController::class,'ajaxList'])->name('engineer-ticket-ajax');
        Route::get('engineer-ticket-view/{id}',[RoleSystemEngineerController::class,'view'])->name('engineer-ticket-view');
        Route::post('ticket-comment-store',[RoleSystemEngineerController::class,'addCommentTicket'])->name('ticket-comment-store');
        Route::get('system-code',[RoleSystemEngineerController::class,'systemList'])->name('engineer-system-code');
        Route::get('system-code-ajax',[RoleSystemEngineerController::class,'systemAjaxList'])->name('engineer-system-code-ajax');
        Route::resource('ticket',TicketController::class);
        Route::get('system-team-show/{id}',[TeamController::class,'show'])->name('system-team.show');
        Route::get('system-all-logs',[UserLogController::class,'index'])->name('system-all-logs');
    });
});

Route::group(['prefix' => 'transport'], function(){
    Route::middleware(['auth','role:Transport Department','autoLogout'])->group(function () {
        Route::get('transport-department-dashboard',[TransportDepartmentController::class,'index'])->name('transport-department-dashboard');
        Route::get('transport-dashboard-ajax',[TransportDepartmentController::class,'transportAjax'])->name('transport-dashboard-ajax');
        Route::get('batch-view/{id}',[TransportDepartmentController::class,'batchView'])->name('batch-view');
        Route::get('transport-team-show/{id}',[TeamController::class,'show'])->name('transport-team.show');

        Route::resource('transport-leave',EmployeeLeaveController::class);
        Route::get('transport-leave-ajax',[EmployeeLeaveController::class,'ajaxList'])->name('transport-leave-ajax');
        Route::get('transport-leave-export',[EmployeeLeaveController::class,'exportCSV'])->name('transport-leave-export');

        Route::resource('transport-ticket',TicketController::class);
        Route::get('transport-ticket-ajax',[TicketController::class,'ajaxList'])->name('transport-ticket-ajax');
        Route::get('transport-ticket-ajax-export',[TicketController::class,'exportCSV'])->name('transport-ticket-ajax-export');
        Route::post('transport-ticket-comment',[TicketController::class,'ticketComment'])->name('transport-ticket-comment');

        Route::get('transport-holiday',[EmployeeHolidayController::class,'index'])->name('transport-holiday');
        Route::get('transport-holiday-ajax',[EmployeeHolidayController::class,'ajaxList'])->name('transport-holiday-ajax');
        Route::get('transport-holiday-export',[EmployeeHolidayController::class,'exportCSV'])->name('transport-holiday-export');
        Route::get('transport-delivery-order',[DriverController::class,'deliveryOrderList'])->name('transport-delivery-order');
        Route::get('transport-deliver-order',[OrderController::class,'deliverOrder'])->name('transport--deliver-order');

        Route::get('transport-all-logs',[UserLogController::class,'index'])->name('transport-all-logs');

        Route::get('transport-order-list',[OrderController::class,'confirmOrderQuery'])->name('transport-order-list');
    });
    Route::get('deliver-order-list',[OrderController::class,'deliveredAjaxList'])->name('deliver-order-list');
    Route::get('delivered-order-ajax',[AfterSaleServiceController::class,'orderAjaxList'])->name('delivered-order-ajax');
    Route::get('delivery-order-ajax',[DriverController::class,'ajaxList'])->name('delivery-order-ajax');
    Route::get('transport-confirm-order',[TransportDepartmentController::class,'orderList'])->name('transport-confirm-order');
    Route::get('transport-confirm-view/{id}',[TransportDepartmentController::class,'show'])->name('transport-confirm-view');
    Route::get('transport-confirm-order-ajax',[TransportDepartmentController::class,'ajaxList'])->name('transport-confirm-order-ajax');      
    Route::get('village-detail',[TransportDepartmentController::class,'getVillageDetail'])->name('village-detail');  
    Route::post('bulk-assign-driver',[TransportDepartmentController::class,'bulkAssignDriver'])->name('bulk-assign-driver');  
    Route::get('batch-list',[TransportDepartmentController::class,'batchList'])->name('batch-list');  
    Route::get('batch-list-ajax',[TransportDepartmentController::class,'batchAjaxList'])->name('batch-list-ajax');  
    Route::get('batch-view-ajax',[TransportDepartmentController::class,'batchViewAjaxList'])->name('batch-view-ajax');  
    Route::get('batch-order-invoice-pdf',[TransportDepartmentController::class,'generateInvoice'])->name('batch-order-invoice-pdf');  
    Route::get('export-batch-order',[TransportDepartmentController::class,'exportBatchOrder'])->name('export-batch-order');  
    Route::get('scheme-detail-code',[SchemeController::class,'schemeDetail'])->name('scheme-detail-code');
    Route::get('scheme-detail-product',[SchemeController::class,'schemeDetailProduct'])->name('scheme-detail-product');
    Route::get('remove-member-batch',[TransportDepartmentController::class,'removeOrderBatch'])->name('remove-member-batch');
});

Route::group(['prefix' => 'warehouse'], function(){
    Route::middleware(['auth','role:Warehouse Manager','autoLogout'])->group(function () {
        Route::get('warehouse-dashboard',[WarehouseManagerController::class,'index'])->name('warehouse-dashboard');      
        Route::get('warehouse-stock-list',[WarehouseManagerController::class,'stockList'])->name('warehouse-stock-list');   
        // Route::get('update-batch',[WarehouseManagerController::class,'updateBatch'])->name('update-batch');  
        Route::get('product-view/{id}',[EmployeeCategoryController::class,'productView'])->name('product-view');
        Route::get('delivered-batch-list',[WarehouseManagerController::class,'deliveredBatchList'])->name('delivered-batch-list');  
        Route::get('delivered-batch-ajax',[WarehouseManagerController::class,'deliveredBatchAjaxList'])->name('delivered-batch-ajax');  
        Route::get('delivered-batch-export',[WarehouseManagerController::class,'deliveredBatchExport'])->name('delivered-batch-export');  
        Route::get('in-out-stock',[TransportDepartmentController::class,'stockList'])->name('in-out-stock');      
        Route::get('order-by-id',[TransportDepartmentController::class,'getOrderDetailById'])->name('order-by-id');      
        Route::get('detail-by-order-id',[TransportDepartmentController::class,'getDetailByOrderId'])->name('detail-by-order-id');      
        Route::post('store-stock-detail',[TransportDepartmentController::class,'storeStockDetail'])->name('store-stock-detail'); 
        Route::post('scan-stock-detail',[TransportDepartmentController::class,'scanStoreStockDetail'])->name('scan-stock-detail'); 
        Route::get('warehouse-team-show/{id}',[TeamController::class,'show'])->name('transport-team.show');

        Route::resource('warehouse-leave',EmployeeLeaveController::class);
        Route::get('warehouse-leave-ajax',[EmployeeLeaveController::class,'ajaxList'])->name('warehouse-leave-ajax');
        Route::get('warehouse-leave-export',[EmployeeLeaveController::class,'exportCSV'])->name('warehouse-leave-export');

        Route::resource('warehouse-ticket',TicketController::class);
        Route::get('warehouse-ticket-ajax',[TicketController::class,'ajaxList'])->name('warehouse-ticket-ajax');
        Route::get('warehouse-ticket-ajax-export',[TicketController::class,'exportCSV'])->name('warehouse-ticket-ajax-export');
        Route::post('warehouse-ticket-comment',[TicketController::class,'ticketComment'])->name('warehouse-ticket-comment');

        Route::get('warehouse-holiday',[EmployeeHolidayController::class,'index'])->name('warehouse-holiday');
        Route::get('warehouse-holiday-ajax',[EmployeeHolidayController::class,'ajaxList'])->name('warehouse-holiday-ajax');
        Route::get('warehouse-holiday-export',[EmployeeHolidayController::class,'exportCSV'])->name('warehouse-holiday-export');

        Route::get('warehouse-all-logs',[UserLogController::class,'index'])->name('warehouse-all-logs');
    });
    Route::get('in-out-stock-ajax',[TransportDepartmentController::class,'stockAjaxListList'])->name('in-out-stock-ajax');   
    Route::get('warehouse-stock-ajax',[WarehouseManagerController::class,'stockAjaxList'])->name('warehouse-stock-ajax');   
    Route::get('batch-list',[TransportDepartmentController::class,'batchList'])->name('batch-list');  
    Route::get('export-batch-list',[TransportDepartmentController::class,'exportCSV'])->name('export-batch-list');  
        Route::get('batch-list-ajax',[TransportDepartmentController::class,'batchAjaxList'])->name('batch-list-ajax');  
        Route::get('batch-view/{id}',[TransportDepartmentController::class,'batchView'])->name('batch-view');  
        Route::get('batch-view-ajax',[TransportDepartmentController::class,'batchViewAjaxList'])->name('batch-view-ajax');  
        Route::get('batch-order-invoice-pdf',[TransportDepartmentController::class,'generateInvoice'])->name('batch-order-invoice-pdf');
        Route::get('order-view/{id}',[SalesManagerController::class,'view'])->name('order-view'); 
        Route::get('single-batch-pdf',[TransportDepartmentController::class,'batchPDF'])->name('single-batch-pdf'); 
        Route::get('warehouse-stock-export',[WarehouseManagerController::class,'exportCSV'])->name('warehouse-stock-export');   
        Route::get('batch-detail',[WarehouseManagerController::class,'getBatchDetail'])->name('batch-detail');
        Route::get('update-batch',[WarehouseManagerController::class,'updateBatch'])->name('update-batch');  
    Route::post('update-batch',[WarehouseManagerController::class,'addOrderInBatch'])->name('update-batch');

});
Route::group(['prefix' => 'sales'], function(){
    Route::middleware(['auth','role:Sales Manager','autoLogout'])->group(function () {
        Route::get('sales-manager-dashboard',[SalesManagerController::class,'index'])->name('sales-manager-dashboard');
        Route::get('sales-manager-ajax',[SalesManagerController::class,'dashboardOrderAjax'])->name('sales-manager-ajax');
        Route::get('sales-stock-list',[SalesManagerController::class,'inOutStockList'])->name('sales-stock-list'); 
        Route::get('sales-stock-ajax',[SalesManagerController::class,'inOutStockAjax'])->name('sales-stock-ajax'); 
        Route::get('pending-order-list',[SalesManagerController::class,'orderList'])->name('pending-order-list');
        Route::get('pending-order-ajax',[SalesManagerController::class,'orderAjaxList'])->name('pending-order-ajax');
        Route::get('pending-order-view/{id}',[SalesManagerController::class,'view'])->name('pending-order-view');
        Route::resource('sale-orders',OrderController::class);
        Route::get('sale-manual-order',[ConfirmationDepartmentController::class,'index'])->name('sale-manual-order');
        Route::get('sale-confirm-order',[OrderController::class,'confirmOrder'])->name('sale-confirm-order');
        Route::get('sale-deliver-order',[OrderController::class,'deliverOrder'])->name('sale-deliver-order');
        Route::get('sale-return-order',[ConfirmationDepartmentController::class,'returnOrder'])->name('sale-return-order');
        Route::get('sale-cancel-order',[OrderController::class,'cancelOrder'])->name('sale-cancel-order');
        Route::get('sale-employee',[SalesManagerController::class,'employeeList'])->name('sale-employee');
        Route::get('sale-employee-ajax',[SalesManagerController::class,'employeeAjaxList'])->name('sale-employee-ajax');
        Route::get('sale-employee-view/{id}',[SalesManagerController::class,'show'])->name('sale-employee-view');
        Route::get('sale-winner-ajax-dashboard',[DashboardController::class,'winnerAjax'])->name('sale-winner-ajax-dashboard');
        Route::get('sale-manager-vip-customer-list', [EmployeeController::class, 'vipCustomerList'])->name('sale-manager-vip-customer-list');
        Route::resource('sale-manager-team', TeamController::class);

        Route::resource('sales-leave',EmployeeLeaveController::class);
        Route::get('sales-leave-ajax',[EmployeeLeaveController::class,'ajaxList'])->name('sales-leave-ajax');
        Route::get('sales-leave-export',[EmployeeLeaveController::class,'exportCSV'])->name('sales-leave-export');

        Route::resource('sales-ticket',TicketController::class);
        Route::get('sales-ticket-ajax',[TicketController::class,'ajaxList'])->name('sales-ticket-ajax');
        Route::get('sales-ticket-ajax-export',[TicketController::class,'exportCSV'])->name('sales-ticket-ajax-export');
        Route::post('sales-ticket-comment',[TicketController::class,'ticketComment'])->name('sales-ticket-comment');

        Route::get('sales-holiday',[EmployeeHolidayController::class,'index'])->name('sales-holiday');
        Route::get('sales-holiday-ajax',[EmployeeHolidayController::class,'ajaxList'])->name('sales-holiday-ajax');
        Route::get('sales-holiday-export',[EmployeeHolidayController::class,'exportCSV'])->name('sales-holiday-export');
        Route::get('sales-all-logs',[UserLogController::class,'index'])->name('sales-all-logs');

        Route::get('sale-on-delivery-order',[OrderController::class,'onDeliverOrder'])->name('sale-on-delivery-order');
        Route::get('manager-daily-attendance', [AttendanceController::class, 'dailyAttendance'])->name('manager-daily-attendance');

    });
});
Route::group(['prefix' => 'sales_service'], function(){
    Route::middleware(['auth','role:After Sales Service','autoLogout'])->group(function () {
        Route::get('sales-service-dashboard',[AfterSaleServiceController::class,'index'])->name('sales-service-dashboard');
        Route::get('sales-service-ajax',[AfterSaleServiceController::class,'dashboardAjax'])->name('sales-service-ajax');
        Route::get('delivered-order-list',[AfterSaleServiceController::class,'orderList'])->name('delivered-order-list');
        Route::get('sale-order-feedback-list',[AfterSaleServiceController::class,'feedbackList'])->name('sale-order-feedback-list');
        Route::get('sale-service-vip-customer-list', [EmployeeController::class, 'vipCustomerList'])->name('sale-service-vip-customer-list');
        Route::get('sales_service-team-show/{id}',[TeamController::class,'show'])->name('sales_service-team.show');

        Route::resource('sale-service-leave',EmployeeLeaveController::class);
        Route::get('sale-service-leave-ajax',[EmployeeLeaveController::class,'ajaxList'])->name('sale-service-leave-ajax');
        Route::get('sale-service-leave-export',[EmployeeLeaveController::class,'exportCSV'])->name('sale-service-leave-export');

        Route::resource('sale-service-ticket',TicketController::class);
        Route::get('sale-service-ticket-ajax',[TicketController::class,'ajaxList'])->name('sale-service-ticket-ajax');
        Route::get('sale-service-ticket-ajax-export',[TicketController::class,'exportCSV'])->name('sale-service-ticket-ajax-export');
        Route::post('sale-service-ticket-comment',[TicketController::class,'ticketComment'])->name('sale-service-ticket-comment');

        Route::get('sale-service-holiday',[EmployeeHolidayController::class,'index'])->name('sale-service-holiday');
        Route::get('sale-service-holiday-ajax',[EmployeeHolidayController::class,'ajaxList'])->name('sale-service-holiday-ajax');
        Route::get('sale-service-holiday-export',[EmployeeHolidayController::class,'exportCSV'])->name('sale-service-holiday-export');
        Route::get('sale-service-all-logs',[UserLogController::class,'index'])->name('sale-service-all-logs');

        Route::get('service-order-view/{id}',[SalesManagerController::class,'view'])->name('service-order-view');

        Route::resource('service-info-sheet', EmployeeInfoSheetController::class);;
        Route::get('service-info-ajax',[EmployeeInfoSheetController::class,'ajaxList'])->name('service-info-ajax');
        Route::get('service-info-export',[EmployeeInfoSheetController::class,'exportCSV'])->name('service-info-export');
    });
    Route::get('feedback-detail',[AfterSaleServiceController::class,'feedbackDetail'])->name('feedback-detail');
    Route::get('add-order-feedback',[AfterSaleServiceController::class,'storeFeedbackDetail'])->name('add-order-feedback');
    Route::get('order-feedback-ajax',[AfterSaleServiceController::class,'feedbackAjaxList'])->name('order-feedback-ajax');
    Route::get('confirm-order-query-ajax',[OrderController::class,'confirmOrderQueryAjax'])->name('confirm-order-query-ajax');
});

Route::get('mark-as-read', function(){
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
})->name('markasread');
Route::post('notification/mark-as-read', [NotificationController::class, 'readNotification'])->name('notification.mark_as_read');
Route::post('notification/mark_all_as_read', [NotificationController::class, 'readAllNotifications'])->name('notification.mark_all_as_read');
Route::get('send-notification', [NotificationController::class, 'sendOfferNotification']);

Route::any('{query}',
    function() {
        $route = "login";
        if(Auth()->user() !== null){
            $route = "dashboard";
            if(Auth()->user()->role_id == 2) {
                $route = "employee-dashboard";
            } else if(Auth()->user()->role_id == 3){
                $route = "hr-dashboard";
            } else if(Auth()->user()->role_id == 4){
                $route = "confirm-dashboard";
            } else if(Auth()->user()->role_id == 5){
                $route = "driver-dashboard";
            } else if(Auth()->user()->role_id == 6){
                $route = "system-engineer-dashboard";
            }  else if(Auth()->user()->role_id == 7){
                $route = "transport-department-dashboard";
            } else if(Auth()->user()->role_id == 8){
                $route = "warehouse-dashboard";
            } else if(Auth()->user()->role_id == 9){
                $route = "sales-manager-dashboard";
            } else if(Auth()->user()->role_id == 10){
                $route = "sales-service-dashboard";
            }
        }
        return redirect()->route($route); 
    })
->where('query', '.*');