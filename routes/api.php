<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

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

if (Features::enabled(Features::registration())) {
    Route::post('/register', [RegisteredUserController::class => 'store']);
}

Route::post('/sanctum/token', TokenController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'update']);
});
