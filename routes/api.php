<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\BookingController;
use App\Http\Controllers\API\V1\RouteController;
use App\Http\Controllers\API\V1\ScheduleController;
use App\Http\Controllers\API\V1\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Auth ───────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
        Route::post('logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    // ── Public: Routes & Schedules ─────────────────────────────
    Route::get('routes',                         [RouteController::class, 'index']);
    Route::get('routes/{route}',                 [RouteController::class, 'show']);
    Route::get('routes/{route}/stops',           [RouteController::class, 'stops']);

    Route::get('schedules',                      [ScheduleController::class, 'index']);
    Route::get('schedules/{schedule}',           [ScheduleController::class, 'show']);
    Route::get('schedules/{schedule}/availability', [ScheduleController::class, 'availability']);

    // ── Protected: Bookings & Tickets ──────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('bookings',                   [BookingController::class, 'index']);
        Route::post('bookings',                  [BookingController::class, 'store']);
        Route::get('bookings/{booking}',         [BookingController::class, 'show']);
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);

        Route::get('tickets/{code}',             [TicketController::class, 'show']);
    });
});
