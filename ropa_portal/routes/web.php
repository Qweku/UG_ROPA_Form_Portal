<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RopaFormController;
use App\Http\Controllers\Auth\OtpVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SchoolController;

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/submitted-forms', [AdminController::class, 'submittedForms'])->name('submitted-forms');
    Route::get('/form/{ropaForm}', [AdminController::class, 'viewForm'])->name('view-form');
    Route::post('/form/{ropaForm}/approve', [AdminController::class, 'approveForm'])->name('approve-form');
    Route::post('/form/{ropaForm}/reject', [AdminController::class, 'rejectForm'])->name('reject-form');
    Route::get('/export-forms', [AdminController::class, 'exportForms'])->name('export-forms');
    Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk-action');
});

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
        Route::get('/edit/{step?}', [RopaFormController::class, 'edit'])->name('edit'); // step optional
        Route::post('/update', [RopaFormController::class, 'update'])->name('update');
        Route::get('/add-sub-process/{ropaForm}', [RopaFormController::class, 'addSubProcess'])->name('add-sub-process');
        Route::get('/finalize/{ropaForm}', [RopaFormController::class, 'finalize'])->name('finalize');
        Route::get('/submission/{ropaSubmission}', [RopaFormController::class, 'viewSubmission'])->name('view-submission');
        Route::delete('/{ropaForm}', [RopaFormController::class, 'destroy'])->name('destroy');
        Route::get('/add-more/{ropaForm}', [RopaFormController::class, 'addMore'])->name('add-more');
        Route::get('/api/schools/{college}', [SchoolController::class, 'index'])->name('schools.index');
        Route::post('/api/schools', [SchoolController::class, 'store'])->name('schools.store');
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
