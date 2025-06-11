<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Unauthorized access page
Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

// Admin routes
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

// Protected admin routes
Route::middleware(['auth_web'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.overview');
    })->name('dashboard');

    Route::get('/users/admins', function () {
        return view('admin.users.admins');
    })->name('users.admins');

    Route::get('/users/users', function () {
        return view('admin.users.users');
    })->name('users.users');

    Route::get('/submissions', function () {
        return view('admin.submissions');
    })->name('submissions');

    Route::get('/edit-requests/pending', [App\Http\Controllers\AdminController::class, 'pendingEditRequests'])->name('edit-requests.pending');
    Route::get('/edit-requests/history', [App\Http\Controllers\AdminController::class, 'editHistory'])->name('edit-requests.history');

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
    
    // Library routes - Only for Superadmin
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', [App\Http\Controllers\LibraryController::class, 'index'])->name('index');
        Route::get('/divisions', [App\Http\Controllers\LibraryController::class, 'divisions'])->name('divisions');
        Route::get('/districts', [App\Http\Controllers\LibraryController::class, 'districts'])->name('districts');
        Route::get('/upazilas', [App\Http\Controllers\LibraryController::class, 'upazilas'])->name('upazilas');
        Route::get('/mouzas', [App\Http\Controllers\LibraryController::class, 'mouzas'])->name('mouzas');
    });
});

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

Route::get('/user/edit-history', function () {
    return view('user.edit-history');
})->name('user.edit-history');

Route::get('/user/profile', function () {
    return view('user.profile');
})->name('user.profile');
