<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'cita_id',
        'monto',
        'metodo_pago',
        'estado_pago',
        'fecha_pago',
        'motivo_anulacion',
        'fecha_anulacion', 
        'anulado_por'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'fecha_anulacion' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function estaPagado(): bool
    {
        return $this->estado_pago === 'pagado';
    }

    public function estaAnulado(): bool
    {
        return $this->estado_pago === 'anulado';
    }

    public function estaCerrado(): bool
    {
        return $this->estaPagado() || $this->estaAnulado();
    }
}