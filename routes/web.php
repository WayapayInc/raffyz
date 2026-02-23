<?php

use App\Livewire\HomePage;
use App\Livewire\Installer;
use App\Livewire\TermsPage;
use App\Livewire\RafflesPage;
use App\Livewire\ViewMyTickets;
use App\Livewire\RaffleDetailPage;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\InstallMiddleware;
use App\Http\Controllers\UpgradeController;

// Installation Route
Route::get('/install', Installer::class)
    ->middleware(InstallMiddleware::class)
    ->name('install');

// Frontend (public) routes
Route::get('/', HomePage::class)->name('home');
Route::get('/raffles', RafflesPage::class)->name('raffles.index');
Route::get('/raffles/{slug}', RaffleDetailPage::class)->name('raffles.show');
Route::get('/my-tickets', ViewMyTickets::class)->name('my-tickets');
Route::get('/terms', TermsPage::class)->name('terms');

// Locale switcher
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'es', 'pt_BR'])) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }

    return redirect()->back();
})->name('locale.switch');

// Upgrades
Route::middleware(['auth', 'verified', 'license'])->group(function () {
    Route::get('/update', [UpgradeController::class, 'execute'])->name('upgrade');
});

Route::get('sitemaps.xml', function () {
    return response()->view('sitemap.show')->header('Content-Type', 'application/xml');
});

// Force 404 for login
Route::any('/login', function () {
    abort(404);
})->name('login');
