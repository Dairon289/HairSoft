@extends('layouts.app')

@section('title')
    Empleados
@endsection

@section('content')

@php
    $hora = fn ($valor) => $valor ? \Carbon\Carbon::parse($valor)->format('h:i A') : '—';
    $fecha = fn ($valor) => $valor ? \Carbon\Carbon::parse($valor)->format('d/m/Y') : '—';

    // Soporte seguro para $empleado o $empleados
    $coleccion = $empleado ?? ($empleados ?? collect());

    $catalogoEmpleados = $coleccion->map(fn ($e) => [
        'id' => $e->id,
        'nombre' => trim($e->nombreEmpleado . ' ' . $e->apellidoEmpleado),
        'disponible' => strtolower((string) $e->disponibilidad) === 'disponible',
    ])->values();
@endphp

<link rel="stylesheet" href="{{ asset('css/empleados-tarjetas.css') }}">

<div class="emp-pagina container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8"
     x-data="filtroEmpleados(@js($catalogoEmpleados))">

    {{-- Encabezado de página --}}
    <div class="emp-cabecera mb-6">
        <div>
            <h2 class="emp-titulo text-2xl sm:text-3xl font-bold tracking-tight">
                Empleados
            </h2>
            <p class="emp-subtitulo mt-1">
                Administra el equipo de trabajo, sus horarios y disponibilidad.
            </p>
        </div>

        @can('crear empleados')
            <a href="{{ route('empleados.create') }}" class="emp-btn emp-btn-pri shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Nuevo empleado(a)</span>
            </a>
        @endcan
    </div>

    {{-- Alertas del sistema --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6 text-sm font-medium border"
             style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); border-color: rgba(74, 160, 110, 0.35); color: var(--hs-ok-texto, #9BE0BA);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('successedit'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6 text-sm font-medium border"
             style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); border-color: rgba(74, 160, 110, 0.35); color: var(--hs-ok-texto, #9BE0BA);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('successedit') }}</span>
        </div>
    @endif

    @if(session('successdelete'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6 text-sm font-medium border"
             style="background: var(--hs-err-fondo, rgba(229, 103, 95, 0.14)); border-color: rgba(229, 103, 95, 0.35); color: var(--hs-err-texto, #F4B6B1);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            <span>{{ session('successdelete') }}</span>
        </div>
    @endif

    @if ($coleccion->count() > 0)

        {{-- Herramientas: Buscador y Filtros --}}
        <div class="emp-herramientas mb-3">
            <div class="emp-buscador">
                <label for="buscar-empleado" class="sr-only">Buscar empleado por nombre</label>
                <div class="relative">
                    <input id="buscar-empleado" type="search" x-model="buscar"
                           placeholder="Buscar por nombre..." class="emp-input"
                           style="padding-left: 2.5rem;">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                         style="color: var(--emp-tenue, #9BA1A8);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <div class="emp-filtros" role="group" aria-label="Filtrar por disponibilidad">
                <button type="button" class="emp-filtro"
                        :class="{ 'is-activo': filtro === 'todos' }"
                        :aria-pressed="(filtro === 'todos').toString()"
                        @click="filtro = 'todos'">
                    Todos <span class="emp-filtro-n" x-text="total"></span>
                </button>

                <button type="button" class="emp-filtro"
                        :class="{ 'is-activo': filtro === 'disponibles' }"
                        :aria-pressed="(filtro === 'disponibles').toString()"
                        @click="filtro = 'disponibles'">
                    Disponibles <span class="emp-filtro-n" x-text="disponibles"></span>
                </button>

                <button type="button" class="emp-filtro"
                        :class="{ 'is-activo': filtro === 'no' }"
                        :aria-pressed="(filtro === 'no').toString()"
                        @click="filtro = 'no'">
                    No disponibles <span class="emp-filtro-n" x-text="noDisponibles"></span>
                </button>
            </div>
        </div>

        <p class="emp-contador mb-6" aria-live="polite">
            Mostrando <span class="font-bold" style="color: var(--emp-texto, #ECE7DE);" x-text="visibles.length"></span> de <span x-text="total"></span> profesionales
        </p>

        {{-- Grilla de empleados --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-7">

            @foreach ($coleccion as $e)

                @php
                    $disponible = strtolower((string) $e->disponibilidad) === 'disponible';
                    $activo = strtolower((string) $e->estadoEmpleado) === 'activo';
                    $iniciales = mb_strtoupper(mb_substr($e->nombreEmpleado, 0, 1) . mb_substr($e->apellidoEmpleado ?? '', 0, 1));
                @endphp

                <article class="emp-tarjeta flex flex-col justify-between shadow-lg shadow-black/40 {{ $disponible ? '' : 'emp-tarjeta--apagada' }}"
                         style="border-top: 3px solid var(--emp-acento, #C9A24D);"
                         x-show="muestra({{ $e->id }})">

                    <div>
                        {{-- 1. CABECERA: Avatar, Nombre optimizado y Badge Luminoso --}}
                        <div class="pb-3 border-b" style="border-color: var(--emp-linea, #363C44);">
                            <div class="flex items-center justify-between gap-2.5">
                                
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    {{-- Avatar compacto --}}
                                    <div class="emp-avatar flex-shrink-0" 
                                         style="width: 2.5rem; height: 2.5rem; font-size: 0.85rem;" 
                                         aria-hidden="true">
                                        {{ $iniciales }}
                                    </div>

                                    {{-- Nombre con tamaño optimizado y sin partir palabras a la mitad --}}
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-bold text-[13px] sm:text-sm leading-tight" 
                                            style="color: var(--emp-texto, #ECE7DE); overflow-wrap: normal; word-break: normal;"
                                            title="{{ $e->nombreEmpleado }} {{ $e->apellidoEmpleado }}">
                                            {{ $e->nombreEmpleado }} {{ $e->apellidoEmpleado }}
                                        </h3>
                                        <span class="text-[11px] font-mono block mt-0.5" style="color: var(--emp-tenue, #9BA1A8);">
                                            #EMP-{{ str_pad($e->id, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Badge de Disponibilidad --}}
                                @if ($disponible)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wide flex-shrink-0"
                                          style="background: rgba(34, 197, 94, 0.22); color: #4ade80; border: 1.5px solid #22c55e;">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background: #22c55e; box-shadow: 0 0 5px #22c55e;"></span>
                                        Disponible
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold flex-shrink-0"
                                          style="background: rgba(229, 103, 95, 0.16); color: #F4B6B1; border: 1px solid rgba(229, 103, 95, 0.4);">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background: #E5675F;"></span>
                                        No disponible
                                    </span>
                                @endif

                            </div>
                        </div>

                        {{-- 2. SERVICIOS QUE OFRECE: Delimitado con su propio separador --}}
                        <div class="py-3.5 border-b" style="border-color: var(--emp-linea, #363C44);">
                            <div class="flex items-center justify-between mb-2">
                                <p class="emp-bloque-titulo uppercase tracking-wider text-xs font-semibold">
                                    Servicios asignados
                                </p>
                                <span class="text-[11px] font-mono px-2 py-0.5 rounded"
                                      style="background: var(--emp-campo, #14171A); color: var(--emp-tenue, #9BA1A8); border: 1px solid var(--emp-linea, #363C44);">
                                    {{ $e->servicios->count() }}
                                </span>
                            </div>

                            <div class="emp-chips flex flex-wrap gap-1.5">
                                @forelse ($e->servicios as $servicio)
                                    <span class="emp-chip inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--emp-acento, #C9A24D);"></span>
                                        {{ $servicio->nombreServicio }}
                                    </span>
                                @empty
                                    <span class="emp-sin text-xs" style="color: var(--emp-tenue, #9BA1A8);">
                                        Sin servicios asignados.
                                    </span>
                                @endforelse
                            </div>
                        </div>

                        {{-- 3. FICHA DE DATOS TÉCNICOS: Con iconos para escaneo ágil --}}
                        <div class="pt-3.5 pb-2">
                            <dl class="emp-datos grid gap-2.5 text-xs">
                                {{-- Correo --}}
                                <div class="flex items-center justify-between gap-2">
                                    <dt class="flex items-center gap-1.5" style="color: var(--emp-tenue, #9BA1A8);">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Correo</span>
                                    </dt>
                                    <dd class="font-medium truncate max-w-[190px]" style="color: var(--emp-texto, #ECE7DE);" title="{{ $e->correoEmpleado }}">
                                        {{ $e->correoEmpleado }}
                                    </dd>
                                </div>

                                {{-- Teléfono --}}
                                <div class="flex items-center justify-between gap-2">
                                    <dt class="flex items-center gap-1.5" style="color: var(--emp-tenue, #9BA1A8);">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span>Teléfono</span>
                                    </dt>
                                    <dd class="font-medium font-mono" style="color: var(--emp-texto, #ECE7DE);">
                                        {{ $e->telefonoEmpleado }}
                                    </dd>
                                </div>

                                {{-- Horario --}}
                                <div class="flex items-center justify-between gap-2">
                                    <dt class="flex items-center gap-1.5" style="color: var(--emp-tenue, #9BA1A8);">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Horario</span>
                                    </dt>
                                    <dd class="font-medium" style="color: var(--emp-texto, #ECE7DE);">
                                        {{ $hora($e->horaIngreso) }} – {{ $hora($e->horaSalida) }}
                                    </dd>
                                </div>

                                {{-- Ingreso --}}
                                <div class="flex items-center justify-between gap-2">
                                    <dt class="flex items-center gap-1.5" style="color: var(--emp-tenue, #9BA1A8);">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2-0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Ingreso</span>
                                    </dt>
                                    <dd class="font-medium font-mono" style="color: var(--emp-tenue, #9BA1A8);">
                                        {{ $fecha($e->fechaIngreso) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- 4. PIE DE ACCIONES: Estado de contrato + Botones Editar/Eliminar --}}
                    <div class="emp-pie pt-3.5 mt-auto border-t" style="border-color: var(--emp-linea, #363C44);">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $activo ? 'text-emerald-400' : 'text-gray-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $activo ? 'bg-emerald-400' : 'bg-gray-500' }}"></span>
                                {{ $activo ? 'Activo' : ucfirst((string) $e->estadoEmpleado) }}
                            </span>
                        </div>

                        @canany(['editar empleados', 'eliminar empleados'])
                            <div class="emp-acciones">
                                @can('editar empleados')
                                    <a href="{{ route('empleados.edit', $e->id) }}"
                                       title="Editar empleado"
                                       aria-label="Editar empleado"
                                       class="emp-icono">
                                        <span aria-hidden="true" class="flex items-center justify-center">
                                            @include('layouts.partials.icons', ['icon' => 'pencil'])
                                        </span>
                                    </a>
                                @endcan

                                @can('eliminar empleados')
                                    <form action="{{ route('empleados.destroy', $e->id) }}" method="post"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este empleado?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Eliminar empleado"
                                                aria-label="Eliminar empleado"
                                                class="emp-icono emp-icono--peligro">
                                            <span aria-hidden="true" class="flex items-center justify-center">
                                                @include('layouts.partials.icons', ['icon' => 'trash'])
                                            </span>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endcanany
                    </div>

                </article>

            @endforeach

        </div>

        {{-- Estado de búsqueda sin resultados --}}
        <div class="emp-vacio max-w-md mx-auto my-12" x-show="visibles.length === 0" x-cloak>
            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-xl"
                 style="background: var(--emp-campo, #14171A); border: 1px solid var(--emp-linea, #363C44); color: var(--emp-tenue, #9BA1A8);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="font-bold mb-1">Ningún empleado coincide</h3>
            <p class="text-sm">Prueba con otro nombre o cambia el filtro de disponibilidad.</p>
        </div>

    @else

        {{-- Estado vacío general --}}
        <div class="emp-vacio max-w-md mx-auto my-12">
            <div class="w-14 h-14 mx-auto mb-4 flex items-center justify-center rounded-2xl"
                 style="background: var(--emp-campo, #14171A); border: 1px solid var(--emp-linea, #363C44); color: var(--emp-tenue, #9BA1A8);">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="font-bold mb-1">Todavía no hay empleados registrados</h3>
            <p class="text-sm">Cuando agregues empleados, aparecerán aquí con su disponibilidad y sus servicios.</p>
        </div>

    @endif

</div>

<script>
    function filtroEmpleados(lista) {
        const limpiar = (texto) => texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

        return {
            lista: lista,
            buscar: '',
            filtro: 'todos',
            get total() { return this.lista.length; },
            get disponibles() { return this.lista.filter(e => e.disponible).length; },
            get noDisponibles() { return this.lista.filter(e => !e.disponible).length; },
            get visibles() {
                const texto = limpiar(this.buscar.trim());

                return this.lista
                    .filter(e => this.filtro === 'todos'
                        || (this.filtro === 'disponibles' ? e.disponible : !e.disponible))
                    .filter(e => texto === '' || limpiar(e.nombre).includes(texto))
                    .map(e => e.id);
            },
            muestra(id) { return this.visibles.includes(id); }
        };
    }
</script>

@endsection 