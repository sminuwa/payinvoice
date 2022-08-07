<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/balance', [UserController::class, 'balance']);
    Route::get('/wallet', [UserController::class, 'wallet']);
    Route::get('/transactions', [UserController::class, 'transactions']);

    //card
    Route::prefix('card')->group(function(){
       Route::post('/create', [CardController::class,'create']);
       Route::post('/transactions', [CardController::class,'transactions']);
    });
});
    Route::get('/getBalance1', [UserController::class, 'balance1']);
