<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Asset\App\Http\Controllers\AudioController;
use Modules\Asset\App\Http\Controllers\PhotoController;
use Modules\Asset\App\Http\Controllers\VideoController;

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

Route::middleware(['auth:sanctum', 'api'])->prefix('assets')->group(function () {
    // Photo Routes
    Route::prefix('photos')->group(function () {
        Route::post('upload', [PhotoController::class, 'uploadPhoto']);
    });

    // Video Routes
    Route::prefix('videos')->group(function () {
        Route::post('upload', [VideoController::class, 'uploadVideo']);
    });

    // Audio Routes
    Route::prefix('audios')->group(function () {
        Route::post('upload', [AudioController::class, 'uploadAudio']);
    });
});
