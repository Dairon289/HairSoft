<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ServicioController;

Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
    Route::get('/cliente/dashboard', [DashboardController::class, 'index'])->name('cliente.dashboard');

    Route::get('/', [DashboardController::class, 'index']);

    Route::resource('cliente', ClienteController::class);
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('servicio', ServicioController::class);


    Route::resource('citas', CitaController::class);

    Route::get('citas/{cita}/cancelar', [CitaController::class, 'formularioCancelar'])
        ->name('citas.cancelar.form');
    Route::patch('citas/{cita}/cancelar', [CitaController::class, 'cancelar'])
        ->name('citas.cancelar');

    Route::get('citas/{cita}/motivo', [CitaController::class, 'formularioMotivo'])
        ->name('citas.motivo.form');
    Route::patch('citas/{cita}/motivo', [CitaController::class, 'actualizarMotivo'])
        ->name('citas.motivo');

    Route::get('citas/{id}/factura', [CitaController::class, 'factura'])
        ->name('citas.factura');
        //->middleware('modulo:citas')
    
    Route::resource('pagos', PagoController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);

    Route::get('pagos/{pago}/anular', [PagoController::class, 'formularioAnular'])
        ->name('pagos.anular.form');
       //->middleware('modulo:pagos')//

    Route::patch('pagos/{pago}/anular', [PagoController::class, 'anular'])
        ->name('pagos.anular');
        //->middleware('modulo:pagos')
    Route::get('reportes/ingresos', [ReporteController::class, 'ingresos'])
        ->name('reportes.ingresos');