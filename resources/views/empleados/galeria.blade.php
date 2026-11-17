@extends('layouts.app')

@section('title')
    Nuestro equipo
@endsection

@section('content')

<link rel="stylesheet" href="{{ asset('css/galeria-empleados.css') }}">

<div class="gal-pagina container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

    {{-- Encabezado con métricas rápidas --}}
    <div class="gal-encabezado">
        <div>
            <h2 class="gal-titulo">Nuestro equipo</h2>
            <p class="gal-subtitulo">
                Conoce a nuestros maestros profesionales y los servicios que ofrece cada uno.
            </p>
        </div>

        @if (!$empleados->isEmpty())
            <div class="gal-resumen">
                <span class="gal-pildora">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>{{ $empleados->count() }} Profesionales</span>
                </span>

                <span class="gal-pildora">
                    <span class="gal-punto animate-pulse"></span>
                    <span>{{ $empleados->where('disponibilidad', 'disponible')->count() }} Activos</span>
                </span>
            </div>
        @endif
    </div>

    @if ($empleados->isEmpty())
        {{-- Estado vacío --}}
        <div class="gal-vacio">
            <h3>No hay profesionales registrados</h3>
            <p>Por ahora no hay miembros del equipo para mostrar.</p>
        </div>
    @else
        <div class="gal-grid">

            @foreach ($empleados as $empleado)
                @php
                    $disponible = strtolower($empleado->disponibilidad) === 'disponible';
                @endphp

                <article class="gal-tarjeta {{ $disponible ? '' : 'gal-tarjeta--apagada' }}">

                    {{-- Disponibilidad --}}
                    <div class="gal-fila-estado">
                        @if ($disponible)
                            <span class="gal-estado gal-estado--ok">Disponible</span>
                        @else
                            <span class="gal-estado gal-estado--no">No disponible</span>
                        @endif
                    </div>

                    {{-- Cabecera: avatar, nombre y especialidad --}}
                    <header class="gal-cab">
                        <div class="gal-avatar" aria-hidden="true">
                            {{ strtoupper(substr($empleado->nombreEmpleado, 0, 1) . substr($empleado->apellidoEmpleado ?? '', 0, 1)) }}
                        </div>

                        <div class="gal-id">
                            <h3 class="gal-nombre">
                                {{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }}
                            </h3>
                            <p class="gal-especialidad">{{ $empleado->especialidad ?: 'Especialista de Salón' }}</p>
                        </div>
                    </header>

                    {{-- Servicios --}}
                    <section class="gal-servicios">
                        <h4 class="gal-bloque-titulo">
                            Servicios
                            <span class="gal-contador">{{ $empleado->servicios->count() }}</span>
                        </h4>

                        @if ($empleado->servicios->isEmpty())
                            <p class="gal-sin">Sin servicios asignados por el momento.</p>
                        @else
                            <div class="gal-chips">
                                @foreach ($empleado->servicios as $servicio)
                                    <span class="gal-chip">{{ $servicio->nombreServicio }}</span>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    {{-- Botón de agendar --}}
                    <footer class="gal-pie">
                        @if ($disponible)
                            <a href="{{ route('citas.create', ['empleado_id' => $empleado->id]) }}" class="gal-boton">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Agendar cita
                            </a>
                        @else
                            <span class="gal-boton gal-boton--off" aria-disabled="true">No disponible por ahora</span>
                        @endif
                    </footer>

                </article>
            @endforeach

        </div>
    @endif

</div>

@endsection