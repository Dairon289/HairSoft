<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Pago;
use App\Repositories\PagoRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PagoService
{
    private PagoRepository $pagoRepository;

    public function __construct(PagoRepository $pago_repository)
    {
        $this->pagoRepository = $pago_repository;
    }

    public function listar()
    {
        return $this->pagoRepository->obtenerTodos();
    }

    public function obtener(int $id): Pago
    {
        return $this->pagoRepository->obtenerPorId($id);
    }

   public function crear(array $datos): Pago
    {
        $cita = Cita::findOrFail($datos['cita_id']);

        if ($cita->estado !== 'finalizada') {
            throw ValidationException::withMessages([
                'cita_id' => 'Solo se puede registrar un pago para una cita finalizada.',
            ]);
        }

        if ($cita->pago()->where('estado_pago', '!=', 'anulado')->exists()) {
            throw ValidationException::withMessages([
                'cita_id' => 'Esta cita ya tiene un pago registrado.',
            ]);
        }

        $datos['monto'] = $cita->total;

        return $this->pagoRepository->crear($this->ajustarFecha($datos));   
    }
    
    public function actualizar(int $id, array $datos): Pago
    {
        $pago = $this->pagoRepository->obtenerPorId($id);

        if ($pago->estaCerrado()) {
            throw ValidationException::withMessages([
                'estado_pago' => 'Un pago ya realizado no se puede modificar.',
            ]);
        }

        $datos['monto'] = $pago->cita->total;

        return $this->pagoRepository->actualizar($pago, $this->ajustarFecha($datos));
    }

    private function ajustarFecha(array $datos): array
    {
        $datos['fecha_pago'] = $datos['estado_pago'] === 'pagado' ? now() : null;

        return $datos;
    }

    public function anular(int $id, string $motivo): Pago
    {
        $pago = $this->pagoRepository->obtenerPorId($id);

        if ($pago->estaAnulado()) {
            throw ValidationException::withMessages([
                'motivo_anulacion' => 'Este pago ya fue anulado.',
            ]);
        }

        return $this->pagoRepository->actualizar($pago, [
            'estado_pago'      => 'anulado',
            'motivo_anulacion' => $motivo,
            'fecha_anulacion'  => now(),
            'anulado_por'      => Auth::id(),
        ]);
    }
}