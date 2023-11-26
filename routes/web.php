<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('listings', ListingController::class);
    Route::post('/listings/{listing}/purchase', [ListingController::class, 'purchase'])->name('listings.purchase');

    Route::get('/transactions', [TransactionController::class, 'send']);
    Route::post('/transactions/approve/{transaction}', [TransactionController::class, 'approve']);
    Route::post('/transactions/decline/{transaction}', [TransactionController::class, 'decline']);

    Route::post('/create-payment-intent', [TransactionController::class, 'createPaymentIntent']);
    Route::post('/confirm-payment', [TransactionController::class, 'confirmPayment']);

});


Route::prefix('messages')->middleware('auth')->group(function () {
    Route::get('/', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/chat/{user}', [MessageController::class, 'chat'])->name('messages.chat');
    Route::post('/send/{receiver_id}', [MessageController::class, 'send'])->name('messages.send');
});

// Route::get('/', 'App\Http\Controllers\PusherController@index');
// Route::post('/broadcast', 'App\Http\Controllers\PusherController@broadcast');
// Route::post('/broadcast', 'App\Http\Controllers\PusherController@broadcast');
// Route::post('/receive', 'App\Http\Controllers\PusherController@receive');



require __DIR__.'/auth.php';
