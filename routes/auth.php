<?php

use Illuminate\Support\Facades\Route;


Route::get('/loginform', 'AuthController@showLoginForm')->name('loginform');

Route::post('/login', 'AuthController@login')->name('login');

Route::post('/logout', 'AuthController@logout')->name('logout');

Route::get('/forgot-password', 'AuthController@showForgotPasswordForm')->name('forgot-password');
Route::post('/forgot-password-link', 'AuthController@forgotPasswordLink')->name('forgot-password-link');

Route::get('/confirm-user-mail/{userId}', 'AuthController@verifyUserEmail');
