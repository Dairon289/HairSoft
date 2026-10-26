@extends('layouts.app')

@section('title')

    Editar Empleado

@endsection

@section('content')

<div class="container mx-auto mt-4">

    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">

        <h2 class="text-2xl font-bold text-center mb-4">
            Editar Empleado
        </h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('empleados.update', $empleado->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">

                <div class="mb-4">
                    <label for="nombreEmpleado" class="block mb-1 font-semibold">Nombre Empleado</label>
                    <input type="text" id="nombreEmpleado" name="nombreEmpleado"
                        value="{{ old('nombreEmpleado', $empleado->nombreEmpleado) }}"
                        class="w-full border rounded px-3 py-2 @error('nombreEmpleado') border-red-500 @enderror">
                </div>

                <div class="mb-4">
                    <label for="apellidoEmpleado" class="block mb-1 font-semibold">Apellido Empleado</label>
                    <input type="text" id="apellidoEmpleado" name="apellidoEmpleado"
                        value="{{ old('apellidoEmpleado', $empleado->apellidoEmpleado) }}"
                        class="w-full border rounded px-3 py-2 @error('apellidoEmpleado') border-red-500 @enderror">
                </div>

                <div class="mb-4">
                    <label for="correoEmpleado" class="block mb-1 font-semibold">Correo Empleado</label>
                    <input type="email" id="correoEmpleado" name="correoEmpleado"
                        value="{{ old('correoEmpleado', $empleado->correoEmpleado) }}"
                        class="w-full border rounded px-3 py-2 @error('correoEmpleado') border-red-500 @enderror">
                </div>

                <div class="mb-4">
                    <label for="telefonoEmpleado" class="block mb-1 font-semibold">Teléfono Empleado</label>
                    <input type="number" id="telefonoEmpleado" name="telefonoEmpleado"
                        value="{{ old('telefonoEmpleado', $empleado->telefonoEmpleado) }}"
                        class="w-full border rounded px-3 py-2 @error('telefonoEmpleado') border-red-500 @enderror">
                </div>

                <div class="mb-4 md:col-span-2">
                    <label for="especialidad" class="block mb-1 font-semibold">Especialidad</label>
                    <input type="text" id="especialidad" name="especialidad"
                        value="{{ old('especialidad', $empleado->especialidad) }}"
                        class="w-full border rounded px-3 py-2 @error('especialidad') border-red-500 @enderror">
                </div>

                <div class="mb-4">
                    <label for="disponibilidad" class="block mb-1 font-semibold">Disponibilidad</label>
                    <select id="disponibilidad" name="disponibilidad" class="w-full border rounded px-3 py-2">
                        <option value="disponible" @selected(old('disponibilidad', $empleado->disponibilidad) === 'disponible')>Disponible</option>
                        <option value="no disponible" @selected(old('disponibilidad', $empleado->disponibilidad) === 'no disponible')>No Disponible</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="estadoEmpleado" class="block mb-1 font-semibold">Estado Empleado</label>
                    <select id="estadoEmpleado" name="estadoEmpleado" class="w-full border rounded px-3 py-2">
                        <option value="activo" @selected(old('estadoEmpleado', $empleado->estadoEmpleado) === 'activo')>Activo</option>
                        <option value="inactivo" @selected(old('estadoEmpleado', $empleado->estadoEmpleado) === 'inactivo')>Inactivo</option>
                    </select>
                </div>

                <div class="mb-4 md:col-span-2">
                    <p class="block mb-1 font-semibold">Servicios Ofrecidos</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 border rounded px-3 py-2">
                        @foreach ($servicios as $servicio)
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                    id="servicio_{{ $servicio->id }}"
                                    @checked(in_array($servicio->id, old('servicios', $serviciosAsignados)))>
                                <label for="servicio_{{ $servicio->id }}">{{ $servicio->nombreServicio }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex items-end gap-3 mb-4 md:col-span-2">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                        Guardar
                    </button>
                    <a href="{{ route('empleados.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white rounded px-5 py-2">
                        Volver
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection