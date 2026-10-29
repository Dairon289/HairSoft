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
        if ($this->servicioRepository->tieneCitasActivasFuturas($id)) {
            throw new Exception('No se puede eliminar el servicio porque tiene citas pendientes o confirmadas programadas a futuro.');
        }

        if ($this->servicioRepository->tieneCitas($id)) {
            throw new Exception('No se puede eliminar el servicio porque ya forma parte del historial de citas. Cambia su estado en lugar de eliminarlo.');
        }

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
