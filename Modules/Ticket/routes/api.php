<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Ticket\App\Http\Controllers\TicketController;

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

 Route::post('/tickets/create', [TicketController::class, 'store'])->name('tickets.create');
