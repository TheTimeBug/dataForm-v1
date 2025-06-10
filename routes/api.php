<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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

// Authentication routes
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/user/login', [AuthController::class, 'userLogin']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
});

// User routes
Route::middleware('auth:api')->prefix('user')->group(function () {
    Route::post('/data-records', [UserController::class, 'storeDataRecord']);
    Route::get('/data-records', [UserController::class, 'getDataRecords']);
    Route::get('/edit-requests', [UserController::class, 'getEditRequests']);
    Route::put('/edit-requests/{editRequestId}', [UserController::class, 'updateDataRecord']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
});

// Admin routes
Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::post('/users', [AdminController::class, 'addUser']);
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::get('/submissions', [AdminController::class, 'getSubmissions']);
    Route::post('/send-for-edit', [AdminController::class, 'sendForEdit']);
    Route::get('/edit-requests', [AdminController::class, 'getEditRequests']);
}); 