<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlayerController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Guest Routes (redirect to dashboard if logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

// Auth Routes (require login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/files', [DashboardController::class, 'files'])->name('files');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings/profile', [DashboardController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [DashboardController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/cloud/verify', [DashboardController::class, 'verifyGpmc'])->name('settings.cloud.verify');
    Route::post('/settings/cloud/import', [DashboardController::class, 'importGpmc'])->name('settings.cloud.import');
    Route::get('/settings/cloud/progress', [DashboardController::class, 'checkGpmcProgress'])->name('settings.cloud.progress');
    Route::post('/settings/cloud/sync', [DashboardController::class, 'syncGpmc'])->name('settings.cloud.sync');
    Route::get('/sync-status', [DashboardController::class, 'syncStatus'])->name('sync.status');
    Route::post('/manual-sync', [DashboardController::class, 'manualSync'])->name('sync.manual');
    Route::get('/player-settings', [DashboardController::class, 'playerSettings'])->name('player.settings');
    Route::post('/player-settings', [DashboardController::class, 'updatePlayerSettings'])->name('player.settings.update');
    Route::get('/file/{id}/preview', [DashboardController::class, 'preview'])->name('file.preview');
    Route::post('/settings/api-key/generate', [DashboardController::class, 'generateApiKey'])->name('settings.apikey.generate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Routes (require admin privileges)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/files', [AdminController::class, 'files'])->name('files');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('/users/{user}/toggle-cloud', [AdminController::class, 'toggleGpmcPermission'])->name('users.toggle-cloud');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/favicon', [AdminController::class, 'uploadFavicon'])->name('settings.favicon');
    Route::get('/monetization', [AdminController::class, 'ads'])->name('ads');
    Route::post('/monetization', [AdminController::class, 'updateAds'])->name('ads.update');
});

// Video Player Routes (public access)
Route::get('/v/{slug}', [PlayerController::class, 'watch'])->name('player.watch');
Route::get('/d/{slug}', [PlayerController::class, 'download'])->name('player.download');
Route::get('/d/{slug}/countdown/{server}', [PlayerController::class, 'downloadCountdown'])->name('player.download.countdown');
Route::get('/d/{slug}/server/{server}', [PlayerController::class, 'downloadServer'])->name('player.download.server')->middleware('signed');
Route::post('/d/{slug}/start/{server}', [PlayerController::class, 'initiateDownload'])->name('player.download.start');

// Installation Routes
Route::get('/install', [InstallController::class, 'index'])->name('install');
Route::get('/install/check-requirements', [InstallController::class, 'checkRequirements']);
Route::post('/install/test-db', [InstallController::class, 'testDatabase']);
Route::post('/install/save-db', [InstallController::class, 'saveDatabase']);
Route::post('/install/create-admin', [InstallController::class, 'createAdmin']);

// Internal API for progress callback (called by background process)
Route::post('/api/internal/progress-callback', [DashboardController::class, 'updateProgressCallback']);
