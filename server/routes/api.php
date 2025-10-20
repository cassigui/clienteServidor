<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


Route::group(['middleware' => 'auth:api'], function () {
});

Route::post('/login', [LoginController::class, 'login'])->name('api.login');
Route::post('/users', [RegisterController::class, 'store']);
Route::get('/users/{user_id}', [UserController::class, 'get'])->name('get.user');
Route::patch('/users/{user_id}', [UserController::class, 'update'])->name('update.user');
Route::post('/logout', [LoginController::class, 'logout'])->name('api.logout');
Route::delete('/users/{user_id?}', [UserController::class, 'destroy'])->name('delete.user');
