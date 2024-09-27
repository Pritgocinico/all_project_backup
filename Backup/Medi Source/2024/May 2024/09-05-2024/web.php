<?php

use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactInquryController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Admin\ProductDetailController;
use App\Http\Controllers\DoctorUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DosageController;
use App\Http\Controllers\Admin\LotController;
use App\Http\Controllers\Admin\OfferCodeController;
use App\Http\Controllers\HelpInquiriesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\FedExController;
use App\Http\Controllers\PaymentController;

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




// web.php
Route::get('/', [FrontController::class, 'index'])->name('home');

Route::get('/about/{id?}', [FrontController::class, 'about'])->name('about');

Route::get('/product', [FrontController::class, 'product'])->name('product');

Route::get('/catalog', [FrontController::class, 'catalog'])->name('catalog');

Route::get('/events', [FrontController::class, 'events'])->name('events');

Route::get('/clinical-trials', [FrontController::class, 'clinicalTrials'])->name('clinical.trials');

Route::get('/contactus', [FrontController::class, 'contactus'])->name('contactus');

Route::get('/surgery', [FrontController::class, 'surgery'])->name('surgery');

Route::get('/help', [FrontController::class, 'help'])->name('help');

Route::get('/order-form', [FrontController::class, 'orderForm'])->name('order.form');

Route::get('/order-page', [FrontController::class, 'orderNewPage'])->name('order-page');

Route::get('/hippa-compliance', [FrontController::class, 'hippaCompliancePage'])->name('hippa-compliance');

Route::get('/cookie-policy', [FrontController::class, 'cookiePolicy'])->name('cookie.policy');

Route::get('/product-detail', [FrontController::class, 'productdetail'])->name('product-detail');

Route::get('/product/{id}', [FrontController::class, 'productdetail'])->name('product-detail');

// Route::get('product-packages-price/{id}', [FrontController::class, 'getvariantPrice'])->name('getvariantPriceFront');
Route::get('product-packages-price', [FrontController::class, 'getvariantPrice'])->name('getvariantPriceFront');
Route::get('/fedex/rates', [FedExController::class, 'getRates'])->name('fedex-rate');
Route::get('/refresh-token', [CheckoutController::class, 'refreshQuickbookToken'])->name('refresh-token');

Route::middleware(['auth:web', 'role:doctor'])->group(function () {
    //Cart
    Route::post('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add.to.cart');
    Route::post('/update-cart', [CartController::class, 'UpdateCart'])->name('update.cart');
    Route::get('/delete-cart-item/{id}', [CartController::class, 'deleteCartItem'])->name('delete.cart.item');


    //Cart Page
    Route::get('/cart', [CartController::class, 'Cart'])->name('cart');
    // Route::get('/user/login', [FrontController::class, 'login'])->name('user.login');

    //Checkout Page
    Route::get('/checkout', [CheckoutController::class, 'Checkout'])->name('checkout');
    // Route::get('/fedex/rates', [FedExController::class, 'getRates'])->name('fedex-rate');

    Route::controller(PaymentController::class)->group(function () {
        Route::get('payment/{order_id}', 'stripe')->name('payment');
        Route::post('stripe', 'stripePost')->name('stripe.post');
        Route::get('get-card-detail', 'cardDetail')->name('get-card-detail');
    });
    Route::post('/transaction', [PaymentController::class, 'transactionStore'])->name('transaction.store');

    // Place Order
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place.order');

    Route::get('/my-account', [FrontController::class, 'myaccount'])->name('myaccount');
    Route::get('/my-orders', [FrontController::class, 'orders'])->name('orders');
    Route::get('/card-detail', [FrontController::class, 'userCardDetail'])->name('card-detail');
    Route::get('/view-order/{id}', [FrontController::class, 'viewOrder'])->name('cancel.order');
    Route::get('/cancel-order/{id}', [FrontController::class, 'cancelOrder'])->name('view.order');
    Route::get('/view-order/{id}', [FrontController::class, 'viewOrder'])->name('view.order');
    Route::get('/cancel-order/{id}', [FrontController::class, 'cancelOrder'])->name('cancel.order');
    Route::get('/re-order/{id}', [FrontController::class, 'reOrder'])->name('re.order');
    Route::post('/update-profile', [FrontController::class, 'updateProfile'])->name('update.profile');
    Route::post('/change-password', [FrontController::class, 'changePassword'])->name('change.password');
    Route::get('thank-you', [FrontController::class, 'thankYouPage'])->name('thank-you');
    Route::get('generate-invoice/{id}', [OrderController::class, 'generateInvoice'])->name('doctor-generate-invoice');
});
Route::get('city-by-state/{id}', [FrontController::class, 'cityByState'])->name('cityByState');


Route::get('/login', [FrontController::class, 'logindoctor'])->name('logindoctor');

// Route for handling the doctor login form submission
Route::post('/doctor/login-post', [DoctorUserController::class, 'doctorLogin'])->name('doctor.login');
Route::get('/forgot-password', [FrontController::class, 'forgotPassword'])->name('forgot.password');
// Route for displaying the registration form
Route::get('/register', [DoctorUserController::class, 'showRegistrationForm'])->name('register');

// Route for handling the registration form submission
Route::post('/register', [DoctorUserController::class, 'register']);



Route::post('/get-cities-for-state', [DoctorUserController::class, 'getCitiesForState'])->name('get-cities-for-state');




Route::get('/terms-and-condition', [FrontController::class, 'terms'])->name('terms');
Route::get('/pharmacy-agreement', [FrontController::class, 'pharmacy'])->name('pharmacy-agreement');
Route::get('/privay-policy', [FrontController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/jump', [FrontController::class, 'jump'])->name('jump');

Route::get('/qualityAssurance', [FrontController::class, 'qualityAssurance'])->name('quality.Assurance');

Route::get('/profile', [FrontController::class, 'profile'])->name('profile');

Route::get('/methylcobalamin', [FrontController::class, 'methylcobalamin'])->name('methylcobalamin');

Route::get('/methylcobalamin5ml', [FrontController::class, 'methylcobalamin5ml'])->name('methylcobalamin5ml');

Route::get('/glutathione', [FrontController::class, 'glutathione'])->name('glutathione');

Route::get('/faq', [FrontController::class, 'faq'])->name('faq');
Route::get('/meet-lead-pharmacy', [FrontController::class, 'leadPharmacy'])->name('meet-lead-pharmacy');

Route::get('/admin', [AuthController::class, 'index'])->name('admin');
Route::post('/submit-help-inquiry', [HelpInquiriesController::class, 'submitForm'])->name('submit.help.inquiry');
// Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('admin-login', [AuthController::class, 'adminLogin'])->name('admin_login');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('admin/logout', [AuthController::class, 'adminLogout'])->name('adminLogout');

Route::post('/submit-form', [InquiryController::class, 'submitForm'])->name('contact.submit');
Route::post('/contactus', [ContactInquryController::class, 'store'])->name('contactus.store');
Route::get('default-card/{id}', [FrontController::class, 'defaultCard'])->name('default-card');

Route::get('coupon-detail', [OfferCodeController::class, 'detailByCode'])->name('coupon-detail');

Route::middleware(['auth:admin', 'role:admin,user'])->group(function () {
    //Dashboard
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/404', [FrontController::class, 'error_404'])->name('not_found');

    Route::get('state-by-country/{id}', [UserController::class, 'stateByCountry'])->name('stateByCountry');

    Route::get('admin/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('admin/settings/update', [SettingController::class, 'update'])->name('admin.settings.update');

    Route::get('/admin/settings/companysetting', [SettingController::class, 'company'])->name('admin.settings.companysetting');
    Route::post('/admin/settings/company/save', [SettingController::class, 'saveCompany'])->name('admin.settings.company.save');

    Route::get('/admin/settings/email', [SettingController::class, 'email'])->name('admin.settings.email');
    Route::post('/admin/settings/email/save', [SettingController::class, 'saveEmail'])->name('admin.settings.email.save');

    Route::get('/admin/profile', [AdminProfileController::class, 'show'])->name('admin.profile.show');
    Route::post('/admin/profile/update', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/admin/profile/update-password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.update-password');
    Route::post('/check-email-unique', [AdminProfileController::class, 'updatePassword'])->name('check.email.unique');
    
    Route::get('admin/doctors/create', [DoctorUserController::class, 'create'])->name('admin.doctors.create');
    Route::post('doctors', [DoctorUserController::class, 'store'])->name('admin.doctors.store');
    Route::get('/admin/doctors/show/{id}', [DoctorUserController::class, 'show'])->name('admin.doctors.show');
    Route::post('/admin/doctors/{id}/submitForm', [DoctorUserController::class, 'submitForm'])->name('admin.doctors.submitForm');
    Route::post('admin/doctors/removeField', [DoctorUserController::class, 'removeField'])->name('admin.doctors.removeField');
    Route::post('admin/doctors/getproductPackage', [DoctorUserController::class, 'getproductPackage'])->name('getproductPackage');
    Route::put('/doctors/{id}/updateStatus', [DoctorUserController::class, 'updateStatus'])->name('admin.doctors.updateStatus');
    Route::get('/admin/doctors', [DoctorUserController::class, 'index'])->name('admin.doctors.index');
    Route::get('doctor/{id}/edit', [DoctorUserController::class, 'edit'])->name('admin.doctor.edit');
    Route::put('doctor/{id}', [DoctorUserController::class, 'update'])->name('admin.doctor.update');
    Route::delete('doctor/{id}', [DoctorUserController::class, 'destroy'])->name('admin.doctor.destroy');

    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('admin/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::post('admin-upload-document', [UserController::class, 'uploadDocument'])->name('admin.upload.document');
    Route::post('/user/{id}/updateStatus', [UserController::class, 'updateUserStatus'])->name('admin.user.updateStatus');
    // Route::get('admin/active/{id}', [UserController::class, 'userStatusChange'])->name('admin.users.active');

    //Orders
    Route::get('admin/orders', [OrderController::class, 'orders'])->name('admin.orders');
    Route::get('admin/order-view/{id}', [OrderController::class, 'viewOrder'])->name('admin.view.order');
    Route::get('admin/orders/create', [OrderController::class, 'create'])->name('create_order');
    Route::post('admin/orders', [OrderController::class, 'store'])->name('store');
    Route::post('admin/orders-status', [OrderController::class, 'changeStatus'])->name('changeStatus');
    Route::get('package-by-product/{id}', [OrderController::class, 'productPackage'])->name('productPackage');
    Route::get('price-by-package/{id}', [OrderController::class, 'packagePrice'])->name('packagePrice');
    Route::get('admin/orders/{id}/edit', [OrderController::class, 'edit'])->name('edit');
    Route::put('orders-details/{id}', [OrderController::class, 'update'])->name('update');
    Route::delete('admin/orders/{id}', [OrderController::class, 'destroy'])->name('destroy');
    Route::get('admin/generate-invoice/{id}', [OrderController::class, 'generateInvoice'])->name('generate-invoice');
    Route::get('admin/generate-PackageSlip/{id}', [OrderController::class, 'generatePackageSlip'])->name('generate-PackageSlip');
    Route::get('admin/generate-label/{id}', [FedExController::class, 'generateLabel'])->name('generate-label');

    Route::get('admin/product-details/create', [ProductDetailController::class, 'create'])->name('admin.product.create');
    Route::post('admin/product-details', [ProductDetailController::class, 'store'])->name('admin.product-details.store');
    Route::get('product-details', [ProductDetailController::class, 'index'])->name('admin.product-details.index');
    Route::get('product-details/{id}/edit', [ProductDetailController::class, 'edit'])->name('admin.product-details.edit');
    Route::put('product-details/{id}', [ProductDetailController::class, 'update'])->name('admin.product-details.update');
    Route::delete('product-details/{id}', [ProductDetailController::class, 'destroy'])->name('admin.product-details.destroy');
    Route::get('/admin/product-details/{id}', [ProductDetailController::class, 'show'])->name('admin.product-details.show');
    Route::get('product-package-price/{id}', [ProductDetailController::class, 'getvariantPrice'])->name('getvariantPrice');
    Route::get('remove-product-package', [ProductDetailController::class, 'removeProductPackage'])->name('remove-product-package');
    Route::get('remove-product-image', [ProductDetailController::class, 'removeProductImage'])->name('remove-product-image');

    Route::get('inquiries', [ContactInquryController::class, 'showInquiries'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [ContactInquryController::class, 'show'])->name('inquiries.show');
    Route::delete('inquiries/{inquiry}', [ContactInquryController::class, 'destroy'])->name('inquiries.destroy');


    Route::get('/homeinquiries', [InquiryController::class, 'index'])->name('homeinquiries.index');
    Route::delete('/homeinquiries/{id}', [InquiryController::class, 'destroy'])->name('homeinquiries.destroy');
    Route::get('/homeinquiries/{id}', [InquiryController::class, 'show'])->name('homeinquiries.show');

    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/dosage/create', [DosageController::class, 'create'])->name('dosage.create');
    Route::post('/dosage/store', [DosageController::class, 'store'])->name('dosage.store');
    Route::get('/dosage', [DosageController::class, 'index'])->name('dosage.index');
    Route::get('/dosage/{id}/edit', [DosageController::class, 'edit'])->name('dosage.edit');
    Route::put('/dosage/{id}/update', [DosageController::class, 'update'])->name('dosage.update');
    Route::delete('/dosage/{id}/destroy', [DosageController::class, 'destroy'])->name('dosage.destroy');

    Route::get('lots/create', [LotController::class, 'create'])->name('admin.lots.create');
    Route::post('lots', [LotController::class, 'store'])->name('admin.lots.store');
    Route::get('admin/lots', [LotController::class, 'index'])->name('admin.lots.index');
    Route::get('lots/{lot}/edit', [LotController::class, 'edit'])->name('admin.lots.edit');
    Route::put('lots/{lot}', [LotController::class, 'update'])->name('admin.lots.update');
    Route::delete('lots/{lot}', [LotController::class, 'destroy'])->name('admin.lots.destroy');
    Route::get('admin/lots/files', [LotController::class, 'showFiles'])->name('admin.lots.files');


    Route::get('/helpinquiries', [HelpInquiriesController::class, 'index'])->name('helpinquiries.index');
    Route::get('/helpinquiries/{id}', [HelpInquiriesController::class, 'show'])->name('helpinquiries.show');
    Route::delete('/helpinquiries/{id}/delete', [HelpInquiriesController::class, 'destroy'])->name('helpinquiries.delete');

    Route::get('payment-detail', [PaymentController::class, 'paymentDetail'])->name('payment-detail');

    Route::get('coupon/create', [OfferCodeController::class, 'create'])->name('coupon.create');
    Route::post('coupon', [OfferCodeController::class, 'store'])->name('coupon.store');
    Route::get('admin/coupon', [OfferCodeController::class, 'index'])->name('coupon.index');
    Route::get('coupon/{coupon}/edit', [OfferCodeController::class, 'edit'])->name('coupon.edit');
    Route::post('coupon/{coupon}', [OfferCodeController::class, 'update'])->name('coupon.update');
    Route::delete('coupon/{coupon}', [OfferCodeController::class, 'destroy'])->name('coupon.destroy');

    Route::get('admin/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('permission/create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('permission/store', [PermissionController::class, 'store'])->name('permission.store');
    Route::post('permission/update', [PermissionController::class, 'update'])->name('permission.update');
    Route::get('permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
});

// Route::post('/submit-help-inquiry', [HelpInquiriesController::class, 'submitForm'])->name('submit.help.inquiry');
