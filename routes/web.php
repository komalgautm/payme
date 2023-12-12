<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaypalController;

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

Route::get('/', function () {
    return view('welcome');
});



Route::get('/createSubscription', [PaypalController::class, 'createSubscription']);






Route::get('/create_token', [PaymentController::class, 'authenticate']);
Route::get('/create_payment', [PaymentController::class, 'create_payment']);
Route::post('/create_payment_form', [PaymentController::class, 'index']);
// Route::get('/payment', [PaymentController::class, 'index']);
Route::get('/return', [PaymentController::class, 'retuen']);
Route::get('/success', [PaymentController::class, 'success']);
Route::get('/failure', [PaymentController::class, 'failure']);
Route::get('/generate_uuid', [PaymentController::class, 'generate_uuid']);
Route::get('/request_date_time', [PaymentController::class, 'request_date_time']);
Route::get('/paycode', [PaymentController::class, 'paycode']);


Route::get('/get_payment_request_status', [PaymentController::class, 'get_payment_request_status']);

Route::get('/test_image', [PaymentController::class, 'test_image']);
