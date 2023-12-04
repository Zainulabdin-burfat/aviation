<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['permissionsWeb', 'auth'])->group(function () {

    Route::get('/users', 'UserController@list')->name('user.list');
    Route::resource('/user', UserController::class);

    Route::resource('/role', \App\Http\Controllers\RoleController::class);

    Route::resource('/dashboard', DashboardController::class);

    Route::resource('listings', \App\Http\Controllers\ListingController::class);
    Route::post('/listings/{listing}/purchase', [\App\Http\Controllers\ListingController::class, 'purchase'])->name('listings.purchase');

    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'send']);
    Route::post('/transactions/approve/{transaction}', [\App\Http\Controllers\TransactionController::class, 'approve']);
    Route::post('/transactions/decline/{transaction}', [\App\Http\Controllers\TransactionController::class, 'decline']);

    Route::post('/create-payment-intent', [\App\Http\Controllers\TransactionController::class, 'createPaymentIntent']);
    Route::post('/confirm-payment', [\App\Http\Controllers\TransactionController::class, 'confirmPayment']);

    Route::prefix('messages')->group(function () {
        Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/chat/{user}', [\App\Http\Controllers\MessageController::class, 'chat'])->name('messages.chat');
        Route::post('/send/{receiver_id}', [\App\Http\Controllers\MessageController::class, 'send'])->name('messages.send');
        Route::post('/receive', [\App\Http\Controllers\MessageController::class, 'receiveMessage'])->name('messages.receiveMessage');
    });

});
