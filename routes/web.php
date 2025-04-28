<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TraderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\WithdrawTypeController;
use App\Models\Plan;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Dashboard\DashboardController;

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



// admin routes

Route::get('/admin', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/admin', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin');


Route::get('/admin/{userId}', [DashboardController::class, 'edit'])
    ->name('admin.edit');

Route::get('/admin/plan/edit', [PlanController::class, 'edit'])
    ->name('admin.plan');

Route::get('/admin/withdraw_type/edit', [WithdrawTypeController::class, 'edit'])
    ->name('admin.withdraw_type');

Route::get('/admin/wallet/edit', [WalletController::class, 'edit'])
    ->name('admin.wallet');

Route::get('/admin/trader/edit', [TraderController::class, 'edit'])
    ->name('admin.trader');

Route::get('/admin/{userId}/notification/', [NotificationController::class, 'create'])
    ->name('admin.createNotification');

Route::get('/admin/{userId}/deposit', [DepositController::class, 'create'])
    ->name('admin.createDeposit');

Route::get('/admin/{userId}/withdraw', [WithdrawController::class, 'create'])
    ->name('admin.createWithdraw');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//pages route
Route::get('/', function () {
    $plans = Plan::all();
    return view('pages.index', compact('plans'));
});

Route::get('/welcome', function () {
    return view('welcome');
});


//dashboard

require __DIR__ . '/auth.php';
