<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\AdminUserController;
use App\Http\Controllers\api\MeasurementUserController;
use App\Http\Controllers\api\QuotationUserController;
use App\Http\Controllers\api\WorkshopUserController;
use App\Http\Controllers\api\FittingUserController;
use App\Http\Controllers\api\LeadController;
use App\Http\Controllers\api\MeasurementController;
use App\Http\Controllers\api\QuotationController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\WorkshopController;
use App\Http\Controllers\api\FittingController;
use App\Http\Controllers\api\TaskManagementController;
use App\Http\Controllers\api\FeedbackController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\LogsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User Login Route
Route::post('/user-login', [AuthController::class, 'loginUser'])->name('user.validate');
Route::post('/feedback-store', [AuthController::class, 'storeFeedback']);

Route::middleware('auth.api')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class,'dashboard']);

    // Customers
    Route::get('listCustomer', [CustomerController::class,'Customers']);
    Route::post('/addCustomer', [CustomerController::class, 'addCustomer']);
    Route::post('/updateCustomer', [CustomerController::class, 'updateCustomer']);
    Route::get('deleteCustomer/{id}', [CustomerController::class,'deleteCustomer']);

    // Admins
    Route::get('listAdmin', [AdminUserController::class,'adminUser']);
    Route::post('/addAdminUser', [AdminUserController::class, 'addAdminUser']);
    Route::post('/updateAdminUser', [AdminUserController::class, 'updateAdminUser']);
    Route::get('deleteAdmin/{id}', [AdminUserController::class,'deleteAdminUser']);

    // Measurement User
    Route::get('listMeasurementUser', [MeasurementUserController::class,'measurementUser']);
    Route::post('/addMeasurementUser', [MeasurementUserController::class, 'addMeasurementUser']);
    Route::post('/updateMeasurementUser', [MeasurementUserController::class, 'updateMeasurementUser']);
    Route::get('deleteMeasurementUser/{id}', [MeasurementUserController::class,'deleteMeasurementUser']);

    // Quotation User
    Route::get('listQuotationUser', [QuotationUserController::class,'quotationUser']);
    Route::post('/addQuotationUser', [QuotationUserController::class, 'addQuotationUser']);
    Route::post('/updateQuotationUser', [QuotationUserController::class, 'updateQuotationUser']);
    Route::get('deleteQuotationUser/{id}', [QuotationUserController::class,'deleteQuotationUser']);

    // Workshop User
    Route::get('listWorkshopUser', [WorkshopUserController::class,'workshopUser']);
    Route::post('/addWorkshopUser', [WorkshopUserController::class, 'addWorkshopUser']);
    Route::post('/updateWorkshopUser', [WorkshopUserController::class, 'updateWorkshopUser']);
    Route::get('deleteWorkshopUser/{id}', [WorkshopUserController::class,'deleteWorkshopUser']);

    // Fitting User
    Route::get('listFittingUser', [FittingUserController::class,'fittingUser']);
    Route::post('/addFittingUser', [FittingUserController::class, 'addFittingUser']);
    Route::post('/updateFittingUser', [FittingUserController::class, 'updateFittingUser']);
    Route::get('deleteFittingUser/{id}', [FittingUserController::class,'deleteFittingUser']);

    // Leads
    Route::get('leads', [LeadController::class,'leads']);
    Route::post('/addLead', [LeadController::class, 'addLead']);
    Route::post('/updateLead', [LeadController::class, 'updateLead']);
    Route::get('deleteLead/{id}', [LeadController::class,'deleteLead']);
    Route::get('viewLead/{id}', [LeadController::class,'viewLead']);

    // Measurement
    Route::get('getMeasurementByProject/{id}', [MeasurementController::class,'viewMeasurementByProject']);
    Route::post('/addMeasurement', [MeasurementController::class, 'addMeasurement']);
    Route::post('/updateMeasurement', [MeasurementController::class, 'updateMeasurement']);
    Route::get('deleteMeasurement/{id}', [MeasurementController::class,'deleteMeasurement']);
    Route::post('/deleteMeasurementDoc', [MeasurementController::class,'deleteMeasurementDoc']);
    Route::get('/deleteSitephoto', [MeasurementController::class,'deleteSitephoto']);

    Route::post('/measurementSitephotos', [MeasurementController::class, 'measurementSitephotos']);

    // Quotation
    Route::get('getQuotation/{id}', [QuotationController::class,'getQuotation']);
    Route::post('/addQuotation', [QuotationController::class, 'addQuotation']);
    Route::post('/updateQuotation', [QuotationController::class, 'updateQuotation']);
    Route::get('deleteQuotation/{id}', [QuotationController::class,'deleteQuotation']);
    Route::post('/deleteQuotationDoc', [QuotationController::class,'deleteQuotationDoc']);
    Route::post('/rejectQuotation', [QuotationController::class, 'rejectQuotation']);

    // Project

    Route::post('/addProject', [ProjectController::class, 'addProject']);
    Route::post('/updateProject', [ProjectController::class, 'updateProject']);
    Route::post('/addPurchaseFile', [ProjectController::class, 'addPurchaseFile']);
    Route::get('/listPurchaseFile', [ProjectController::class, 'listPurchaseFile']);
    Route::post('/projectCostCalculation', [ProjectController::class, 'projectCostCalculation']);
    Route::post('/ConfirmProject', [ProjectController::class, 'ConfirmProject']);
    Route::get('/listProject', [ProjectController::class, 'listProject']);
    Route::get('deleteProject/{id}', [ProjectController::class,'deleteProject']);
    Route::get('viewProject/{id}', [ProjectController::class,'viewProject']);
    Route::post('projectDone', [ProjectController::class, 'projectDone']);

    // Workshop Questions
    Route::get('/listworkshopQuestion', [WorkshopController::class, 'listworkshopQuestion']);
    Route::post('/addworkshopQuestion', [WorkshopController::class, 'addworkshopQuestion']);
    Route::post('/updateworkshopQuestion', [WorkshopController::class, 'updateworkshopQuestion']);
    Route::post('/checkWorkshop', [WorkshopController::class, 'checkWorkshop']);
    Route::get('deleteworkshopQuestion/{id}', [WorkshopController::class,'deleteworkshopQuestion']);
    Route::post('updateCutting', [WorkshopController::class, 'updateCutting']);
    Route::post('updateShutter', [WorkshopController::class, 'updateShutter']);
    Route::post('updateGlassmeasure', [WorkshopController::class, 'updateGlassmeasure']);
    Route::post('updateGlassReceive', [WorkshopController::class, 'updateGlassReceive']);
    Route::post('updateShutterReady', [WorkshopController::class, 'updateShutterReady']);
    Route::post('storeMaterialStatus', [WorkshopController::class, 'storeMaterialStatus']);
    Route::get('deletePartial/{id}', [WorkshopController::class,'deletePartial']);

    // Fitting Questions
    Route::get('/listfittingQuestion', [FittingController::class, 'listfittingQuestion']);
    Route::post('/addfittingQuestion', [FittingController::class, 'addfittingQuestion']);
    Route::post('/updatefittingQuestion', [FittingController::class, 'updatefittingQuestion']);
    Route::post('/checkFitting', [FittingController::class, 'checkFitting']);
    Route::get('deletefittingQuestion/{id}', [FittingController::class,'deletefittingQuestion']);
    Route::post('/fittingSitephotos', [FittingController::class, 'fittingSitephotos']);

    // Task Management
    Route::get('/listTask', [TaskManagementController::class, 'listTask']);
    Route::post('/addTask', [TaskManagementController::class, 'addTask']);
    Route::post('/updateTask', [TaskManagementController::class, 'updateTask']);
    Route::get('deleteTask/{id}', [TaskManagementController::class,'deleteTask']);
    Route::post('/changeTaskStatus', [TaskManagementController::class, 'changeTaskStatus']);

    // Feedback
    Route::get('/listFeedback', [FeedbackController::class, 'listFeedback']);
    Route::get('viewFeedback/{id}', [FeedbackController::class,'viewFeedback']);
    Route::get('deleteFeedback/{id}', [FeedbackController::class,'deleteFeedback']);

    // State
    Route::get('listState', [CustomerController::class,'listState']);
    Route::get('listCity', [CustomerController::class,'listCity']);

    // Logs
    Route::get('listLogs', [LogsController::class,'listLogs']);

});