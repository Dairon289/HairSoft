@extends('layouts.app')

@section('title')
    Nueva Cita
@endsection

@section('content')

@php
    $catalogoServicios = $servicios->map(fn ($s) => [
        'id' => $s->id,
        'nombre' => $s->nombreServicio,
        'precio' => $s->precioServicio,
    ])->values();
@endphp

<x-card>

    <div class="container mx-auto mt-10">

        <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

            <h2 class="text-3xl font-bold text-center mb-6">
                Nueva cita
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

            <form action="{{ route('citas.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="cliente_id" class="block mb-2 font-semibold">Cliente</label>
                    <select id="cliente_id" name="cliente_id" required
                            class="w-full border rounded px-3 py-2 @error('cliente_id') border-red-500 @enderror">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>
                                {{ $cliente->nombre }} {{ $cliente->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label for="empleado_id" class="block mb-2 font-semibold">Empleado</label>
                    <select id="empleado_id" name="empleado_id" required
                            class="w-full border rounded px-3 py-2 @error('empleado_id') border-red-500 @enderror">
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" @selected(old('empleado_id') == $empleado->id)>
                                {{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label for="fecha_programada" class="block mb-2 font-semibold">Fecha programada</label>
                    <input type="text" id="fecha_programada" name="fecha_programada"
                           value="{{ old('fecha_programada') }}" required
                           class="selector-fecha-hora w-full border rounded px-3 py-2 @error('fecha_programada') border-red-500 @enderror">
                </div>

                <div class="mb-5">
                    <label for="estado" class="block mb-2 font-semibold">Estado de la cita</label>
                    <select id="estado" name="estado" required
                            class="w-full border rounded px-3 py-2 @error('estado') border-red-500 @enderror">
                        <option value="pendiente" @selected(old('estado', 'pendiente') === 'pendiente')>Pendiente</option>
                        <option value="confirmada" @selected(old('estado') === 'confirmada')>Confirmada</option>
                    </select>
                </div>

                <div class="mb-5" x-data="selectorServicios(@js($catalogoServicios), @js(old('servicios', [])))">
                    <label class="block mb-2 font-semibold">Servicios</label>

                    <div class="relative">
                        <button type="button" @click="abierto = !abierto"
                                class="w-full border rounded px-3 py-2 text-left flex justify-between items-center bg-white @error('servicios') border-red-500 @enderror">
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

                <div class="flex justify-between items-center">
                    <a href="{{ route('citas.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white rounded px-5 py-2">
                        Volver
                    </a>

                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                        Guardar Cita
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-card>

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