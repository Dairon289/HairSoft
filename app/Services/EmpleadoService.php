<?php
namespace App\Services;

use App\Repositories\EmpleadoRepository;
use Illuminate\Support\Facades\Hash;

use Exception;

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


    public function guardar(array $datos)
    {
        $this->empleadoRepository->guardar($datos);
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
