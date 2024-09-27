<?php



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;

use App\Http\Controllers\API\UserApiController;

use App\Http\Controllers\API\CategoryApiController;

use App\Http\Controllers\API\SubCategoryApiController;

use App\Http\Controllers\API\CompanyApiController;

use App\Http\Controllers\API\CustomerApiController;

use App\Http\Controllers\API\PlansApiController;

use App\Http\Controllers\API\BusinessSourceController;

use App\Http\Controllers\API\PolicyController;

use App\Http\Controllers\API\EndorsementController;

use App\Http\Controllers\API\ClaimController;

use App\Http\Controllers\API\ReportController;

use App\Http\Controllers\API\CovernoteController;

use App\Http\Controllers\API\PayoutController;

use App\Http\Controllers\API\AdminPayoutController;

use App\Http\Controllers\API\AgentController;

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
Route::post('/loginValidate', [AuthController::class, 'loginUser'])->name('user.validate');
Route::post('forgetPassword', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 

Route::get('mark-as-read', function(){
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
})->name('markasread');
Route::post('notification/mark-as-read', [AuthController::class, 'readNotification'])->name('notification.mark_as_read');
Route::get('Notifications', [AuthController::class, 'Notifications'])->name('notification.mark_as_read');

Route::middleware('auth.api')->group(function () {

    // Users
    Route::get('list-users', [UserApiController::class,'Users']);
    Route::post('store-user', [UserApiController::class,'storeUser']);
    Route::get('delete-user/{id}', [UserApiController::class,'deleteUser']);
    Route::post('update-user', [UserApiController::class,'updateUser']);
    Route::post('update-profile-image', [UserApiController::class,'updateProfileImage']);
    Route::post('update-cover-image', [UserApiController::class,'updateCoverImage']);

    // Categories
    Route::get('list-categories', [CategoryApiController::class,'Categories']);
    Route::post('store-category', [CategoryApiController::class,'storeCategory']);
    Route::get('delete-category/{id}', [CategoryApiController::class,'deleteCategory']);
    Route::post('update-category', [CategoryApiController::class,'updateCategory']);

    // Sub Categories
    Route::get('list-sub-categories', [SubCategoryApiController::class,'subCategories']);
    Route::post('store-sub-category', [SubCategoryApiController::class,'storeSubCategory']);
    Route::get('delete-sub-category/{id}', [SubCategoryApiController::class,'deleteSubCategory']);
    Route::post('update-sub-category', [SubCategoryApiController::class,'updateSubCategory']);

    // Insurance Companies
    Route::get('list-insurance-companies', [CompanyApiController::class,'Companies']);
    Route::post('store-insurance-company', [CompanyApiController::class,'storeInsuranceCompany']);
    Route::get('delete-insurance-company/{id}', [CompanyApiController::class,'deleteInsuranceCompany']);
    Route::post('update-insurance-company', [CompanyApiController::class,'updateInsuranceCompany']);

    // Customers
    Route::get('getAllCustomer', [CustomerApiController::class,'Customers']);
    Route::post('addCustomer', [CustomerApiController::class,'storeCustomer']);
    Route::get('deleteCustomer/{id}', [CustomerApiController::class,'deleteCustomer']);
    Route::post('updateCustomer', [CustomerApiController::class,'updateCustomer']);

    // Health Plan
    Route::get('list-health-plans', [PlansApiController::class,'Plans']);
    Route::post('store-health-plan', [PlansApiController::class,'storePlan']);
    Route::get('delete-health-plan/{id}', [PlansApiController::class,'deletePlan']);
    Route::post('update-health-plan', [PlansApiController::class,'updatePlan']);

    // Business Sourcing
    Route::get('getBusinessSourceList', [BusinessSourceController::class,'getBusinessSourceList']);
    Route::post('addBusinessSource', [BusinessSourceController::class,'addBusinessSource']);
    Route::get('deleteBusinessSource/{id}', [BusinessSourceController::class,'deleteBusinessSource']);
    Route::post('updateBusinessSource', [BusinessSourceController::class,'updateBusinessSource']);

    // Policy
    Route::get('getPolicyInitialData', [PolicyController::class,'getPolicyInitialData']);
    Route::get('getTpCalculationData', [PolicyController::class,'getTpCalculationData']);
    
    //Health Insurance Policy
    Route::get('getHealthInsurancePolicyList', [PolicyController::class,'getHealthInsurancePolicyList']);

    // Vehicle Insurance Policy
    Route::get('getVehicleInsurancePolicyList', [PolicyController::class,'getVehicleInsurancePolicyList']);
    Route::post('addVehicleInsurancePolicy', [PolicyController::class,'addVehicleInsurancePolicy']);
    Route::post('addHealthInsurancePolicy', [PolicyController::class,'addHealthInsurancePolicy']);
    Route::post('editVehicleInsurancePolicy', [PolicyController::class,'editVehicleInsurancePolicy']);
    Route::post('editHealthInsurancePolicy', [PolicyController::class,'editHealthInsurancePolicy']);
    Route::get('deleteVehicleInsurancePolicy/{id}', [PolicyController::class,'deleteVehicleInsurancePolicy']);
    Route::get('deleteHealthInsurancePolicy/{id}', [PolicyController::class,'deleteHealthInsurancePolicy']);
    Route::post('renewVehicleInsurancePolicy', [PolicyController::class,'renewVehicleInsurancePolicy']);
    Route::post('renewHealthInsurancePolicy', [PolicyController::class,'renewHealthInsurancePolicy']);
    Route::post('cancelInsurancePolicy', [PolicyController::class,'cancelInsurancePolicy']);
    Route::post('addPolicyAttachment', [PolicyController::class,'addPolicyAttachment']);

    //Covernote
    Route::get('getCovernoteList', [CovernoteController::class,'getCovernoteList']);
    Route::post('addCovernote', [CovernoteController::class,'addCovernote']);
    Route::post('updateCovernote', [CovernoteController::class,'updateCovernote']);
    Route::get('deleteCovernote/{id}', [CovernoteController::class,'deleteCovernote']);
    Route::post('convertCovernote/{id}', [CovernoteController::class,'convertCovernote']);

    // Endorsement
    Route::get('getMotorEndorsementList', [EndorsementController::class,'getMotorEndorsementList']);
    Route::get('getHealthEndorsementList', [EndorsementController::class,'getHealthEndorsementList']);
    Route::post('addMotorEndorsement',[EndorsementController::class,'addMotorEndorsement']);
    Route::post('editMotorEndorsement',[EndorsementController::class,'editMotorEndorsement']);
    Route::post('addHealthEndorsement',[EndorsementController::class,'addHealthEndorsement']);
    Route::post('editHealthEndorsement',[EndorsementController::class,'editHealthEndorsement']);
    Route::get('deleteMotorEndorsement/{id}', [EndorsementController::class,'deleteMotorEndorsement']);
    Route::get('deleteHealthEndorsement/{id}', [EndorsementController::class,'deleteHealthEndorsement']);

    //Claim
    Route::get('getMotorClaimList', [ClaimController::class,'getMotorClaimList']);
    Route::get('getHealthClaimList', [ClaimController::class,'getHealthClaimList']);
    Route::post('addMotorClaim',[ClaimController::class,'addMotorClaim']);
    Route::post('addHealthClaim',[ClaimController::class,'addHealthClaim']);
    Route::post('editMotorClaim',[ClaimController::class,'editMotorClaim']);
    Route::post('editHealthClaim',[ClaimController::class,'editHealthClaim']);
    Route::get('deleteMotorClaim/{id}',[ClaimController::class,'deleteMotorClaim']);
    Route::get('deleteHealthClaim/{id}',[ClaimController::class,'deleteHealthClaim']);

    // Claim Remarks
    Route::get('getClaimRemarks',[ClaimController::class,'getClaimRemarks']);
    Route::post('addClaimRemark',[ClaimController::class,'addClaimRemark']);

    //Reports
    Route::get('getMotorInsurancePolicyReport',[ReportController::class,'getMotorInsurancePolicyReport']);
    Route::get('getHealthInsurancePolicyReport',[ReportController::class,'getHealthInsurancePolicyReport']);
    Route::get('getMotorEndorsementReport',[ReportController::class,'getMotorEndorsementReport']);
    Route::get('getHealthEndorsementReport',[ReportController::class,'getHealthEndorsementReport']);
    Route::get('getMotorClaimReport',[ReportController::class,'getMotorClaimReport']);
    Route::get('getHealthClaimReport',[ReportController::class,'getHealthClaimReport']);
    Route::get('getPayoutReport',[PayoutController::class,'getPayoutReport']);

    // Payout
    Route::get('getPayoutInitialData', [PayoutController::class,'getPayoutInitialData']);
    Route::get('getPayoutList', [PayoutController::class,'getPayoutList']);
    Route::get('getPayoutPolicyList', [PayoutController::class,'getPayoutPolicyList']);
    Route::get('getPayoutCreationData', [PayoutController::class,'getPayoutCreationData']);
    Route::post('generatePayout', [PayoutController::class,'generatePayout']);
    Route::post('disbursePayout', [PayoutController::class,'disbursePayout']);
    Route::get('deletePayout/{id}', [PayoutController::class,'deletePayout']);
    Route::post('updatePayoutPolicyList', [PayoutController::class,'updatePayoutPolicyList']);

    // Admin Payout
    Route::get('adminPayout/{id}', [AdminPayoutController::class,'adminPayout']);

    // Sourcing Agents
    Route::get('SourcingAgentsList', [AgentController::class,'SourcingAgentsList']);
    Route::post('storeSourcingAgent', [AgentController::class,'storeSourcingAgent']);
    Route::get('deleteSourcingAgent/{id}', [AgentController::class,'deleteSourcingAgent']);
    Route::post('updateSourcingAgent', [AgentController::class,'updateSourcingAgent']);
    Route::get('getSourcingAgentData/{id}', [AgentController::class,'agentPayoutData']);

    // Dashboard
    Route::get('/dashboard-data', [AuthController::class,'DashboardData']);
    Route::post('changePassword', [AuthController::class,'changePassword']);
    
});
Route::any('{any}', function() {
    return response()->json(['result'=>404,'description'=>'Not Found.','status'=>0,'message' => 'Not Found.' ], 404); 
})->where('any', '.*');
Route::get('/token', function () {
    return csrf_token(); 
});