<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;

Route::get('/', [DashboardController::class, 'index']);

Route::resource('cliente', ClienteController::class);
Route::resource('empleados', EmpleadoController::class);
Route::resource('servicio', ServicioController::class);