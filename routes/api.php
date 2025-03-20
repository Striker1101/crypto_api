<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\DepositTypeController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DebitCardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\KYCInfoController;
use App\Http\Controllers\TraderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\WithdrawTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

], routes: function ($router): void {
    //auth
    Route::post('login', 'App\Http\Controllers\API\AuthController@login');
    Route::post('register', 'App\Http\Controllers\API\AuthController@register');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.mail');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
});

Route::post('sendEmail', 'App\Http\Controllers\API\MailController@sendEmail');

Route::post('/verify_user_token', 'App\Http\Controllers\API\UserController@verify_user_token');
Route::post('/resend_user_token', [UserController::class, 'resend_user_token'])
    ->middleware('throttle:1,5');
Route::apiResources([
    'plan' => PlanController::class,
    'account_type' => AccountTypeController::class
]);
Route::post('/account_types/bulk_upload', [AccountTypeController::class, 'bulkUpload']);

Route::middleware('auth:sanctum')->group(function () {
    // Your authenticated API routes go here

    //auth
    Route::post('sendPasswordResetLink', 'App\Http\Controllers\API\PasswordResetRequestController@sendEmail');
    Route::post('resetPassword', 'App\Http\Controllers\API\ChangePasswordController@passwordResetProcess');
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout');
    Route::get('user-profile', 'App\Http\Controllers\API\AuthController@userProfile');
    Route::get('user/auth', 'App\Http\Controllers\API\UserController@auth');
    Route::get('/sendEmailVerificationLink', [AuthController::class, 'sendEmailVerificationLink']);
    Route::Post('/verifyEmail', ['uses' => 'AuthController@verifyEmail']);
    Route::Post('/storeDeposit', 'App\Http\Controllers\Dashboard\DashboardController@storeDeposit');
    Route::Post('/storeWithdraw', 'App\Http\Controllers\Dashboard\DashboardController@storeWithdraw');
    Route::get('/deposit_and_withdraw', 'App\Http\Controllers\API\UserController@deposit_and_withdraw');

    //resouces
    Route::apiResources([
        'asset' => AssetController::class,
        'debit_card' => DebitCardController::class,
        'deposit' => DepositController::class,
        'kyc_info' => KYCInfoController::class,
        'notify' => NotificationController::class,
        'withdraw' => WithdrawController::class,
        'account' => AccountController::class,
        'user' => UserController::class,
        'trader' => TraderController::class,
        'wallet' => WalletController::class,
        'withdraw_type' => WithdrawTypeController::class,
        'invest' => InvestmentController::class,
        'deposit_type' => DepositTypeController::class,
    ]);
});

