<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HelpController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\User\RoleController;
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


});
