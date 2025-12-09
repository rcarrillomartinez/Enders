<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransferReservaController;
use Illuminate\Support\Facades\Route;

/**
 * Rutas Públicas - Autenticación
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
});

/**
 * Rutas Protegidas - Requieren Autenticación
 */
Route::middleware('CheckMultiGuardAuth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    /**
     * Rutas de Perfil
     */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin-only hotel creation (form + store). Methods validate admin guard internally.
    Route::get('/admin/hotels/create', [AuthController::class, 'showHotelCreate'])->name('admin.hotels.create');
    Route::post('/admin/hotels', [AuthController::class, 'storeHotel'])->name('admin.hotels.store');

    /**
     * Rutas de Reservas de Transfer
     */
    Route::resource('reservas', TransferReservaController::class);
    Route::get('/reservas-calendar', [TransferReservaController::class, 'calendar'])->name('reservas.calendar');
});

/**
 * Ruta de Inicio/Índice
 */
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');
