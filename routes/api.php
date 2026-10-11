<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// All API routes require API key authentication
Route::middleware('api.key')->group(function () {
    Route::get('/search', [ApiController::class, 'search']);
    Route::get('/file/{slug}', [ApiController::class, 'show']);
});
