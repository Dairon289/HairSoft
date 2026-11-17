<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Models\servicio;
use App\Services\ServicioService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServicioController extends Controller
{
    private ServicioService $servicio_service;

    public function __construct(ServicioService $servicioservice)
    {
        $this->servicio_service = $servicioservice;
    }

    public function index(): View
    {
        $servicios = $this->servicio_service->listarTodo();

        return view('servicios.index', compact('servicios'));
    }

    public function create(): View
    {
        return view('servicios.create');
    }

    public function store(StoreServicioRequest $request): RedirectResponse
    {
        $this->servicio_service->guardar($request->validated());

        return redirect()->route('servicio.index')->with('success', 'Servicio creado correctamente');
    }

    public function edit(int $id): View
    {
        $servicios = $this->servicio_service->buscarporid($id);

        return view('servicios.edit', compact('servicios'));
    }

    public function update(UpdateServicioRequest $request, int $id): RedirectResponse
    {
        $this->servicio_service->actualizar($id, $request->validated());

        return redirect()->route('servicio.index')->with('successedit', 'Servicio actualizado correctamente');
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->servicio_service->eliminar($id);
            return redirect()->route('servicio.index')->with('successdelete', 'Servicio eliminado correctamente');
        } catch (Exception $e) {
            return redirect()->route('servicio.index')->with('error', $e->getMessage());
        }
    }
}
