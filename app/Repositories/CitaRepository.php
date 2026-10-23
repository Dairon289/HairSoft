<?php

namespace App\Repositories;

use App\Models\Cita;
use App\Models\servicio;

class CitaRepository
{
    protected Cita $model;

    public function __construct(Cita $model)
    {
        $this->model = $model;
    }

     public function buscarPorId(int $idCita): ?Cita
    {
        return Cita::findOrFail($idCita);
    }


    public function obtenerTodas()
    {
        return $this->model
            ->with(['cliente', 'detalleServicios.servicio'])
            ->get();
    }

    public function create(array $datos)
    {
        return $this->model->create($datos);
    }

    public function actualizar(int $id, array $datosValidos)
    {
        $cita = $this->model->findOrFail($id);
        $cita->update($datosValidos);

        return $cita;
    }

    public function sincronizarServicios(int $id, array $idsNuevos)
    {
        $cita = $this->model->findOrFail($id);
        
        $idsActuales = $cita->detalleServicios()->pluck('servicio_id')->toArray();

        $idsAEliminar = array_diff($idsActuales, $idsNuevos);
        $idsAAgregar = array_diff($idsNuevos, $idsActuales);

        $cita->detalleServicios()->whereIn('servicio_id', $idsAEliminar)->delete();

        
        foreach ($idsAAgregar as $servicioId) {
            $servicio = servicio::findOrFail($servicioId);

            $cita->detalleServicios()->create([
                'servicio_id' => $servicioId,
                'precioServicio' => $servicio->precioServicio,
            ]);
        }
    }

    public function cancelar(int $id, string $motivo)
    {
        $cita = $this->model->findOrFail($id);

        $cita->update([
            'estado' => 'cancelada',
            'fechaCancelacion' => now(),
            'motivoCancelacion' => $motivo,
        ]);

        return $cita;
    }

    public function existeCruceHorario($empleadoId, $fechaInicio, $fechaFin, $citaIdExcluir = null)
    {
        $query = $this->model
            ->where('empleado_id', $empleadoId)
            ->where('estado', '!=', 'cancelada')
            ->where('fecha_inicio', '<', $fechaFin)
            ->where('fecha_fin', '>', $fechaInicio);

        if ($citaIdExcluir !== null) {
            $query->where('id', '!=', $citaIdExcluir);
        }

        return $query->exists();
    }   
}