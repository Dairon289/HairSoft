<?php

namespace App\Http\Controllers;

use App\Services\ReporteService;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    private ReporteService $reporte_service;

    public function __construct(ReporteService $reporte_service)
    {
        $this->reporte_service = $reporte_service;
    }

    public function ingresos(Request $request)
    {
        $datos = $request->validate([
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date|after_or_equal:desde',
        ]);

        $reporte = $this->reporte_service->ingresos(
            $datos['desde'] ?? null,
            $datos['hasta'] ?? null
        );

        return view('reportes.ingresos', $reporte);
    }
}