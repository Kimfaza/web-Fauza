<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactControler;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\PaymentController;

use Illuminate\Support\Facades\Route;



Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('product', [ProductController::class, 'getAllProducts'])->name('produk');
Route::get('detail', [LandingController::class, 'detail'])->name('detail');
Route::get('product/{product:id}', [ProductController::class, 'detail']);


Route::get('/payment', function () {
    return view('payment');
});
    Route::post('/create-transaction', [PaymentController::class, 'createTransaction'])->name('create-transaction');
    Route::post('/payment-success', [PaymentController::class, 'success']);


Route::middleware('auth')->group(function () {
    // cart
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add.to.cart');

    Route::get('cart', [CartController::class, 'index'])->name('cart');
    Route::post('cart', [CartController::class, 'add']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);
    Route::patch('cart/{id}', [CartController::class, 'update']);



    // // Payment Midtrans
    // Route::get('payment', [PaymentController::class, 'paymentSuccess'])->name('payment');
    // // Route::post('/payment', [PaymentController::class, 'createPayment']);
    // // Route::get('/payment-success', [PaymentController::class, 'paymentSuccess']);
    // Route::get('/payment/details', [PaymentController::class, 'showPaymentDetails'])->name('paymentdetails');


    // Route::post('/create-transaction', [PaymentController::class, 'createTransaction'])->name('create-transaction');
    

    // Route::post('/payment-success', [PaymentController::class, 'success']);


    // checkout
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

    // logout
    Route::post('logout', [UserController::class, 'logout'])->name('logout');

    // thanks page
    Route::get('thanks', [CheckoutController::class, 'thanks'])->name('thanks');

    // history
    Route::get('history', [CheckoutController::class, 'getTransaction'])->name('history');
});


Route::get('login', [UserController::class, 'showlogin'])->name('login');
Route::get('register', [UserController::class, 'showRegister'])->name('register');
Route::post('login', [UserController::class, 'loginProcess']);
Route::post('register', [UserController::class, 'registerProcess']);

// Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handleWebhook']);

Route::get('contact', [ContactControler::class, 'index'])->name('contact');
Route::get('chat', [ChatController::class, 'index'])->name('chat');
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handleWebhook']);
