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

    public const SIGUIENTE_ESTADO = [
        'pendiente'  => 'confirmada',
        'confirmada' => 'iniciada',
        'iniciada'   => 'finalizada',
    ];

    public const MINUTOS_ANTES = 5;

    public const MINUTOS_TOLERANCIA = 10;

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

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'finalizada');
    }

    public function scopePagables($query)
    {
        return $query->finalizadas()->whereDoesntHave('pago', function ($pago) {
            $pago->where('estado_pago', '!=', 'anulado');
        });
    }

    public function inicioPermitidoDesde()
    {
        return $this->fecha_programada->copy()->subMinutes(self::MINUTOS_ANTES);
    }

    public function limiteInicio()
    {
        return $this->fecha_programada->copy()->addMinutes(self::MINUTOS_TOLERANCIA);
    }

    public function estaVencida(): bool
    {
        return in_array($this->estado, ['pendiente', 'confirmada'], true)
            && now()->gt($this->limiteInicio());
    }

    public function mensajeVencida(): string
    {
        return 'Pasó el tiempo de espera de esta cita (hora programada '
            . $this->fecha_programada->format('g:i A')
            . ' + ' . self::MINUTOS_TOLERANCIA . ' minutos). Ya no se puede iniciar.';
    }

    public function estadosPermitidos(): array
    {
        if ($this->estaVencida()) {
            return [$this->estado];
        }

        $siguiente = self::SIGUIENTE_ESTADO[$this->estado] ?? null;

        return array_values(array_filter([$this->estado, $siguiente]));
    }


    public function datosEditables(): bool
    {
        return in_array($this->estado, ['pendiente', 'confirmada'], true);
    }
}