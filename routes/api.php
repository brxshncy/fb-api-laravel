<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FriendShipController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::prefix('post')->name('post.')->group(function () {
    Route::apiResource('/', PostController::class);
})->middleware('auth:api');

Route::prefix('friendship')->name('friendship.')->group(function () {
    Route::apiResource('/', FriendShipController::class);
})->middleware('auth:api');