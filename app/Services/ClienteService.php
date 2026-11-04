<?php
namespace App\Services;

use App\Repositories\ClienteRepository;
use Illuminate\Support\Facades\DB;
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
        $passwordPlano = $datos['password'];
        $datos['password'] = Hash::make($passwordPlano);

        DB::transaction(function () use ($datos, $passwordPlano) {
            $cliente = $this->clienteRepository->guardar($datos);
            $this->clienteRepository->sincronizarCuenta($cliente, $passwordPlano);
        });
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
        $passwordPlano = $datos['password'] ?? null;

        if (empty($passwordPlano)) {
            unset($datos['password']);
        } else {
            $datos['password'] = Hash::make($passwordPlano);
        }

        DB::transaction(function () use ($id, $datos, $passwordPlano) {
            $cliente = $this->clienteRepository->actualizar($id, $datos);
            $this->clienteRepository->sincronizarCuenta($cliente, $passwordPlano);
        });
    }
}