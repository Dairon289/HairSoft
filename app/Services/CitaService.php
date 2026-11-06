<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Empleado;
use App\Models\servicio;
use App\Repositories\CitaRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $this->validarFueraDeMantenimiento($fechaProgramada, $fechaFinProgramada);
        $this->validarEmpleadoOfreceServicios($datos['empleado_id'], $servicios);
        $this->validarFueraDeMantenimiento($fechaProgramada, $fechaFinProgramada);

        $this->validarCruceHorario(
            $datos['empleado_id'],
            $fechaProgramada,
            $fechaFinProgramada
        );

        $datos['fecha_fin_programada'] = $fechaFinProgramada;

        $cita = $this->citaRepository->create($datos);

        foreach ($servicios as $servicioId) {
            $servicio = servicio::findOrFail($servicioId);

            $cita->detalleServicios()->create([
                'servicio_id'    => $servicio->id,
                'precioServicio' => $servicio->precioServicio,
            ]);
        }
        return $cita;
    }

    private const MAX_HORAS_ENTRE_FECHAS = 24;

    public function actualizar(int $id, array $datosValidos)
    {
        $cita = $this->obtenerPorId($id);

        if ($cita->estado === 'cancelada') {
            throw new \DomainException('Una cita cancelada no se puede modificar.');
        }

        $this->validarAvanceDeEstado($cita->estado, $datosValidos['estado']);
        $this->validarFechasSegunEstado($datosValidos);

        $fechaProgramada = Carbon::parse($datosValidos['fecha_programada']);
        $serviciosEditados = servicio::whereIn('id', $datosValidos['servicios'])->get();
        $fechaFinProgramada = $fechaProgramada->copy()
            ->addMinutes($this->calcularDuracionMinutos($serviciosEditados));

        if (! $fechaProgramada->equalTo($cita->fecha_programada)) {
            $this->validarFueraDeMantenimiento($fechaProgramada, $fechaFinProgramada);
        }

        if (!empty($datosValidos['empleado_id'])) {
            $this->validarEmpleadoOfreceServicios($datosValidos['empleado_id'], $datosValidos['servicios']);
        }

        if (!empty($datosValidos['fecha_inicio']) && !empty($datosValidos['fecha_fin'])) {
            $inicioCruce = Carbon::parse($datosValidos['fecha_inicio']);
            $finCruce = Carbon::parse($datosValidos['fecha_fin']);
        } else {
            $inicioCruce = $fechaProgramada;
            $finCruce = $fechaFinProgramada;
        }

        if (!empty($datosValidos['empleado_id'])) {
            $this->validarCruceHorario($datosValidos['empleado_id'], $inicioCruce, $finCruce, $id);
        }

        $datosValidos['fecha_fin_programada'] = $fechaFinProgramada;

        $servicios = $datosValidos['servicios'];
        unset($datosValidos['servicios']);

        return DB::transaction(function () use ($id, $datosValidos, $servicios) {
            $cita = $this->citaRepository->actualizar($id, $datosValidos);
            $this->citaRepository->sincronizarServicios($id, $servicios);

            return $cita;
        });
    }

    private function validarAvanceDeEstado(string $estadoActual, string $estadoNuevo): void
    {
        $orden = \App\Models\Cita::ORDEN_ESTADOS;

        if ($orden[$estadoNuevo] < $orden[$estadoActual]) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'estado' => "No se puede cambiar el estado de \"{$estadoActual}\" a \"{$estadoNuevo}\". El estado solo puede avanzar.",
            ]);
        }
    }

    private function validarFechasSegunEstado(array $datos): void
    {
        $estado = $datos['estado'];
        $inicio = !empty($datos['fecha_inicio']) ? \Carbon\Carbon::parse($datos['fecha_inicio']) : null;
        $fin    = !empty($datos['fecha_fin']) ? \Carbon\Carbon::parse($datos['fecha_fin']) : null;
        $errores = [];

        if (in_array($estado, ['pendiente', 'confirmada'])) {
            if ($inicio) {
                $errores['fecha_inicio'] = 'Una cita pendiente o confirmada no lleva fecha de inicio.';
            }
            if ($fin) {
                $errores['fecha_fin'] = 'Una cita pendiente o confirmada no lleva fecha fin.';
            }
        }

        if ($estado === 'iniciada' && $fin) {
            $errores['fecha_fin'] = 'Una cita iniciada todavía no tiene fecha fin.';
        }

        if ($inicio && !isset($errores['fecha_inicio'])) {
            $programada = \Carbon\Carbon::parse($datos['fecha_programada']);

            if ($inicio->lt($programada)) {
                $errores['fecha_inicio'] = 'La fecha de inicio no puede ser anterior a la fecha programada.';
            } elseif ($inicio->gte($programada->copy()->addHours(self::MAX_HORAS_ENTRE_FECHAS))) {
                $errores['fecha_inicio'] = 'La fecha de inicio no puede ser 24 horas o más después de la fecha programada.';
            } elseif ($inicio->gt(now())) {
                $errores['fecha_inicio'] = 'La fecha de inicio no puede estar en el futuro.';
            }
        }

        if ($fin && !isset($errores['fecha_fin'])) {
            if ($inicio && $fin->gte($inicio->copy()->addHours(self::MAX_HORAS_ENTRE_FECHAS))) {
                $errores['fecha_fin'] = 'La fecha fin no puede ser 24 horas o más después de la fecha de inicio.';
            } elseif ($fin->gt(now())) {
                $errores['fecha_fin'] = 'La fecha fin no puede estar en el futuro.';
            }
        }

        if ($errores) {
            throw \Illuminate\Validation\ValidationException::withMessages($errores);
        }
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
        if ($cita->fecha_programada->isToday()) {
            throw new \Exception('No se puede cancelar una cita programada para hoy.');
        }
        $this->citaRepository->actualizar($idCita, [
            'estado' => 'cancelada',
            'fechaCancelacion' => now(),
            'motivoCancelacion' => $motivoCancelacion,
        ]);
    }  


    private function validarCruceHorario($empleadoId, $inicio, $fin, $citaIdExcluir = null)
    {
        $existeCruce = $this->citaRepository->existeCruceHorario(
            $empleadoId,
            $inicio,
            $fin,
            $citaIdExcluir
        );

        if ($existeCruce) {
            throw ValidationException::withMessages([
                'fecha_programada' => 'El empleado ya tiene otra cita que se cruza con ese horario. Elige otro horario.',
            ]);
        }
    }

    private function validarEmpleadoOfreceServicios($empleadoId, array $idsServicios): void
    {
        $empleado = Empleado::findOrFail($empleadoId);

        $idsQueHace = $empleado->servicios->pluck('id')->map(fn ($id) => (int) $id)->all();
        $idsPedidos = array_map('intval', $idsServicios);

        if (array_diff($idsPedidos, $idsQueHace)) {
            throw ValidationException::withMessages([
                'empleado_id' => 'El empleado seleccionado no ofrece todos los servicios elegidos. Elige otro empleado o cambia los servicios.',
            ]);
        }
    }

    private function calcularDuracionMinutos($servicios): int
    {
        $total = 0;

        foreach ($servicios as $servicio) {
            $duracion = Carbon::parse($servicio->duracionServicio);
            $total += ($duracion->hour * 60) + $duracion->minute;
        }

        return $total;
    }

    private function validarFueraDeMantenimiento(Carbon $inicio, Carbon $fin): void
    {
        [$horaInicio, $minutoInicio] = array_map('intval', explode(':', config('mantenimiento.inicio')));
        [$horaFin, $minutoFin] = array_map('intval', explode(':', config('mantenimiento.fin')));

        $dia = $inicio->copy()->subDay()->startOfDay();
        $ultimoDia = $fin->copy()->startOfDay();

        while ($dia->lte($ultimoDia)) {
            $franjaInicio = $dia->copy()->setTime($horaInicio, $minutoInicio);
            $franjaFin = $dia->copy()->setTime($horaFin, $minutoFin);

            if ($franjaFin->lte($franjaInicio)) {
                $franjaFin->addDay();
            }

            if ($inicio->lt($franjaFin) && $fin->gt($franjaInicio)) {
                $horaInicioTexto = Carbon::createFromFormat('H:i', config('mantenimiento.inicio'))->format('g:i A');
                $horaFinTexto = Carbon::createFromFormat('H:i', config('mantenimiento.fin'))->format('g:i A');
                $citaDesde = $inicio->format('g:i A');
                $citaHasta = $fin->format('g:i A');

                throw ValidationException::withMessages([
                    'fecha_programada' => "La cita ({$citaDesde} a {$citaHasta}) se cruza con el horario de mantenimiento del sistema ({$horaInicioTexto} a {$horaFinTexto}). Elige otro horario.",
                ]);
            }

            $dia->addDay();
        }
    }

    public function obtenerTodas(?string $desde = null, ?string $hasta = null, ?int $servicioId = null, bool $soloPagoPendiente = false, ?int $clienteId = null)
    {
        return $this->citaRepository->obtenerTodas($desde, $hasta, $servicioId, $soloPagoPendiente, $clienteId);
    }

    public function obtenerPorId(int $id)
    {
        return $this->citaRepository->buscarPorId($id)->load('detalleServicios');
    }

    public function puedeCancelarse(Cita $cita): bool
    {
        if (in_array($cita->estado, ['iniciada', 'finalizada', 'cancelada'])) {
            return false;
        }

        return ! $cita->fecha_programada->isToday();
    }

    public function actualizarMotivo(int $id, string $motivo)
    {
        $cita = $this->obtenerPorId($id);

        if ($cita->estado !== 'cancelada') {
            throw new \DomainException('Solo se puede editar el motivo de una cita cancelada.');
        }

        return $this->citaRepository->actualizar($id, ['motivoCancelacion' => $motivo]);
    }

    public function puedeFacturarse(Cita $cita): bool
    {
        return $cita->estado !== 'cancelada';
    }

    public function obtenerParaFactura(int $id): Cita
    {
        $cita = $this->citaRepository->buscarPorId($id);

        if (! $this->puedeFacturarse($cita)) {
            throw new \DomainException('Las citas canceladas no tienen factura.');
        }

        return $cita->load(['cliente', 'empleado', 'detalleServicios.servicio']);
    }
}