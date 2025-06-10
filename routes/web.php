<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin routes
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/users', function () {
    return view('admin.dashboard');
})->name('admin.users');

Route::get('/admin/submissions', function () {
    return view('admin.dashboard');
})->name('admin.submissions');

Route::get('/admin/edit-requests/pending', [App\Http\Controllers\AdminController::class, 'pendingEditRequests'])->name('admin.edit-requests.pending');
Route::get('/admin/edit-requests/history', [App\Http\Controllers\AdminController::class, 'editHistory'])->name('admin.edit-requests.history');

Route::get('/admin/library', [App\Http\Controllers\LibraryController::class, 'index'])->name('admin.library');
Route::get('/admin/library/divisions', [App\Http\Controllers\LibraryController::class, 'divisions'])->name('admin.library.divisions');
Route::get('/admin/library/districts', [App\Http\Controllers\LibraryController::class, 'districts'])->name('admin.library.districts');
Route::get('/admin/library/upazilas', [App\Http\Controllers\LibraryController::class, 'upazilas'])->name('admin.library.upazilas');
Route::get('/admin/library/mouzas', [App\Http\Controllers\LibraryController::class, 'mouzas'])->name('admin.library.mouzas');

// User routes
Route::get('/user/login', function () {
    return view('user.login');
})->name('user.login');

Route::get('/user/dashboard', function () {
    return redirect()->route('user.data-submission');
});

Route::get('/user/data-submission', function () {
    return view('user.data-submission');
})->name('user.data-submission');

Route::get('/user/edit-requests', function () {
    return view('user.edit-requests');
})->name('user.edit-requests');

Route::get('/user/profile', function () {
    return view('user.profile');
})->name('user.profile');
