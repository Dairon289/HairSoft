<?php
namespace App\Services;

use App\Repositories\ServicioRepository;
use Illuminate\Support\Facades\Hash;

use Exception;

class ServicioService
{
    private ServicioRepository $servicioRepository;

    public function __construct(ServicioRepository $servicio_repository)
    {
        $this->servicioRepository = $servicio_repository;
    }
    

    public function listarTodo()
    {   
        return $this->servicioRepository->listarTodo();
    }


    public function guardar(array $datos)
    {
        $this->servicioRepository->guardar($datos);
    }

    public function eliminar(int $id)
    {
        $this->servicioRepository->eliminar($id);
    }


    public function buscarporid(int $id)
    {
        return $this->servicioRepository->buscarporid($id);
    }

    public function actualizar(int $id, array $datos)
    {

        $this->servicioRepository->actualizar($id, $datos);
    }
}
