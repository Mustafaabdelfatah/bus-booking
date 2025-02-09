<?php

use App\Http\Controllers\API\BookingDepositController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BusController;
use App\Http\Controllers\API\HelpController;
use App\Http\Controllers\API\SeatController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\TravelController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\User\RoleController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\PriceCalculatorController;
use App\Http\Controllers\API\User\PermissionController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('login/{userType?}', [AuthController::class, 'login'])->defaults('userType', 'employee');

Route::post('register', [AuthController::class, 'register']);
Route::middleware(['auth:client'])->group(function () {
    Route::get('clients/projects', [ClientController::class, 'projects']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('settings', [SettingController::class, 'generalSettings']);
    Route::get('help-enums', [HelpController::class, 'enums']);

    /*
    |--------------------------------------------------------------------------
    | client Routes
    |--------------------------------------------------------------------------
    */
    Route::delete('clients/delete-all', [ClientController::class, 'destroyAll']);
    Route::APIResource('clients', ClientController::class);

    /*
    |--------------------------------------------------------------------------
    | employee Routes
    |--------------------------------------------------------------------------
    */
    Route::delete('employees/delete-all', [EmployeeController::class, 'destroyAll']);
    Route::APIResource('employees', EmployeeController::class);

    /*
    |--------------------------------------------------------------------------
    | Roles Routes
    |--------------------------------------------------------------------------
    */
    Route::delete('roles/delete-all', [RoleController::class, 'destroyAll']);
    Route::APIResource('roles', RoleController::class);
    Route::APIResource('permissions', PermissionController::class);

    /**
     * |--------------------------------------------------------------------------
     * | Bus Routes
     * |--------------------------------------------------------------------------
     */
    Route::apiResource('buses', BusController::class);

    /**
     * |--------------------------------------------------------------------------
     * | Travels Routes
     * |--------------------------------------------------------------------------
     */
    Route::apiResource('travels', TravelController::class);

    /**
     * |--------------------------------------------------------------------------
     * | Reservation Routes
     * |--------------------------------------------------------------------------
     */
    Route::apiResource('reservations', ReservationController::class);

    /**
     * |--------------------------------------------------------------------------
     * | seat Routes
     * |--------------------------------------------------------------------------
     */

    Route::apiResource('seats', SeatController::class);

    Route::prefix('seats')->group(function () {
        Route::post('/reserve', [SeatController::class, 'reserveSeat']); // Reserve a seat
        Route::post('/cancel', [SeatController::class, 'cancelReservation']); // Cancel a reservation
        Route::get('/available', [SeatController::class, 'availableSeats']); // Get available seats
    });

     /**
     * |--------------------------------------------------------------------------
     * | deposits Routes
     * |--------------------------------------------------------------------------
     */

     Route::apiResource('deposits', BookingDepositController::class);


    Route::post('/calculate-price', [PriceCalculatorController::class, 'calculate']);


    Route::post('reservations/{reservation}/confirm-payment', [ReservationController::class, 'confirmPayment']);

    // Route::get('clients/export/{country}', [ReservationController::class, 'exportByCountry']);

});