<?php

use App\Http\Controllers\AccessTokensController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'auth', 'middleware' => 'throttle:60,1'], function () {
    Route::post('/signIn', [UserController::class, 'store']);
    Route::post('/logIn', [UserController::class, 'login']);
    Route::delete('/logOut', [UserController::class, 'logout'])->middleware(['auth:sanctum']);
});



Route::apiResource('post', PostController::class)->middleware(['auth:sanctum', 'throttle:60,1'])->except('index', 'show');
Route::get('post', [PostController::class, 'index'])->middleware([AdminMiddleware::class]);
Route::get('post/{id}', [PostController::class, 'show'])->middleware('throttle:60,1');


Route::apiResource('post/{post_id}/comments', CommentController::class)->middleware('throttle:60,1');
