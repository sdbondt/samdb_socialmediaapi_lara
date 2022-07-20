<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'myprofile']);

    Route::get('/users/{user:slug}', [ProfileController::class, 'show']);
    Route::get('/users', [ProfileController::class, 'index']);
    Route::patch('/users/{user:slug}', [ProfileController::class, 'update']);
    Route::delete('/users/{user:slug}', [ProfileController::class,'destroy']);

    Route::post('/users/{user:slug}', [FollowsController::class, 'toggleUserFollow']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::patch('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
});

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
