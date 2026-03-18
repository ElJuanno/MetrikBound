<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\BuilderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('welcome');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Surveys (Controller)
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::middleware(['auth'])->group(function () {
    Route::post('/surveys/{survey}/publish', [SurveyController::class, 'publish'])
        ->name('surveys.publish');
    });
    // Builder (Editor)
    Route::get('/builder/{survey}', [BuilderController::class, 'edit'])->name('builder.edit');
    Route::post('/builder/{survey}/autosave', [BuilderController::class, 'autosave'])->name('builder.autosave');

    // Extra pages (si solo son vistas)
    Route::view('/templates', 'templates.index')->name('templates.index');
    Route::view('/results', 'results.index')->name('results.index');
});
use App\Http\Controllers\PublicSurveyController;

Route::get('/s/{token}', [PublicSurveyController::class, 'show'])
    ->name('surveys.public.show');

Route::post('/s/{token}/submit', [PublicSurveyController::class, 'submit'])
    ->name('surveys.public.submit');

    use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze/Fortify/etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
