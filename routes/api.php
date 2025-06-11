<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibraryController;


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
    Route::get('/edit-history', [UserController::class, 'getEditHistory']);
    Route::put('/edit-requests/{editRequestId}', [UserController::class, 'updateDataRecord']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
});

// Admin routes
Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::post('/users', [AdminController::class, 'addUser']);
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::get('/submissions', [AdminController::class, 'getSubmissions']);
    Route::post('/send-edit-request', [AdminController::class, 'sendEditRequest']);
    Route::get('/edit-requests', [AdminController::class, 'getEditRequests']);
    Route::get('/edit-history', [AdminController::class, 'getEditHistory']);
    
    // Admin user management
    Route::post('/check-existing-admins', [AdminController::class, 'checkExistingAdmins']);
    Route::post('/create-admin-user', [AdminController::class, 'createAdminUser']);
    Route::get('/admin-users', [AdminController::class, 'getAdminUsers']);
    Route::put('/admin-users/{id}', [AdminController::class, 'updateAdminUser']);
    Route::delete('/admin-users/{id}', [AdminController::class, 'deleteAdminUser']);
    
    // Library routes
    Route::prefix('library')->group(function () {
        // Division routes
        Route::get('/divisions', [LibraryController::class, 'getDivisions']);
        Route::post('/divisions', [LibraryController::class, 'storeDivision']);
        Route::put('/divisions/{id}', [LibraryController::class, 'updateDivision']);
        Route::delete('/divisions/{id}', [LibraryController::class, 'deleteDivision']);
        
        // District routes
        Route::get('/districts/{divisionId?}', [LibraryController::class, 'getDistricts']);
        Route::post('/districts', [LibraryController::class, 'storeDistrict']);
        Route::put('/districts/{id}', [LibraryController::class, 'updateDistrict']);
        Route::delete('/districts/{id}', [LibraryController::class, 'deleteDistrict']);
        
        // Upazila routes
        Route::get('/upazilas/{districtId?}', [LibraryController::class, 'getUpazilas']);
        Route::post('/upazilas', [LibraryController::class, 'storeUpazila']);
        Route::put('/upazilas/{id}', [LibraryController::class, 'updateUpazila']);
        Route::delete('/upazilas/{id}', [LibraryController::class, 'deleteUpazila']);
        
        // Mouza routes
        Route::get('/mouzas/{upazilaId?}', [LibraryController::class, 'getMouzas']);
        Route::post('/mouzas', [LibraryController::class, 'storeMouza']);
        Route::put('/mouzas/{id}', [LibraryController::class, 'updateMouza']);
        Route::delete('/mouzas/{id}', [LibraryController::class, 'deleteMouza']);
    });
}); 