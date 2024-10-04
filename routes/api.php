<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/social_login', [SocialLoginController::class, 'login']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->group(function() {
  Route::post('/token', [UserController::class, 'storeToken']);
  Route::get('/user', [UserController::class, 'index']);
  Route::post('/fav', [UserController::class, 'storeFavDoc']);
  Route::post('/logout', [UserController::class, 'logout']);
  Route::post('/book', [AppointmentsController::class, 'store']);
  Route::post('/reviews', [DocsController::class, 'store']);
  Route::get('/appointments', [AppointmentsController::class, 'index']);
});
 

