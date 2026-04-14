<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Architect\ProfileController;
use App\Http\Controllers\Architect\ProjectController;
use App\Http\Controllers\Architect\AvailabilityController;
use App\Http\Controllers\Architect\BookingController;
use App\Http\Controllers\Architect\QuoteController;
use App\Http\Controllers\Architect\BlogController;
use App\Http\Controllers\Architect\MessageController;


Route::get('/', function () {
    return redirect()->route('login');
});
// auth
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'is.client'])->get('/client/dashboard', function () {
    return 'Client dashboard';
})->name('client.dashboard');

Route::middleware(['auth', 'is.architect'])->get('/architect/dashboard', function () {
    return 'Architect dashboard';
})->name('architect.dashboard');

Route::middleware(['auth', 'is.admin'])->get('/admin/dashboard', function () {
    return 'Admin dashboard';
})->name('admin.dashboard');













Route::middleware(['auth', 'is.architect'])->prefix('architect')->name('architect.')->group(function () {

        // Dashboard
        Route::get('/dashboard', fn() => view('architect.dashboard'))->name('dashboard');

        // Profil
        Route::get('/profile',        [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit',   [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile',        [ProfileController::class, 'update'])->name('profile.update');

        // Projets
        Route::get('/projects',              [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create',       [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects',             [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}',    [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}',    [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        // Disponibilités
        Route::get('/availabilities',             [AvailabilityController::class, 'index'])->name('availabilities.index');
        Route::post('/availabilities',            [AvailabilityController::class, 'store'])->name('availabilities.store');
        Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

        // Réservations
        Route::get('/bookings',                        [BookingController::class, 'index'])->name('bookings.index');
        Route::put('/bookings/{booking}/confirm',      [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::put('/bookings/{booking}/cancel',       [BookingController::class, 'cancel'])->name('bookings.cancel');

    });
