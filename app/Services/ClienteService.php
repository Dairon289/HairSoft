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

    public function eliminar(int $id)
    {
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
