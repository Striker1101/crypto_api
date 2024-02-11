<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\PlanController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('/dashboard/{userId}', [DashboardController::class, 'edit'])
    ->name('dashboard.edit');

Route::get('/dashboard/plan/edit', [PlanController::class, 'edit'])
    ->name('dashboard.plan');

Route::get('/dashboard/{userId}/notification/', [NotificationController::class, 'create'])
    ->name('dashboard.createNotification');

Route::get('/dashboard/{userId}/deposit', [DepositController::class, 'create'])
    ->name('dashboard.createDeposit');

Route::get('/dashboard/{userId}/withdraw', [WithdrawController::class, 'create'])
    ->name('dashboard.createWithdraw');

Route::get('/dashboard/{userId}/earning', [EarningController::class, 'create'])
    ->name('dashboard.createEarning');

Route::get('/dashboard/{userId}/investment', [InvestmentController::class, 'create'])
    ->name('dashboard.createInvestment');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
