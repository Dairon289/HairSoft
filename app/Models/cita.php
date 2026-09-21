<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'fecha_programada',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'fechaCancelacion',
        'motivoCancelacion',
    ];


    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'fechaCancelacion' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalleServicios()
    {
        return $this->hasMany(DetalleServicio::class);
    }   
}   

