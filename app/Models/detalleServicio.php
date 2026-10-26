<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleServicio extends Model
{
    protected $table = 'detalleServicios';

    protected $fillable = [
        'cita_id',
        'servicio_id',
        'precioServicio'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    protected $casts = [
    'precioServicio' => 'decimal:2',
];
}