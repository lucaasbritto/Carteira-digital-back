<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;

Route::post('login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/exchange-rates', [ApiController::class, 'getExchangeRates']);   

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wallets', [WalletController::class, 'getWallets']);
    Route::get('/walletUsers', [WalletController::class, 'getWalletUsers']);
    Route::post('/wallet-create', [WalletController::class, 'store']);
    Route::get('/wallet/{id}', [WalletController::class, 'getWallet']);
    Route::get('/wallet-pix', [WalletController::class, 'getPersonByPixKey']);

    Route::post('/transfer', [WalletController::class, 'transfer']);
    Route::get('/transactions', [TransactionController::class, 'getTransaction']);
    Route::get('/user', [UserController::class, 'getUserId']);

});
