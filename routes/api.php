<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AdminManagementController;
use App\Http\Controllers\Api\CustomerManagementController;
use App\Http\Middleware\SuperAdminAccessMiddleware;
use App\Http\Middleware\AdminAccessMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AdminManagementController::class)->prefix('users/admin/')->middleware(['auth:sanctum', 'ability:superadmin'])->group(function () {
    Route::get('retrieve',  'index');
    Route::delete('delete/{id}', 'destroy');
});

Route::controller(CustomerManagementController::class)->prefix('users/customer/')->middleware(['auth:sanctum', 'ability:admin,superadmin'])->group(function () {
    Route::get('retrieve',  'index');
    Route::delete('delete/{id}', 'destroy');
});