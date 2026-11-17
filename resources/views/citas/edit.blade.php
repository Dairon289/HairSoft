@extends('layouts.app')

@section('title')
    Editar Cita
@endsection

@section('content')

    @php
        $estadoActual = $citas->estado;

        $estados = [
            'pendiente'  => 'Pendiente',
            'confirmada' => 'Confirmada',
            'iniciada'   => 'Iniciada',
            'finalizada' => 'Finalizada',
        ];

        $datosEditables = $citas->datosEditables();
        $estadosPermitidos = $citas->estadosPermitidos();

        $mostrarInicio = in_array($estadoActual, ['confirmada', 'iniciada'], true);
        $inicioEditable = $estadoActual === 'confirmada';

        $mostrarFin = $citas->fecha_inicio !== null;

        $estado = [
            'pendiente'  => 'Al confirmar la cita se habilita la fecha de inicio.',
            'confirmada' => 'Para iniciar la cita debes registrar la fecha de inicio.',
            'iniciada'   => 'Para finalizar la cita debes registrar la fecha fin.',
        ];

        $seleccionados = old('servicios', $citas->detalleServicios->pluck('servicio_id')->toArray());

        $catalogoServicios = $servicios->map(fn ($s) => [
            'id' => $s->id,
            'nombre' => $s->nombreServicio,
            'precio' => $s->precioServicio,
        ])->values();
    @endphp

    <div class="max-w-4xl mx-auto">

        <div class="bg-white shadow-lg rounded-xl p-4 sm:p-5">

            <h2 class="text-xl font-bold text-center mb-3">
                Editar Cita #{{ $citas->id }}
            </h2>

            <form action="{{ route('citas.update', $citas->id) }}" method="post">
                @csrf
                @method('PUT')

                {{-- Cliente y empleado --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 mb-2">

                    <div>
                        <label for="cliente_id" class="block text-xs font-semibold mb-1">Cliente</label>

                        @if ($datosEditables)
                            <select id="cliente_id" name="cliente_id" class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('cliente_id') border-red-500 @enderror">
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        @selected(old('cliente_id', $citas->cliente_id) == $cliente->id)>
                                        {{ $cliente->nombre }} {{ $cliente->apellido }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <p class="w-full border rounded-lg px-2.5 py-1.5 text-sm bg-gray-100 text-gray-700 break-words">
                                {{ $citas->cliente->nombre }} {{ $citas->cliente->apellido }}
                            </p>
                        @endif
                        @error('cliente_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="empleado_id" class="block text-xs font-semibold mb-1">Empleado</label>

                        @if ($datosEditables)
                            <select id="empleado_id" name="empleado_id" class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('empleado_id') border-red-500 @enderror">
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}"
                                        @selected(old('empleado_id', $citas->empleado_id) == $empleado->id)>
                                        {{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <p class="w-full border rounded-lg px-2.5 py-1.5 text-sm bg-gray-100 text-gray-700 break-words">
                                {{ $citas->empleado->nombreEmpleado }} {{ $citas->empleado->apellidoEmpleado }}
                            </p>
                        @endif
                        @error('empleado_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Fecha programada --}}
                <div class="mb-2">
                    <label for="fecha_programada" class="block text-xs font-semibold mb-1">Fecha programada</label>

                    @if ($datosEditables)
                        <input type="text" id="fecha_programada" name="fecha_programada"
                            value="{{ old('fecha_programada', $citas->fecha_programada?->format('Y-m-d H:i')) }}"
                            class="selector-fecha-hora w-full border rounded-lg px-2.5 py-1.5 text-sm @error('fecha_programada') border-red-500 @enderror">
                    @else
                        <p class="w-full border rounded-lg px-2.5 py-1.5 text-sm bg-gray-100 text-gray-700">
                            {{ $citas->fecha_programada?->format('d/m/Y h:i A') }}
                        </p>
                    @endif
                    @error('fecha_programada')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de inicio y fecha fin (según el estado de la cita) --}}
                @if ($mostrarInicio || $mostrarFin)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        @if ($mostrarInicio)
                            <div>
                                <label for="fecha_inicio" class="block mb-1 font-semibold">Fecha de inicio</label>

                                @if ($inicioEditable)
                                    <input type="text" id="fecha_inicio" name="fecha_inicio"
                                        value="{{ old('fecha_inicio', $citas->fecha_inicio?->format('Y-m-d H:i')) }}"
                                        class="selector-fecha-hora w-full border rounded px-3 py-2 @error('fecha_inicio') border-red-500 @enderror">
                                @else
                                    <p class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-700">
                                        {{ $citas->fecha_inicio?->format('d/m/Y h:i A') }}
                                    </p>
                                @endif
                                @error('fecha_inicio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @if ($mostrarFin)
                            <div>
                                <label for="fecha_fin" class="block mb-1 font-semibold">Fecha fin</label>
                                <input type="text" id="fecha_fin" name="fecha_fin"
                                    value="{{ old('fecha_fin', $citas->fecha_fin?->format('Y-m-d H:i')) }}"
                                    class="selector-fecha-hora w-full border rounded px-3 py-2 @error('fecha_fin') border-red-500 @enderror">
                                @error('fecha_fin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Estado --}}
                <div class="mb-4">
                    <label for="estado" class="block mb-1 font-semibold">Estado de la cita</label>

                    <select id="estado" name="estado"
                        class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-500
                            {{ $errors->has('estado') ? 'border-red-500' : 'border-gray-300' }}">
                        @foreach ($estadosPermitidos as $valor)
                            <option value="{{ $valor }}" @selected(old('estado', $estadoActual) === $valor)>
                                {{ $estados[$valor] }}
                            </option>
                        @endforeach
                    </select>

                    @isset($estado[$estadoActual])
                        <p class="mt-1 text-sm text-gray-500">{{ $estado[$estadoActual] }}</p>
                    @endisset

                    @error('estado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Servicios --}}
                @if ($datosEditables)
                    <div class="mb-4"
                         x-data="selectorServicios(@js($catalogoServicios), @js($seleccionados))">

                        <p class="block mb-1 font-semibold">Servicios</p>

                        <div class="relative">
                            <button type="button" @click="abierto = !abierto"
                                    class="w-full border rounded px-3 py-2 text-left flex justify-between items-center gap-2 bg-white @error('servicios') border-red-500 @enderror">
                                <span class="min-w-0 break-words"
                                      x-text="elegidos.length ? elegidos.length + ' servicio(s) seleccionado(s)' : 'Seleccionar servicios'"></span>
                                <span aria-hidden="true">&#9662;</span>
                            </button>

                            <div x-show="abierto" @click.outside="abierto = false" style="display: none;"
                                 class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-60 overflow-y-auto">
                                @foreach($servicios as $servicio)
                                    <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                               x-model="seleccionados" class="mr-2 shrink-0">
                                        <span class="min-w-0 break-words">
                                            {{ $servicio->nombreServicio }} - ${{ number_format($servicio->precioServicio, 0, ',', '.') }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('servicios')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="flex flex-wrap gap-2 mt-2">
                            <template x-for="s in elegidos" :key="s.id">
                                <span class="inline-flex items-center bg-blue-100 text-blue-800 rounded px-2 py-1 text-sm">
                                    <span class="break-words" x-text="s.nombre + ' - ' + formato(s.precio)"></span>
                                    <button type="button" @click="quitar(s.id)" class="ml-2 font-bold hover:text-red-600" aria-label="Quitar servicio">&times;</button>
                                </span>
                            </template>
                        </div>

                        <p class="mt-3 font-semibold text-gray-700">
                            Total a pagar estimado: <span class="text-green-600" x-text="formato(total)"></span>
                        </p>
                    </div>
                @else
                    <div class="mb-4">
                        <p class="block mb-1 font-semibold">Servicios</p>

                        <div class="flex flex-wrap gap-2">
                            @foreach ($citas->detalleServicios as $detalle)
                                <span class="inline-flex items-center bg-gray-100 text-gray-700 rounded px-2 py-1 text-sm break-words">
                                    {{ $detalle->servicio->nombreServicio }} - ${{ number_format($detalle->precioServicio, 0, ',', '.') }}
                                </span>
                            @endforeach
                        </div>

                        <p class="mt-3 font-semibold text-gray-700">
                            Total a pagar: <span class="text-green-600">${{ number_format($citas->total, 0, ',', '.') }}</span>
                        </p>
                    </div>
                @endif

                {{-- Botones --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-6">
                    <a href="{{ route('citas.index') }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white rounded px-5 py-2 text-center">
                        Volver
                    </a>

                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                        Actualizar Cita
                    </button>
                </div>

            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/es.js"></script>

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
                    return this.elegidos.reduce((acc, s) => acc + Number(s.precio), 0);
                },
                formato(valor) {
                    return '$ ' + Number(valor).toLocaleString('es-CO');
                },
                quitar(id) {
                    this.seleccionados = this.seleccionados.filter(x => x !== String(id));
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const opciones = {
                locale: 'es',
                enableTime: true,
                time_24hr: false,
                minuteIncrement: 1,
                dateFormat: 'Y-m-d H:i',
                altInput: true,
                altFormat: 'd/m/Y h:i K',
                allowInput: true
            };

            // La fecha programada puede ser futura
            if (document.getElementById('fecha_programada')) {
                flatpickr('#fecha_programada', opciones);
            }

            // Inicio y fin no pueden pasar del momento actual
            if (document.getElementById('fecha_inicio')) {
                flatpickr('#fecha_inicio', { ...opciones, maxDate: new Date() });
            }
            if (document.getElementById('fecha_fin')) {
                flatpickr('#fecha_fin', { ...opciones, maxDate: new Date() });
            }
        });
    </script>

@endsection