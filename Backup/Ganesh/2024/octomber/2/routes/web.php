<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\common\ProjectController;
use App\Http\Controllers\common\TaskManagementController;
use App\Http\Controllers\common\MeasurementController;
use App\Http\Controllers\common\MaterialController;
use App\Http\Controllers\common\QuotationController;
use App\Http\Controllers\common\WorkshopController;
use App\Http\Controllers\common\FittingController;
use App\Http\Controllers\common\LeadController;
use App\Http\Controllers\common\CustomerController;
use App\Http\Controllers\common\ConvertProjectController;
use App\Http\Controllers\common\FeedbackController;
use App\Http\Controllers\common\LogsController;
use App\Http\Controllers\common\ProjectQuestionController;
use App\Http\Controllers\Header\QualityAnalyticController;
use App\Http\Controllers\common\ReportsController;
use App\Http\Controllers\Header\AdminUserController;
use App\Http\Controllers\Header\HeaderMeasurementController;
use App\Http\Controllers\Header\HeaderQuotationController;
use App\Http\Controllers\Header\HeaderWorkshopController;
use App\Http\Controllers\Header\HeaderFittingController;
use App\Http\Controllers\Header\PurchaseController;

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
Route::get('/', [AuthController::class, 'index']);
Route::get('/admin', [AuthController::class, 'index'])->name('admin');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/feedback/{project_id}', [AuthController::class, 'feedbackForm'])->name('feedbackForm');
Route::get('/view-project/{project_id}', [AuthController::class, 'projectView'])->name('projectView');
Route::get('/terms-and-conditions', [AuthController::class, 'TermsNConditions'])->name('TermsNConditions');
Route::get('/privacy-policy', [AuthController::class, 'PrivacyPolicies'])->name('PrivacyPolicies');
Route::get('/contact-us', [AuthController::class, 'contactUs'])->name('contact-us');
Route::post('/store-contact-us', [AuthController::class, 'storeContactUs'])->name('store-contact-us');
Route::post('/feedback-store', [AuthController::class, 'feedbackStore'])->name('feedbackStore');
Route::post('admin-login', [AuthController::class, 'adminLogin'])->name('admin_login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('notification/mark-as-read', [NotificationController::class, 'readNotification'])->name('notification.mark_as_read');
Route::post('notification/mark_all_as_read', [NotificationController::class, 'readAllNotifications'])->name('notification.mark_all_as_read');
Route::get('send-notification', [NotificationController::class, 'sendOfferNotification']);

Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/dashboard-count', [AdminController::class, 'dashboardCount'])->name('admin.dashboard.count');

    // Setting
    Route::get('admin/settings', [SettingController::class, 'settings'])->name('settings');
    Route::get('admin/general-settings', [SettingController::class, 'settings'])->name('general.setting');
    Route::post('admin/save-general-setting', [SettingController::class, 'save_general_setting'])->name('save_general_settings');
    Route::get('admin/company-settings', [SettingController::class, 'company_settings'])->name('company.setting');
    Route::post('admin/save-company-setting', [SettingController::class, 'save_company_setting'])->name('save_company_settings');
    Route::get('admin/email-settings', [SettingController::class, 'email_settings'])->name('email.setting');
    Route::post('admin/save-email-setting', [SettingController::class, 'save_email_setting'])->name('save_email_settings');
    Route::get('admin/terms-and-condition', [SettingController::class, 'termsANDcondition'])->name('termsANDcondition');
    Route::post('admin/save-terms-and-condition', [SettingController::class, 'save_termsANDcondition'])->name('save_termsANDcondition');
    Route::get('admin/privacy-policies', [SettingController::class, 'privacyPolicies'])->name('privacyPolicies');
    Route::post('admin/save-privacy-policies', [SettingController::class, 'save_privacyPolicies'])->name('save_privacyPolicies');

    // Profile
    Route::get('admin/profile/edit-profile', [AdminController::class, 'edit_profile'])->name('edit.profile');
    Route::post('admin/save-profile', [AdminController::class, 'save_profile'])->name('save.profile');
    Route::get('admin/profile/view-profile', [AdminController::class, 'view_profile'])->name('view.profile');

    Route::post('change-password', [AdminController::class, 'change_password'])->name('admin.change.password');

    // Projects
    Route::get('admin/projects', [ProjectController::class, 'projects'])->name('projects');
    Route::match (['get', 'post'], 'admin/add-projects', [ProjectController::class, 'addproject'])->name('addprojects');
    Route::get('/get-cities/{state_id}', [ProjectController::class, 'getCities'])->name('get.cities');
    Route::post('admin/store-project-data', [ProjectController::class, 'store_project_data'])->name('storeproject');
    Route::get('admin/get-customer', [ProjectController::class, 'getCustomer'])->name('getCustomer');
    Route::get('admin/delete-project/{id}', [ProjectController::class, 'deleteproject'])->name('delete.project');
    Route::get('admin/edit-project/{id}', [ProjectController::class, 'editproject'])->name('edit.project');
    Route::post('admin/update-project/{id}', [ProjectController::class, 'updateproject'])->name('update.project');
    Route::get('admin/view-project/{id}', [ProjectController::class, 'viewproject'])->name('view.project');
    Route::get('admin/view-progressbar-project/{id}', [ProjectController::class, 'viewProgressBarproject'])->name('view.progressbar.project');
    Route::get('admin/view-completed-project/{id}', [ProjectController::class, 'viewCompletedproject'])->name('view.completed.project');
    Route::get('admin/view-lead-project/{id}', [ProjectController::class, 'viewCompletedproject'])->name('view.lead.project');
    Route::get('admin/view-complete/{id}', [ProjectController::class, 'viewcomplete'])->name('view.complete');
    Route::get('admin/view-measurement/{id}', [MeasurementController::class, 'viewmeasurement'])->name('view.measurement');
    Route::post('admin/edit-measurement/{id}', [MeasurementController::class, 'editMeasurement'])->name('edit.measurement');
    Route::post('admin/store-measurement-data', [MeasurementController::class, 'view_store_measurement'])->name('storemeasurement');
    Route::get('admin/delete-measurement/{id}', [MeasurementController::class, 'deletemeasurement'])->name('delete.measurement');
    Route::get('admin/delete-measurement_pic/{id}', [MeasurementController::class, 'deletemeasurementPic'])->name('delete.measurement_pic');
    Route::get('admin/view-material/{id}', [MaterialController::class, 'viewMaterial'])->name('view.material');
    Route::post('admin/store-material-data', [MaterialController::class, 'storeMaterial'])->name('store.project.materials');
    Route::get('admin/delete-material/{id}', [MaterialController::class, 'deleteMaterial'])->name('delete.material');
    Route::get('admin/delete-purchase/{id}', [MaterialController::class, 'deletePurchase'])->name('delete.purchase');
    Route::get('admin/viewquotation/{id}', [QuotationController::class, 'viewquotation'])->name('view.quotation');
    Route::post('admin/storequotation-data', [QuotationController::class, 'view_store_quotation'])->name('store.quotations');
    Route::get('admin/delete-quotation/{id}', [QuotationController::class, 'deletequotation'])->name('delete.quotation');
    Route::get('admin/finalize-quotation/{id}', [QuotationController::class, 'finalizeQuotation'])->name('finalize.quotation');
    Route::post('admin/reject-quotation', [QuotationController::class, 'rejectQuotation'])->name('admin.reject.quotation');
    Route::get('admin/view-workshop/{id}', [WorkshopController::class, 'viewworkshop'])->name('view.workshop');
    Route::post('admin/store-workshop-data', [WorkshopController::class, 'view_store_workshop'])->name('storeworkshop');
    Route::post('admin/store-workshop-question', [WorkshopController::class, 'store_workshop_question'])->name('storeworkshopquestion');
    Route::get('admin/delete-workshop/{id}', [WorkshopController::class, 'deleteworkshop'])->name('delete.workshop');
    Route::get('admin/view-fitting/{id}', [FittingController::class, 'viewfitting'])->name('view.fitting');
    Route::post('admin/store-fitting-question', [FittingController::class, 'store_fitting_question'])->name('storefittingquestion');
    Route::post('admin/store-fitting-data', [FittingController::class, 'view_store_fitting'])->name('storefitting');
    Route::post('admin/store-fitting-complete-data', [FittingController::class, 'model_store_fitting'])->name('modelstorefitting');
    Route::get('admin/delete-fitting/{id}', [FittingController::class, 'deletefitting_image'])->name('delete.fittingimage');
    Route::get('/convert-to-lead/{projectId}', [ProjectController::class, 'convertToLead'])->name('convert.to.lead');
    Route::post('admin/update-material-status', [ProjectController::class, 'updateMaterialStatus'])->name('update_material_status');
    Route::post('admin/update-cutting_option-status', [ProjectController::class, 'updateCutting'])->name('updateCutting');
    Route::post('admin/update-shutter-status', [ProjectController::class, 'updateShutter'])->name('updateShutter');
    Route::post('admin/update-glass-measurement', [ProjectController::class, 'updateGlassmeasure'])->name('updateGlassmeasure');
    Route::post('admin/update-glass-receive', [ProjectController::class, 'updateGlassReceive'])->name('updateGlassReceive');
    Route::post('admin/update-shutter-ready', [ProjectController::class, 'updateShutterReady'])->name('updateShutterReady');
    Route::get('admin/view-selection/{id}', [QuotationController::class, 'viewSelection'])->name('view.selection');
    Route::post('admin/store-selection', [QuotationController::class, 'storeSelection'])->name('store.selection');
    Route::post('admin/store-project-cost', [ProjectController::class, 'storeProjectCost'])->name('store.project.cost');
    Route::post('admin/done-project', [ProjectController::class, 'projectDone'])->name('projectDone');
    Route::post('admin/store-material-status', [WorkshopController::class, 'storeMaterialStatus'])->name('storeMaterialStatus');
    Route::post('admin/store-invoice-status', [WorkshopController::class, 'storeInvoiceStatus'])->name('storeInvoiceStatus');
    Route::get('admin/delete-invoice/{id}', [WorkshopController::class, 'deleteInvoice'])->name('delete.invoice');
    Route::get('admin/delete-partial/{id}', [WorkshopController::class, 'deletePartial'])->name('delete.partial');
    Route::get('admin/qa-question/{id}', [ProjectController::class, 'qaQuestionList'])->name('view.qa.question');
    Route::post('admin/store-qa-question', [ProjectQuestionController::class, 'store_qa_question'])->name('store_qa_question');
    Route::post('admin/store-qa-data', [ProjectQuestionController::class, 'view_store_qa'])->name('store_qa_data');

    // Task Management
    Route::get('admin/task-management', [TaskManagementController::class, 'taskmanagement'])->name('task-management');
    Route::get('admin/add-task', [TaskManagementController::class, 'addTask'])->name('addTask');
    Route::post('admin/store-task', [TaskManagementController::class, 'storetask'])->name('storetask');
    Route::get('admin/edit-task/{id}', [TaskManagementController::class, 'editTask'])->name('editTask');
    Route::post('admin/update-task/{id}', [TaskManagementController::class, 'updateTask'])->name('updateTask');
    Route::get('admin/delete-task/{id}', [TaskManagementController::class, 'deleteTask'])->name('deleteTask');

    // Users
    Route::get('admin/users', [UserController::class, 'users'])->name('admin.users');
    Route::get('admin/add-user', [UserController::class, 'addUser'])->name('admin.add_user');
    Route::post('admin/add-user-data/{id?}', [UserController::class, 'addUserData'])->name('admin.add.user.data');
    Route::get('admin/edit-user/{id}', [UserController::class, 'editUser'])->name('admin.edit.user');
    Route::post('admin/update-user/{id}', [UserController::class, 'updateUser'])->name('admin.update.user');
    Route::get('get-user', [UserController::class, 'getUser'])->name('get_user');
    Route::get('admin/delete-user/{id}', [UserController::class, 'deleteuser'])->name('delete.user');

    // Customer
    Route::get('admin/customers', [CustomerController::class, 'customers'])->name('admin.customers');
    Route::get('admin/add-customer', [CustomerController::class, 'addCustomer'])->name('admin.add_customer');
    Route::post('admin/add-customer-data/{id?}', [CustomerController::class, 'addCustomerData'])->name('admin.add.customer.data');
    Route::get('admin/edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('admin.edit.customer');
    Route::post('admin/update-customer/{id}', [CustomerController::class, 'updateCustomer'])->name('admin.update.customer');
    Route::get('get-customer', [CustomerController::class, 'getCustomer'])->name('get_customer');
    Route::get('admin/get-customer', [CustomerController::class, 'getCustomer'])->name('admin.get_customer');
    Route::get('admin/delete-customer/{id}', [CustomerController::class, 'deletecustomer'])->name('delete.customer');

    // Leads
    Route::get('admin/leads', [LeadController::class, 'leads'])->name('leads');
    Route::match (['get', 'post'], 'admin/add-leads', [LeadController::class, 'addleads'])->name('addleads');
    Route::post('admin/store-leads-data', [LeadController::class, 'store_leads_data'])->name('storeleads');
    Route::get('admin/delete-leads/{id}', [LeadController::class, 'deleteleads'])->name('delete.leads');
    Route::get('admin/edit-leads/{id}', [LeadController::class, 'editleads'])->name('edit.leads');
    Route::post('admin/update-leads/{id}', [LeadController::class, 'updateleads'])->name('update.leads');
    Route::get('/convert-to-project/{lead}', [LeadController::class, 'convertToProject'])->name('convert.to.project');
    Route::get('admin/view-lead/{id}', [ProjectController::class, 'viewProject'])->name('view.lead');
    Route::get('admin/generate-project-report/{id}', [ProjectController::class, 'generateReport'])->name('generate.project.report');

    Route::get('/lead/{id}', [LeadController::class, 'showLeadDetails'])->name('lead.details');


    // admin user

    Route::get('/admin/add', [AdminUserController::class, 'showAddAdminForm'])->name('admin.add.admin');
    Route::post('/admin/add/admin/data', [AdminUserController::class, 'addAdmin'])->name('admin.add.admin.data');
    Route::get('/admin/admin/list', [AdminUserController::class, 'showAdminList'])->name('admin.admin.list');
    Route::get('/admin/edit/{id}', [AdminUserController::class, 'showEditAdminForm'])->name('admin.edit.admin');
    Route::put('/admin/update/{id}', [AdminUserController::class, 'updateAdmin'])->name('admin.update.admin');
    Route::get('admin/delete-admin/{id}', [AdminUserController::class, 'deleteAdmin'])->name('delete.admin');
    Route::get('get-admin', [AdminUserController::class, 'getAdmin'])->name('get_admin');

    // measurement

    Route::get('/admin/add/measurement', [HeaderMeasurementController::class, 'create'])->name('admin.add.measurement');
    Route::post('/admin/insert/measurement', [HeaderMeasurementController::class, 'insertMeasurement'])->name('admin.insert.measurement');
    Route::get('edit/measurement/{id}', [HeaderMeasurementController::class, 'editMeasurement'])->name('admin.edit.measurement');
    Route::put('update/measurement/{id}', [HeaderMeasurementController::class, 'updateMeasurement'])->name('admin.update.measurement');
    Route::get('admin/delete-measurement-user/{id}', [HeaderMeasurementController::class, 'deleteMeasurementUser'])->name('delete.measurement.user');
    Route::get('get-measurement', [HeaderMeasurementController::class, 'getMeasurement'])->name('get_measurement');
    // Quotation


    Route::get('quotation/create', [HeaderQuotationController::class, 'create'])->name('quotation.create');
    Route::post('quotation/store', [HeaderQuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotation/{id}/edit', [HeaderQuotationController::class, 'edit'])->name('quotation.edit');
    Route::put('/quotation/update/{id}', [HeaderQuotationController::class, 'update'])->name('quotation.update');
    Route::get('admin/delete-quotation-user/{id}', [HeaderQuotationController::class, 'deleteQuotationUser'])->name('delete.quotation.user');
    Route::get('get-quotation', [HeaderQuotationController::class, 'getQuotation'])->name('get_quotation');

    // Workshop

    Route::get('header/workshops/create', [HeaderWorkshopController::class, 'create'])->name('header.workshop.create');
    Route::post('header/workshops', [HeaderWorkshopController::class, 'store'])->name('header.workshop.store');
    Route::get('/workshops/{id}/edit', [HeaderWorkshopController::class, 'edit'])->name('workshops.edit');
    Route::put('/workshops/{id}', [HeaderWorkshopController::class, 'update'])->name('workshops.update');
    Route::get('admin/delete-workshop-user/{id}', [HeaderWorkshopController::class, 'deleteWorkshopUser'])->name('delete.workshop.user');
    Route::get('get-workshop-user', [HeaderWorkshopController::class, 'getWorkshopUser'])->name('get_workshop_user');

    // Fitting
    Route::get('/header/fitting/create', [HeaderFittingController::class, 'create'])->name('header.fitting.create');
    Route::post('/header/fitting/store', [HeaderFittingController::class, 'store'])->name('header.fitting.store');
    Route::get('/header/fitting/edit/{id}', [HeaderFittingController::class, 'edit'])->name('header.fitting.edit');
    Route::put('/header/fitting/update/{id}', [HeaderFittingController::class, 'update'])->name('header.fitting.update');
    Route::get('admin/delete-fitting-user/{id}', [HeaderFittingController::class, 'deleteFittingUser'])->name('delete.fitting.user');
    Route::get('get-fitting-user', [HeaderFittingController::class, 'getFittingUser'])->name('get_fitting_user');

    // Feedback
    Route::get('admin/feedbacks', [FeedbackController::class, 'feedBack'])->name('feedbacks');
    Route::get('admin/view-feedback/{id}', [FeedbackController::class, 'viewFeedback'])->name('viewFeedback');
    Route::get('admin/delete-feedback/{id}', [FeedbackController::class, 'deleteFeedback'])->name('deleteFeedback');
    // Route::get('admin/insert/feedback', [FeedbackController::class, 'submitFeedback'])->name('submitFeedback');

    // Reports
    Route::get('admin/reports', [ReportsController::class, 'getReport'])->name('reports');
    Route::get('admin/export', [ReportsController::class, 'exportFile'])->name('reports.export');
    Route::get('admin/pending-report', [ReportsController::class, 'pendingReport'])->name('pending.reports');
    Route::get('admin/pending-report-export', [ReportsController::class, 'pendingReportExport'])->name('pending.reports.export');

    // Logs
    Route::get('admin/logs', [LogsController::class, 'listLogs'])->name('logs');
    Route::get('admin/resume-work', [ProjectController::class, 'resumeWork'])->name('resume.work');
    Route::get('admin/resume-work-complete', [ProjectController::class, 'resumeWorkComplete'])->name('resume.work.complete');
    Route::get('admin/cancel-lead', [ProjectController::class, 'cancelLead'])->name('cancel-lead');

    // Purchase User
    Route::get('header/purchase/create',[PurchaseController::class,'create'])->name('header.purchase.create');
    Route::post('header/purchase/store',[PurchaseController::class,'storePurchaseUser'])->name('header.purchase.store');
    Route::get('/header/purchase/edit', [PurchaseController::class, 'edit'])->name('header.purchase.edit');
    Route::post('/header/purchase/update/{id}', [PurchaseController::class, 'update'])->name('header.purchase.update');
    Route::get('admin/delete-purchase-user/{id}', [PurchaseController::class, 'deletePurchaseUser'])->name('delete.purchase.user');

    Route::post('admin/material-received', [MaterialController::class, 'materialReceivedUpdate'])->name('update.material.received');

    Route::get('admin/additional-project/{id}', [ProjectController::class, 'addAdditionalProject'])->name('additional.project');
    Route::get('admin/convert-lead/{id}', [ProjectController::class, 'convertLead'])->name('convert.lead.project');

    Route::get('admin/user-verify/{id}', [AdminController::class, 'userVerify'])->name('verify.user');
    
    Route::get('admin/project-question', [ProjectQuestionController::class, 'index'])->name('admin_project_question');
    Route::Post('admin/add-project-question',[ProjectQuestionController::class,'store'])->name('admin.add.purchase');
    Route::Post('admin/update-project-question',[ProjectQuestionController::class,'update'])->name('admin.update.project.question');
    Route::get('admin/edit-project-question', [ProjectQuestionController::class, 'edit'])->name('admin.edit.project.question');
    Route::get('admin/delete-project-question/{id}', [ProjectQuestionController::class, 'destroy'])->name('admin.delete.project.question');

    Route::get('admin/quality-analytic', [QualityAnalyticController::class, 'index'])->name('admin.quality.analytic');
    Route::Post('admin/add-quality-analytic',[QualityAnalyticController::class,'store'])->name('admin.add.quality.analytic');
    Route::Post('admin/update-quality-analytic',[QualityAnalyticController::class,'update'])->name('admin.update.quality.analytic');
    Route::get('admin/edit-quality-analytic', [QualityAnalyticController::class, 'edit'])->name('admin.edit.quality.analytic');
    Route::get('admin/delete-quality-analytic/{id}', [QualityAnalyticController::class, 'destroy'])->name('admin.delete.quality.analytic');

    Route::post('admin/final-store-measurement-data', [MeasurementController::class, 'finalMeasurement'])->name('final-storemeasurement');
    // notification
    Route::get('mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('markasread');


    // Route::get('/convert-to-project/{lead}', 'LeadController@convertToProject')->name('convert.to.project');

});


Route::middleware('auth', 'role:quatation')->group(function () {
    Route::get('quotation/dashboard', [AdminController::class, 'dashboard'])->name('quotation.dashboard');

    // Setting
    Route::get('quotation/settings', [SettingController::class, 'settings'])->name('quotation_settings');
    Route::get('quotation/general-settings', [SettingController::class, 'settings'])->name('quotation_general.setting');
    Route::post('quotation/save-general-setting', [SettingController::class, 'save_general_setting'])->name('quotation_save_general_settings');
    Route::get('quotation/company-settings', [SettingController::class, 'company_settings'])->name('quotation_company.setting');
    Route::post('quotation/save-company-setting', [SettingController::class, 'save_company_setting'])->name('quotation_save_company_settings');
    Route::get('quotation/email-settings', [SettingController::class, 'email_settings'])->name('quotation_email.setting');
    Route::post('quotation/save-email-setting', [SettingController::class, 'save_email_setting'])->name('quotation_save_email_settings');

    // Profile
    Route::get('quotation/profile/edit-profile', [AdminController::class, 'edit_profile'])->name('quotation_edit.profile');
    Route::post('quotation/save-profile', [AdminController::class, 'save_profile'])->name('quotation_save.profile');
    Route::get('quotation/profile/view-profile', [AdminController::class, 'view_profile'])->name('quotation_view.profile');

    Route::post('quotation/change-password', [AdminController::class, 'change_password'])->name('quotation.change.password');

    // Projects
    Route::get('quotation/projects', [ProjectController::class, 'projects'])->name('quotation_projects');
    Route::match (['get', 'post'], 'quotation/add-projects', [ProjectController::class, 'addproject'])->name('quotation_addprojects');
    Route::get('quotation/get-cities/{state_id}', [ProjectController::class, 'getCities'])->name('quotation_get.cities');
    Route::post('quotation/store-project-data', [ProjectController::class, 'store_project_data'])->name('quotation_storeproject');
    Route::get('quotation/getcustomer', [ProjectController::class, 'getCustomer'])->name('quotation_getCustomer');
    Route::get('quotation/delete-project/{id}', [ProjectController::class, 'deleteproject'])->name('quotation_delete.project');
    Route::get('quotation/edit-project/{id}', [ProjectController::class, 'editproject'])->name('quotation_edit.project');
    Route::post('quotation/update-project/{id}', [ProjectController::class, 'updateproject'])->name('quotation_update.project');
    Route::get('quotation/view-project/{id}', [ProjectController::class, 'viewproject'])->name('quotation_view.project');
    Route::get('quotation/view-progressbar-project/{id}', [ProjectController::class, 'viewProgressBarproject'])->name('quotation_view.progressbar.project');
    Route::get('quotation/view-completed-project/{id}', [ProjectController::class, 'viewCompletedproject'])->name('quotation_view.completed.project');
    Route::get('quotation/view-lead-project/{id}', [ProjectController::class, 'viewCompletedproject'])->name('quotation_view.lead.project');
    Route::get('quotation/view-complete/{id}', [ProjectController::class, 'viewcomplete'])->name('quotation_view.complete');
    Route::get('quotation/view-measurement/{id}', [MeasurementController::class, 'viewmeasurement'])->name('quotation_view.measurement');
    Route::post('quotation/edit-measurement/{id}', [MeasurementController::class, 'editMeasurement'])->name('quotation_edit.measurement');
    Route::post('quotation/store-measurement-data', [MeasurementController::class, 'view_store_measurement'])->name('quotation_storemeasurement');
    Route::get('quotation/delete-measurement/{id}', [MeasurementController::class, 'deletemeasurement'])->name('quotation_delete.measurement');
    Route::get('quotation/view-material/{id}', [MaterialController::class, 'viewMaterial'])->name('quotation_view.material');
    Route::post('quotation/store-material-data', [MaterialController::class, 'storeMaterial'])->name('quotation_store.project.materials');
    Route::get('quotation/delete-material/{id}', [MaterialController::class, 'deleteMaterial'])->name('quotation_delete.material');
    Route::get('quotation/delete-purchase/{id}', [MaterialController::class, 'deletePurchase'])->name('quotation_delete.purchase');
    Route::get('quotation/delete-measurement_pic/{id}', [MeasurementController::class, 'deletemeasurementPic'])->name('quotation_delete.measurement_pic');
    Route::get('quotation/viewquotation/{id}', [QuotationController::class, 'viewquotation'])->name('quotation_view.quotation');
    Route::post('quotation/storequotation-data', [QuotationController::class, 'view_store_quotation'])->name('quotation_store.quotations');
    Route::get('quotation/delete-quotation/{id}', [QuotationController::class, 'deletequotation'])->name('quotation_delete.quotation');
    Route::get('quotation/finalize-quotation/{id}', [QuotationController::class, 'finalizeQuotation'])->name('quotation_finalize.quotation');
    Route::post('quotation/reject-quotation', [QuotationController::class, 'rejectQuotation'])->name('quotation.reject.quotation');
    Route::get('quotation/view-workshop/{id}', [WorkshopController::class, 'viewworkshop'])->name('quotation_view.workshop');
    Route::post('quotation/store-workshop-data', [WorkshopController::class, 'view_store_workshop'])->name('quotation_storeworkshop');
    Route::post('quotation/store-workshop-question', [WorkshopController::class, 'store_workshop_question'])->name('quotation_storeworkshopquestion');
    Route::get('quotation/delete-workshop/{id}', [WorkshopController::class, 'deleteworkshop'])->name('quotation_delete.workshop');
    Route::get('quotation/view-fitting/{id}', [FittingController::class, 'viewfitting'])->name('quotation_view.fitting');
    Route::post('quotation/store-fitting-question', [FittingController::class, 'store_fitting_question'])->name('quotation_storefittingquestion');
    Route::post('quotation/store-fitting-data', [FittingController::class, 'view_store_fitting'])->name('quotation_storefitting');
    Route::post('quotation/store-fitting-complete-data', [FittingController::class, 'model_store_fitting'])->name('quotation_modelstorefitting');
    Route::get('quotation/delete-fitting/{id}', [FittingController::class, 'deletefitting_image'])->name('quotation_delete.fittingimage');
    Route::get('quotation/convert-to-lead/{projectId}', [ProjectController::class, 'convertToLead'])->name('quotation_convert.to.lead');
    Route::post('quotation/update-material-status', [ProjectController::class, 'updateMaterialStatus'])->name('quotation_update_material_status');
    Route::post('quotation/update-cutting_option-status', [ProjectController::class, 'updateCutting'])->name('quotation_updateCutting');
    Route::post('quotation/update-shutter-status', [ProjectController::class, 'updateShutter'])->name('quotation_updateShutter');
    Route::post('quotation/update-glass-measurement', [ProjectController::class, 'updateGlassmeasure'])->name('quotation_updateGlassmeasure');
    Route::post('quotation/update-glass-receive', [ProjectController::class, 'updateGlassReceive'])->name('quotation_updateGlassReceive');
    Route::post('quotation/update-shutter-ready', [ProjectController::class, 'updateShutterReady'])->name('quotation_updateShutterReady');
    Route::get('quotation/view-selection/{id}', [QuotationController::class, 'viewSelection'])->name('quotation_view.selection');
    Route::post('quotation/store-selection', [QuotationController::class, 'storeSelection'])->name('quotation_store.selection');
    Route::post('quotation/store-project-cost', [ProjectController::class, 'storeProjectCost'])->name('quotation_store.project.cost');
    Route::post('quotation/done-project', [ProjectController::class, 'projectDone'])->name('quotation_projectDone');
    Route::post('quotation/store-material-status', [WorkshopController::class, 'storeMaterialStatus'])->name('quotation_storeMaterialStatus');
    Route::post('quotation/store-invoice-status', [WorkshopController::class, 'storeInvoiceStatus'])->name('quotation_storeInvoiceStatus');

    // Task Management
    Route::get('quotation/task-management', [TaskManagementController::class, 'taskmanagement'])->name('quotation_task-management');
    Route::get('quotation/add-task', [TaskManagementController::class, 'addTask'])->name('quotation_addTask');
    Route::post('quotation/store-task', [TaskManagementController::class, 'storetask'])->name('quotation_storetask');
    Route::get('quotation/edit-task/{id}', [TaskManagementController::class, 'editTask'])->name('quotation_editTask');
    Route::post('quotation/update-task/{id}', [TaskManagementController::class, 'updateTask'])->name('quotation_updateTask');
    Route::get('quotation/delete-task/{id}', [TaskManagementController::class, 'deleteTask'])->name('quotation_deleteTask');

    // // Users
    // Route::get('admin/users', [UserController::class, 'users'])->name('admin.users');
    // Route::get('admin/add-user', [UserController::class, 'addUser'])->name('admin.add_user');
    // Route::post('admin/add-user-data/{id?}', [UserController::class, 'addUserData'])->name('admin.add.user.data');
    // Route::get('admin/edit-user/{id}', [UserController::class, 'editUser'])->name('admin.edit.user');
    // Route::post('admin/update-user/{id}', [UserController::class, 'updateUser'])->name('admin.update.user');
    // Route::get('get-user', [UserController::class, 'getUser'])->name('get_user');
    // Route::get('admin/delete-user/{id}',[UserController::class,'deleteuser'])->name('delete.user');

    // // Customer
    Route::get('quotation/customers', [CustomerController::class, 'customers'])->name('quotation.customers');
    Route::get('quotation/add-customer', [CustomerController::class, 'addCustomer'])->name('quotation.add_customer');
    Route::post('quotation/add-customer-data/{id?}', [CustomerController::class, 'addCustomerData'])->name('quotation.add.customer.data');
    Route::get('quotation/edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('quotation.edit.customer');
    Route::post('quotation/update-customer/{id}', [CustomerController::class, 'updateCustomer'])->name('quotation.update.customer');
    Route::get('quotation/get-customer', [CustomerController::class, 'getCustomer'])->name('quotation_get_customer');
    Route::get('quotation/delete-customer/{id}', [CustomerController::class, 'deletecustomer'])->name('quotation_delete.customer');

    // Leads
    Route::get('quotation/leads', [LeadController::class, 'leads'])->name('quotation_leads');
    Route::match (['get', 'post'], 'quotation/add-leads', [LeadController::class, 'addleads'])->name('quotation_addleads');
    Route::post('quotation/store-leads-data', [LeadController::class, 'store_leads_data'])->name('quotation_storeleads');
    Route::get('quotation/delete-leads/{id}', [LeadController::class, 'deleteleads'])->name('quotation_delete.leads');
    Route::get('quotation/edit-leads/{id}', [LeadController::class, 'editleads'])->name('quotation_edit.leads');
    Route::post('quotation/update-leads/{id}', [LeadController::class, 'updateleads'])->name('quotation_update.leads');
    Route::get('quotation/convert-to-project/{lead}', [LeadController::class, 'convertToProject'])->name('quotation_convert.to.project');
    Route::get('quotation/view-lead/{id}', [ProjectController::class, 'viewProject'])->name('quotation_view.lead');

    Route::get('quotation/lead/{id}', [LeadController::class, 'showLeadDetails'])->name('quotation_lead.details');

    // Feedback
    Route::get('quotation/feedbacks', [FeedbackController::class, 'feedBack'])->name('quotation_feedbacks');
    Route::get('quotation/view-feedback/{id}', [FeedbackController::class, 'viewFeedback'])->name('quotation_viewFeedback');
    Route::get('quotation/delete-feedback/{id}', [FeedbackController::class, 'deleteFeedback'])->name('quotation_deleteFeedback');

    // Logs
    Route::get('quotation/logs', [LogsController::class, 'listLogs'])->name('quotation_logs');

    // Route::get('/convert-to-project/{lead}', 'LeadController@convertToProject')->name('convert.to.project');

    // Reports
    Route::get('quotation/reports', [ReportsController::class, 'getReport'])->name('quotation_reports');
    Route::get('quotation/export', [ReportsController::class, 'exportFile'])->name('quotation.reports.export');
    Route::get('quotation/pending-report', [ReportsController::class, 'pendingReport'])->name('quotation.pending.reports');
    Route::get('quotation/pending-report-export', [ReportsController::class, 'pendingReportExport'])->name('quotation.pending.reports.export');

    Route::get('quotation/resume-work', [ProjectController::class, 'resumeWork'])->name('quotation.resume.work');

    Route::get('quotation/cancel-lead', [ProjectController::class, 'cancelLead'])->name('quotation.cancel-lead');
    Route::post('quotation/material-received', [MaterialController::class, 'materialReceivedUpdate'])->name('quotation_update.material.received');
    Route::get('quotation/resume-work-complete', [ProjectController::class, 'resumeWorkComplete'])->name('quotation.resume.work.complete');
    Route::get('quotation/additional-project/{id}', [ProjectController::class, 'addAdditionalProject'])->name('quotation_additional.project');

    Route::get('quotation/customers', [CustomerController::class, 'customers'])->name('quotation.customers');
    Route::get('quotation/add-customer', [CustomerController::class, 'addCustomer'])->name('quotation.add_customer');
    Route::post('quotation/add-customer-data/{id?}', [CustomerController::class, 'addCustomerData'])->name('quotation.add.customer.data');
    Route::get('quotation/edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('quotation.edit.customer');
    Route::post('quotation/update-customer/{id}', [CustomerController::class, 'updateCustomer'])->name('quotation.update.customer');
    Route::get('get-customer', [CustomerController::class, 'getCustomer'])->name('quotation_get_customer');
    Route::get('quotation/delete-customer/{id}', [CustomerController::class, 'deletecustomer'])->name('quotation_delete.customer');

    Route::get('quotation/qa-question/{id}', [ProjectController::class, 'qaQuestionList'])->name('quotation_view.qa.question');
    Route::post('quotation/store-qa-question', [ProjectQuestionController::class, 'store_qa_question'])->name('quotation_store_qa_question');
    Route::post('quotation/store-qa-data', [ProjectQuestionController::class, 'view_store_qa'])->name('quotation_store_qa_data');
});
Route::middleware('auth', 'role:admin,user')->group(function () {
    // Route::get('get-user', [UserController::class, 'getuser'])->name('get_user');
});
Route::middleware('auth','role:purchase')->group(function(){
    Route::get('purchase/dashboard', [AdminController::class, 'dashboard'])->name('purchase.dashboard');
    // Projects
    Route::get('purchase/projects', [ProjectController::class, 'projects'])->name('purchase_projects');
    Route::get('purchase/get_customer', [ProjectController::class, 'getCustomer'])->name('purchase_getCustomer');
    Route::get('purchase/view-project/{id}', [ProjectController::class, 'viewproject'])->name('quotation_view.project');
    Route::get('purchase/view-measurement/{id}', [MeasurementController::class, 'viewMeasurement'])->name('purchase_view.measurement');
    Route::post('purchase/edit-measurement/{id}', [MeasurementController::class, 'editMeasurement'])->name('purchase_edit.measurement');
    Route::post('purchase/store-measurement-data', [MeasurementController::class, 'view_store_measurement'])->name('purchase_store_measurement');
    Route::get('purchase/delete-measurement/{id}', [MeasurementController::class, 'deleteMeasurement'])->name('purchase_delete.measurement');
    Route::get('purchase/view-material/{id}', [MaterialController::class, 'viewMaterial'])->name('purchase_view.material');
    Route::post('purchase/store-material-data', [MaterialController::class, 'storeMaterial'])->name('purchase_store.project.materials');
    Route::get('purchase/delete-material/{id}', [MaterialController::class, 'deleteMaterial'])->name('purchase_delete.material');
    Route::get('purchase/delete-purchase/{id}', [MaterialController::class, 'deletePurchase'])->name('purchase_delete.purchase');
    Route::get('purchase/delete-measurement_pic/{id}', [MeasurementController::class, 'deleteMeasurementPic'])->name('purchase_delete.measurement_pic');

    Route::get('purchase/task-management', [TaskManagementController::class, 'taskManagement'])->name('purchase_task-management');
    Route::get('purchase/add-task', [TaskManagementController::class, 'addTask'])->name('purchase_addTask');
    Route::post('purchase/store-task', [TaskManagementController::class, 'storeTask'])->name('purchase_store_task');
    Route::get('purchase/edit-task/{id}', [TaskManagementController::class, 'editTask'])->name('purchase_editTask');
    Route::post('purchase/update-task/{id}', [TaskManagementController::class, 'updateTask'])->name('purchase_updateTask');
    Route::get('purchase/delete-task/{id}', [TaskManagementController::class, 'deleteTask'])->name('purchase_deleteTask');

    Route::get('purchase/view-completed-project/{id}', [ProjectController::class, 'viewCompletedProject'])->name('purchase_view.completed.project');
    Route::get('purchase/view-project/{id}', [ProjectController::class, 'viewproject'])->name('purchase_view.project');
    Route::get('purchase/view_quotation/{id}', [QuotationController::class, 'viewquotation'])->name('purchase_view.quotation');
    Route::post('purchase/store_quotation-data', [QuotationController::class, 'view_store_quotation'])->name('purchase_store.quotations');
    Route::post('purchase/reject-quotation', [QuotationController::class, 'rejectQuotation'])->name('purchase.reject.quotation');
    Route::get('purchase/finalize-quotation/{id}', [QuotationController::class, 'finalizeQuotation'])->name('purchase_finalize.quotation');
    Route::post('purchase/material-received', [MaterialController::class, 'materialReceivedUpdate'])->name('purchase_update.material.received');
    Route::post('purchase/store-project-cost', [ProjectController::class, 'storeProjectCost'])->name('purchase_store.project.cost');
    Route::get('purchase/view-workshop/{id}', [WorkshopController::class, 'viewworkshop'])->name('purchase_view.workshop');
    Route::post('purchase/store-workshop-question', [WorkshopController::class, 'store_workshop_question'])->name('purchase_storeworkshopquestion');
    Route::post('purchase/store-workshop-data', [WorkshopController::class, 'view_store_workshop'])->name('purchase_storeworkshop');

    Route::post('purchase/update-cutting_option-status', [ProjectController::class, 'updateCutting'])->name('purchase_updateCutting');
    Route::post('purchase/update-shutter-status', [ProjectController::class, 'updateShutter'])->name('purchase_updateShutter');
    Route::post('purchase/update-glass-measurement', [ProjectController::class, 'updateGlassmeasure'])->name('purchase_updateGlassmeasure');
    Route::post('purchase/update-glass-receive', [ProjectController::class, 'updateGlassReceive'])->name('purchase_updateGlassReceive');
    Route::post('purchase/update-shutter-ready', [ProjectController::class, 'updateShutterReady'])->name('purchase_updateShutterReady');
    Route::get('purchase/view-selection/{id}', [QuotationController::class, 'viewSelection'])->name('purchase_view.selection');
    Route::post('purchase/store-selection', [QuotationController::class, 'storeSelection'])->name('purchase_store.selection');
    Route::post('purchase/store-project-cost', [ProjectController::class, 'storeProjectCost'])->name('purchase_store.project.cost');
    Route::post('purchase/done-project', [ProjectController::class, 'projectDone'])->name('purchase_projectDone');
    Route::post('purchase/store-material-status', [WorkshopController::class, 'storeMaterialStatus'])->name('purchase_storeMaterialStatus');
    Route::post('purchase/store-invoice-status', [WorkshopController::class, 'storeInvoiceStatus'])->name('purchase_storeInvoiceStatus');
    Route::get('purchase/delete-invoice/{id}', [WorkshopController::class, 'deleteInvoice'])->name('purchase_delete.invoice');
    Route::get('purchase/delete-partial/{id}', [WorkshopController::class, 'deletePartial'])->name('purchase_delete.partial');

    Route::get('purchase/view-fitting/{id}', [FittingController::class, 'viewfitting'])->name('purchase_view.fitting');
    Route::get('purchase/delete-workshop/{id}', [WorkshopController::class, 'deleteworkshop'])->name('purchase_delete.workshop');

    Route::get('purchase/logs', [LogsController::class, 'listLogs'])->name('purchase_logs');

    Route::get('purchase/customers', [CustomerController::class, 'customers'])->name('purchase.customers');
    Route::get('purchase/add-customer', [CustomerController::class, 'addCustomer'])->name('purchase.add_customer');
    Route::post('purchase/add-customer-data/{id?}', [CustomerController::class, 'addCustomerData'])->name('purchase.add.customer.data');
    Route::get('purchase/edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('purchase.edit.customer');
    Route::post('purchase/update-customer/{id}', [CustomerController::class, 'updateCustomer'])->name('purchase.update.customer');
    Route::get('purchase-get-customer', [CustomerController::class, 'getCustomer'])->name('purchase_get_customer');
    Route::get('purchase/delete-customer/{id}', [CustomerController::class, 'deletecustomer'])->name('purchase_delete.customer');

    Route::get('purchase/qa-question/{id}', [ProjectController::class, 'qaQuestionList'])->name('purchase_view.qa.question');
    Route::post('purchase/store-qa-question', [ProjectQuestionController::class, 'store_qa_question'])->name('purchase_store_qa_question');
    Route::post('purchase/store-qa-data', [ProjectQuestionController::class, 'view_store_qa'])->name('purchase_store_qa_data');

    Route::get('purchase/get-cities/{state_id}', [ProjectController::class, 'getCities'])->name('purchase_get.cities');

    Route::get('purchase/additional-project/{id}', [ProjectController::class, 'addAdditionalProject'])->name('purchase_additional.project');
    Route::get('purchase/profile/edit-profile', [AdminController::class, 'edit_profile'])->name('purchase_edit.profile');
    Route::post('purchase/save-profile', [AdminController::class, 'save_profile'])->name('purchase_save.profile');
    Route::post('purchase/change-password', [AdminController::class, 'change_password'])->name('purchase.change.password');
});

Route::middleware('auth','role:Quality Analytic')->group(function(){
    Route::get('qa/dashboard', [AdminController::class, 'dashboard'])->name('qa.dashboard');
    // Projects
    Route::get('qa/projects', [ProjectController::class, 'projects'])->name('qa_projects');
    Route::get('qa/get_customer', [ProjectController::class, 'getCustomer'])->name('qa_getCustomer');
    Route::get('qa/view-project/{id}', [ProjectController::class, 'viewproject'])->name('qa_view.project');
    Route::get('qa/view-measurement/{id}', [MeasurementController::class, 'viewMeasurement'])->name('qa_view.measurement');
    Route::post('qa/edit-measurement/{id}', [MeasurementController::class, 'editMeasurement'])->name('qa_edit.measurement');
    Route::post('qa/store-measurement-data', [MeasurementController::class, 'view_store_measurement'])->name('qa_store_measurement');
    Route::get('qa/delete-measurement/{id}', [MeasurementController::class, 'deleteMeasurement'])->name('qa_delete.measurement');
    Route::get('qa/view-material/{id}', [MaterialController::class, 'viewMaterial'])->name('qa_view.material');
    Route::post('qa/store-material-data', [MaterialController::class, 'storeMaterial'])->name('qa_store.project.materials');
    Route::get('qa/delete-material/{id}', [MaterialController::class, 'deleteMaterial'])->name('qa_delete.material');
    Route::get('qa/delete-purchase/{id}', [MaterialController::class, 'deletePurchase'])->name('qa_delete.purchase');
    Route::get('qa/delete-measurement_pic/{id}', [MeasurementController::class, 'deleteMeasurementPic'])->name('qa_delete.measurement_pic');

    Route::get('qa/task-management', [TaskManagementController::class, 'taskManagement'])->name('qa_task-management');
    Route::get('qa/add-task', [TaskManagementController::class, 'addTask'])->name('qa_addTask');
    Route::post('qa/store-task', [TaskManagementController::class, 'storeTask'])->name('qa_store_task');
    Route::get('qa/edit-task/{id}', [TaskManagementController::class, 'editTask'])->name('qa_editTask');
    Route::post('qa/update-task/{id}', [TaskManagementController::class, 'updateTask'])->name('qa_updateTask');
    Route::get('qa/delete-task/{id}', [TaskManagementController::class, 'deleteTask'])->name('qa_deleteTask');

    Route::get('qa/view-completed-project/{id}', [ProjectController::class, 'viewCompletedProject'])->name('qa_view.completed.project');
    Route::get('qa/view-project/{id}', [ProjectController::class, 'viewproject'])->name('qa_view.project');
    Route::get('qa/view_quotation/{id}', [QuotationController::class, 'viewquotation'])->name('qa_view.quotation');
    Route::post('qa/store_quotation-data', [QuotationController::class, 'view_store_quotation'])->name('qa_store.quotations');
    Route::post('qa/reject-quotation', [QuotationController::class, 'rejectQuotation'])->name('qa.reject.quotation');
    Route::get('qa/finalize-quotation/{id}', [QuotationController::class, 'finalizeQuotation'])->name('qa_finalize.quotation');
    Route::post('qa/material-received', [MaterialController::class, 'materialReceivedUpdate'])->name('qa_update.material.received');
    Route::post('qa/store-project-cost', [ProjectController::class, 'storeProjectCost'])->name('qa_store.project.cost');
    Route::get('qa/view-workshop/{id}', [WorkshopController::class, 'viewworkshop'])->name('qa_view.workshop');
    Route::post('qa/store-workshop-question', [WorkshopController::class, 'store_workshop_question'])->name('qa_storeworkshopquestion');
    Route::post('qa/store-workshop-data', [WorkshopController::class, 'view_store_workshop'])->name('qa_storeworkshop');

    Route::post('qa/update-cutting_option-status', [ProjectController::class, 'updateCutting'])->name('qa_updateCutting');
    Route::post('qa/update-shutter-status', [ProjectController::class, 'updateShutter'])->name('qa_updateShutter');
    Route::post('qa/update-glass-measurement', [ProjectController::class, 'updateGlassmeasure'])->name('qa_updateGlassmeasure');
    Route::post('qa/update-glass-receive', [ProjectController::class, 'updateGlassReceive'])->name('qa_updateGlassReceive');
    Route::post('qa/update-shutter-ready', [ProjectController::class, 'updateShutterReady'])->name('qa_updateShutterReady');
    Route::get('qa/view-selection/{id}', [QuotationController::class, 'viewSelection'])->name('qa_view.selection');
    Route::post('qa/store-selection', [QuotationController::class, 'storeSelection'])->name('qa_store.selection');
    Route::post('qa/store-project-cost', [ProjectController::class, 'storeProjectCost'])->name('qa_store.project.cost');
    Route::post('qa/done-project', [ProjectController::class, 'projectDone'])->name('qa_projectDone');
    Route::post('qa/store-material-status', [WorkshopController::class, 'storeMaterialStatus'])->name('qa_storeMaterialStatus');
    Route::post('qa/store-invoice-status', [WorkshopController::class, 'storeInvoiceStatus'])->name('qa_storeInvoiceStatus');
    Route::get('qa/delete-invoice/{id}', [WorkshopController::class, 'deleteInvoice'])->name('qa_delete.invoice');
    Route::get('qa/delete-partial/{id}', [WorkshopController::class, 'deletePartial'])->name('qa_delete.partial');

    Route::get('qa/view-fitting/{id}', [FittingController::class, 'viewfitting'])->name('qa_view.fitting');
    Route::get('qa/delete-workshop/{id}', [WorkshopController::class, 'deleteworkshop'])->name('qa_delete.workshop');

    Route::get('qa/logs', [LogsController::class, 'listLogs'])->name('qa_logs');

    Route::get('qa/customers', [CustomerController::class, 'customers'])->name('qa.customers');
    Route::get('qa/add-customer', [CustomerController::class, 'addCustomer'])->name('qa.add_customer');
    Route::post('qa/add-customer-data/{id?}', [CustomerController::class, 'addCustomerData'])->name('qa.add.customer.data');
    Route::get('qa/edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('qa.edit.customer');
    Route::post('qa/update-customer/{id}', [CustomerController::class, 'updateCustomer'])->name('qa.update.customer');
    Route::get('qa-get-customer', [CustomerController::class, 'getCustomer'])->name('qa_get_customer');
    Route::get('qa/delete-customer/{id}', [CustomerController::class, 'deletecustomer'])->name('qa_delete.customer');

    Route::get('qa/get-cities/{state_id}', [ProjectController::class, 'getCities'])->name('qa_get.cities');

    Route::get('qa/qa-question/{id}', [ProjectController::class, 'qaQuestionList'])->name('qa_view.qa.question');
    Route::post('qa/store-qa-question', [ProjectQuestionController::class, 'store_qa_question'])->name('qa_store_qa_question');
    Route::post('qa/store-qa-data', [ProjectQuestionController::class, 'view_store_qa'])->name('qa_store_qa_data');


    Route::get('qa/additional-project/{id}', [ProjectController::class, 'addAdditionalProject'])->name('qa_additional.project');
    Route::get('qa/profile/edit-profile', [AdminController::class, 'edit_profile'])->name('qa_edit.profile');
    Route::post('qa/save-profile', [AdminController::class, 'save_profile'])->name('qa_save.profile');
    Route::post('qa/change-password', [AdminController::class, 'change_password'])->name('qa.change.password');
});
Route::get('/projects/create/{id}', [ConvertProjectController::class, 'create'])->name('create.project');
Route::post('/projects/store', [ConvertProjectController::class, 'store'])->name('store.project');
// Route::get('/get_user', [LeadController::class, 'getUser'])->name('get_user');