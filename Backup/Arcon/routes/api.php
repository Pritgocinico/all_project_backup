<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\ForgotPasswordController;
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
Route::get('/settings', [ApiController::class, 'settings'])->name('settings');
Route::get('/gallery', [ApiController::class, 'gallery'])->name('gallery');
Route::get('/banners', [ApiController::class, 'banners'])->name('banners');
Route::get('/agents', [ApiController::class, 'agents'])->name('agents');

Route::get('/categories', [ApiController::class, 'categories'])->name('categories');
Route::get('/sub-category/{id}', [ApiController::class, 'subCategory'])->name('sub_category');
Route::get('/products', [ApiController::class, 'products'])->name('products');
Route::get('/product/{id}', [ApiController::class, 'product'])->name('product');
Route::get('/category-products/{id}', [ApiController::class, 'categoryProduct'])->name('category.product');
Route::get('/delear-categories/{id}', [ApiController::class, 'dealerCategories'])->name('dealer.categories');
Route::get('/delear-sub-categories/{id}/{dealer}', [ApiController::class, 'dealerSubCategories'])->name('dealer.sub.categories');

//user
Route::get('/user/{id}', [ApiController::class, 'user'])->name('user');
Route::post('/user-details', [ApiController::class, 'userDetails'])->name('user.details');
Route::post('/user-validate', [ApiController::class, 'checkUser'])->name('user.validate');
Route::post('/user-update', [ApiController::class, 'updateUser'])->name('user.update');
Route::get('/user-delete/{id}', [ApiController::class, 'deleteUser'])->name('user.delete');

//Cart

Route::post('/add-to-cart-items', [ApiController::class, 'addToCartItems'])->name('add_to_cart');
Route::get('/get-cart-items/{id}', [ApiController::class, 'getCartItems'])->name('get_cart_items');
Route::get('/get-cart-count/{id}', [ApiController::class, 'getCartCount'])->name('get_cart_count');
Route::post('/update-cart-items', [ApiController::class, 'updateCartItems'])->name('update_cart_items');
Route::get('/delete-cart-items/{id}', [ApiController::class, 'deleteCartItem'])->name('delete_cart_items');

//orders

Route::post('/place-order', [ApiController::class, 'placeOrder'])->name('user.order');
Route::get('/user-orders/{id}', [ApiController::class, 'userOrders'])->name('user.orders');
Route::get('/order-details/{id}', [ApiController::class, 'OrderById'])->name('order.id');
Route::post('/change-order-status', [ApiController::class, 'changeOrderStatus'])->name('change.order.status');


Route::get('/get-statement', [ApiController::class, 'getStatement'])->name('get.statement');
Route::get('/download-statement', [ApiController::class, 'downloadStatement'])->name('download.statement');
Route::get('/view-statement', [ApiController::class, 'viewStatement'])->name('view.statement');

Route::get('/delete-order/{id}', [ApiController::class, 'deleteOrder'])->name('delete.order');

//forgot password
Route::post('/forgot-password', [ApiController::class, 'ForgetPassword'])->name('forgot.password');


//Change Password

Route::post('/change-password', [ApiController::class, 'changePassword'])->name('change.password');

//Get Product Inquiry Form Data from arcon website
Route::post('/cf7_data', [ApiController::class,'cf7_data'])->name('cf7_data');

// Agent Api's
Route::get('/agent-details/{id}', [ApiController::class, 'agentData'])->name('agent.details');
Route::get('/agent/dealers/{id}', [ApiController::class, 'agentDealers'])->name('agent.dealers');
Route::get('/agent/orders/{id}', [ApiController::class, 'agentOrders'])->name('agent.orders');
Route::get('/agent/delete-order/{id}', [ApiController::class, 'agentDeleteOrder'])->name('agent.delete.order');
Route::post('/agent/verify-dealer', [ApiController::class, 'verifyAgentDealer'])->name('agent.verify.dealer');
Route::get('/agent/get-statement', [ApiController::class, 'getAgentStatement'])->name('agent.get.statement');
Route::get('/agent/download-statement', [ApiController::class, 'downloadAgentStatement'])->name('agent.download.statement');
Route::get('/agent/view-statement', [ApiController::class, 'viewAgentStatement'])->name('agent.view.statement');
Route::post('/agent/place-order', [ApiController::class, 'placeAgentOrder'])->name('agent.order');
Route::post('/agent/update', [ApiController::class, 'updateAgent'])->name('agent.update');

Route::fallback(function () {
    return Response::json(["error" => 0], 404);
});
