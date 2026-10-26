<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{   
    public const ORDEN_ESTADOS = [
        'pendiente'  => 0,
        'confirmada' => 1,
        'iniciada'   => 2,
        'finalizada' => 3,
    ];

    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'fecha_programada',
        'fecha_fin_programada',
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
        'fecha_programada' => 'datetime',
        'fecha_fin_programada' => 'datetime',
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

    protected function total(): Attribute
    {
        return Attribute::get(fn () => $this->detalleServicios->sum('precioServicio'));
    }

    public function pago() {
        return $this->hasOne(Pago::class);
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'finalizada');
    }

    public function scopePagables($query)
    {
        return $query->finalizadas()->doesntHave('pago');
    }
}   

