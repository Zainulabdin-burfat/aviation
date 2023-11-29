<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


Route::get('/loginform', [AuthController::class, 'showLoginForm'])->name('loginform');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password-link', [AuthController::class, 'forgotPasswordLink'])->name('forgot-password-link');

Route::get('/confirm-user-mail/{userId}', [AuthController::class, 'verifyUserEmail']);
