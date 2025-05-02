<?php

use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DepositTypeController;
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

// Route::get('/admin', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('admin');

// Route::get('/admin/{userId}', [DashboardController::class, 'edit'])
//     ->name('admin.edit');

// Route::get('/admin/plan/edit', [PlanController::class, 'edit'])
//     ->name('admin.plan');

// Route::get('/admin/withdraw_type/edit', [WithdrawTypeController::class, 'edit'])
//     ->name('admin.withdraw_type');

// Route::get('/admin/wallet/edit', [WalletController::class, 'edit'])
//     ->name('admin.wallet');

// Route::get('/admin/trader/edit', [TraderController::class, 'edit'])
//     ->name('admin.trader');

// Route::get('/admin/{userId}/notification/', [NotificationController::class, 'create'])
//     ->name('admin.createNotification');

// Route::get('/admin/{userId}/deposit', [DepositController::class, 'create'])
//     ->name('admin.createDeposit');

// Route::get('/admin/{userId}/withdraw', [WithdrawController::class, 'create'])
//     ->name('admin.createWithdraw');

Route::middleware(['auth', 'verified', 'admin.access'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');

        Route::get('/{userId}', [AdminController::class, 'edit'])->name('edit');

        Route::get('/plan/edit', [PlanController::class, 'edit'])->name('plan');

        Route::get('/withdraw_type/edit', [WithdrawTypeController::class, 'edit'])->name('withdraw_type');

        Route::get('/deposit_type/edit', [DepositTypeController::class, 'edit'])->name('deposit_type');

        Route::get('/account_type/edit', [AccountTypeController::class, 'edit'])->name('account_type');

        Route::get('/wallet/edit', [WalletController::class, 'edit'])->name('wallet');

        Route::get('/trader/edit', [TraderController::class, 'edit'])->name('trader');

        Route::get('/{userId}/notification', [NotificationController::class, 'create'])->name('createNotification');

        Route::get('/{userId}/deposit', [DepositController::class, 'create'])->name('createDeposit');

        Route::get('/{userId}/withdraw', [WithdrawController::class, 'create'])->name('createWithdraw');
    });




// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware('auth')->prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::get('/', 'edit')->name('profile.edit');
    Route::patch('/', 'update')->name('profile.update');
    Route::delete('/', 'destroy')->name('profile.destroy');
});


//pages route
Route::get('/', function () {
    $plans = Plan::take(3)->get();
    return view('pages.index', compact('plans'));
})->name('homepage');

Route::get('/about-us', function () {
    return view('pages.about');
})->name('about-us');

Route::get('/product', function () {
    $plans = Plan::take(3)->get();
    return view('pages.product', compact('plans'));
})->name('product');

Route::get('/membership', function () {
    $plans = Plan::take(3)->get();
    return view('pages.membership', compact('plans'));
})->name('membership');

Route::get('/contact-us', function () {
    return view('pages.contact');
})->name('contact-us');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'get_notification'])->name('index');
        Route::get('dashboard', ['as' => 'user-dashboard', 'uses' => 'DashboardController@getDashboard']);
        // Route::get('user-statement', ['as' => 'user-statement', 'uses' => 'DashboardController@getStatement']);
    
        // Route::get('user-edit', ['as' => 'user-edit', 'uses' => 'DashboardController@editUser']);
        // Route::put('user-edit/{id}', ['as' => 'user-update', 'uses' => 'DashboardController@updateUser']);
    
        // Route::get('switch/start/{id}', ['as' => 'user/switch/start/', 'uses' => 'DashboardController@user_switch_start']);
        // Route::get('switch/stop', ['as' => 'user/switch/stop', 'uses' => 'DashboardController@user_switch_stop']);
    
        // Route::get('/stocks/toggle', 'StockController@toggleStock')->name('stocks.toggle');
    

        // Route::get('user-password', ['as' => 'user-password', 'uses' => 'DashboardController@userPassword']);
        // Route::put('user-password/{id}', ['as' => 'user-password-update', 'uses' => 'DashboardController@updatePassword']);
    
        // Route::get('fund-add', ['as' => 'add-fund', 'uses' => 'DashboardController@addFund']);
        // Route::post('fund-add', ['as' => 'add-fund', 'uses' => 'DashboardController@storeFund']);
        // Route::get('fund-history', ['as' => 'fund-history', 'uses' => 'DashboardController@historyFund']);
    
        // Route::get('deposit-new', ['as' => 'deposit-new', 'uses' => 'DashboardController@newDeposit']);
        // Route::post('deposit-post', ['as' => 'deposit-post', 'uses' => 'DashboardController@postDeposit']);
        // Route::post('deposit-submit', ['as' => 'deposit-submit', 'uses' => 'DashboardController@depositSubmit']);
        // Route::get('deposit-history', ['as' => 'deposit-history', 'uses' => 'DashboardController@depositHistory']);
    
        // Route::get('repeat-history', ['as' => 'repeat-history', 'uses' => 'DashboardController@repeatHistory']);
        // Route::get('repeat-table/{id}', ['as' => 'repeat-table', 'uses' => 'DashboardController@repeatTable']);
    
        // Route::get('withdraw-new', ['as' => 'withdraw-new', 'uses' => 'WithdrawController@newWithdraw']);
        // Route::post('withdraw-new', ['as' => 'withdraw-new', 'uses' => 'WithdrawController@postWithdraw']);
        // Route::post('withdraw-submit', ['as' => 'withdraw-submit', 'uses' => 'WithdrawController@submitWithdraw']);
        // Route::post('submit-growth', ['as' => 'submit-growth', 'uses' => 'ManualPaymentController@submitGrowth']);
        // Route::get('withdraw-history', ['as' => 'withdraw-history', 'uses' => 'WithdrawController@withdrawHistory']);
    
        // Route::get('reference-user', ['as' => 'reference-user', 'uses' => 'DashboardController@referenceUser']);
        // Route::get('reference-history', ['as' => 'reference-history', 'uses' => 'DashboardController@referenceHistory']);
        // Route::post('add-profile', ['as' => 'add-profile', 'uses' => 'DashboardController@addProfilel']);
    
        // Route::get('user-activity', ['as' => 'user-activity', 'uses' => 'DashboardController@userActivity']);
        // Route::get('user-buy-and-trade', ['as' => 'user-buy-and-trade', 'uses' => 'DashboardController@userBuyAndSell']);
    
        // route::get('manual-fund-add', ['as' => 'manual-fund-add', 'uses' => 'DashboardController@manualFundAdd']);
        // route::post('manual-fund-add', ['as' => 'manual-fund-add', 'uses' => 'DashboardController@StoreManualFundAdd']);
        // Route::post('manual-fund-submit', ['as' => 'manual-fund-submit', 'uses' => 'DashboardController@submitManualFund']);
        // Route::get('manual-fund-history', ['as' => 'manual-fund-history', 'uses' => 'DashboardController@manualFundHistory']);
        // Route::get('manual-fund-details/{id}', ['as' => 'manual-fund-details', 'uses' => 'DashboardController@manualFundAddDetails']);
    
        // Route::get('user-notification', ['as' => 'user-notification', 'uses' => 'DashboardController@userNotify']);
        // Route::get('user-notification/{id}', ['as' => 'user-notification-details', 'uses' => 'DashboardController@userNotifyDetails']);
        // Route::get('user-notification-delete/{id}', ['as' => 'user-notification-delete', 'uses' => 'DashboardController@deleteNotification']);
        // Route::get('user-notification-compose', ['as' => 'user-notification-compose', 'uses' => 'DashboardController@userNotifyCompose']);
        // Route::post('user-notification-compose-submit', ['as' => 'user-notification-compose-submit', 'uses' => 'DashboardController@userNotifyComposeSubmit']);
        // Route::get('notification/{id}/status', 'DashboardController@updateNotificationStatus')->name('update-notification-status');
    

        // Route::get('user-task', ['as' => 'user-task', 'uses' => 'DashboardController@userTask']);
        // Route::get('tasks/{id}/delete', ['as' => 'delete-task', 'uses' => 'DashboardController@deleteTask']);
        // Route::post('user-task-store', ['as' => 'task.store', 'uses' => 'DashboardController@userTaskStore']);
        // Route::get('tasks/{id}/status', 'DashboardController@updateTaskStatus')->name('update-task-status');
    

        // Route::get('user-calender', ['as' => 'user-calender', 'uses' => 'DashboardController@userCalender']);
    
        // Route::post('user-liquidate', ['as' => 'user-liquidate', 'uses' => 'DashboardController@UserLiquidate']);
        // Route::post('user-task-submit', ['as' => 'user-task-submit', 'uses' => 'DashboardController@UserTaskSubmit']);
    });


//dashboard

require __DIR__ . '/auth.php';
