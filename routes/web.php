<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Architect\ProfileController;
use App\Http\Controllers\Architect\ProjectController;
use App\Http\Controllers\Architect\AvailabilityController;
use App\Http\Controllers\Architect\BookingController as ArchitectBookingController;
use App\Http\Controllers\Architect\QuoteController as ArchitectQuoteController;
use App\Http\Controllers\Architect\BlogController as ArchitectBlogController;
use App\Http\Controllers\Architect\MessageController as ArchitectMessageController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ArchitectController;
use App\Http\Controllers\Client\BookingController as ClientBookingController;
use App\Http\Controllers\Client\QuoteController as ClientQuoteController;
use App\Http\Controllers\Client\MessageController as ClientMessageController;
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\Client\BlogController as ClientBlogController;

// ── Page d'accueil ────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->dashboardRouteName());
    }

    return redirect()->route('login');
});

// ── Authentification — accessible uniquement aux invités ──
// middleware 'guest' empêche un utilisateur déjà connecté
// d'accéder à login/register et évite les conflits de session
Route::middleware('guest')->group(function () {
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Espace Architecte ─────────────────────────────────────
Route::middleware(['auth', 'is.architect'])
    ->prefix('architect')
    ->name('architect.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('architect.dashboard'))->name('dashboard');

        // Profil
        Route::get('/profile',       [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit',  [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile',       [ProfileController::class, 'update'])->name('profile.update');

        // Projets
        Route::get('/projects',                [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create',         [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects',               [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}',      [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}',      [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}',   [ProjectController::class, 'destroy'])->name('projects.destroy');

        // Disponibilités
        Route::get('/availabilities',                    [AvailabilityController::class, 'index'])->name('availabilities.index');
        Route::post('/availabilities',                   [AvailabilityController::class, 'store'])->name('availabilities.store');
        Route::delete('/availabilities/{availability}',  [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

        // Réservations
        Route::get('/bookings',                      [ArchitectBookingController::class, 'index'])->name('bookings.index');
        Route::put('/bookings/{booking}/confirm',    [ArchitectBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::put('/bookings/{booking}/cancel',     [ArchitectBookingController::class, 'cancel'])->name('bookings.cancel');

        // Devis
        Route::get('/quotes',                          [ArchitectQuoteController::class, 'index'])->name('quotes.index');
        Route::get('/bookings/{booking}/quotes/create',[ArchitectQuoteController::class, 'create'])->name('quotes.create');
        Route::post('/bookings/{booking}/quotes',      [ArchitectQuoteController::class, 'store'])->name('quotes.store');
        Route::get('/quotes/{quote}/pdf',              [ArchitectQuoteController::class, 'pdf'])->name('quotes.pdf');

        // Blog
        Route::get('/blog',              [ArchitectBlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/create',       [ArchitectBlogController::class, 'create'])->name('blog.create');
        Route::post('/blog',             [ArchitectBlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit',  [ArchitectBlogController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}',       [ArchitectBlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}',    [ArchitectBlogController::class, 'destroy'])->name('blog.destroy');

        // Messages
        Route::get('/messages',          [ArchitectMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{user}',   [ArchitectMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{user}',  [ArchitectMessageController::class, 'store'])->name('messages.store');
    });

// espace client 
Route::middleware(['auth', 'is.client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Architectes
        Route::get('/architects',           [ArchitectController::class, 'index'])->name('architects.index');
        Route::get('/architects/{profile}', [ArchitectController::class, 'show'])->name('architects.show');

        // Réservations
        Route::get('/bookings',                   [ClientBookingController::class, 'index'])->name('bookings.index');
        Route::post('/architects/{profile}/book', [ClientBookingController::class, 'store'])->name('bookings.store');

        // Devis
        Route::get('/quotes',                  [ClientQuoteController::class, 'index'])->name('quotes.index');
        Route::put('/quotes/{quote}/accept',   [ClientQuoteController::class, 'accept'])->name('quotes.accept');
        Route::put('/quotes/{quote}/reject',   [ClientQuoteController::class, 'reject'])->name('quotes.reject');

        // Messages
        Route::get('/messages',          [ClientMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{user}',   [ClientMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{user}',  [ClientMessageController::class, 'store'])->name('messages.store');

        // Moodboard
        Route::get('/favorites',                  [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites',                 [FavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{favorite}',    [FavoriteController::class, 'destroy'])->name('favorites.destroy');

        // Blog
        Route::get('/blog',        [ClientBlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/{post}', [ClientBlogController::class, 'show'])->name('blog.show');
    });

// ── Espace Admin ──────────────────────────────────────────
Route::middleware(['auth', 'is.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    });
