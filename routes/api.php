<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatContoller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OmsetController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UserChatController;
use App\Http\Controllers\UserController;
use App\Models\DataCustomer;
use App\Models\UserChat;
use App\Models\UserRoomChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/store/by/salesman/monthly', [StoreController::class, 'storeMonthly']);
Route::get('/customer/by/salesman/monthly', [CustomerController::class, 'customerMonthly']);
Route::post('/store/by/salesman/recap/range', [StoreController::class, 'storeRangeRecap']);
Route::post('/customer/by/salesman/recap/range', [CustomerController::class, 'customerRangeRecap']);


    Route::post('/refresh', [AuthController::class,'refresh']);

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:1000,1');

Route::middleware(['jwt.cookie', 'throttle:200,1', 'auth:api'])->group(function() {
    Route::get('/inboxs/count', [RequestController::class, 'count']);
});
Route::get('/salesman', [SalesmanController::class, 'allSalesman']);


Route::middleware(['jwt.cookie' ,'throttle:120,1'])->group(function() {
    // auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/proffile/edit', [UserController::class, 'edit']);
    Route::post('/change/password', [UserController::class, 'changePassword']);
    // salesman
    Route::post('/salesman/create', [SalesmanController::class, 'create']);
    Route::post('/salesman/delete', [SalesmanController::class, 'deleteSalesman']);
    Route::post('/salesman/hari-kerja',[SalesmanController::class, 'hariKerja']);
    // supervisor
    Route::get('/supervisor', [SupervisorController::class, 'getSupervisor']);
    Route::post('/supervisor/create', [SupervisorController::class, 'createSuperVisor']);
    Route::post('/supervisor/delete', [SupervisorController::class, 'deleteSupervisor']);
    // product
    Route::post('/brand/create', [ProductController::class, 'createBrand']);
    Route::post('/uom/create', [ProductController::class, 'createUom']);
    Route::post('/configuration/create', [ProductController::class, 'createConfiguration']);
    Route::get('/brand', [ProductController::class, 'getBrand']);
    Route::get('/uom', [ProductController::class, 'getUom']);
    Route::get('/configuration', [ProductController::class, 'getConfiguration']);
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::get('/products', [ProductController::class, 'getProduct']);
    Route::get('/product/{keyword}', [ProductController::class, 'searchProduct']);
    Route::post('/product/delete', [ProductController::class, 'delete']);
    Route::get('/product/detail/{id}', [ProductController::class, 'detail']);
    Route::post('/product/edit', [ProductController::class, 'edit']);
    Route::get('/products/all', [ProductController::class, 'all']);
    Route::post('/uom/edit',[ProductController::class, 'editUom']);
    Route::post('/brand/edit', [ProductController::class, 'editBrand']);
    Route::post('/configuration/edit', [ProductController::class, 'editConfiguration']);
    // customer
    Route::get('/customer/all', [CustomerController::class, 'allCust']);
    Route::get('/customers', [CustomerController::class, 'show']);
    Route::get('/customers/{keyword}', [CustomerController::class, 'search']);
    Route::post('/customer/delete', [CustomerController::class, 'delete']);
    Route::post('/customer/create', [CustomerController::class, 'create']);
    Route::get('/customer/detail/{id}', [CustomerController::class, 'detail']);
    Route::post('/customer/edit', [CustomerController::class, 'edit']);
    // store
    Route::get('/store/all', [StoreController::class, 'allStore']);
    Route::get('/stores', [StoreController::class, 'all']);
    Route::get('/store/{keyword}', [StoreController::class,'search']);
    Route::post('/store/edit', [StoreController::class, 'edit']);
    Route::get('/store/detail/{id}', [StoreController::class, 'detail']);
    Route::post('/store/create', [StoreController::class, 'create']);
    Route::post('/store/delete', [StoreController::class, 'destroy']);
    // request salesman
    Route::post('/salesman/request', [RequestController::class, 'create']);
    Route::get('/salesman/all/request', [RequestController::class, 'allSalesmanRequest']);
    Route::get('/salesman/detail/requests/{keyword}', [RequestController::class, 'detailRequest']);
    // inbox
    Route::get('/inbox/all', [InboxController::class, 'getInbox']);
    Route::get('/inbox/{keyword}', [InboxController::class, 'search']);
    Route::get('/inbox/detail/{id}', [InboxController::class, 'detail']);
    // tindakan request
    Route::post('/reject', [RequestController::class, 'reject']);
    Route::post('/approved', [RequestController::class, 'approved']);
    // payment method
    Route::get('/payment/method',[RequestController::class, 'getPaymentMethod']);
    Route::post('/payment-method/create', [PaymentMethodController::class, 'create']);
    Route::post('/payment-method/edit', [PaymentMethodController::class, 'edit']);
    // attendance
    Route::post('/attendance/create', [AttendanceController::class, 'createAttendance']);
    Route::post('/attendance', [AttendanceController::class, 'create']);
    Route::post('/izin', [AttendanceController::class, 'izin']);
    Route::post('/sakit', [AttendanceController::class, 'sakit']);
    Route::get('/attendances/detail/{date}', [AttendanceController::class, 'show']);
    Route::get('/attendances/today', [AttendanceController::class, 'today']);
    Route::get('/attendance/type', [AttendanceController::class, 'type']);
    Route::post('/attendance/edit', [AttendanceController::class, 'edit']);
    Route::post('/attendance/edit/type', [AttendanceController::class, 'editType']);
    // discount manage
    Route::post('/discount2', [DiscountController::class, 'discount2']);
    Route::post('/discount1', [DiscountController::class, 'discount1']);
    Route::post('/discount1/create', [DiscountController::class, 'create1']);
    // omset
    Route::post('/salesman/target', [OmsetController::class, 'create']);
    Route::get('/chart/omset', [OmsetController::class, 'chartOmset']);
    Route::get('/pie/omset',[OmsetController::class, 'chartOmsetPie']);
    Route::get('/omset/salesman',[OmsetController::class, 'omsetSalesman']);
    Route::post('/omset/edit', [OmsetController::class,'editOmset']);
    // user chat
    Route::get('/user/chat', [UserChatController::class, 'index']);
    // notification
    Route::post('/notification/global/create', [NotificationController::class, 'createGlobal']);
    Route::post('/notification/create', [NotificationController::class, 'notifCreate']);
    Route::get('/salesman/notification', [NotificationController::class, 'salesmanNotif']);
    Route::get('/notification/count', [NotificationController::class, 'count']);
    Route::post('/notification/read', [NotificationController::class, 'readNotif']);

    // user
    Route::get('/user/all', [UserController::class, 'getUser']);
    // chat developer
    Route::post('/chat/developer', [ChatContoller::class, 'send']);
    Route::get('/user/chat', [ChatContoller::class, 'getUserChat']);
    Route::post('/developer/detail/chat', [ChatContoller::class, 'devDetailChat']);
    Route::get('/developer/room-chat', [ChatContoller::class, 'roomChatDev']);
    Route::post('/developer/send', [ChatContoller::class, 'devSend']);
    Route::get('/user/chat/count/notification', [ChatContoller::class, 'userChatCountNotification']);
    Route::post('/user/chat/read', [ChatContoller::class, 'userReadChat']);
});

Route::get('/salesman/omset/monthly', [OmsetController::class, 'salesmanOmset']);
