<?php
namespace App\Services;

use App\Repositories\EmpleadoRepository;
use Illuminate\Support\Facades\Hash;

use Exception;
use Illuminate\Support\Facades\DB;

class EmpleadoService
{
    private EmpleadoRepository $empleadoRepository;

    public function __construct(EmpleadoRepository $empleado_repository)
    {
        $this->empleadoRepository = $empleado_repository;
    }
    

    public function listarTodo()
    {   
        return $this->empleadoRepository->listarTodo();
    }


    public function guardar(array $datos, array $idsServicios)
    {
        DB::transaction(function () use ($datos, $idsServicios) {
            $empleado = $this->empleadoRepository->guardar($datos);
            $this->empleadoRepository->sincronizarServicios($empleado->id, $idsServicios);
        });
    }

    public function eliminar(int $id)
    {
        $this->empleadoRepository->eliminar($id);
    }


    public function buscarporid(int $id)
    {
        return $this->empleadoRepository->buscarporid($id);
    }

    public function actualizar(int $id, array $datos)
    {

        $this->empleadoRepository->actualizar($id, $datos);
    }

    public function obtenerServiciosAsignados(int $id)
    {
        return $this->empleadoRepository->obtenerServiciosAsignados($id); 
    }

    public function sincronizarServicios(int $id, array $idsServicios)
    {
        $this->empleadoRepository->sincronizarServicios($id, $idsServicios);
    }    
}
