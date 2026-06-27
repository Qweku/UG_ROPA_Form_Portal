<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RopaFormController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

// // Admin routes
// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
//     Route::get('/submitted-forms', [AdminController::class, 'submittedForms'])->name('submitted-forms');
//     Route::get('/form/{ropaForm}', [AdminController::class, 'viewForm'])->name('view-form');
//     Route::post('/form/{ropaForm}/approve', [AdminController::class, 'approveForm'])->name('approve-form');
//     Route::post('/form/{ropaForm}/reject', [AdminController::class, 'rejectForm'])->name('reject-form');
//     Route::get('/export-forms', [AdminController::class, 'exportForms'])->name('export-forms');
//     Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk-action');
// });

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('ropa.index');
    }
    return redirect()->route('login');
})->name('home');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/submitted-forms', [AdminController::class, 'submittedForms'])->name('submitted-forms');
    Route::get('/form/{ropaForm}', [AdminController::class, 'viewForm'])->name('view-form');
    Route::get('/export-forms', [AdminController::class, 'exportForms'])->name('export-forms');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::put('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
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

    // User-facing RoPA form management routes
    Route::middleware(['user.only'])->prefix('ropa')->name('ropa.')->group(function () {
        Route::get('/', [RopaFormController::class, 'index'])->name('index');
        Route::get('/create', [RopaFormController::class, 'create'])->name('create');
        Route::get('/edit/{step?}', [RopaFormController::class, 'edit'])->name('edit');
        Route::post('/update', [RopaFormController::class, 'update'])->name('update');
        Route::get('/add-more/{ropaForm}', [RopaFormController::class, 'addMore'])->name('add-more');
        Route::get('/finalize/{ropaForm}', [RopaFormController::class, 'finalize'])->name('finalize');
        Route::get('/add-sub-process/{ropaForm}', [RopaFormController::class, 'addSubProcess'])->name('add-sub-process');
        Route::delete('/{ropaForm}', [RopaFormController::class, 'destroy'])->name('destroy');
    });

    // Shared submission detail routes (accessible to both users and admins)
    Route::prefix('ropa')->name('ropa.')->group(function () {
        Route::get('/submission/{submission}', [RopaFormController::class, 'viewSubmission'])->name('view-submission');
        Route::patch('/submission/{submission}', [RopaFormController::class, 'updateSubmission'])->name('update-submission');
        Route::patch('/submission/{submission}/identity', [RopaFormController::class, 'updateProcessIdentity'])->name('update-process-identity');
        Route::delete('/submission/{submission}', [RopaFormController::class, 'destroySubmission'])->name('destroy-submission');
        Route::get('/api/schools/{college}', [SchoolController::class, 'index'])->name('schools.index');
        Route::post('/api/schools', [SchoolController::class, 'store'])->name('schools.store');
    });
});

Route::get('/test-404', fn () => abort(404));
Route::get('/test-403', fn () => abort(403));
Route::get('/test-500', fn () => abort(500));
