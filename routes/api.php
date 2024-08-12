<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DivisiController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\NilaiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/divisions', [DivisiController::class, 'getDivision']);
    Route::get('/employees', [EmployeeController::class, 'getEmployee']);
    Route::post('/employees', [EmployeeController::class, 'storeEmployee']);
    Route::put('/employees/{id}', [EmployeeController::class, 'updateEmployee']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'deleteEmployee']);
});

Route::get('/nilaiRT', [NilaiController::class, 'getNilaiRt']);
Route::get('/nilaiST', [NilaiController::class, 'getNilaiSt']);