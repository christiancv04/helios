<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SubdepartmentController;

Route::prefix('department')->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::post('/', [DepartmentController::class, 'store']);
    Route::put('{id}', [DepartmentController::class, 'update']);
    Route::delete('{id}', [DepartmentController::class, 'delete']);
});

Route::prefix('subdepartment')->group(function () {
    Route::get('/', [SubdepartmentController::class, 'index']);
    Route::post('/', [SubdepartmentController::class, 'store']);
    Route::delete('{id}', [SubdepartmentController::class, 'delete']);
});
