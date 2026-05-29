<?php


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RopaFormController;
use App\Http\Controllers\Auth\OtpVerificationController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', function () {
        return view('auth.login');
    })->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// OTP Verification routes (authenticated but not verified)
Route::middleware(['auth'])->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'showVerificationForm'])->name('verify.otp');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resendOtp'])->name('resend.otp');
});

// Authenticated and verified routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::prefix('ropa')->name('ropa.')->group(function () {

        Route::get('/', [RopaFormController::class, 'index'])->name('index');
        Route::get('/create', [RopaFormController::class, 'create'])->name('create');
        Route::get('/{ropaForm}/edit', [RopaFormController::class, 'edit'])->name('edit');
        Route::get('/{ropaForm}', [RopaFormController::class, 'show'])->name('show');
        Route::get('/{ropaForm}/view-submitted', [RopaFormController::class, 'viewSubmitted'])->name('view-submitted');
        Route::put('/{ropaForm}', [RopaFormController::class, 'update'])->name('update');
        Route::delete('/{ropaForm}', [RopaFormController::class, 'destroy'])->name('destroy');
    });
});

// Authenticated routes
// Route::middleware(['auth'])->group(function () {
//     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//     Route::prefix('ropa')->name('ropa.')->group(function () {
//         Route::get('/', [RopaFormController::class, 'index'])->name('index');
//         Route::get('/create', [RopaFormController::class, 'create'])->name('create');
//         Route::get('/{ropaForm}/edit', [RopaFormController::class, 'edit'])->name('edit');
//         Route::get('/{ropaForm}', [RopaFormController::class, 'show'])->name('show');
//         Route::put('/{ropaForm}', [RopaFormController::class, 'update'])->name('update');
//         Route::delete('/{ropaForm}', [RopaFormController::class, 'destroy'])->name('destroy');
//     });
// });
