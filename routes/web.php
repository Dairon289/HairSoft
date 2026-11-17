<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\GaleriaEmpleadoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Seguridad\AuthController;
use App\Http\Controllers\ServicioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $usuario = Auth::user();

    if ($usuario && $usuario->hasRole(config('seguridad.rol_empleado'))) {
        return redirect()->route('agenda.index');
    }

    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('seguridad.login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.post');

Route::get('/registro', [AuthController::class, 'showRegisterForm'])->name('registro');
Route::post('/registro', [AuthController::class, 'register'])->name('registro.post');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');
    Route::get('/dashboard-legacy', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Perfil de Usuario (Admin, Empleado, Cliente)
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])
        ->name('admin.dashboard')
        ->middleware('role:administrador'); 
    Route::get('/cliente/dashboard', function () {
        session()->reflash(); // conserva el mensaje de bienvenida del registro
        return redirect()->route('citas.index');
    })
        ->name('cliente.dashboard')
        ->middleware('role:cliente');
    Route::get('/cliente/empleados', GaleriaEmpleadoController::class)
        ->name('cliente.empleados')
        ->middleware('role:cliente');

    Route::resource('empleados', EmpleadoController::class)->middleware('modulo:empleados');

    Route::resource('cliente', ClienteController::class)->middleware('modulo:clientes');

    Route::resource('servicio', ServicioController::class)->middleware('modulo:servicios');

    Route::resource('citas', CitaController::class)
        ->except(['show', 'destroy'])
        ->middleware('modulo:citas');
    Route::get('citas/{cita}/cancelar', [CitaController::class, 'formularioCancelar'])
        ->name('citas.cancelar.form')
        ->middleware('modulo:citas');
    Route::patch('citas/{cita}/cancelar', [CitaController::class, 'cancelar'])
        ->name('citas.cancelar')
        ->middleware('modulo:citas');

    Route::get('citas/{cita}/motivo', [CitaController::class, 'formularioMotivo'])
        ->name('citas.motivo.form')
        ->middleware('modulo:citas');
    Route::patch('citas/{cita}/motivo', [CitaController::class, 'actualizarMotivo'])
        ->name('citas.motivo')
        ->middleware('modulo:citas');
    Route::patch('citas/{cita}/iniciar', [CitaController::class, 'iniciar'])
        ->name('citas.iniciar')
        ->middleware('modulo:citas');
    Route::patch('citas/{cita}/finalizar', [CitaController::class, 'finalizar'])
        ->name('citas.finalizar')
        ->middleware('modulo:citas');
    Route::get('citas/{id}/factura', [CitaController::class, 'factura'])
        ->name('citas.factura')
        ->middleware('modulo:citas');
    Route::get('agenda', [AgendaController::class, 'index'])
        ->name('agenda.index')
        ->middleware('modulo:agenda');
    Route::get('agenda/eventos', [AgendaController::class, 'eventos'])
        ->name('agenda.eventos')
        ->middleware('modulo:agenda');

    Route::resource('pagos', PagoController::class)
        ->only(['index', 'create', 'store', 'edit', 'update'])
        ->middleware('modulo:pagos');

    Route::get('pagos/{pago}/anular', [PagoController::class, 'formularioAnular'])
        ->name('pagos.anular.form')
        ->middleware('modulo:pagos');
    Route::patch('pagos/{pago}/anular', [PagoController::class, 'anular'])
        ->name('pagos.anular')
        ->middleware('modulo:pagos');

    Route::get('reportes/ingresos', [ReporteController::class, 'ingresos'])
        ->name('reportes.ingresos')
        ->middleware('modulo:reportes');
});