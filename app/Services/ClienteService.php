<?php
namespace App\Services;

use App\Repositories\ClienteRepository;
use Illuminate\Support\Facades\Hash;

use Exception;

class ClienteService
{
    private ClienteRepository $clienteRepository;

    public function __construct(ClienteRepository $cliente_repository)
    {
        $this->clienteRepository = $cliente_repository;
    }
    

    public function listarTodo()
    {   
        return $this->clienteRepository->listarTodo();
    }


    public function guardar(array $datos)
    {
        $datos['password'] = Hash::make($datos['password']);

        $this->clienteRepository->guardar($datos);
    }

    public function eliminar(int $id): void
    {
        $cliente = $this->clienteRepository->buscarporid($id);

        if (!$cliente) {
            throw new \Exception('El cliente no existe.');
        }

        if ($this->clienteRepository->tieneCitas($id)) {
            throw new \Exception('No se puede eliminar el cliente porque tiene citas registradas. Puedes cambiar su estado a inactivo para conservar el historial.');
        }

        $this->clienteRepository->eliminar($id);
    }


    public function buscarporid(int $id)
    {
        return $this->clienteRepository->buscarporid($id);
    }

    public function actualizar(int $id, array $datos)
    {
        if (empty($datos['password'])) {
            unset($datos['password']);
        } else {
            $datos['password'] = Hash::make($datos['password']);
        }

        $this->clienteRepository->actualizar($id, $datos);
    }
}
