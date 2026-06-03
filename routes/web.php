<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UniversityController;
use Illuminate\Support\Facades\Route;

// ─── Public / Landing ────────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register',     [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',    [RegisterController::class, 'register']);

    Route::get('/login',        [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',       [LoginController::class, 'login']);

    Route::get('/forgot-password',    [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password',   [PasswordResetController::class, 'sendOtp'])->name('password.email');
    Route::get('/reset-password',     [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password',    [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // OTP Verification
    Route::get('/otp/verify',  [OtpController::class, 'showForm'])->name('otp.verify');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify.post');
    Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});

// ─── Admin ───────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/universities',           [AdminController::class, 'universities'])->name('universities');
    Route::get('/students',               [AdminController::class, 'students'])->name('students');
    Route::get('/users',                  [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',           [AdminController::class, 'createUserForm'])->name('users.create');
    Route::post('/users',                 [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}',        [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{user}/toggle',   [AdminController::class, 'toggleUser'])->name('users.toggle');
});

// ─── University ──────────────────────────────────────────────────────────────
Route::prefix('university')->name('university.')->middleware(['auth', 'role:university'])->group(function () {
    Route::get('/dashboard',                   [UniversityController::class, 'dashboard'])->name('dashboard');
    Route::get('/students',                    [UniversityController::class, 'students'])->name('students');
    Route::get('/students/create',             [UniversityController::class, 'createStudent'])->name('students.create');
    Route::post('/students',                   [UniversityController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}/edit',     [UniversityController::class, 'editStudent'])->name('students.edit');
    Route::post('/students/{student}/approve', [UniversityController::class, 'approveStudent'])->name('students.approve');
    Route::put('/students/{student}',          [UniversityController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}',       [UniversityController::class, 'deleteStudent'])->name('students.delete');
    Route::get('/profile',                     [UniversityController::class, 'profile'])->name('profile');
});

// ─── Government ──────────────────────────────────────────────────────────────
Route::prefix('government')->name('government.')->middleware(['auth', 'role:government'])->group(function () {
    Route::get('/dashboard',     [GovernmentController::class, 'dashboard'])->name('dashboard');
    Route::get('/data',          [GovernmentController::class, 'data'])->name('data');
    Route::get('/export',        [GovernmentController::class, 'export'])->name('export');
    Route::get('/universities',  [GovernmentController::class, 'universities'])->name('universities');
    Route::post('/universities/{university}/approve', [GovernmentController::class, 'approve'])->name('universities.approve');
    Route::post('/universities/{university}/reject',  [GovernmentController::class, 'reject'])->name('universities.reject');
});

// ─── Student ─────────────────────────────────────────────────────────────────
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
});
