@extends('layouts.app')


@section('title')
    Editar Cliente
@endsection


@section('content')


<div class="container mx-auto mt-4">

    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">

        <h2 class="text-2xl font-bold text-center mb-4">
            Editar Cliente
        </h2>

        <form action="{{ route('cliente.update', $cliente->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">

                <div class="mb-4">
                    <label for="nombre" class="block mb-1 font-semibold">Nombre Cliente</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                        class="w-full border rounded px-3 py-2 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="apellido" class="block mb-1 font-semibold">Apellido Cliente</label>
                    <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $cliente->apellido) }}"
                        class="w-full border rounded px-3 py-2 @error('apellido') border-red-500 @enderror">
                    @error('apellido')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="correo" class="block mb-1 font-semibold">Correo Cliente</label>
                    <input type="email" id="correo" name="correo" value="{{ old('correo', $cliente->correo) }}"
                        class="w-full border rounded px-3 py-2 @error('correo') border-red-500 @enderror">
                    @error('correo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="telefono" class="block mb-1 font-semibold">Teléfono Cliente</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                        class="w-full border rounded px-3 py-2 @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-1 font-semibold">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Dejar en blanco para no cambiarla"
                        class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="rol" class="block mb-1 font-semibold">Rol Cliente</label>
                    <select id="rol" name="rol"
                        class="w-full border rounded px-3 py-2 @error('rol') border-red-500 @enderror">
                        <option value="cliente" @selected(old('rol', $cliente->rol) === 'cliente')>Cliente</option>
                        <option value="administrador" @selected(old('rol', $cliente->rol) === 'administrador')>Administrador</option>
                    </select>
                    @error('rol')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="estado" class="block mb-1 font-semibold">Estado Cliente</label>
                    <select id="estado" name="estado"
                        class="w-full border rounded px-3 py-2 @error('estado') border-red-500 @enderror">
                        <option value="activo" @selected(old('estado', $cliente->estado) === 'activo')>Activo</option>
                        <option value="inactivo" @selected(old('estado', $cliente->estado) === 'inactivo')>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end gap-3 mb-4">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                        Guardar
                    </button>
                    <a href="{{ route('cliente.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white rounded px-5 py-2">
                        Volver
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection