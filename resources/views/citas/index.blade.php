@extends('layouts.app')

@section('title')
    Citas
@endsection

@section('content')

<div class="emp-pagina container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

    {{-- Encabezado de página --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight" style="color: #ECE7DE;">
                Registro de Citas
            </h2>
            <p class="text-sm sm:text-base mt-1" style="color: #9BA1A8;">
                Monitorea el flujo de citas, estados y atención en el salón.
            </p>
        </div>

        @can('crear citas')
        <a href="{{ route('citas.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition hover:brightness-105 flex-shrink-0"
           style="background: #C9A24D; color: #14171A;">
            <span class="text-lg leading-none font-bold">+</span>
            <span>Nueva Cita</span>
        </a>
        @endcan
    </div>

    {{-- Alertas con alto contraste en tema oscuro --}}
    @if(session('success') || session('mensaje'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6 text-sm font-medium border"
             style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); border-color: rgba(74, 160, 110, 0.35); color: var(--hs-ok-texto, #9BE0BA);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') ?? session('mensaje') }}</span>
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

    @if(session('successdelete') || $errors->any())
        <div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-6 text-sm font-medium border"
             style="background: var(--hs-err-fondo, rgba(229, 103, 95, 0.14)); border-color: rgba(229, 103, 95, 0.35); color: var(--hs-err-texto, #F4B6B1);">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                @if(session('successdelete'))
                    <span>{{ session('successdelete') }}</span>
                @endif
                @if($errors->any())
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif

    {{-- Panel de Filtros integrado en tema oscuro --}}
    <div class="p-4 sm:p-5 rounded-2xl mb-6 border shadow-sm"
         style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
        <form method="GET" action="{{ route('citas.index') }}" class="flex flex-wrap items-end gap-3 sm:gap-4">
            
            {{-- Desde --}}
            <div class="flex-1 min-w-[140px]">
                <label for="desde" class="block text-xs font-semibold uppercase tracking-wider mb-1.5"
                       style="color: var(--emp-tenue, #9BA1A8);">
                    Desde
                </label>
                <input type="date" name="desde" id="desde" value="{{ $desde }}"
                       class="w-full text-xs sm:text-sm rounded-xl px-3 py-2 border font-mono"
                       style="background: var(--emp-campo, #14171A); border-color: var(--emp-linea, #363C44); color: var(--emp-texto, #ECE7DE); color-scheme: dark;">
            </div>

            {{-- Hasta --}}
            <div class="flex-1 min-w-[140px]">
                <label for="hasta" class="block text-xs font-semibold uppercase tracking-wider mb-1.5"
                       style="color: var(--emp-tenue, #9BA1A8);">
                    Hasta
                </label>
                <input type="date" name="hasta" id="hasta" value="{{ $hasta }}"
                       class="w-full text-xs sm:text-sm rounded-xl px-3 py-2 border font-mono"
                       style="background: var(--emp-campo, #14171A); border-color: var(--emp-linea, #363C44); color: var(--emp-texto, #ECE7DE); color-scheme: dark;">
            </div>

            {{-- Servicio --}}
            <div class="flex-1 min-w-[170px]">
                <label for="servicio_id" class="block text-xs font-semibold uppercase tracking-wider mb-1.5"
                       style="color: var(--emp-tenue, #9BA1A8);">
                    Servicio
                </label>
                <select name="servicio_id" id="servicio_id"
                        class="w-full text-xs sm:text-sm rounded-xl px-3 py-2 border"
                        style="background: var(--emp-campo, #14171A); border-color: var(--emp-linea, #363C44); color: var(--emp-texto, #ECE7DE); color-scheme: dark;">
                    <option value="">Todos los servicios</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}"
                            {{ (string) $servicioId === (string) $servicio->id ? 'selected' : '' }}>
                            {{ $servicio->nombreServicio }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Checkbox pago pendiente --}}
            <div class="flex items-center gap-2 py-2 px-1">
                <input type="checkbox" name="pago_pendiente" id="pago_pendiente" value="1"
                       {{ $pagoPendiente ? 'checked' : '' }}
                       class="w-4 h-4 rounded border"
                       style="accent-color: var(--emp-acento, #C9A24D);">
                <label for="pago_pendiente" class="text-xs sm:text-sm font-medium cursor-pointer"
                       style="color: var(--emp-texto, #ECE7DE);">
                    Solo pago pendiente
                </label>
            </div>
            
            {{-- Botones Filtrar y Limpiar --}}
            <div class="flex items-center gap-2">
                    <button type="submit"
                        class="px-5 py-2 rounded-xl text-xs sm:text-sm font-bold shadow transition hover:brightness-105"
                        style="background: #C9A24D; color: #14171A;">
                    Filtrar
                </button>
                <a href="{{ route('citas.index') }}"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold border transition hover:bg-white/5"
                style="border-color: #363C44; color: #9BA1A8; background: transparent;">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- Tabla Optimizada con Scroll Suave para cualquier nivel de Zoom --}}
    <div class="rounded-2xl border overflow-hidden shadow-lg shadow-black/30"
         style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                
                {{-- Cabecera con 5 columnas lógicas --}}
                <thead class="border-b text-xs font-semibold uppercase tracking-wider"
                       style="background: var(--emp-campo, #14171A); border-color: var(--emp-linea, #363C44); color: var(--emp-tenue, #9BA1A8);">
                    <tr>
                        <th class="px-5 py-3.5 min-w-[200px]">Cliente & Servicios</th>
                        <th class="px-4 py-3.5 min-w-[150px]">Fecha Programada</th>
                        <th class="px-4 py-3.5 min-w-[160px]">Tiempos de Atención</th>
                        <th class="px-4 py-3.5 min-w-[140px]">Estado</th>
                        <th class="px-5 py-3.5 text-right min-w-[120px]">Acciones</th>
                    </tr>
                </thead>

                {{-- Cuerpo de la tabla --}}
                <tbody class="divide-y text-sm" style="border-color: var(--emp-linea, #363C44);">
                    @forelse ($citas as $cita)
                    
                    @php
                        $estado = strtolower((string) $cita->estado);
                    @endphp

                    <tr class="transition-colors hover:bg-white/[0.02]">

                        {{-- 1. Cliente & Servicios --}}
                        <td class="px-5 py-4 align-top">
                            <div class="font-bold text-sm sm:text-base leading-snug" style="color: var(--emp-texto, #ECE7DE);">
                                {{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}
                            </div>
                            <div class="text-xs mt-1 leading-relaxed line-clamp-2 max-w-xs" style="color: var(--emp-tenue, #9BA1A8);">
                                {{ $cita->detalleServicios->pluck('servicio.nombreServicio')->implode(' · ') ?: 'Sin servicios detallados' }}
                            </div>
                        </td>

                        {{-- 2. Fecha Programada --}}
                        <td class="px-4 py-4 align-top">
                            @if($cita->fecha_programada)
                                <div class="font-semibold text-xs sm:text-sm font-mono flex items-center gap-1.5"
                                     style="color: var(--emp-texto, #ECE7DE);">
                                    <svg class="w-4 h-4 flex-shrink-0 text-[#C9A24D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($cita->fecha_programada)->format('d/m/Y') }}</span>
                                </div>
                                <div class="text-xs font-mono mt-0.5 pl-5.5" style="color: var(--emp-tenue, #9BA1A8);">
                                    {{ \Carbon\Carbon::parse($cita->fecha_programada)->format('h:i A') }}
                                </div>
                            @else
                                <span class="text-xs italic" style="color: var(--emp-tenue, #9BA1A8);">No programada</span>
                            @endif
                        </td>

                        {{-- 3. Tiempos de Atención (Inicio y Fin agrupados) --}}
                        <td class="px-4 py-4 align-top text-xs">
                            @if($estado === 'cancelada')
                                <span class="text-xs italic" style="color: var(--emp-tenue, #9BA1A8);">No ejecutada</span>
                            @else
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-medium" style="color: var(--emp-tenue, #9BA1A8);">Inicio:</span>
                                        <span class="font-mono" style="color: var(--emp-texto, #ECE7DE);">
                                            {{ $cita->fecha_inicio ? \Carbon\Carbon::parse($cita->fecha_inicio)->format('h:i A') : 'Pendiente' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-medium" style="color: var(--emp-tenue, #9BA1A8);">Fin:</span>
                                        <span class="font-mono" style="color: var(--emp-texto, #ECE7DE);">
                                            @if($cita->fecha_fin)
                                                {{ \Carbon\Carbon::parse($cita->fecha_fin)->format('h:i A') }}
                                            @elseif($estado === 'iniciada')
                                                <span class="text-[#60a5fa] font-semibold">En curso</span>
                                            @else
                                                Pendiente
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </td>

                        {{-- 4. Estado & Detalle de Cancelación --}}
                        <td class="px-4 py-4 align-top">
                            {{-- Badges con contraste según estado --}}
                            @if($estado === 'completada' || $estado === 'finalizada')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold"
                                      style="background: rgba(34, 197, 94, 0.18); color: #4ade80; border: 1px solid #22c55e;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>
                                    Finalizada
                                </span>
                            @elseif($estado === 'iniciada' || $estado === 'en curso')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold"
                                      style="background: rgba(59, 130, 246, 0.18); color: #60a5fa; border: 1px solid #3b82f6;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#3b82f6] animate-pulse"></span>
                                    En curso
                                </span>
                            @elseif($estado === 'cancelada')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                      style="background: rgba(229, 103, 95, 0.18); color: #F4B6B1; border: 1px solid rgba(229, 103, 95, 0.4);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#E5675F]"></span>
                                    Cancelada
                                </span>

                                {{-- Fecha y motivo de cancelación debajo (ahorra 2 columnas completas) --}}
                                <div class="mt-1 text-[11px] leading-tight max-w-[200px]" style="color: #F4B6B1;">
                                    @if($cita->fechaCancelacion)
                                        <span class="block opacity-75 font-mono">
                                            {{ \Carbon\Carbon::parse($cita->fechaCancelacion)->format('d/m/Y h:i A') }}
                                        </span>
                                    @endif
                                    @if($cita->motivoCancelacion)
                                        <span class="block italic truncate mt-0.5" title="{{ $cita->motivoCancelacion }}">
                                            "{{ $cita->motivoCancelacion }}"
                                        </span>
                                    @endif
                                </div>
                            @elseif($estado === 'confirmada')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                      style="background: rgba(14, 165, 233, 0.18); color: #38bdf8; border: 1px solid rgba(14, 165, 233, 0.35);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#38bdf8]"></span>
                                    Confirmada
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                      style="background: rgba(201, 162, 77, 0.18); color: #C9A24D; border: 1px solid rgba(201, 162, 77, 0.35);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#C9A24D]"></span>
                                    {{ ucfirst($cita->estado ?: 'Pendiente') }}
                                </span>
                            @endif

                            @if ($cita->estaVencida())
                                <div class="mt-1.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                        style="background: rgba(229, 103, 95, 0.18); color: #F4B6B1; border: 1px solid rgba(229, 103, 95, 0.4);"
                                        title="{{ $cita->mensajeVencida() }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#E5675F]"></span>
                                        Vencida
                                    </span>
                                    <p class="mt-1 text-[11px] leading-tight max-w-[200px]" style="color: #F4B6B1;">
                                        Pasó el tiempo de espera ({{ \App\Models\Cita::MINUTOS_TOLERANCIA }} min después de la hora programada). La cita se dio por perdida.
                                    </p>
                                </div>
                            @endif
                        </td>

                        {{-- 5. Acciones --}}
                        <td class="px-5 py-4 align-top text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                
                                @if($cita->estado === 'cancelada')
                                    @can('editar citas')
                                        <a href="{{ route('citas.motivo.form', $cita) }}"
                                           title="Editar motivo de cancelación"
                                           class="emp-icono"
                                           style="color: var(--emp-acento, #C9A24D);">
                                            <span class="w-4 h-4 flex items-center justify-center">
                                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                                            </span>
                                        </a>
                                    @endcan
                                @else
                                    @can('editar citas')
                                        <a href="{{ route('citas.edit', $cita->id) }}"
                                           title="Editar cita"
                                           class="emp-icono">
                                            <span class="w-4 h-4 flex items-center justify-center">
                                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                                            </span>
                                        </a>
                                    @endcan

                                    @can('cancelar citas')
                                        @php
                                            $esHoy = $cita->fecha_programada ? \Carbon\Carbon::parse($cita->fecha_programada)->isToday() : false;
                                        @endphp

                                        @if(in_array($cita->estado, ['pendiente', 'confirmada']) && !$esHoy)
                                            <a href="{{ route('citas.cancelar.form', $cita) }}"
                                               onclick="return confirm('¿Seguro que deseas cancelar esta cita?')"
                                               title="Cancelar cita"
                                               class="emp-icono emp-icono--peligro">
                                                <span class="w-4 h-4 flex items-center justify-center">
                                                    @include('layouts.partials.icons', ['icon' => 'x-circle'])
                                                </span>
                                            </a>
                                        @endif
                                    @endcan
                                @endif

                                {{-- Cobrar / Pagar cita finalizada --}}
                                @if($cita->estado === 'finalizada')
                                    <a href="{{ route('pagos.create', ['cita_id' => $cita->id]) }}"
                                       title="Cobrar en Pagos"
                                       class="emp-icono"
                                       style="color: #38bdf8;">
                                        <span class="w-4 h-4 flex items-center justify-center">
                                            @include('layouts.partials.icons', ['icon' => 'credit-card'])
                                        </span>
                                    </a>
                                @endif

                                {{-- Ver factura --}}
                                <a href="{{ route('citas.factura', $cita->id) }}"
                                   title="Ver factura"
                                   class="emp-icono"
                                   style="color: #4ade80;">
                                    <span class="w-4 h-4 flex items-center justify-center">
                                        @include('layouts.partials.icons', ['icon' => 'eye'])
                                    </span>
                                </a>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center" style="color: var(--emp-tenue, #9BA1A8);">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-2xl"
                                 style="background: var(--emp-campo, #14171A); border: 1px solid var(--emp-linea, #363C44); color: var(--emp-tenue, #9BA1A8);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="font-bold text-base" style="color: var(--emp-texto, #ECE7DE);">No se encontraron citas</p>
                            <p class="text-xs mt-1">Prueba cambiando el rango de fechas o los filtros seleccionados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection