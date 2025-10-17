<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\NewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'th'])) {
        abort(404);
    }

    App::setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
});


Route::get('/', [FrontController::class, 'index']);
Route::get('/home', [FrontController::class, 'index'])->name('home');
Route::get('/about-us', [FrontController::class, 'about']);
Route::get('/activities', [ActivityController::class, 'index'])->name('activities');
Route::post('/activities/join', [ActivityController::class, 'join'])->name('activities.join');
Route::delete('/activities/{id}/leave', [ActivityController::class, 'leave'])->name('activities.leave');
Route::get('/courses', [FrontController::class, 'course']);
Route::get('/content/{id}', [FrontController::class, 'content'])->name('content');
Route::get('/contact-us', [FrontController::class, 'contact']);
Route::get('/news', NewController::class);
Route::get('/search', [FrontController::class, 'search'])->name('search');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile/{id}', [ProfileController::class, 'index'])->name('profile.show');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/report/{id?}', [ProfileController::class, 'report'])->name('report.submit');
Route::delete('/profile/report/{key}', [ProfileController::class, 'deleteReport'])->name('report.delete');
Route::get('/arsa-card/{id?}', [ProfileController::class, 'showCard'])->name('arsa-card.show');
Route::get('/quiz', [FrontController::class, 'quiz'])->name('quiz');

Route::get('/consent', [FrontController::class, 'consent'])->name('consent');
Route::post('/consent', [FrontController::class, 'submitConsent'])->name('consent.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/sign-up', [AuthController::class, 'showSignup'])->name('register.form');
Route::post('/sign-up', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/api/activities', [ProfileController::class, 'searchActivities']);

require __DIR__ . '/api.php';