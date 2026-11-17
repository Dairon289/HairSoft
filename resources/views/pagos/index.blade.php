@extends('layouts.app')

@section('title')
    Pagos
@endsection

@section('content')

@php
    $totalRecaudado = $pagos->where('estado_pago', 'pagado')->sum('monto');
    $conteoPagados = $pagos->where('estado_pago', 'pagado')->count();
    $conteoAnulados = $pagos->where('estado_pago', 'anulado')->count();
    $conteoPendientes = $pagos->where('estado_pago', 'pendiente')->count();
    $montoAnulados = $pagos->where('estado_pago', 'anulado')->sum('monto');
    $ticketPromedio = $conteoPagados > 0 ? ($totalRecaudado / $conteoPagados) : 0;
    $efectivoCount = $pagos->where('metodo_pago', 'efectivo')->count();
    $transferenciaCount = $pagos->where('metodo_pago', 'transferencia')->count();
@endphp

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6"
    x-data="{
    tab: 'all',
    search: '',
    metodo: '',
    matches(estado, metodoItem, searchData) {
        const matchTab = (this.tab === 'all') || (estado.toLowerCase() === this.tab.toLowerCase());
        const matchMetodo = (this.metodo === '') || (metodoItem.toLowerCase() === this.metodo.toLowerCase());
        const query = this.search.toLowerCase().trim();
        const matchSearch = !query || searchData.toLowerCase().includes(query);
        return matchTab && matchMetodo && matchSearch;
    }
    }">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#363C44]">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-[#ECE7DE]">
                    Pagos
                </h1>
            </div>
            <p class="text-sm mt-1 text-[#9BA1A8]">
                Control de caja, ingresos de servicios y transacciones de citas
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('pagos.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition hover:brightness-105 flex-shrink-0"
               style="background: #C9A24D; color: #14171A;">
                <span class="text-lg leading-none font-bold">+</span>
                <span>Registrar pago</span>
            </a>
        </div>
    </div> 
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border"
             style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); border-color: rgba(74, 160, 110, 0.35); color: var(--hs-ok-texto, #9BE0BA);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('successedit'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border"
             style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); border-color: rgba(74, 160, 110, 0.35); color: var(--hs-ok-texto, #9BE0BA);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('successedit') }}</span>
        </div>
    @endif

    @if(session('successdelete'))
        <div class="flex items-start gap-3 px-4 py-3 rounded-xl text-sm font-medium border"
             style="background: var(--hs-err-fondo, rgba(229, 103, 95, 0.14)); border-color: rgba(229, 103, 95, 0.35); color: var(--hs-err-texto, #F4B6B1);">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('successdelete') }}</span>
        </div>
    @endif


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        

        <div class="p-4 rounded-2xl border shadow-sm relative overflow-hidden"
             style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-[#9BA1A8]">
                <span>Total Recaudado</span>
                <span class="p-1.5 rounded-lg" style="background: rgba(74, 160, 110, 0.15); color: #9BE0BA;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold font-mono mt-2 text-[#ECE7DE]">
                $ {{ number_format($totalRecaudado, 0, ',', '.') }}
            </p>
            <p class="text-xs mt-1 font-medium text-[#9BE0BA] flex items-center gap-1">
                <span>Caja activa</span>
            </p>
        </div>

    
        <div class="p-4 rounded-2xl border shadow-sm"
             style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-[#9BA1A8]">
                <span>Pagos Realizados</span>
                <span class="p-1.5 rounded-lg" style="background: rgba(201, 162, 77, 0.15); color: #C9A24D;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold font-mono mt-2 text-[#ECE7DE]">
                {{ $conteoPagados }} <span class="text-xs font-normal text-[#9BA1A8]">citas cobradas</span>
            </p>
            <p class="text-xs mt-1 text-[#9BA1A8]">
                Ticket promedio: $ {{ number_format($ticketPromedio, 0, ',', '.') }}
            </p>
        </div>

        <div class="p-4 rounded-2xl border shadow-sm"
             style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-[#9BA1A8]">
                <span>Pagos Anulados</span>
                <span class="p-1.5 rounded-lg" style="background: rgba(229, 103, 95, 0.15); color: #F4B6B1;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold font-mono mt-2 text-[#F4B6B1]">
                {{ $conteoAnulados }} <span class="text-xs font-normal text-[#F4B6B1]/70">($ {{ number_format($montoAnulados, 0, ',', '.') }})</span>
            </p>
            <p class="text-xs mt-1 text-[#9BA1A8]">
                Citas anuladas o canceladas
            </p>
        </div>

        <div class="p-4 rounded-2xl border shadow-sm"
             style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-[#9BA1A8]">
                <span>Métodos de Pago</span>
                <span class="p-1.5 rounded-lg" style="background: rgba(114, 169, 197, 0.15); color: #72a9c5;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold mt-2 text-[#ECE7DE]">
                {{ $efectivoCount >= $transferenciaCount ? 'Efectivo' : 'Transferencia' }}
            </p>
            <p class="text-xs mt-1 text-[#9BA1A8]">
                {{ $efectivoCount }} efectivo · {{ $transferenciaCount }} transf.
            </p>
        </div>

    </div>

    <div class="rounded-2xl border shadow-sm overflow-hidden"
        style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
      

        <div class="p-4 border-b flex flex-col md:flex-row md:items-center justify-between gap-3"
            style="background: rgba(20, 23, 26, 0.6); border-color: var(--emp-linea, #363C44);">
        
            <div class="flex items-center gap-1 p-1 rounded-xl border text-xs font-medium"
                style="background: #14171A; border-color: #363C44;">
                <button type="button" @click="tab = 'all'"
                        :class="tab === 'all' ? 'bg-[#262B31] text-[#ECE7DE] shadow-sm font-semibold' : 'text-[#9BA1A8] hover:text-[#ECE7DE]'"
                        class="px-3 py-1.5 rounded-lg transition-all">
                    Todos ({{ $pagos->count() }})
                </button>
                <button type="button" @click="tab = 'pagado'"
                        :class="tab === 'pagado' ? 'bg-[#262B31] text-[#9BE0BA] shadow-sm font-semibold' : 'text-[#9BA1A8] hover:text-[#ECE7DE]'"
                        class="px-3 py-1.5 rounded-lg transition-all">
                    Pagados ({{ $conteoPagados }})
                </button>
                <button type="button" @click="tab = 'anulado'"
                        :class="tab === 'anulado' ? 'bg-[#262B31] text-[#F4B6B1] shadow-sm font-semibold' : 'text-[#9BA1A8] hover:text-[#ECE7DE]'"
                        class="px-3 py-1.5 rounded-lg transition-all">
                    Anulados ({{ $conteoAnulados }})
                </button>
                @if($conteoPendientes > 0)
                <button type="button" @click="tab = 'pendiente'"
                        :class="tab === 'pendiente' ? 'bg-[#262B31] text-amber-300 shadow-sm font-semibold' : 'text-[#9BA1A8] hover:text-[#ECE7DE]'"
                        class="px-3 py-1.5 rounded-lg transition-all">
                    Pendientes ({{ $conteoPendientes }})
                </button>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <div class="relative min-w-[240px] flex-1 sm:flex-initial">
                    <input type="text" x-model="search" placeholder="Buscar cliente, barbero, cita o servicio..."
                           class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl border focus:outline-none transition-colors placeholder:text-[#9BA1A8]"
                           style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                    <svg class="w-3.5 h-3.5 text-[#9BA1A8] absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select x-model="metodo"
                        class="text-xs rounded-xl px-3 py-1.5 border focus:outline-none"
                        style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                    <option value="">Método: Todos</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                
                <thead class="border-b font-semibold uppercase tracking-wider text-[11px]"
                       style="background: #14171A; border-color: #363C44; color: #9BA1A8;">
                    <tr>
                        <th class="py-3.5 px-4 min-w-[170px]">Cita & Cliente</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Servicios & Profesional</th>
                        <th class="py-3.5 px-4 min-w-[150px]">Fecha y Hora</th>
                        <th class="py-3.5 px-4 min-w-[110px]">Método</th>
                        <th class="py-3.5 px-4 text-right min-w-[110px]">Monto</th>
                        <th class="py-3.5 px-4 text-center min-w-[110px]">Estado</th>
                        <th class="py-3.5 px-4 text-right min-w-[110px]">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y text-xs" style="border-color: #2D333B;">
                    @forelse($pagos as $pago)
                        @php
                            $estado = strtolower((string) $pago->estado_pago);
                            $esPagado = $estado === 'pagado';
                            $esAnulado = $estado === 'anulado';
                            
                            $clienteNombre = trim(($pago->cita->cliente->nombre ?? '') . ' ' . ($pago->cita->cliente->apellido ?? ''));
                            if (empty($clienteNombre)) {
                                $clienteNombre = 'Cliente general';
                            }
                            
                            $serviciosTxt = $pago->cita->detalleServicios->pluck('servicio.nombreServicio')->implode(', ');
                            if (empty($serviciosTxt)) {
                                $serviciosTxt = 'Sin servicios detallados';
                            }

                            $empleadoNombre = trim(($pago->cita->empleado->nombreEmpleado ?? '') . ' ' . ($pago->cita->empleado->apellidoEmpleado ?? ''));
                            
                            $busquedaData = $pago->cita->id . ' ' . $clienteNombre . ' ' . $serviciosTxt . ' ' . $empleadoNombre . ' ' . $pago->metodo_pago;
                        @endphp

                        <tr class="transition-colors hover:bg-white/[0.02] {{ $esAnulado ? 'opacity-75' : '' }}"
                            x-show="matches('{{ $estado }}', '{{ $pago->metodo_pago }}', '{{ addslashes($busquedaData) }}')">
                            
                            <td class="py-3.5 px-4 align-middle">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold font-mono px-2 py-0.5 rounded text-xs {{ $esPagado ? 'text-[#ECE7DE]' : 'text-[#9BA1A8]' }}"
                                          style="background: #262B31;">
                                        #{{ $pago->cita->id }}
                                    </span>
                                    <div>
                                        <div class="font-semibold text-sm {{ $esAnulado ? 'line-through text-[#9BA1A8]' : 'text-[#ECE7DE]' }}">
                                            {{ $clienteNombre }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 align-middle">
                                <div class="font-medium text-sm text-[#ECE7DE] flex items-center gap-1.5">
                                    @if($esPagado)
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#9BE0BA]"></span>
                                    @endif
                                    <span>{{ $serviciosTxt }}</span>
                                </div>
                                @if(!empty($empleadoNombre))
                                    <div class="text-[11px] text-[#9BA1A8] mt-0.5">
                                        Atendido por: <span class="text-[#ECE7DE]/80">{{ $empleadoNombre }}</span>
                                    </div>
                                @endif
                            </td>

    
                            <td class="py-3.5 px-4 align-middle font-mono">
                                <div class="text-sm font-semibold text-[#ECE7DE]">
                                    {{ $pago->fecha_pago?->format('d/m/Y') ?? '—' }}
                                    <span class="text-xs text-[#9BA1A8] font-normal">
                                        {{ $pago->fecha_pago?->format('h:i A') ?? '' }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-[#9BA1A8] font-sans mt-0.5">
                                    Fin cita: {{ $pago->cita->fecha_fin?->format('d/m/Y h:i A') ?? '—' }}
                                </div>
                            </td>


                            <td class="py-3.5 px-4 align-middle">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] font-medium"
                                      style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                                    @if(strtolower($pago->metodo_pago) === 'efectivo')
                                        <svg class="w-3.5 h-3.5 text-[#9BE0BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-[#72a9c5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    @endif
                                    <span>{{ ucfirst($pago->metodo_pago) }}</span>
                                </span>
                            </td>


                            <td class="py-3.5 px-4 align-middle text-right font-mono">
                                <span class="text-sm font-bold {{ $esPagado ? 'text-[#9BE0BA]' : ($esAnulado ? 'line-through text-[#9BA1A8]' : 'text-[#ECE7DE]') }}">
                                    $ {{ number_format($pago->monto, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 align-middle text-center">
                                @if($esPagado)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                          style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); color: var(--hs-ok-texto, #9BE0BA); border: 1px solid var(--hs-ok-borde, rgba(74, 160, 110, 0.4));">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#9BE0BA] animate-pulse"></span>
                                        Pagado
                                    </span>
                                @elseif($esAnulado)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                          style="background: var(--hs-err-fondo, rgba(229, 103, 95, 0.14)); color: var(--hs-err-texto, #F4B6B1); border: 1px solid var(--hs-err-borde, rgba(229, 103, 95, 0.4));">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#E5675F]"></span>
                                        Anulado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-300 border border-amber-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        {{ ucfirst($pago->estado_pago) }}
                                    </span>
                                @endif
                            </td>


                            <td class="py-3.5 px-4 align-middle text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">

                                    <a href="{{ route('citas.factura', $pago->cita->id) }}"
                                       title="Ver factura" aria-label="Ver factura"
                                       class="inline-flex items-center justify-center rounded-lg p-2 transition hover:brightness-110"
                                       style="background: rgba(74, 160, 110, 0.2); color: #9BE0BA; border: 1px solid rgba(74, 160, 110, 0.35);">
                                        <span class="w-4 h-4" aria-hidden="true">
                                            @include('layouts.partials.icons', ['icon' => 'eye'])
                                        </span>
                                    </a>

                                    @unless($pago->estaCerrado())
                                        <a href="{{ route('pagos.edit', $pago->id) }}"
                                           title="Editar pago" aria-label="Editar pago"
                                           class="inline-flex items-center justify-center rounded-lg p-2 transition hover:brightness-110"
                                           style="background: rgba(114, 169, 197, 0.2); color: #72a9c5; border: 1px solid rgba(114, 169, 197, 0.35);">
                                            <span class="w-4 h-4" aria-hidden="true">
                                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                                            </span>
                                        </a>
                                    @endunless

                                    @unless($pago->estaAnulado())
                                        <a href="{{ route('pagos.anular.form', $pago->id) }}"
                                           title="Anular pago" aria-label="Anular pago"
                                           class="inline-flex items-center justify-center rounded-lg p-2 transition hover:brightness-110"
                                           style="background: rgba(229, 103, 95, 0.2); color: #F4B6B1; border: 1px solid rgba(229, 103, 95, 0.35);">
                                            <span class="w-4 h-4" aria-hidden="true">
                                                @include('layouts.partials.icons', ['icon' => 'x-circle'])
                                            </span>
                                        </a>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#9BA1A8]">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <svg class="w-8 h-8 mx-auto text-[#9BA1A8]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="font-medium">No hay pagos registrados</p>
                                    <p class="text-xs">Los pagos completados o anulados aparecerán listados aquí.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#9BA1A8]"
             style="background: rgba(20, 23, 26, 0.6); border-color: var(--emp-linea, #363C44);">
            <div>
                Total de registros cargados: <span class="font-semibold text-[#ECE7DE]">{{ $pagos->count() }}</span>
            </div>
            <div class="text-[11px] text-[#9BA1A8]">
                Sincronizado con caja HairSoft
            </div>
        </div>

    </div>

</div>

@endsection