<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\servicio;
use App\Repositories\CitaRepository;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CitaService
{
    protected CitaRepository $citaRepository;

    public function __construct(CitaRepository $citaRepository)
    {
        $this->citaRepository = $citaRepository;
    }

    public function create(array $datos)
    {
        $servicios = $datos['servicios'];

        unset($datos['servicios']);

        
        $serviciosSeleccionados = servicio::whereIn('id', $servicios)->get();

        $fechaProgramada = Carbon::parse($datos['fecha_programada']);

        $duracionTotal = 0;

        foreach ($serviciosSeleccionados as $servicio) {
            $duracion = Carbon::parse($servicio->duracionServicio);

            $duracionTotal += ($duracion->hour * 60);
            $duracionTotal += $duracion->minute;
        }

        $fechaFinProgramada = $fechaProgramada->copy()->addMinutes($duracionTotal);

        $this->validarCruceHorario(
            $datos['empleado_id'],
            $fechaProgramada,
            $fechaFinProgramada
        );

        $datos['fecha_fin_programada'] = $fechaFinProgramada;

        $cita = $this->citaRepository->create($datos);

        foreach ($servicios as $servicioId) {
            $cita->detalleServicios()->create([
                'servicio_id' => $servicioId
            ]);
        }
        return $cita;
    }

    public function actualizar(int $id, array $datosValidos)
    {
        if (!empty($datosValidos['empleado_id']) &&!empty($datosValidos['fecha_inicio']) &&!empty($datosValidos['fecha_fin'])
        ) {
            $this->validarCruceHorario(
                $datosValidos['empleado_id'],
                $datosValidos['fecha_inicio'],
                $datosValidos['fecha_fin'],
                $id
            );
        }

        return $this->citaRepository->actualizar($id, $datosValidos);
    }

     public function cancelar(int $idCita, ?string $motivoCancelacion = null): void
    {
        $cita = $this->citaRepository->buscarPorId($idCita);

        if (!$cita) {
            throw new \Exception('La cita no existe.');
        }
        if (in_array($cita->estado, ['iniciada', 'finalizada', 'cancelada'])) {
            throw new \Exception('No se puede cancelar una cita en estado: ' . $cita->estado);
        }
        $this->citaRepository->actualizar($idCita, [
            'estado' => 'cancelada',
            'fechaCancelacion' => now(),
            'motivoCancelacion' => $motivoCancelacion,
        ]);
    }  


    private function validarCruceHorario($empleadoId, $fechaProgramada, $citaIdExcluir = null)
    {
        $existeCruce = $this->citaRepository->existeCruceHorario(
            $empleadoId,
            $fechaProgramada,
            $citaIdExcluir
        );

        if ($existeCruce) {
            throw ValidationException::withMessages([
                'fecha_programada' => 'El empleado ya tiene una cita programada en ese horario.',
            ]);
        }
    }

    public function obtenerTodas()
    {
        return $this->citaRepository->obtenerTodas();
    }   


    public function obtenerPorId(int $id)
    {
        return $this->citaRepository->buscarPorId($id)->load('detalleServicios');
    }
}