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
                <input type="text" id="fecha_programada" name="fecha_programada"
                    value="{{ old('fecha_programada', $citas->fecha_programada?->format('Y-m-d H:i')) }}"
                    class="selector-fecha-hora w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block mb-1 font-semibold">Fecha de Inicio</label>
                <input type="text" id="fecha_inicio" name="fecha_inicio"
                    value="{{ old('fecha_inicio', $citas->fecha_inicio?->format('Y-m-d H:i')) }}"
                    class="selector-fecha-hora w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="fecha_fin" class="block mb-1 font-semibold">Fecha fin</label>
                <input type="text" id="fecha_fin" name="fecha_fin"
                    value="{{ old('fecha_fin', $citas->fecha_fin?->format('Y-m-d H:i')) }}"
                    class="selector-fecha-hora w-full border rounded px-3 py-2">
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

                        @php
                $catalogoServicios = $servicios->map(fn ($s) => [
                    'id' => $s->id,
                    'nombre' => $s->nombreServicio,
                    'precio' => $s->precioServicio,
                ])->values();
            @endphp

            <script>
                function selectorServicios(servicios, seleccionados) {
                    return {
                        abierto: false,
                        servicios: servicios,
                        seleccionados: seleccionados.map(String),
                        get elegidos() {
                            return this.servicios.filter(s => this.seleccionados.includes(String(s.id)));
                        },
                        get total() {
                            return this.elegidos.reduce((suma, s) => suma + Number(s.precio), 0);
                        },
                        formato(valor) {
                            return '$' + Number(valor).toLocaleString('es-CO', { maximumFractionDigits: 0 });
                        },
                        quitar(id) {
                            this.seleccionados = this.seleccionados.filter(x => x !== String(id));
                        }
                    };
                }
            </script>

            <div class="mb-4" x-data="selectorServicios(@js($catalogoServicios), @js($seleccionados))">
                <p class="block mb-1 font-semibold">Servicios</p>

                <div class="relative">
                    <button type="button" @click="abierto = !abierto"
                            class="w-full border rounded px-3 py-2 text-left flex justify-between items-center bg-white">
                        <span x-text="elegidos.length ? elegidos.length + ' servicio(s) seleccionado(s)' : 'Seleccionar servicios'"></span>
                        <span aria-hidden="true">▾</span>
                    </button>

                    <div x-show="abierto" @click.outside="abierto = false" style="display: none;"
                         class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-60 overflow-y-auto">
                        @foreach($servicios as $servicio)
                            <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                       x-model="seleccionados" class="mr-2">
                                {{ $servicio->nombreServicio }} - ${{ number_format($servicio->precioServicio, 0, ',', '.') }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="s in elegidos" :key="s.id">
                        <span class="inline-flex items-center bg-blue-100 text-blue-800 rounded px-2 py-1 text-sm">
                            <span x-text="s.nombre + ' - ' + formato(s.precio)"></span>
                            <button type="button" @click="quitar(s.id)" class="ml-2 font-bold" aria-label="Quitar servicio">×</button>
                        </span>
                    </template>
                </div>

                <p class="mt-3 font-semibold">
                    Total a pagar: <span x-text="formato(total)"></span>
                </p>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/es.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.selector-fecha-hora', {
            locale: 'es',
            enableTime: true,
            time_24hr: false,
            minuteIncrement: 1,
            dateFormat: 'Y-m-d H:i',
            altInput: true,
            altFormat: 'd/m/Y h:i K',
            allowInput: true
        });
    });
</script>

@endsection