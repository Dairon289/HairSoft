@extends('layouts.app')


@section('title')

    Cita
    
@endsection


@section('content')

<div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">
            Editar Cita
        </h2>


        @if($errors->any())

            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        
        <form action="{{route ('citas.update', $citas->id)}}" method="post">

            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="cliente_id" class="block mb-1 font-semibold">Cliente</label>
                <select id="cliente_id" name="cliente_id" class="w-full border rounded px-3 py-2">
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            @selected(old('cliente_id', $citas->cliente_id) == $cliente->id)>
                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="empleado_id" class="block mb-1 font-semibold">Empleado</label>
                <select id="empleado_id" name="empleado_id" class="w-full border rounded px-3 py-2">
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}"
                            @selected(old('empleado_id', $citas->empleado_id) == $empleado->id)>
                            {{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha_programada" class="block mb-1 font-semibold">Fecha programada</label>
                <input type="datetime-local" id="fecha_programada" name="fecha_programada"
                    value="{{ old('fecha_programada', $citas->fecha_programada?->format('Y-m-d\TH:i')) }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block mb-1 font-semibold">Fecha de Inicio</label>
                <input type="datetime-local" id="fecha_inicio" name="fecha_inicio"
                    value="{{ old('fecha_inicio', $citas->fecha_inicio?->format('Y-m-d\TH:i')) }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="fecha_fin" class="block mb-1 font-semibold">Fecha fin</label>
                <input type="datetime-local" id="fecha_fin" name="fecha_fin"
                    value="{{ old('fecha_fin', $citas->fecha_fin?->format('Y-m-d\TH:i')) }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="estado" class="block mb-1 font-semibold">Estado de la cita</label>

                @php
                    $estados = ['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'iniciada' => 'Iniciada', 'finalizada' => 'Finalizada'];
                    $orden = \App\Models\Cita::ORDEN_ESTADOS;
                @endphp

                <select id="estado" name="estado"
                    class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-500
                        {{ $errors->has('estado') ? 'border-red-500' : 'border-gray-300' }}">
                    @foreach ($estados as $valor => $texto)
                        @if ($orden[$valor] >= $orden[$citas->estado])
                            <option value="{{ $valor }}" @selected(old('estado', $citas->estado) === $valor)>{{ $texto }}</option>
                        @endif
                    @endforeach
                </select>
                @error('estado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @php
                $seleccionados = old('servicios', $citas->detalleServicios->pluck('servicio_id')->toArray());
            @endphp

            <div class="mb-4 md:col-span-2">
                <p class="block mb-1 font-semibold">Servicios</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded px-3 py-2">
                    @foreach($servicios as $servicio)
                        <div class="flex items-center">
                            <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                id="servicio_{{ $servicio->id }}" class="mr-2"
                                @checked(in_array($servicio->id, $seleccionados))>
                            <label for="servicio_{{ $servicio->id }}">
                                {{ $servicio->nombreServicio }} - ${{ number_format($servicio->precioServicio, 0, ',', '.') }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <a href="{{ route('citas.index') }}" class="bg-blue-600 hover:bg-blue-600 text-white rounded px-5 py-2" >Volver</a>

                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                    Guardar
                </button>
            </div>

        </form>

    </div>

</div>

@endsection