@extends('layouts.app')

@section('title', 'Acceso no permitido')
@section('page-title', 'Acceso no permitido')

@section('content')
    @php
        $usuario = auth()->user();

        // Misma lógica de inicio por rol que usa el login
        $inicio = match (true) {
            ! $usuario => route('welcome'),
            $usuario->hasRole(config('seguridad.rol_admin')) => route('admin.dashboard'),
            $usuario->hasRole(config('seguridad.rol_cliente')) => route('cliente.dashboard'),
            $usuario->hasRole(config('seguridad.rol_empleado')) => route('agenda.index'),
            default => route('welcome'),
        };

        // Página anterior; si no existe o es esta misma, se usa el inicio
        $volver = url()->previous($inicio);
        if ($volver === url()->current()) {
            $volver = $inicio;
        }

        // Mensaje específico solo si es uno nuestro (no los mensajes técnicos en inglés)
        $detalle = trim((string) $exception->getMessage());
        $esMensajeTecnico = $detalle === ''
            || \Illuminate\Support\Str::startsWith($detalle, ['User ', 'This action', 'Forbidden']);
    @endphp

    <div class="max-w-xl mx-auto mt-6">
        <x-card title="No tienes permiso para esta acción" subtitle="Error 403 · Acceso restringido">
            <p class="text-sm text-slate-500">
                Tu cuenta no tiene los permisos necesarios para ver esta página o realizar esta acción.
                Si crees que deberías tener acceso, comunícate con el administrador del sistema.
            </p>

            @unless ($esMensajeTecnico)
                <p class="mt-3 text-sm text-slate-400">{{ $detalle }}</p>
            @endunless

            <div class="mt-6 flex flex-wrap gap-3">
                <x-button href="{{ $inicio }}">Volver a donde estaba</x-button>
            </div>
        </x-card>
    </div>
@endsection 