<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombreServicio',
        'duracionServicio',
        'descripcionServicio',
        'precioServicio',
        'estadoServicio',
        
    ];


    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_servicio');
    }
}
