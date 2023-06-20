<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shop\ProfileController as ShopprofileController;
use App\Http\Controllers\Shop\OrderController as ShoporderController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\OpeninghourController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController as AdminloginController;
use App\Http\Controllers\Admin\ShopController as AdminshopController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\DriverController as AdmindriverController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\OrderController as AdminorderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Shop\ServiceController as ShopserviceController;
use App\Http\Controllers\Driver\LoginController as DriverloginController;
use App\Http\Controllers\Driver\ProfileController as DriverprofileController;
use App\Http\Controllers\Driver\OrderController as DriverorderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('postlogin', [LoginController::class, 'postlogin'])->name('postlogin');
Route::resource('register', RegisterController::class);
Route::resource('maps', MapsController::class);

// About
Route::get('about', [AboutController::class, 'index'])->name('about');

// Contact
Route::get('contact', [ContactController::class, 'index'])->name('contact');


// Shop
Route::get('shop/login', [ShopController::class, 'login'])->name('shop.login');
Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('shop/logout', [ShopController::class, 'logout'])->name('shop.logout');
Route::post('shop/postlogin', [ShopController::class, 'postlogin'])->name('shop.postlogin');
Route::get('shop/register', [ShopController::class, 'register'])->name('shop.register');
Route::post('shop/store', [ShopController::class, 'store'])->name('shop.store');
Route::get('shop/show/{slug}', [ShopController::class, 'show'])->name('shop.show');

// Administrator
Route::get('admin/login', [AdminloginController::class, 'index'])->name('admin.login');
Route::get('admin/logout', [AdminloginController::class, 'logout'])->name('admin.logout');
Route::post('admin/postlogin', [AdminloginController::class, 'postlogin'])->name('admin.postlogin');

// Driver
Route::get('driver/login', [DriverloginController::class, 'index'])->name('driver.login');
Route::post('driver/postlogin', [DriverloginController::class, 'postlogin'])->name('driver.postlogin');
Route::get('driver/register', [DriverloginController::class, 'register'])->name('driver.register');
Route::post('driver/postregister', [DriverloginController::class, 'postregister'])->name('driver.postregister');
Route::get('driver/logout', [DriverloginController::class, 'logout'])->name('driver.logout');

// Service
Route::get('services', [ServiceController::class, 'index'])->name('services.index');
Route::get('services/{slug}/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::post('service/search', [SearchController::class, 'store'])->name('service.search');

// Verification
Route::get('email/verify/need-verification', [VerificationController::class, 'notice'])->middleware('auth')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');

Route::group(['middleware' => ['auth:web']], function() {

    Route::middleware(['auth', 'verified'])->group(function() {

        // Profile
        Route::get('profile/editpassword', [ProfileController::class, 'editpassword'])->name('profile.editpassword');
        Route::get('profile/membercard/{id}', [ProfileController::class, 'membercard'])->name('profile.membercard');
        Route::post('profile/updatepassword', [ProfileController::class, 'updatepassword'])->name('profile.updatepassword');
        Route::post('profile/updateaddress', [ProfileController::class, 'updateaddress'])->name('profile.updateaddress');
        Route::resource('profile', ProfileController::class);
    
        // Order
        Route::get('order/getlistdata', [OrderController::class, 'listData'])->name('order.list');
        Route::get('order/getlistfinish', [OrderController::class, 'listFinish'])->name('order.list.finish');
        Route::get('order/getlistcancel', [OrderController::class, 'listCancel'])->name('order.list.cancel');
        Route::get('order/getdriver', [OrderController::class, 'getDriver'])->name('order.driver');
        Route::get('order/payment/{id}', [OrderController::class, 'payment'])->name('order.payment');
        Route::get('order/tracking/{id}', [OrderController::class, 'tracking'])->name('order.tracking');
        Route::get('driver/order/confirmation/{id}', [OrderController::class, 'confirmation'])->name('order.confirmation');
        Route::put('order/payment_process/{id}', [OrderController::class, 'paymentProcess'])->name('order.payment_process');
        Route::get('order/cancel/{id}', [OrderController::class, 'destroy'])->name('order.cancel');
        Route::resource('order', OrderController::class);
    
        // Review
        Route::post('review-service', [ServiceController::class, 'reviewstore'])->name('review.service.store');
    });

});

Route::group(['middleware' => ['auth:webdriver']], function() {

    // Profile
    Route::get('driverprofile/editpassword', [DriverprofileController::class, 'editpassword'])->name('driverprofile.editpassword');
    Route::post('driverprofile/updatepassword', [DriverprofileController::class, 'updatepassword'])->name('driverprofile.updatepassword');
    Route::resource('driverprofile', DriverprofileController::class);

    // Order
    Route::get('driver/order', [DriverorderController::class, 'index'])->name('driver.order');
    Route::get('driver/order/getlistdata', [DriverorderController::class, 'listData'])->name('driver.order.list');
    Route::get('driver/order/getlistfinish', [DriverorderController::class, 'listFinish'])->name('driver.order.finish');
    Route::get('driver/order/confirmation/{order_id}/{id}', [DriverorderController::class, 'confirmation'])->name('driver.order.confirmation');
    Route::get('driver/order/pickup/{order_id}/{id}', [DriverorderController::class, 'pickup'])->name('driver.order.pickup');
    Route::get('driver/order/shop/{order_id}/{id}', [DriverorderController::class, 'shop'])->name('driver.order.shop');
    Route::get('driver/order/received/{order_id}/{id}', [DriverorderController::class, 'received'])->name('driver.order.received');
    Route::get('driver/order/google_maps/{id}', [DriverorderController::class, 'google_maps'])->name('driver.order.google_maps');

});

Route::group(['middleware' => ['auth:webshop']], function() {

    // Profile
    Route::get('shopprofile/editpassword', [ShopprofileController::class, 'editpassword'])->name('shopprofile.editpassword');
    Route::post('shopprofile/updatepassword', [ShopprofileController::class, 'updatepassword'])->name('shopprofile.updatepassword');
    Route::post('shopprofile/updateaddress', [ShopprofileController::class, 'updateaddress'])->name('shopprofile.updateaddress');
    Route::resource('shopprofile', ShopprofileController::class);

    // Opening Hour
    Route::get('openinghour/getlistdata', [OpeninghourController::class, 'listData'])->name('openinghour.list');
    Route::get('openinghour/delete/{id}', [OpeninghourController::class, 'destroy'])->name('openinghour.delete');
    Route::resource('openinghour', OpeninghourController::class);

    // Rekening
    Route::get('rekening/getlistdata', [RekeningController::class, 'listData'])->name('rekening.list');
    Route::get('rekening/delete/{id}', [RekeningController::class, 'destroy'])->name('rekening.delete');
    Route::resource('rekening', RekeningController::class);

    // Service
    Route::get('shopservice/getlistdata', [ShopserviceController::class, 'listData'])->name('shopservice.list');
    Route::get('shopservice/delete/{id}', [ShopserviceController::class, 'destroy'])->name('shopservice.delete');
    Route::resource('shopservice', ShopserviceController::class);

    // Order
    Route::get('shop/order', [ShoporderController::class, 'index'])->name('shop.order');
    Route::get('shop/order/getdriver', [OrderController::class, 'getDriver'])->name('shop.order.driver');
    Route::get('shop/order/getlistdata', [ShoporderController::class, 'listData'])->name('shop.order.list');
    Route::get('shop/order/getlistfinish', [ShoporderController::class, 'listFinish'])->name('shop.order.list.finish');
    Route::get('shop/order/getlistcancel', [ShoporderController::class, 'listCancel'])->name('shop.order.list.cancel');
    Route::get('shop/order/tracking/{id}', [ShoporderController::class, 'tracking'])->name('shop.order.tracking');
    Route::get('shop/order/confirm/{order_id}', [ShoporderController::class, 'confirm'])->name('shop.order.confirm');
    Route::get('shop/order/confirmation/{order_id}', [ShoporderController::class, 'confirmation'])->name('shop.order.confirmation');
    Route::get('shop/order/delivery/{order_id}', [ShoporderController::class, 'delivery'])->name('shop.order.delivery');
    Route::put('shop/order/deliverypost/{order_id}', [ShoporderController::class, 'postDelivery'])->name('shop.order.postdelivery');

});

Route::group(['middleware' => ['auth:webadmin']], function() {

    // Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Shop
    Route::get('admin/shop', [AdminshopController::class, 'index'])->name('admin.shop');
    Route::get('admin/shop/getlistdata', [AdminshopController::class, 'listData'])->name('admin.shop.list');
    Route::get('admin/shop/edit/{id}', [AdminshopController::class, 'edit'])->name('admin.shop.edit');
    Route::get('admin/shop/delete/{id}', [AdminshopController::class, 'destroy'])->name('admin.shop.delete');

    // Order
    Route::get('admin/order', [AdminorderController::class, 'index'])->name('admin.order');
    Route::get('admin/order/payment', [AdminorderController::class, 'payment'])->name('admin.order.payment');
    Route::get('admin/order/getlistdata', [AdminorderController::class, 'listData'])->name('admin.order.list');
    Route::get('admin/order/getlistpayment', [AdminorderController::class, 'listPayment'])->name('admin.order.list.payment');
    Route::get('admin/order/confirmation/{id}', [AdminorderController::class, 'confirmation'])->name('admin.order.confirmation');
    Route::get('admin/order/delete/{id}', [AdminorderController::class, 'destroy'])->name('admin.order.delete');

    // Payment
    Route::get('admin/payment/shop', [PaymentController::class, 'shop'])->name('admin.payment.shop');
    Route::get('admin/payment/getlistshop', [PaymentController::class, 'listShop'])->name('admin.payment.list.shop');
    Route::get('admin/payment/shop/confirmation/{id}', [PaymentController::class, 'show'])->name('admin.payment.shop.confirmation');
    Route::put('admin/payment/shop/postconfirmation/{id}', [PaymentController::class, 'update'])->name('admin.payment.shop.postconfirmation');

    // Member
    Route::get('admin/member', [MemberController::class, 'index'])->name('admin.member');
    Route::get('admin/member/getlistdata', [MemberController::class, 'listData'])->name('admin.member.list');
    Route::get('admin/member/edit/{id}', [MemberController::class, 'edit'])->name('admin.member.edit');
    Route::get('admin/member/delete/{id}', [MemberController::class, 'destroy'])->name('admin.member.delete');

    // Driver
    Route::get('admin/driver', [AdmindriverController::class, 'index'])->name('admin.driver');
    Route::get('admin/driver/getlistdata', [AdmindriverController::class, 'listData'])->name('admin.driver.list');
    Route::get('admin/driver/edit/{id}', [AdmindriverController::class, 'edit'])->name('admin.driver.edit');
    Route::get('admin/driver/delete/{id}', [AdmindriverController::class, 'destroy'])->name('admin.driver.delete');

    // Setting
    Route::get('admin/setting', [SettingController::class, 'index'])->name('admin.setting');
    Route::get('admin/setting/getlistdata', [SettingController::class, 'listData'])->name('admin.setting.list');
    Route::get('admin/setting/edit/{id}', [SettingController::class, 'edit'])->name('admin.setting.edit');
    Route::put('admin/setting/update/{id}', [SettingController::class, 'update'])->name('admin.setting.update');
    Route::delete('admin/setting/delete/{id}', [SettingController::class, 'destroy'])->name('admin.setting.delete');

    // Bank
    Route::get('admin/bank', [BankController::class, 'index'])->name('admin.bank');
    Route::get('admin/bank/getlistdata', [BankController::class, 'listData'])->name('admin.bank.list');
    Route::get('admin/bank/add', [BankController::class, 'create'])->name('admin.bank.add');
    Route::get('admin/bank/edit/{id}', [BankController::class, 'edit'])->name('admin.bank.edit');
    Route::post('admin/bank/store', [BankController::class, 'store'])->name('admin.bank.store');
    Route::put('admin/bank/update/{id}', [BankController::class, 'update'])->name('admin.bank.update');
    Route::get('admin/bank/delete/{id}', [BankController::class, 'destroy'])->name('admin.bank.delete');

    // Slider
    Route::get('admin/slider', [SliderController::class, 'index'])->name('admin.slider');
    Route::get('admin/slider/getlistdata', [SliderController::class, 'listData'])->name('admin.slider.list');
    Route::get('admin/slider/add', [SliderController::class, 'create'])->name('admin.slider.add');
    Route::get('admin/slider/edit/{id}', [SliderController::class, 'edit'])->name('admin.slider.edit');
    Route::post('admin/slider/store', [SliderController::class, 'store'])->name('admin.slider.store');
    Route::put('admin/slider/update/{id}', [SliderController::class, 'update'])->name('admin.slider.update');
    Route::get('admin/slider/delete/{id}', [SliderController::class, 'destroy'])->name('admin.slider.delete');
});
