<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DebitCardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\KYCInfoController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

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


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    //auth
    Route::post('login', 'App\Http\Controllers\API\AuthController@login');
    Route::post('register', 'App\Http\Controllers\API\AuthController@register');

});
Route::post('/sendEmailVerificationLink', 'App\Http\Controllers\API\AuthController@sendEmailVerificationLink');
Route::post('sendEmail', 'App\Http\Controllers\API\MailController@sendEmail');



Route::middleware('auth:sanctum')->group(function () {
    // Your authenticated API routes go here

    //auth
    Route::post('sendPasswordResetLink', 'App\Http\Controllers\API\PasswordResetRequestController@sendEmail');
    Route::post('resetPassword', 'App\Http\Controllers\API\ChangePasswordController@passwordResetProcess');
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout');
    Route::get('user-profile', 'App\Http\Controllers\API\AuthController@userProfile');
    Route::get('user/auth', 'App\Http\Controllers\API\UserController@auth');

    Route::Post('/verifyEmail', ['uses' => 'AuthController@verifyEmail']);
    Route::Post('/storeDeposit', 'App\Http\Controllers\Dashboard\DashboardController@storeDeposit');
    Route::Post('/storeWithdraw', 'App\Http\Controllers\Dashboard\DashboardController@storeWithdraw');

    //resouces
    Route::apiResources([
        'asset' => AssetController::class,
        'debit_card' => DebitCardController::class,
        'deposit' => DepositController::class,
        'plan' => PlanController::class,
        'invest' => InvestmentController::class,
        'earn' => EarningController::class,
        'kyc_info' => KYCInfoController::class,
        'notify' => NotificationController::class,
        'withdraw' => WithdrawController::class,
        'account' => AccountController::class,
        'user' => UserController::class,
    ]);
});
