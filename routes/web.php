<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/register');

Route::middleware('khach')->group(function () {
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('dangnhap')->group(function () {
    Route::get('logout', [AuthController::class, 'logoutConfirm'])->name('logout.confirm');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('rooms', RoomController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('invoices', InvoiceController::class)->only(['index', 'show']);

    Route::get('rooms/{room}/delete', [RoomController::class, 'delete'])->name('rooms.delete');
    Route::get('bookings/{booking}/delete', [BookingController::class, 'delete'])->name('bookings.delete');

    Route::post('bookings/{booking}/services', [BookingController::class, 'addService'])->name('bookings.services.add');
    Route::delete('bookings/{booking}/services/{bookingServiceId}', [BookingController::class, 'removeService'])->name('bookings.services.remove');
});
