<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Beranda
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [FamilyController::class, 'index'])->name('dashboard');

    // Family routes
    Route::resource('families', FamilyController::class);

    // Member routes
    // ...
    // If you're using resource routes, make sure you have this:
    Route::resource('families.members', MemberController::class);

    // Or if you're defining routes individually, add these two routes:
    Route::get('/families/{family}/members/{member}/edit', [MemberController::class, 'edit'])
        ->name('families.members.edit');
    Route::put('/families/{family}/members/{member}', [MemberController::class, 'update'])
        ->name('families.members.update');

    // Tambahan rute untuk modal edit dan delete
    Route::post('/members/{member}/update', [MemberController::class, 'updateAjax']);
    Route::delete('/members/{member}/delete', [MemberController::class, 'destroyAjax']);
});

// Rute untuk mendapatkan data anggota keluarga (dapat diakses tanpa login)
Route::get('/members/{member}', function (App\Models\Member $member) {
    return response()->json([
        'id' => $member->id,
        'name' => $member->name,
        'gender' => $member->gender,
        'photo' => $member->photo ? Storage::url($member->photo) : null,
        'birth_date' => $member->birth_date,
        'birth_place' => $member->birth_place,
        'death_date' => $member->death_date,
        'death_place' => $member->death_place,
        'occupation' => $member->occupation,
        'bio' => $member->bio,
    ]);
});
