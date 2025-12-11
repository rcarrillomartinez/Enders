<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransferReservaController;
use App\Http\Controllers\HotelPanelController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\RedirectIfAuthenticatedMultiGuard;
use Illuminate\Support\Facades\Route;

/**
 * Rutas Públicas - Autenticación
 */
Route::middleware(RedirectIfAuthenticatedMultiGuard::class)->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
});

/**
 * Rutas del Panel de Hotel
 */
Route::get('/hotel/dashboard', [HotelPanelController::class, 'index'])->name('hotel.dashboard');
Route::get('/hotel/reservas', [HotelPanelController::class, 'reservasIndex'])->name('hotel.reservas.index');
Route::get('/hotel/reservas/create', [HotelPanelController::class, 'reservasCreate'])->name('hotel.reservas.create');
Route::post('/hotel/reservas', [HotelPanelController::class, 'reservasStore'])->name('hotel.reservas.store');
Route::get('/hotel/commissions', [HotelPanelController::class, 'commissionsMonthly'])->name('hotel.commissions');

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

    // Admin: list all hotels
    Route::get('/admin/hotels', [AdminController::class, 'listHotels'])->name('admin.hotels.list');

    // Admin: view reservations for a specific hotel and monthly totals
    Route::get('/admin/hotels/{hotel}/reservas', [AdminController::class, 'hotelReservations'])->name('admin.hotels.reservas');

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
