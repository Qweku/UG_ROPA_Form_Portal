<?php
// routes/web.php

use App\Http\Controllers\RopaFormController;
use Illuminate\Support\Facades\Route;

// Remove the auth middleware group temporarily
Route::prefix('ropa')->group(function () {
    Route::get('/', [RopaFormController::class, 'index'])->name('ropa.index');
    Route::get('/create', [RopaFormController::class, 'create'])->name('ropa.create');
    Route::get('/{ropaForm}/edit', [RopaFormController::class, 'edit'])->name('ropa.edit');
    Route::get('/{ropaForm}', [RopaFormController::class, 'show'])->name('ropa.show');
    Route::put('/{ropaForm}', [RopaFormController::class, 'update'])->name('ropa.update');
    Route::delete('/{ropaForm}', [RopaFormController::class, 'destroy'])->name('ropa.destroy');
});
