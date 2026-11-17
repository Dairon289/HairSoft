<?php

namespace App\Http\Controllers;

use App\Models\Empleado;

class GaleriaEmpleadoController extends Controller
{
    public function __invoke()
    {
        $empleados = Empleado::where('estadoEmpleado', 'activo')
            ->with(['servicios' => function ($consulta) {
                $consulta->where('estadoServicio', 'activo');
            }])
            ->orderBy('nombreEmpleado')
            ->get();

        return view('empleados.galeria', compact('empleados'));
    }
}