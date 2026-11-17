@extends('layouts.app')


@section('title')
    Editar Cliente
@endsection


@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white shadow-lg rounded-xl p-4 sm:p-5">

        <h2 class="text-xl font-bold text-center mb-3">
            Editar Cliente
        </h2>

        <form action="{{ route('cliente.update', $cliente->id) }}" method="post">
            @csrf
            @method('PUT')

            <input type="hidden" name="rol" value="{{ old('rol', $cliente->rol ?? 'cliente') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2.5">

                <div>
                    <label for="nombre" class="block text-xs font-semibold mb-1">Nombre Cliente</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="apellido" class="block text-xs font-semibold mb-1">Apellido Cliente</label>
                    <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $cliente->apellido) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('apellido') border-red-500 @enderror">
                    @error('apellido')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="correo" class="block text-xs font-semibold mb-1">Correo Cliente</label>
                    <input type="email" id="correo" name="correo" value="{{ old('correo', $cliente->correo) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('correo') border-red-500 @enderror">
                    @error('correo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-xs font-semibold mb-1">Teléfono Cliente</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Dejar en blanco para no cambiarla"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-xs font-semibold mb-1">Estado Cliente</label>
                    <select id="estado" name="estado"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('estado') border-red-500 @enderror">
                        <option value="activo" @selected(old('estado', $cliente->estado) === 'activo')>Activo</option>
                        <option value="inactivo" @selected(old('estado', $cliente->estado) === 'inactivo')>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 mt-2 md:col-span-2">
                    <a href="{{ route('cliente.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white rounded-lg px-4 py-1.5 text-sm">
                        Volver
                    </a>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-5 py-1.5 text-sm">
                        Guardar
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection