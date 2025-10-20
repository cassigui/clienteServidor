<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware('auth.external')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::get('/users', [RegisterController::class, 'create'])->name('register');
Route::get('/login', [LoginController::class, 'create'])->name('login');

// Route::post('/login', [LoginController::class, 'create'])->name('login');

Route::get('/', [SiteController::class, 'index'])->name('site.home');

