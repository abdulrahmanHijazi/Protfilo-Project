<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['setlocale'])->group(function (): void {
    Route::get('/', [PortfolioController::class, 'home'])->name('home');
    Route::get('/about', [PortfolioController::class, 'about'])->name('about');
    Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects.index');
    Route::get('/projects/{project:slug}', [PortfolioController::class, 'show'])->name('projects.show');
    Route::get('/contact', [PortfolioController::class, 'contact'])->name('contact');
    Route::post('/contact', [PortfolioController::class, 'store'])->name('contact.store');

    Route::prefix('{locale}')->where(['locale' => 'en|ar'])->group(function (): void {
        Route::get('/', [PortfolioController::class, 'home'])->name('home.locale');
        Route::get('/about', [PortfolioController::class, 'about'])->name('about.locale');
        Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects.index.locale');
        Route::get('/projects/{project:slug}', [PortfolioController::class, 'show'])->name('projects.show.locale');
        Route::get('/contact', [PortfolioController::class, 'contact'])->name('contact.locale');
    });
});
