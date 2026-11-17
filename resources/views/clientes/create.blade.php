@extends('layouts.app')


@section('title')

    Crear Cliente
    
@endsection


@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white shadow-lg rounded-xl p-4 sm:p-5">

        <h2 class="text-xl font-bold text-center mb-3">
            Nuevo Cliente
        </h2>

        <form action="{{ route('cliente.store') }}" method="post">
            @csrf

            <input type="hidden" name="rol" value="{{ old('rol', 'cliente') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2.5">

                <div>
                    <label for="nombre" class="block text-xs font-semibold mb-1">Nombre Cliente</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('nombre') border-red-500 @enderror" required>
                    @error('nombre')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror 
                </div>

                <div>
                    <label for="apellido" class="block text-xs font-semibold mb-1">Apellido Cliente</label>
                    <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('apellido') border-red-500 @enderror" required>
                    @error('apellido')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="correo" class="block text-xs font-semibold mb-1">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
                        placeholder="ejemplo@gmail.com"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('correo') border-red-500 @enderror" required>
                    @error('correo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-xs font-semibold mb-1">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}"
                        placeholder="Ej: 3001234567"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('telefono') border-red-500 @enderror" required>
                    @error('telefono')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-xs font-semibold mb-1">Estado Cliente</label>
                    <select id="estado" name="estado" class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('estado') border-red-500 @enderror">
                        <option value="activo" @selected(old('estado', 'activo') === 'activo')>Activo</option>
                        <option value="inactivo" @selected(old('estado') === 'inactivo')>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold mb-1">Contraseña</label>
                    <input type="password" id="password" name="password"
                        placeholder="Mínimo 8 caracteres"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 mt-2 md:col-span-2">
                    <a href="{{ route('cliente.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white rounded-lg px-4 py-1.5 text-sm">
                        Volver
                    </a>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-5 py-1.5 text-sm">
                        Guardar Cliente
                    </button>
                </div>

            </div>
        </form>

    </div>

</div>

@endsection