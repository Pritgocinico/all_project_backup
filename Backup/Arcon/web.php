<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\GalleryController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\AgentController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\SliderController;

use App\Http\Controllers\AgentsController;

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
Route::get('/agent/login', [AuthController::class, 'index'])->name('login');
Route::post('admin-login', [AuthController::class, 'adminLogin'])->name('admin_login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

//Forgot Password Route

// Route::get('forgot-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
// Route::post('forgot-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
// Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
// Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

// Route::get('/reset-password/{token}',[AuthController::class, 'showResetPasswordForm'])->middleware('guest')->name('password.reset');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'submitResetPasswordForm'])
    ->middleware('guest')
    ->name('reset.password.post');
Route::get('mark-as-read', function(){
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
})->name('markasread');
Route::post('notification/mark-as-read', [SettingController::class, 'readNotification'])->name('notification.mark_as_read');

Route::get('order/invoice/{id}',[OrderController::class,'downloadInvoice'])->name('order.invoice');
Route::get('view/statement',[OrderController::class,'viewStatement'])->name('view.statement');
Route::get('view/agent/statement',[OrderController::class,'viewAgentStatement'])->name('view.statement');

Route::middleware('auth','role:admin')->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('admin/profile/edit-profile', [AdminController::class, 'edit_profile'])->name('edit.profile');
    Route::post('admin/save-profile', [ProfileController::class, 'save_profile'])->name('save.profile');
    Route::get('admin/profile/view-profile', [AdminController::class, 'view_profile'])->name('view.profile');

    Route::post('change-password', [ProfileController::class,'change_password'])->name('admin.change.password');

    //Inquiry

    Route::get('admin/inquiry', [AdminController::class, 'inquiry'])->name('admin.inquiries');
    Route::get('delete/inquiry/{id}',[AdminController::class,'deleteInquiry'])->name('delete.inquiry');

    //Gallery
    Route::get('admin/gallery', [GalleryController::class, 'gallery'])->name('admin.gallery');
    Route::get('delete/gallery/{id}',[GalleryController::class,'deletegallery'])->name('delete.gallery');
    Route::post('admin/add-gallery', [GalleryController::class,'addGallery'])->name('admin.add_gallery');

    Route::post('admin/store', [GalleryController::class,'store'])->name('admin.store');

    //Banners
    Route::get('admin/banners', [SliderController::class, 'banners'])->name('admin.banners');
    Route::get('delete/banners/{id}',[SliderController::class,'deleteBanner'])->name('delete.banner');

    Route::post('admin/store-banners', [SliderController::class,'store'])->name('admin.store_banner');

     //categories
     Route::get('admin/categories', [CategoryController::class, 'categories'])->name('admin.categories');
     Route::post('admin/add_category_data', [CategoryController::class, 'addCategoryData'])->name('admin.add_category_data');
     Route::get('admin/edit-category/{id}',[CategoryController::class,'editCategory'])->name('admin.edit_category');
     Route::post('admin/update-category', [CategoryController::class, 'updateCategory'])->name('admin.edit.category');
     Route::get('delete/category/{id}',[CategoryController::class,'deleteCategory'])->name('delete.category');

     //Products
    Route::get('admin/products', [ProductController::class, 'products'])->name('admin.products');
    Route::get('admin/add-product', [ProductController::class, 'addProduct'])->name('admin.add_product');
    Route::post('admin/add-product-data', [ProductController::class, 'addProductData'])->name('admin.add_product_data');
    Route::get('admin/edit-product/{id}',[ProductController::class,'editProduct'])->name('admin.edit_product');
    Route::post('admin/update-product', [ProductController::class, 'updateProduct'])->name('admin.edit.product');
    Route::get('delete/product/{id}',[ProductController::class,'deleteProduct'])->name('delete.product');
    Route::get('delete/product-variant/{id}',[ProductController::class,'deleteProductVariant'])->name('delete.product_variant');
    Route::get('delete/product-image/{id}',[ProductController::class,'deleteProductImage'])->name('delete.product.image');

    //Users
    Route::get('admin/users', [UserController::class, 'users'])->name('admin.users');
    Route::get('admin/add_user', [UserController::class, 'addUser'])->name('admin.add_user');
    Route::post('admin/add_user_data', [UserController::class, 'addUserData'])->name('admin.add_user_data');
    Route::get('admin/edit-user/{id}',[UserController::class,'editUser'])->name('admin.edit_user');
    Route::post('admin/update-user', [UserController::class, 'updateUser'])->name('admin.edit.user');
    Route::get('delete/user/{id}',[UserController::class,'deleteUser'])->name('delete.user');
    Route::get('admin/view-user/{id}',[UserController::class,'viewUser'])->name('admin.view_user');
    Route::get('admin/get-cities/{id}',[UserController::class,'getCities'])->name('get_cities');

    //Users
    Route::get('admin/agents', [AgentController::class, 'agents'])->name('admin.agents');
    Route::get('admin/add_agent', [AgentController::class, 'addAgent'])->name('admin.add_agent');
    Route::post('admin/add_agent_data', [AgentController::class, 'addAgentData'])->name('admin.add_agent_data');
    Route::get('admin/edit-agent/{id}',[AgentController::class,'editAgent'])->name('admin.edit_agent');
    Route::post('admin/update-agent', [AgentController::class, 'updateAgent'])->name('admin.edit.agent');
    Route::get('delete/agent/{id}',[AgentController::class,'deleteAgent'])->name('delete.agent');
    Route::post('admin/change-agent-status', [AgentController::class, 'change_status'])->name('change_agent_status');
    Route::get('admin/view-agent/{id}',[AgentController::class,'viewAgent'])->name('admin.view_agent');


    //Orders

    Route::get('admin/orders', [OrderController::class, 'orders'])->name('admin.orders');
    Route::get('order-details/{id}',[OrderController::class,'orderDetail'])->name('order.details');

    Route::get('admin/add-order', [OrderController::class, 'addOrder'])->name('admin.add_order');
    Route::post('admin/add-order-data', [OrderController::class, 'addOrderData'])->name('admin.add_order_data');
    Route::get('admin/edit-order/{id}',[OrderController::class,'editOrder'])->name('admin.edit_order');
    Route::post('admin/update-order', [OrderController::class, 'updateOrder'])->name('admin.edit.order');
    Route::post('admin/upload-lrcopy', [OrderController::class, 'uploadLrcopy'])->name('admin.upload.lrcopy');
    Route::get('delete/order/{id}',[OrderController::class,'deleteOrder'])->name('delete.order');

    //Setting
     Route::get('admin/settings', [SettingController::class, 'settings'])->name('settings');
     Route::get('admin/general-settings', [SettingController::class, 'settings'])->name('general.setting');
     Route::post('admin/save-general-setting', [SettingController::class, 'save_general_setting'])->name('save_general_settings');
     Route::get('admin/company-settings', [SettingController::class, 'company_settings'])->name('company.setting');
     Route::post('admin/save-company-setting', [SettingController::class, 'save_company_setting'])->name('save_company_settings');
     Route::get('admin/email-settings', [SettingController::class, 'email_settings'])->name('email.setting');
     Route::post('admin/save-email-setting', [SettingController::class, 'save_email_setting'])->name('save_email_settings');

     Route::get('admin/logs', [AdminController::class, 'logs'])->name('admin.logs');

    Route::get('/get-cat-products', [ProductController::class, 'getCatProducts'])->name('get_cat_products');
    Route::get('/get-product-variants', [ProductController::class, 'getProductVariants'])->name('get_product_variants');
    Route::get('/get-variant', [ProductController::class, 'getVariant'])->name('get_variant');
    Route::get('/get-category', [CategoryController::class, 'getCategories'])->name('get_categories');

    Route::post('/statement', [OrderController::class, 'getStatementData'])->name('get_statement_data');


    Route::get('/get-state-cities', [AgentController::class, 'getStateCities'])->name('get_state_cities');
});


Route::middleware('auth','role:agent')->group(function () {

    Route::get('agent/dashboard', [AgentsController::class, 'dashboard'])->name('agent.dashboard');

    Route::get('agent/profile/edit-profile', [AdminController::class, 'edit_profile'])->name('agent.edit.profile');
    Route::post('agent/save-profile', [ProfileController::class, 'save_profile'])->name('agent.save.profile');
    Route::get('agent/profile/view-profile', [AdminController::class, 'view_profile'])->name('agent.view.profile');

    Route::post('agent/change-password', [ProfileController::class,'change_password'])->name('agent.change.password');

    // Categories
    Route::get('agent/categories', [CategoryController::class, 'categories'])->name('agent.categories');

    // Products
    Route::get('agent/products', [ProductController::class, 'products'])->name('agent.products');

    //Users
    Route::get('agent/users', [UserController::class, 'users'])->name('agent.users');
    Route::get('agent/add_user', [UserController::class, 'addUser'])->name('agent.add_user');
    Route::get('agent/view-user/{id}',[UserController::class,'viewUser'])->name('agent.view_user');

    //Orders
    Route::get('agent/orders', [OrderController::class, 'orders'])->name('agent.orders');
    Route::get('agent/order-details/{id}',[OrderController::class,'orderDetail'])->name('agent.order.details');

});
Route::middleware('auth','role:admin,agent')->group(function () {
    Route::post('change_order_status', [OrderController::class, 'change_status'])->name('change_order_status');
    Route::post('change_order_item_status', [OrderController::class, 'change_order_item_status'])->name('change_order_item_status');
    Route::post('delete_order_item', [OrderController::class, 'delete_order_item'])->name('delete_order_item');
    Route::post('change-user-status', [UserController::class, 'change_status'])->name('change_user_status');
    Route::post('change-agent-user-status', [UserController::class, 'change_agent_status'])->name('change_user_agent_status');

    Route::get('/sales-report', [OrderController::class, 'SaleReport'])->name('sales_report');
});
