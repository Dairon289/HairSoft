<?php

namespace App\Services;

use App\Repositories\ReporteRepository;
use Carbon\Carbon;

class ReporteService
{
    private ReporteRepository $reporteRepository;

    public function __construct(ReporteRepository $reporte_repository)
    {
        $this->reporteRepository = $reporte_repository;
    }

    public function ingresos(?string $desde, ?string $hasta): array
    {
        $inicio = $desde ? Carbon::parse($desde)->startOfDay() : now()->startOfMonth();
        $fin = $hasta ? Carbon::parse($hasta)->endOfDay() : now()->endOfDay();

        return [
            'desde'       => $inicio->toDateString(),
            'hasta'       => $fin->toDateString(),
            'total'       => $this->reporteRepository->totalIngresos($inicio, $fin),
            'porServicio' => $this->reporteRepository->ingresosPorServicio($inicio, $fin),
        ];
    }
}