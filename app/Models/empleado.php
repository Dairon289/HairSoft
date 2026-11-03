<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'nombreEmpleado',
        'apellidoEmpleado',
        'correoEmpleado',
        'telefonoEmpleado',
        'disponibilidad',
        'fechaIngreso',
        'horaIngreso',
        'horaSalida',
        'estadoEmpleado',
    ];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'empleadoServicio');
    }
}