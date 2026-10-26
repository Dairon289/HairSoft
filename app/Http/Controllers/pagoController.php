<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Services\PagoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PagoController extends Controller
{   
    private PagoService $pagoService;

    public function __construct(PagoService $pagoService) 
    {
        $this->pagoService = $pagoService;
    }

    public function index()
    {
        $pagos = $this->pagoService->listar();
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $citas = Cita::pagables()
            ->with(['cliente', 'detalleServicios'])
            ->orderByDesc('fecha_programada')
            ->get();

        return view('pagos.create', compact('citas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'cita_id' => [
                'required',
                Rule::exists('citas', 'id')->where('estado', 'finalizada'),
                Rule::unique('pagos', 'cita_id')
                    ->where(fn ($q) => $q->where('estado_pago', '!=', 'anulado')),
            ], // <- este cierre faltaba
            'metodo_pago' => ['required', Rule::in(['efectivo', 'transferencia'])],
            'estado_pago' => ['required', Rule::in(['pendiente', 'pagado'])],
        ]); 

        $this->pagoService->crear($datos);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $pago = $this->pagoService->obtener($id);

        if ($pago->estaCerrado()){
            return redirect()->route('pagos.index')
                ->with('succesdelete','Un pago ya re    alizado no se puede editar.');
        }
        
        return view('pagos.edit', compact('pago'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $datos = $request->validate([
            'metodo_pago' => ['required', Rule::in(['efectivo', 'transferencia'])],
            'estado_pago' => ['required', Rule::in(['pendiente', 'pagado'])],
        ]);

        $this->pagoService->actualizar($id, $datos);

        return redirect()->route('pagos.index')->with('successedit', 'Pago actualizado correctamente.');
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'finalizada');
    }

    public function scopePagables($query)
    {
        return $query->finalizadas()->doesntHave('pago');
    }

    public function formularioAnular(int $id)
    {
        $pago = $this->pagoService->obtener($id);

        if ($pago->estaAnulado()) {
            return redirect()->route('pagos.index')
                ->with('successdelete', 'Este pago ya fue anulado.');
        }

        return view('pagos.anular', compact('pago'));
    }

    public function anular(Request $request, int $id)
    {
        $datos = $request->validate([
            'motivo_anulacion' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $this->pagoService->anular($id, $datos['motivo_anulacion']);

        return redirect()->route('pagos.index')
            ->with('successdelete', 'Pago anulado correctamente.');
    }
}