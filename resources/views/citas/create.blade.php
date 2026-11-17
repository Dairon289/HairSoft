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

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600&display=swap">
<link rel="stylesheet" href="{{ asset('css/formulario-oscuro.css') }}">

<div class="cita-wrap">
    <div class="cita-panel">

        <header class="cita-cabecera">
            <h2 class="cita-titulo">Nueva cita</h2>
            <p class="cita-subtitulo">Elige el empleado, el horario y los servicios.</p>
        </header>

        @if($errors->any())
            <div class="cita-errores" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('citas.store') }}" method="POST">
            @csrf

            <div class="cita-grupo">
                <div class="cita-fila">

                    @unless($esCliente)
                        <div class="cita-campo">
                            <label for="cliente_id" class="cita-label">Cliente</label>
                            <select id="cliente_id" name="cliente_id" required
                                    class="cita-input @error('cliente_id') is-invalid @enderror">
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>
                                        {{ $cliente->nombre }} {{ $cliente->apellido }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endunless

                    <div class="cita-campo">
                        <label for="empleado_id" class="cita-label">Empleado</label>
                        <select id="empleado_id" name="empleado_id" required
                                class="cita-input @error('empleado_id') is-invalid @enderror">
                            @foreach($empleados as $empleado)
                                @php $empleadoDisponible = strtolower($empleado->disponibilidad) === 'disponible'; @endphp
                                <option value="{{ $empleado->id }}"
                                            @selected(old('empleado_id', request('empleado_id')) == $empleado->id)
                                        @disabled(! $empleadoDisponible)>
                                    {{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }}
                                    ({{ $empleadoDisponible ? 'disponible' : 'no disponible' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="cita-grupo">
                <div class="cita-fila">

                    <div class="cita-campo">
                        <label for="fecha_programada" class="cita-label">Fecha programada</label>
                        <input type="text" id="fecha_programada" name="fecha_programada"
                               value="{{ old('fecha_programada') }}" required
                               class="selector-fecha-hora cita-input @error('fecha_programada') is-invalid @enderror">
                    </div>

                    @unless($esCliente)
                        <div class="cita-campo">
                            <label for="estado" class="cita-label">Estado de la cita</label>
                            <select id="estado" name="estado" required
                                    class="cita-input @error('estado') is-invalid @enderror">
                                <option value="pendiente" @selected(old('estado', 'pendiente') === 'pendiente')>Pendiente</option>
                                <option value="confirmada" @selected(old('estado') === 'confirmada')>Confirmada</option>
                            </select>
                        </div>
                    @endunless

                </div>
            </div>

            <div class="cita-grupo">
                <div class="cita-campo" x-data="selectorServicios(@js($catalogoServicios), @js(old('servicios', [])))">
                    <span class="cita-label">Servicios</span>

                    <div class="cita-selector">
                        <button type="button" @click="abierto = !abierto" :aria-expanded="abierto"
                                class="cita-input cita-selector-btn @error('servicios') is-invalid @enderror">
                            <span x-text="elegidos.length ? elegidos.length + ' servicio(s) seleccionado(s)' : 'Seleccionar servicios'"></span>
                            <span aria-hidden="true">▾</span>
                        </button>

                        <div x-show="abierto" @click.outside="abierto = false" style="display: none;" class="cita-opciones">
                            @foreach($servicios as $servicio)
                                <label class="cita-opcion">
                                    <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                           x-model="seleccionados">
                                    {{ $servicio->nombreServicio }} - ${{ number_format($servicio->precioServicio, 0, ',', '.') }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="cita-chips">
                        <template x-for="s in elegidos" :key="s.id">
                            <span class="cita-chip">
                                <span x-text="s.nombre + ' - ' + formato(s.precio)"></span>
                                <button type="button" @click="quitar(s.id)" aria-label="Quitar servicio">×</button>
                            </span>
                        </template>
                    </div>

                    <div class="cita-total">
                        <span class="cita-total-label">Total a pagar</span>
                        <span class="cita-total-monto" x-text="formato(total)"></span>
                    </div>
                </div>
            </div>

            <div class="cita-acciones">
                <a href="{{ route('citas.index') }}" class="cita-btn cita-btn-sec">Volver</a>
                <button type="submit" class="cita-btn cita-btn-pri">Guardar cita</button>
            </div>

        </form>

    </div>
</div>

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/themes/dark.css">
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