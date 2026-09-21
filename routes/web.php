<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ServicioController;



Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
Route::get('/cliente/dashboard', [DashboardController::class, 'index'])->name('cliente.dashboard');

Route::get('/', [DashboardController::class, 'index']);

Route::resource('cliente', ClienteController::class);
Route::resource('empleados', EmpleadoController::class);
Route::resource('servicio', ServicioController::class);

Route::resource('citas', CitaController::class);

Route::patch('citas/{cita}/cancelar', [CitaController::class, 'cancelar'])
->name('citas.cancelar');