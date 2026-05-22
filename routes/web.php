<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing.index');
});

Route::get('/media/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);

    $filePath = storage_path('app/public/' . $path);

    abort_unless(is_file($filePath), 404);

    return response()->file($filePath);
})->where('path', '.*')->name('media.public');

// Auth Routes
Route::post('/midtrans/webhook', [\App\Http\Controllers\Brand\FinanceController::class, 'webhook'])->name('midtrans.webhook');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [LoginController::class, 'register']);

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/auth/google/role', [GoogleAuthController::class, 'showRoleSelection'])->name('auth.google.role');
    Route::post('/auth/google/role', [GoogleAuthController::class, 'handleRoleSelection'])->name('auth.google.role.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Dashboard & Features (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard.index'))->name('dashboard');

        // User Management CRUD
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Admin Submissions
        Route::get('/submissions', [\App\Http\Controllers\Admin\SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [\App\Http\Controllers\Admin\SubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/submissions/{submission}/approve', [\App\Http\Controllers\Admin\SubmissionController::class, 'approve'])->name('submissions.approve');
        Route::post('/submissions/{submission}/reject', [\App\Http\Controllers\Admin\SubmissionController::class, 'reject'])->name('submissions.reject');
        Route::get('/submissions/{submission}/proof', [\App\Http\Controllers\Admin\SubmissionController::class, 'getProof'])->name('submissions.proof');

        Route::get('/kreators', fn() => view('admin.kreators.index'))->name('kreators');
        Route::get('/brands', fn() => view('admin.brands.index'))->name('brands');
        Route::get('/campaigns', fn() => view('admin.campaigns.index'))->name('campaigns');
        Route::get('/withdrawals', fn() => view('admin.withdrawals.index'))->name('withdrawals');
        Route::get('/settings', fn() => view('admin.settings.index'))->name('settings');

        // Transactions & Escrow
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/escrow', [TransactionController::class, 'escrow'])->name('transactions.escrow');
    });
});


// Kreator Dashboard (Protected)
Route::middleware(['auth', \App\Http\Middleware\IsKreator::class])->prefix('kreator')->name('kreator.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kreator\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/campaigns', [\App\Http\Controllers\Kreator\CampaignController::class, 'index'])->name('campaigns');
    Route::get('/campaigns/{id}', [\App\Http\Controllers\Kreator\CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/ai-tools', [\App\Http\Controllers\Kreator\AIClipperController::class, 'index'])->name('ai_clipper');
    Route::post('/ai-tools/process', [\App\Http\Controllers\Kreator\AIClipperController::class, 'process'])->name('ai_clipper.process');
    Route::get('/ai-tools/clip/{clip}/status', [\App\Http\Controllers\Kreator\AIClipperController::class, 'status'])->name('ai_clipper.clip.status');
    Route::post('/ai-tools/clip/{clip}/cancel', [\App\Http\Controllers\Kreator\AIClipperController::class, 'cancel'])->name('ai_clipper.clip.cancel');
    Route::delete('/ai-tools/clip/{clip}', [\App\Http\Controllers\Kreator\AIClipperController::class, 'destroy'])->name('ai_clipper.clip.destroy');
    Route::get('/submissions', [\App\Http\Controllers\Kreator\SubmissionController::class, 'index'])->name('submissions');
    Route::get('/submissions/create', [\App\Http\Controllers\Kreator\SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions/create', [\App\Http\Controllers\Kreator\SubmissionController::class, 'store']);
    Route::post('/submissions', [\App\Http\Controllers\Kreator\SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/finance', [\App\Http\Controllers\Kreator\FinanceController::class, 'index'])->name('finance');
    Route::post('/finance/bank', [\App\Http\Controllers\Kreator\FinanceController::class, 'updateBank'])->name('finance.bank.update');
    Route::post('/finance/withdraw', [\App\Http\Controllers\Kreator\FinanceController::class, 'withdraw'])->name('finance.withdraw');
});

// Brand Dashboard (Protected)
Route::middleware(['auth', \App\Http\Middleware\IsBrand::class])->prefix('brand')->name('brand.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Brand\DashboardController::class, 'index'])->name('dashboard');

    // Brand Campaigns
    Route::get('/campaigns', [\App\Http\Controllers\Brand\CampaignController::class, 'index'])->name('campaigns');
    Route::get('/campaigns/create', [\App\Http\Controllers\Brand\CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [\App\Http\Controllers\Brand\CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{id}/edit', [\App\Http\Controllers\Brand\CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{id}', [\App\Http\Controllers\Brand\CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{id}', [\App\Http\Controllers\Brand\CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::get('/campaigns/{id}', [\App\Http\Controllers\Brand\CampaignController::class, 'show'])->name('campaigns.show');

    // Brand Submissions
    Route::get('/submissions', [\App\Http\Controllers\Brand\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [\App\Http\Controllers\Brand\SubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/approve', [\App\Http\Controllers\Brand\SubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{submission}/reject', [\App\Http\Controllers\Brand\SubmissionController::class, 'reject'])->name('submissions.reject');
    Route::get('/submissions/{submission}/proof', [\App\Http\Controllers\Brand\SubmissionController::class, 'getProof'])->name('submissions.proof');

    Route::get('/finance', [\App\Http\Controllers\Brand\FinanceController::class, 'index'])->name('finance');
    Route::post('/finance/topup', [\App\Http\Controllers\Brand\FinanceController::class, 'topup'])->name('finance.topup');
    Route::post('/finance/topup/callback', [\App\Http\Controllers\Brand\FinanceController::class, 'handleCallbackCallback'])->name('finance.topup.callback');
    Route::get('/profile', fn() => view('brand.profile.index'))->name('profile');
});
