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
