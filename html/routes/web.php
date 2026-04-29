<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;

/* RUTAS PÚBLICAS (Landing Page) */
Route::get('/', [ReservationController::class, 'index'])->name('home');
Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve');

/* AUTENTICACIÓN GOOGLE Y LOGOUT */
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


/* RUTAS PROTEGIDAS (Requieren Login) */
Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Gestión de Pedidos
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{id}/payment', [OrderController::class, 'togglePayment'])->name('orders.togglePayment');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // NUEVA RUTA:  /statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');

});

/* PANEL ADMINISTRATIVO (Gestión de Platos en /admin) */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dishes.index');
    Route::post('/dishes', [AdminController::class, 'store'])->name('store');
    Route::put('/dishes/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/dishes/{id}', [AdminController::class, 'destroy'])->name('destroy');
});

/* PERFIL DE USUARIO */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()?->isAdmin()
            ? redirect()->route('admin.dishes.index')
            : redirect()->route('home');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
