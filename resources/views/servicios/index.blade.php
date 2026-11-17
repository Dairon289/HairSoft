@extends('layouts.app')

@section('title')
    Servicios
@endsection

@section('content')

<div class="emp-pagina container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

    {{-- Encabezado de página con botón dorado alineado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color: #ECE7DE;">
                Nuestros servicios
            </h2>
            <p class="text-sm sm:text-base mt-1" style="color: #9BA1A8;">
                Administra los servicios disponibles en HairSoft.
            </p>
        </div>

        @can('crear servicios')
        <a href="{{ route('servicio.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition hover:brightness-105 flex-shrink-0"
           style="background: #ECE7DE; color: #14171A;">
            <span class="text-lg leading-none font-bold">+</span>
            <span>Nuevo servicio</span>
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

    @if(session('successdelete') || session('errordelete'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6 text-sm font-medium border"
             style="background: var(--hs-err-fondo, rgba(229, 103, 95, 0.14)); border-color: rgba(229, 103, 95, 0.35); color: var(--hs-err-texto, #F4B6B1);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('successdelete') ?? session('errordelete') }}</span>
        </div>
    @endif

    {{-- Cuadrícula de servicios --}}
    @if($servicios->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-7">

            @foreach($servicios as $servicio)
                <div class="flex flex-col justify-between rounded-2xl p-5 shadow-lg shadow-black/40 border {{ strtolower($servicio->estadoServicio) == 'activo' ? '' : 'opacity-70' }}"
                     style="background: #1F2328; border-color: #363C44;">

                    <div>
                        {{-- 1. CABECERA: Icono, Nombre, ID y Estado --}}
                        <div class="flex items-start justify-between gap-3 pb-3.5 border-b"
                             style="border-color: #363C44;">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-xl"
                                     style="background: #14171A; border: 1px solid #363C44; color: #ECE7DE  ;">
                                    <span class="w-5 h-5 flex items-center justify-center [&>svg]:w-5 [&>svg]:h-5">
                                        @include('layouts.partials.icons', ['icon' => 'scissors'])
                                    </span>
                                </div>

                                <div class="min-w-0">
                                    <span class="text-xs font-mono font-semibold" style="color: #ECE7DE;">
                                        #{{ str_pad($servicio->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <h3 class="text-base font-bold leading-snug" style="color: #ECE7DE;">
                                        {{ $servicio->nombreServicio }}
                                    </h3>
                                </div>
                            </div>

                            @if(strtolower($servicio->estadoServicio) == 'activo')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide flex-shrink-0"
                                      style="background: rgba(34, 197, 94, 0.22); color: #4ade80; border: 1.5px solid #22c55e;">
                                    <span class="w-2 h-2 rounded-full" style="background: #22c55e; box-shadow: 0 0 6px #22c55e;"></span>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold flex-shrink-0"
                                      style="background: rgba(155, 161, 168, 0.12); color: #9BA1A8; border: 1px solid #363C44;">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: #9BA1A8;"></span>
                                    No disponible
                                </span>
                            @endif
                        </div>

                        {{-- 2. DESCRIPCIÓN --}}
                        <div class="py-3.5">
                            <span class="block text-xs font-medium uppercase tracking-wider mb-1"
                                  style="color: #9BA1A8;">
                                Descripción
                            </span>
                            <p class="text-sm leading-relaxed line-clamp-2 min-h-[2.5rem]"
                               style="color: #ECE7DE;">
                                {{ $servicio->descripcionServicio ?: 'Sin descripción disponible.' }}
                            </p>
                        </div>

                        {{-- 3. DATOS OPERATIVOS: Duración y Precio --}}
                        <div class="pt-3.5 border-t" style="border-color: #363C44;">
                            <div class="grid grid-cols-2 gap-3 mb-2">
                                {{-- Duración --}}
                                <div class="rounded-xl p-3 border"
                                     style="background: #14171A; border-color: #363C44;">
                                    <span class="block text-xs font-medium uppercase tracking-wider mb-1"
                                          style="color: #9BA1A8;">
                                        Duración
                                    </span>
                                    <p class="text-sm font-semibold truncate" style="color: #ECE7DE;">
                                        {{ $servicio->duracionServicio }}
                                    </p>
                                </div>

                                {{-- Precio --}}
                                <div class="rounded-xl p-3 border"
                                     style="background: #14171A; border-color: rgba(201, 162, 77, 0.35);">
                                    <span class="block text-xs font-semibold uppercase tracking-wider mb-1"
                                          style="color: #ECE7DE;">
                                        Precio
                                    </span>
                                    <p class="text-base sm:text-lg font-bold truncate"
                                       style="color: #ECE7DE;">
                                        ${{ number_format((float) $servicio->precioServicio, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. ACCIONES (Lápiz y Basura con tamaño controlado de 16px) --}}
                    @canany(['editar servicios', 'eliminar servicios'])
                    <div class="flex items-center justify-between pt-3.5 mt-auto border-t"
                         style="border-color: #363C44;">
                        <span class="text-xs font-medium" style="color: #9BA1A8;">Acciones</span>

                        <div class="flex items-center gap-2">
                            @can('editar servicios')
                            <a href="{{ route('servicio.edit', $servicio->id) }}"
                               title="Editar servicio"
                               aria-label="Editar servicio"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg border transition hover:border-[#ECE7DE] hover:bg-white/5"
                               style="border-color: #363C44; color: #ECE7DE;">
                                <span class="w-4 h-4 flex items-center justify-center [&>svg]:w-4 [&>svg]:h-4 [&>svg]:max-w-[16px] [&>svg]:max-h-[16px]">
                                    @include('layouts.partials.icons', ['icon' => 'pencil'])
                                </span>
                            </a>
                            @endcan

                            @can('eliminar servicios')
                            <form action="{{ route('servicio.destroy', $servicio->id) }}"
                                method="POST"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    title="Eliminar servicio"
                                    aria-label="Eliminar servicio"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg border transition hover:border-red-500 hover:bg-red-500/10"
                                    style="border-color: #363C44; color: #F4B6B1;">
                                    <span class="w-4 h-4 flex items-center justify-center [&>svg]:w-4 [&>svg]:h-4 [&>svg]:max-w-[16px] [&>svg]:max-h-[16px]">
                                        @include('layouts.partials.icons', ['icon' => 'trash'])
                                    </span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    @endcanany

                </div>
            @endforeach

        </div>
    @else
        {{-- Estado vacío --}}
        <div class="max-w-md mx-auto my-12 p-8 text-center rounded-2xl border border-dashed"
             style="background: #1F2328; border-color: #363C44; color: #9BA1A8;">
            <div class="w-14 h-14 mx-auto mb-4 flex items-center justify-center rounded-2xl"
                 style="background: #14171A; border: 1px solid #363C44; color: #9BA1A8;">
                <span class="w-7 h-7 flex items-center justify-center [&>svg]:w-7 [&>svg]:h-7">
                    @include('layouts.partials.icons', ['icon' => 'scissors'])
                </span>
            </div>

            <h3 class="font-bold text-lg mb-1" style="color: #ECE7DE;">
                Todavía no hay servicios registrados
            </h3>
            <p class="text-sm mb-5">
                Comienza agregando los servicios que ofrece tu peluquería.
            </p>

            @can('crear servicios')
            <a href="{{ route('servicio.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition hover:brightness-105"
               style="background: #ECE7DE; color: #14171A;">
                <span class="text-lg leading-none font-bold">+</span>
                <span>Agregar primer servicio</span>
            </a>
            @endcan
        </div>
    @endif

</div>

@endsection