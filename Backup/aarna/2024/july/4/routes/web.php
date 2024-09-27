<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\common\CategoryController;
use App\Http\Controllers\common\CompanyController;
use App\Http\Controllers\common\SourcingAgentController;
use App\Http\Controllers\common\CustomerController;
use App\Http\Controllers\common\BusinessSourceController;
use App\Http\Controllers\common\PlansController;
use App\Http\Controllers\common\CovernoteController;
use App\Http\Controllers\common\PolicyController;
use App\Http\Controllers\common\ClaimController;
use App\Http\Controllers\common\EndorsementController;
use App\Http\Controllers\common\PayoutController;
use App\Http\Controllers\common\AdminPayoutController;
use App\Http\Controllers\common\ReportController;
use Illuminate\Support\Facades\Auth;

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
Route::post('admin-login', [AuthController::class, 'adminLogin'])->name('admin_login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::get('terms-and-conditions', [AuthController::class, 'terms_and_conditions'])->name('terms_and_conditions');
Route::get('privacy-and-policy', [AuthController::class, 'privacy_and_policy'])->name('privacy_and_policy');
Route::get('contact-us', [AuthController::class, 'contact_us'])->name('contact-us');
Route::post('store.contact-us', [AuthController::class, 'storeContactUs'])->name('store.contact-us');
Route::get('account-delete', [AuthController::class, 'accountdelete'])->name('accountdelete');
Route::post('account-del', [AuthController::class, 'accountdel'])->name('accountdel');

$locale = Request::segment(1);
// echo $locale;
// dd(Auth::user()->getId());
// if($user = Auth::user()){
//     $id = Auth::user()->getId();
//     $prefix = Auth::user()->role();
//     dd($prefix);
// }else{
//     $prefix = '/';
// }
// $rolePrefix = '';
// $user = Auth::user();
// if ($user) {
//     $role = $user->role; // Assuming role is stored in a column named 'role'
//     $rolePrefix = $role ? $role . '/' : '';
// }

Route::get('mark-as-read', function(){
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
})->name('markasread');
Route::post('notification/mark-as-read', [SettingController::class, 'readNotification'])->name('notification.mark_as_read');


Route::middleware('auth','role:admin,staff,agent')->prefix($locale)->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    //Setting
    Route::get('settings', [SettingController::class, 'settings'])->name('settings');
    Route::get('general-settings', [SettingController::class, 'settings'])->name('general.setting');
    Route::post('save-general-setting', [SettingController::class, 'save_general_setting'])->name('save_general_settings');
    Route::get('company-settings', [SettingController::class, 'company_settings'])->name('company.setting');
    Route::post('save-company-setting', [SettingController::class, 'save_company_setting'])->name('save_company_settings');
    Route::get('email-settings', [SettingController::class, 'email_settings'])->name('email.setting');
    Route::post('save-email-setting', [SettingController::class, 'save_email_setting'])->name('save_email_settings');
    Route::get('privacy-policy', [SettingController::class, 'privacy_policy'])->name('privacy.policy');
    Route::post('save-privacy-policy', [SettingController::class, 'save_privacy_policy'])->name('save.privacy.policy');
    Route::get('terms-conditions', [SettingController::class, 'terms_conditions'])->name('terms.conditions');
    Route::post('save-terms-conditions', [SettingController::class, 'save_terms_conditions'])->name('save.terms.conditions');
    
    Route::get('logs', [AdminController::class, 'logs'])->name('admin.logs');

    Route::get('profile/edit-profile', [AdminController::class, 'edit_profile'])->name('edit.profile');
    Route::post('save-profile', [ProfileController::class, 'save_profile'])->name('save.profile');
    Route::get('profile/view-profile', [AdminController::class, 'view_profile'])->name('view.profile');

    Route::post('change-password', [ProfileController::class,'change_password'])->name('admin.change.password');

    //User
    Route::get('users', [UserController::class, 'users'])->name('admin.users');
    Route::get('users/add-user', [UserController::class, 'addUser'])->name('admin.add.user');
    Route::post('users/add-user-data', [UserController::class, 'addUserData'])->name('admin.add.user.data');
    Route::get('users/edit-user/{id}', [UserController::class, 'editUser'])->name('admin.edit.user');
    Route::post('users/update-user/{id}', [UserController::class, 'updateUser'])->name('admin.update.user');
    Route::get('delete/user/{id}',[UserController::class,'deleteUser'])->name('delete.user');

    // User Permissions
    Route::get('users/permissions/{id}',[UserController::class,'userPermission'])->name('user.permission');
    Route::post('users/update_permmission', [UserController::class, 'updatePermission'])->name('update.permission'); 

    //Categories
    Route::get('categories', [CategoryController::class, 'categories'])->name('admin.categories');
    Route::post('add_category_data', [CategoryController::class, 'addCategoryData'])->name('admin.add_category_data');
    Route::get('edit-category/{id}',[CategoryController::class,'editCategory'])->name('admin.edit_category');
    Route::post('update-category', [CategoryController::class, 'updateCategory'])->name('admin.edit.category');
    Route::get('delete/category/{id}',[CategoryController::class,'deleteCategory'])->name('delete.category');

    //Sub Categories
    Route::get('sub-categories', [CategoryController::class, 'subCategories'])->name('admin.sub_categories');
    Route::post('add_subcategory_data', [CategoryController::class, 'addSubCategoryData'])->name('admin.add_subcategory_data');
    Route::get('edit-subcategory/{id}',[CategoryController::class,'editSubCategory'])->name('admin.edit_subcategory');
    Route::post('update-subcategory', [CategoryController::class, 'updateSubCategory'])->name('admin.edit.subcategory');
    Route::get('delete/subcategory/{id}',[CategoryController::class,'deleteSubCategory'])->name('delete.subcategory');
    Route::get('tpcalculationparameter/{id}',[CategoryController::class,'TpCalculationParameters'])->name('admin.tp_calculationparameter');
    Route::post('update-tpcalculationparameter',[CategoryController::class,'UpdateTpCalculationParameters'])->name('admin.update.parameters');
    Route::get('delete/parameter/{id}',[CategoryController::class,'deleteParameter'])->name('delete.parameter');
    Route::get('delete/all-parameter',[CategoryController::class,'deleteAllParameter'])->name('delete.all.parameter');
    Route::get('get-category-parameters',[CategoryController::class,'getCategoryParameters'])->name('get_cat_parameters');
    Route::get('get-gcv-parameters',[CategoryController::class,'getGCVParameters'])->name('get_gcv_parameters');
    Route::get('get-pcv-parameters',[CategoryController::class,'getPCVParameters'])->name('get_pcv_parameters');
    Route::get('get-category-covernote-parameters',[CategoryController::class,'getCategoryCovernoteParameters'])->name('get_cat_covernote_parameters');

    //Insurance Company
    Route::get('insurance-company', [CompanyController::class, 'companies'])->name('admin.companies');
    Route::get('add-company', [CompanyController::class, 'addCompany'])->name('admin.add.company');
    Route::post('add-company-data', [CompanyController::class, 'addCompanyData'])->name('admin.add.company.data');
    Route::get('edit-company/{id}',[CompanyController::class,'editCompany'])->name('admin.edit_company');
    Route::post('update-company', [CompanyController::class, 'updateCompany'])->name('admin.edit.company');
    Route::get('delete/company/{id}',[CompanyController::class,'deleteCompany'])->name('delete.company');

    //Sourcing Agents
    Route::get('sourcing-agents', [SourcingAgentController::class, 'sourcingAgents'])->name('admin.sourcing.agents');
    Route::get('add-sourcing-agent', [SourcingAgentController::class, 'addSourcingAgent'])->name('admin.add.agent');
    Route::post('add-agent-data', [SourcingAgentController::class, 'addAgentData'])->name('admin.add.agent.data');
    Route::get('edit-sourcing-agent/{id}',[SourcingAgentController::class,'editAgent'])->name('admin.edit_agent');
    Route::post('update-agent', [SourcingAgentController::class, 'updateAgent'])->name('admin.edit.agent');
    Route::get('delete/sourcing-agent/{id}',[SourcingAgentController::class,'deleteAgent'])->name('delete.agent');
    Route::get('sourcing-agent-payout/{id}',[SourcingAgentController::class,'AgentPayout'])->name('admin.agent.payout');
    Route::get('get-payout-company-category/{id}',[PayoutController::class,'getPayoutCompanyCategory'])->name('get_payout_company_subcategory');
    Route::post('update-payout',[PayoutController::class,'UpdatePayout'])->name('admin.update.payout');
    Route::get('delete/payout-record/{id}',[PayoutController::class,'deletePayoutRecord'])->name('delete.payout.record');
  
   //Admin Payout
   Route::get('admin-payout',[AdminPayoutController::class,'AdminPayout'])->name('admin.admin_payout');
   Route::post('update-admin-payout',[AdminPayoutController::class,'UpdateAdminPayout'])->name('admin.update.admin_payout');
   Route::get('delete/admin-payout-record/{id}',[AdminPayoutController::class,'deleteAdminPayoutRecord'])->name('delete.admin.payout.record');

    Route::get('payouts/index', [PayoutController::class, 'payoutList'])->name('admin.payout.list');
    Route::get('delete/payout/{id}',[PayoutController::class,'deletePayout'])->name('delete.payout');
    Route::get('payout/create', [PayoutController::class, 'createPayout'])->name('admin.create.payout');
    Route::post('generate-payout',[PayoutController::class,'GeneratePayout'])->name('admin.generate.payout');
    Route::post('generate-payout-data',[PayoutController::class,'GeneratePayoutData'])->name('admin.generate.payout.data');
    Route::get('edit-payout-list/{id}',[PayoutController::class,'editPayoutList'])->name('admin.edit_payout_list');
    Route::post('edit-payout-data',[PayoutController::class,'EditPayoutData'])->name('admin.edit.payout.data');
    Route::get('view-payout/{id}',[PayoutController::class,'ViewPayout'])->name('admin.view.payout');
    Route::get('payout-reports/',[PayoutController::class,'PayoutReports'])->name('admin.payout.reports');
    Route::post('customer-payout-csv',[PayoutController::class,'downloadCustomerPayoutCSV'])->name('admin.download.customer.payoutcsv');
    Route::post('disburse-payout',[PayoutController::class,'disbursePayout'])->name('admin.disburse.payout');
    Route::get('payout-amount/{id}',[PayoutController::class,'getPayoutAmount'])->name('get.payout.amount');
    Route::post('policy-payout-csv',[PayoutController::class,'downloadPolicyPayout'])->name('admin.download.policy.payout');

    //Customers
    Route::get('customers', [CustomerController::class, 'customers'])->name('admin.customers');
    Route::get('add-customer', [CustomerController::class, 'addCustomer'])->name('admin.add.customer');
    Route::post('add-customer-data', [CustomerController::class, 'addCustomerData'])->name('admin.add.customer.data');
    Route::get('edit-customer/{id}',[CustomerController::class,'editCustomer'])->name('admin.edit_customer');
    Route::post('update-customer', [CustomerController::class, 'updateCustomer'])->name('admin.edit.customer');
    Route::get('delete/customer/{id}',[CustomerController::class,'deleteCustomer'])->name('delete.customer');
    Route::get('customers/data',[CustomerController::class,'getCustomerData'])->name('customers.data');

    //Business Source
    Route::get('business-source', [BusinessSourceController::class, 'businessSource'])->name('admin.business.source');
    Route::get('add-business-source', [BusinessSourceController::class, 'addBusinessSource'])->name('admin.add.business.source');
    Route::post('add-business-source-data', [BusinessSourceController::class, 'addBusinessSourceData'])->name('admin.add.source.data');
    Route::get('delete/business-source/{id}',[BusinessSourceController::class,'deleteBusinessSource'])->name('delete.source');
    Route::get('edit-business-source/{id}',[BusinessSourceController::class,'editBusinessSource'])->name('admin.edit_business_source');
    Route::post('update-business-source', [BusinessSourceController::class, 'updateBusinessSource'])->name('admin.update.source');

    //Plans
    Route::get('health-plans', [PlansController::class, 'plans'])->name('admin.plans');
    Route::get('add-health-plan', [PlansController::class, 'addPlan'])->name('admin.add.plan');
    Route::post('add-health-plan-data', [PlansController::class, 'addPlanData'])->name('admin.add.plan.data');
    Route::get('edit-plan/{id}',[PlansController::class,'editPlan'])->name('admin.edit_plan');
    Route::post('update-plan', [PlansController::class, 'updatePlan'])->name('admin.edit.plan');
    Route::get('delete/health-plan/{id}',[PlansController::class,'deletePlan'])->name('delete.plan');

    //Covernote
    Route::get('covernote', [CovernoteController::class, 'covernote'])->name('admin.covernote');
    Route::get('add-covernote/{ids?}', [CovernoteController::class, 'addCovernote'])->name('admin.add.covernote');
    Route::post('add-covernote-data', [CovernoteController::class, 'addCovernoteData'])->name('admin.add.covernote.data');
    Route::get('delete/covernote/{id}',[CovernoteController::class,'deleteCovernote'])->name('delete.covernote');
    Route::get('edit-covernote/{id}',[CovernoteController::class,'editCovernote'])->name('admin.edit_covernote');
    Route::post('edit-covernote-data', [CovernoteController::class, 'editCovernoteData'])->name('admin.edit.covernote.data');
    Route::get('get-covernote-attachment/{id}',[CovernoteController::class,'getCovernoteAttachment'])->name('admin.get_covernote_attachment');
    Route::get('convert-covernote',[CovernoteController::class,'covernoteCovernote'])->name('admin.convert.covernote');
    Route::get('view-covernote/{id}',[CovernoteController::class,'viewCovernote'])->name('admin.view_covernote');

    // Policy
    Route::get('policies', [PolicyController::class, 'policies'])->name('admin.policies');
    Route::get('add-policy', [PolicyController::class, 'addPolicy'])->name('admin.add.policy');
    Route::post('add-policy-data', [PolicyController::class, 'addPolicyData'])->name('admin.add.policy.data');
    Route::get('delete/policy/{id}',[PolicyController::class,'deletePolicy'])->name('delete.policy');
    Route::get('view-policy/{id}',[PolicyController::class,'viewPolicy'])->name('admin.view_policy');
    Route::get('edit-policy/{id}',[PolicyController::class,'editPolicy'])->name('admin.edit_policy');
    Route::get('get-policy-attachment/{id}',[PolicyController::class,'getPolicyAttachment'])->name('admin.get_policy_attachment');
    Route::post('edit-policy-data', [PolicyController::class, 'editPolicyData'])->name('admin.edit.policy.data');
    Route::get('renew-policy/{id}',[PolicyController::class,'renewPolicy'])->name('admin.renew_policy');
    Route::post('renew-policy-data', [PolicyController::class, 'renewPolicyData'])->name('admin.renew.policy.data');
    Route::get('policy-history/{id}',[PolicyController::class,'policyHistory'])->name('admin.policy.history');
    Route::get('delete/policy-document/{id}',[PolicyController::class,'deletePolicyDocument'])->name('delete.policy.document');
    Route::get('delete/policy-payment/{id}',[PolicyController::class,'deletePolicyPayment'])->name('delete.policy.payment');
    Route::post('cancel-policy', [PolicyController::class, 'cancelPolicy'])->name('admin.cancel.policy');
    Route::get('cancelled-policies', [PolicyController::class, 'cancelledPolicies'])->name('admin.cancelled.policies');
    Route::get('cancel-policy-report',[PolicyController::class,'cancelPolicyReport'])->name('admin.cancel.policy.report');
    Route::get('policy/data',[PolicyController::class,'getPolicyData'])->name('policy.data');

    // Reports
    Route::get('reports', [ReportController::class, 'Reports'])->name('admin.reports');
    Route::post('business-source-report',[ReportController::class,'businessSourceReports'])->name('admin.business_source_report');
    Route::post('endorsement-report',[ReportController::class,'endorsementReports'])->name('admin.endorsement_report');
    Route::post('claim-report',[ReportController::class,'claimReports'])->name('admin.claim_report');
    Route::post('policy-report',[ReportController::class,'policyReports'])->name('admin.policy_report');

    // Claims
    Route::get('claims/{id}', [ClaimController::class, 'claims'])->name('admin.claims');
    Route::get('add-claim/{id}', [ClaimController::class, 'addClaim'])->name('admin.add.claim');
    Route::post('add-claim-data', [ClaimController::class, 'addClaimData'])->name('admin.add.claim.data');
    Route::get('delete/claim/{id}',[ClaimController::class,'deleteClaim'])->name('delete.claim');
    Route::get('edit-claim/{id}',[ClaimController::class,'editClaim'])->name('admin.edit_claim');
    Route::get('get-claim-attachment/{id}',[ClaimController::class,'getClaimAttachment'])->name('admin.get_claim_attachment');
    Route::post('edit-claim-data', [ClaimController::class, 'editClaimData'])->name('admin.edit.claim.data');
    Route::get('view-claim/{id}',[ClaimController::class,'viewClaim'])->name('admin.view_claim');

    //Claim Remark
    Route::get('claim-remarks/{id}',[ClaimController::class,'claimRemarks'])->name('admin.claim.remarks');
    Route::get('add-claim-remark/{id}', [ClaimController::class, 'addClaimRemark'])->name('admin.add.claim.remark');
    Route::post('add-remark-data', [ClaimController::class, 'addRemarkData'])->name('admin.add.remark.data');

    //Endorsement
    Route::get('endorsement/{id}', [EndorsementController::class, 'endorsement'])->name('admin.endorsement');
    Route::get('add-endorsement/{id}', [EndorsementController::class, 'addEndorsement'])->name('admin.add.endorsement');
    Route::post('add-endorsement-data', [EndorsementController::class, 'addEndorsementData'])->name('admin.add.endorsement.data');
    Route::get('delete/endorsement/{id}',[EndorsementController::class,'deleteEndorsement'])->name('delete.endorsement');
    Route::get('edit-endorsement/{id}',[EndorsementController::class,'editEndorsement'])->name('admin.edit_endorsement');
    Route::get('get-endorsement-attachment/{id}',[EndorsementController::class,'getEndorsementAttachment'])->name('admin.get_endorsement_attachment');
    Route::post('edit-endorsement-data', [EndorsementController::class, 'editEndorsementData'])->name('admin.edit.endorsement.data');

    Route::get('/get-cat-subcategory', [CategoryController::class, 'getCatSubcategory'])->name('get_cat_subcategory');
    Route::get('/get-insurance-plan', [PlansController::class, 'getInsurancePlan'])->name('get_insurance_plan');


});
// Route::any('{any}', function() {
//     return redirect()->route('login');
// })->where('any', '.*');
Route::get('/token', function () {
    return csrf_token(); 
});