<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;

// ─── AUTH ROUTES (guest only) ─────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showForm'])->name('auth.login');
    Route::post('/login', [LoginController::class, 'login'])->name('auth.login.post');

    // Register
    Route::get('/register', [RegisterController::class, 'showForm'])->name('auth.register');
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.register.post');

    // Forgot Password
    Route::get('/forgot-password', [OtpController::class, 'showForgotForm'])->name('auth.forgot');
    Route::post('/forgot-password', [OtpController::class, 'sendResetOtp'])->name('auth.forgot.send');

    // OTP Reset Password
    Route::get('/reset-password/otp', [OtpController::class, 'showResetOtpForm'])->name('auth.otp.reset-form');
    Route::post('/reset-password/otp', [OtpController::class, 'verifyResetOtp'])->name('auth.otp.reset-verify');
});

// ─── OTP VERIFY (accessible to guests with session) ──────────────────────
Route::get('/verify-email', [OtpController::class, 'showVerifyForm'])->name('auth.otp.verify-form');
Route::post('/verify-email', [OtpController::class, 'verify'])->name('auth.otp.verify');
Route::post('/verify-email/resend', [OtpController::class, 'resendVerify'])->name('auth.otp.resend');

// ─── LOGOUT ───────────────────────────────────────────────────────────────
Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');

// ─── PUBLIC ROUTES (accessible to everyone) ───────────────────────────────
// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Index pages
Route::get('incomes', [IncomeController::class, 'index'])->name('incomes.index');
Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('debts', [DebtController::class, 'index'])->name('debts.index');
Route::get('wishlists', [WishlistController::class, 'index'])->name('wishlists.index');
Route::get('savings', [SavingController::class, 'index'])->name('savings.index');


// ─── PROTECTED ROUTES (must be logged in) ────────────────────────────────
Route::middleware('auth')->group(function () {

    // Income
    Route::resource('incomes', IncomeController::class)->except(['index', 'show']);

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except(['index', 'show']);

    // Debts
    Route::resource('debts', DebtController::class)->except(['index', 'show']);
    Route::post('debts/{debt}/pay', [DebtController::class, 'pay'])->name('debts.pay');

    // Wishlists
    Route::resource('wishlists', WishlistController::class)->except(['index', 'show']);
    Route::post('wishlists/{wishlist}/purchase', [WishlistController::class, 'purchase'])->name('wishlists.purchase');

    // Savings
    Route::resource('savings', SavingController::class)->except(['index', 'show']);
});

// ─── HELPER UNTUK HOSTING ──────────────────────────────────────────────
use Illuminate\Support\Facades\Artisan;

Route::get('/setup-database', function() {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return '<h1>Database berhasil di-setup! 🎉</h1><p>Silakan kembali ke <a href="/">Halaman Utama</a>.</p>';
    } catch (\Exception $e) {
        return '<h1>Terjadi Error:</h1><p>' . $e->getMessage() . '</p>';
    }
});
