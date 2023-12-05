<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('user')->group(function () {
    Route::post('/', [UserController::class, 'create']);
    Route::get('/{user}', [UserController::class, 'view'])->whereNumber('user');
    Route::patch('/{user}', [UserController::class, 'update'])->whereNumber('user');
});

Route::prefix('account')->group(function () {
    Route::post('{sender}/send', [AccountController::class, 'send'])->whereNumber('sender');
    Route::post('{account:id}/fill', [AccountController::class, 'fill'])->whereNumber('account');
});

