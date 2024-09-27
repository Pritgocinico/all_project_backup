<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\client\ClientController;
use App\Http\Controllers\common\RecipientController;
use App\Http\Controllers\common\EmailSettingsController;
use App\Http\Controllers\common\BusinessController;
use App\Http\Controllers\common\ReportController;
use App\Http\Controllers\common\LogsController;
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
Route::post('webhook', [ClientController::class, 'webhook'])->name('webhook');
// Route::middleware(['cors'])->group(function () {
//     Route::get('{slug}', [ClientController::class, 'viewReview'])->name('view.review');
// });
Route::get('/', [AuthController::class, 'index']);
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/forget-password', [AuthController::class, 'forgetPassword'])->name('forget.password');
Route::post('/submit-forget-password', [AuthController::class, 'submitForgetPassword'])->name('submit.forget.password');
Route::post('/submit-reset-password', [AuthController::class, 'submitResetPassword'])->name('submit.reset.password');
Route::get('/reset-password/{token}/{email}', [AuthController::class, 'resetPassword'])->name('reset.password');

Route::post('admin-login', [AuthController::class, 'adminLogin'])->name('admin_login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('notification/mark-as-read', [NotificationController::class, 'readNotification'])->name('notification.mark_as_read');
Route::post('notification/mark_all_as_read', [NotificationController::class, 'readAllNotifications'])->name('notification.mark_all_as_read');
Route::get('send-notification', [NotificationController::class, 'sendOfferNotification']);
Route::get('/subscribe', [AuthController::class, 'subscription'])->name('subscription.plans');

Route::get('{slug}', [ClientController::class, 'viewReview'])->name('view.review');
Route::post('customer-feedback', [ClientController::class, 'customerFeedback'])->name('customer.feedback');
Route::get('landing-page-widget', [ClientController::class, 'landingPageWidgets'])->name('landing.page.widget');


Route::middleware('auth','role:admin')->group(function () {
    Route::get('admin/view-admin', [AdminController::class, 'viewAdmin'])->name('admin.view');
    Route::get('admin/edit-admin', [AdminController::class, 'editAdmin'])->name('admin.edit');
    Route::post('admin/update-admin', [AdminController::class, 'updateAdmin'])->name('admin.update.profile');
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    //Clients
    Route::get('admin/clients', [AdminController::class, 'clients'])->name('admin.clients');
    Route::get('admin/add-client', [AdminController::class, 'addClient'])->name('admin.add.client');
    Route::post('admin/add-client-data', [AdminController::class, 'addClientData'])->name('admin.add.client.data');
    Route::get('admin/edit-client/{id}', [AdminController::class, 'editClient'])->name('admin.edit.client');
    Route::get('admin/view-client/{id}', [AdminController::class, 'viewClient'])->name('admin.view.client');
    Route::post('admin/update-client', [AdminController::class, 'updateClient'])->name('admin.update.client');
    Route::get('delete/client/{id}',[AdminController::class,'deleteClient'])->name('delete.client');

    //Business
    Route::get('admin/business/{id?}', [BusinessController::class, 'business'])->name('admin.business');
    Route::get('admin/add-business', [BusinessController::class, 'addBusiness'])->name('admin.add.business');
    Route::post('admin/add-business-data', [BusinessController::class, 'addBusinessData'])->name('admin.add.business.data');
    Route::get('admin/edit-business/{id}', [BusinessController::class, 'editBusiness'])->name('admin.edit.business');
    Route::post('admin/update-business', [BusinessController::class, 'updateBusiness'])->name('admin.update.business');
    Route::get('delete/business/{id}',[BusinessController::class,'deleteBusiness'])->name('delete.business');
    Route::get('admin/business-request',[BusinessController::class,'businessRequest'])->name('admin.business-request');
    Route::get('admin/view-business/{id}', [BusinessController::class, 'viewBusiness'])->name('admin.view.business');
    Route::get('admin/business-detail/{id}', [BusinessController::class, 'businessDetail'])->name('admin.business.detail');


    Route::get('admin/setting-page', [AuthController::class, 'settingPage'])->name('setting.page');
    Route::post('setting-update', [AuthController::class, 'saveSetting'])->name('save_general_settings');       
    Route::get('admin/payment-detail', [AdminController::class, 'paymentDetail'])->name('admin.payment-detail');
    
    Route::get('admin/dashboard-count', [AdminController::class, 'dashboardCount'])->name('dashboard.count');

    Route::post('admin/store-plan', [AuthController::class, 'storePlan'])->name('store.plan');
    Route::get('admin/edit-plan', [AuthController::class, 'editPlan'])->name('edit.plan');
    Route::post('admin/update-plan', [AuthController::class, 'updatePlan'])->name('update.plan');
    Route::get('admin/delete-plan/{id}', [AuthController::class, 'deletePlan'])->name('delete.plan');


    // Notification
    Route::get('mark-as-read', function(){
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('markasread');

    // Logs
    Route::get('admin/logs', [LogsController::class, 'listLogs'])->name('admin.logs');

});

Route::middleware('auth','role:user')->group(function () {

    Route::get('client/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');

    Route::get('client/funnel', [ClientController::class, 'funnel'])->name('client.funnel');
    Route::post('client/submit/funnel',[ClientController::class,'submitFunnel'])->name('submit.funnel');

    Route::get('client/reviews', [ClientController::class, 'reviews'])->name('client.reviews');

    Route::get('client/recipients', [RecipientController::class, 'recipients'])->name('client.recipients');
    Route::post('request-review', [RecipientController::class, 'requestReview'])->name('request.review');
    Route::post('import-request', [RecipientController::class, 'importRequest'])->name('import.request');
    Route::get('reactive-recipient', [RecipientController::class, 'reactiveRecipient'])->name('reactive.recipient');
    Route::get('end-campaign', [RecipientController::class, 'endCampaign'])->name('end.campaign');

    Route::get('client/email-settings', [EmailSettingsController::class, 'emailSettings'])->name('client.email.settings');
    Route::get('client/add-email', [EmailSettingsController::class, 'addEmail'])->name('client.add.email');
    Route::post('admin/add-email-data', [EmailSettingsController::class, 'addEmailData'])->name('client.add.email.data');
    Route::get('admin/edit-email/{id}', [EmailSettingsController::class, 'editEmail'])->name('client.edit.email');
    Route::post('admin/update-email', [EmailSettingsController::class, 'updateEmail'])->name('client.edit.email.data');
    Route::get('delete/email/{id}',[EmailSettingsController::class,'deleteEmail'])->name('delete.email');

    // Reports
    Route::get('client/report/analytics', [ReportController::class, 'analyticReport'])->name('client.report.analytic');
    Route::get('client/report/generated', [ReportController::class, 'generatedReport'])->name('client.report.generated');
    
    Route::post('client/change/business',[ClientController::class,'changeBusiness'])->name('change.business');
    
    Route::get('delete/feedback/{id}',[ClientController::class,'deleteFeedback'])->name('delete.feedback');
    
    Route::get('client/widgets', [ClientController::class, 'widgets'])->name('client.widgets');
    Route::get('client/business', [ClientController::class, 'businessList'])->name('client.business');
    Route::get('client/business-request/{id}', [ClientController::class, 'businessRequest'])->name('client.business.request');
    
    Route::post('client/report/generate/create', [ReportController::class, 'generatedReportStore'])->name('client.report.generate.create');
    Route::get('client/report-export/{id}', [ReportController::class, 'exportReport'])->name('client.report-export');

    Route::get('client/report/schedule', [ReportController::class, 'scheduleReport'])->name('client.report.schedule');
    Route::post('update-email-template', [ReportController::class, 'updateEmailTemplate'])->name('update.email.template');
    Route::post('client/report/schedule-create', [ReportController::class, 'storeSchedule'])->name('client.report.schedule.create');
    Route::get('client/report/mail', [ReportController::class, 'sentTestMail'])->name('sent.test.email');
    Route::get('client/report/stop', [ReportController::class, 'scheduleReportStop'])->name('client.report.schedule.stop');
    Route::get('client/edit-business/{id}', [BusinessController::class, 'editBusiness'])->name('client.edit.business');
    Route::post('client/update-business', [BusinessController::class, 'updateBusiness'])->name('client.update.business');

    Route::get('client/view-client', [ClientController::class, 'viewClient'])->name('client.view');
    Route::get('client/edit-client', [ClientController::class, 'editClient'])->name('edit.client');
    Route::post('client/update-client', [ClientController::class, 'updateClient'])->name('update.client');

    Route::get('client/transaction-detail',[ClientController::class,'transactionDetail'])->name('client.transaction.page');
});
Route::get('404',[ClientController::class,'errorPage'])->name('error_404');