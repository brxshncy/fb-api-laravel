<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FriendRequestController;
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

Route::apiResource('posts', PostController::class)
      ->names('post')
      ->middleware('auth:api');

Route::apiResource('friend-requests', FriendRequestController::class)
      ->names('friend-request')
      ->middleware('auth:api');