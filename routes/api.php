<?php

use App\Http\Controllers\Auth\Ura\ApartmentController;
use App\Http\Controllers\Auth\Ura\MessageController;
use App\Http\Controllers\Auth\Ura\OrderController;
use App\Http\Controllers\GeneralUser;
use App\Http\Controllers\UserAlreadyAuth;
use App\Http\Controllers\UserAlreadyLogged;
use App\Http\Controllers\NewMessageController; //controller per nuovi messaggi

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')
    ->prefix('user')
    ->name('admin.')
    ->group(function () {
        Route::resource('/utente/dashboard', ApartmentController::class);

    });

    // payment
    Route::get('/order/generate', [OrderController::class, 'generate']);
    Route::post('/order/make/payment', [OrderController::class, 'makePayment']);



Route::get('/home', [GeneralUser::class, 'allApartment']);
Route::get('/services',[GeneralUser::class, 'services']);
Route::get('/messages', [GeneralUser::class, 'messages']);

Route::middleware('auth:sanctum')->post('logout', [AuthenticatedSessionController::class, 'destroy']);

Route::post('register', [RegisteredUserController::class, 'store']);
Route::post('login', [AuthenticatedSessionController::class , 'store']);

// rotta per invio messaggi
// Route::post('/send-message', [NewMessageController::class, 'store']);
Route::post('/apartments/{apartment}/send-message', [NewMessageController::class, 'store']);







