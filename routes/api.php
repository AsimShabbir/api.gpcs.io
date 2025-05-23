<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\GpcsController;
use App\Http\Controllers\api\v1\GenerateGpcsController;
use App\Http\Controllers\api\v1\GetCountryCodeFromOpenStreetMapController;
use App\Http\Controllers\api\v1\GetCountryCodeFromGoogleMapController;
use App\Http\Controllers\api\v1\Users\RegistrationsController;
use App\Http\Controllers\api\v1\GetCountryCodeFromDomainController;
use App\Http\Controllers\api\v1\Users\AuthController;
use App\Http\Controllers\api\v1\Users\GenerateCodeController;
use App\Http\Controllers\api\v1\Users\StripePaymentController;
use App\Http\Controllers\api\v1\Admin\GPCSPaymentChargeController;
use App\Http\Controllers\api\v1\SMSController;
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

Route::get('generategpcscode', [GpcsController::class, 'generateGPCSCode']);
Route::get('generatecode',[GenerateGpcsController::class, 'generateGPCSCode']);
Route::get('getcountrycodefromopenstreetmap',[GetCountryCodeFromOpenStreetMapController::class,'getCountryCodeFromOpenStreetMap']);
Route::get('getcountrycodefromgooglemap',[GetCountryCodeFromGoogleMapController::class,'getCountryCodeFromGoogleMap']);
Route::get('getcountrycodefromdomain',[GetCountryCodeFromDomainController::class,'getCountryCodeFromDomain']);
Route::post('signup',[RegistrationsController::class,'registration']);
Route::post('verify_user_otp',[RegistrationsController::class,'verify_user_otp']);
Route::post('login', [AuthController::class, 'signin']);
Route::middleware('auth:sanctum')->group( function () {
    Route::get('codes', [GenerateCodeController::class,'index']);
    Route::post('code', [GenerateCodeController::class,'store']);
    Route::put('verified_location', [GenerateCodeController::class,'verified_location']);
    Route::put('update_label', [GenerateCodeController::class,'update_label']);
    Route::put('delete_gpc', [GenerateCodeController::class,'delete_gpc']);
    Route::get('get_users_all_gpcs', [GenerateCodeController::class,'get_users_all_gpcs']);
    Route::get('get_gpcs_counts', [GenerateCodeController::class,'get_gpcs_counts']);
    //Route::post('stripe_payment', [StripePaymentController::class, 'processOneTimeDonation']);
});
Route::post('stripe_payment', [StripePaymentController::class, 'processOneTimeDonation']);
Route::post('/sms/send', [SMSController::class, 'sendSMS']);


 Route::get('/payment/active_charges', [GPCSPaymentChargeController::class,'get_active_payment_charge']);
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/payment/charges', [GPCSPaymentChargeController::class,'index']);
    Route::post('/payment/charge', [GPCSPaymentChargeController::class,'store']);
    Route::put('/payment/charge/{id}', [GPCSPaymentChargeController::class, 'update']);
});
