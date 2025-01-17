<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Asset\App\Http\Controllers\AssetController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('asset', fn(Request $request) => $request->user())->name('asset');
});

Route::middleware('auth:sanctum')->prefix('assets')->group(function () {
    Route::post('/generate-signed-url', [AssetController::class, 'generateSignedUrl']);
    Route::post('/quality-selection', [AssetController::class, 'selectQuality']);
});
