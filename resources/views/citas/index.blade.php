@extends('layouts.app')

@section('title')
    Citas
@endsection

@section('content')

    <div class="container mx-auto mt-10">

        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-700">
                Citas
            </h2>

            <a href="{{ route('citas.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Nueva Cita
            </a>
        </div>

        @if(session('success'))

            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">

                {{ session('success') }}

            </div>

        @endif

            
        @if(session('successedit'))

            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">

                {{ session('successedit') }}

            </div>

        @endif

        @if(session('successdelete'))

            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">

                {{ session('successdelete') }}

            </div>

        @endif

        @if(session('mensaje'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('mensaje') }}
            </div>
        @endif

        
        <div class="overflow-x-auto">

            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">

                    <tr>
                        <th class="border px-4 py-2">
                            ID
                        </th>

                        <th class="border px-4 py-2">
                            Cliente
                        </th>

                        <th class="border px-4 py-2">
                            Servicios
                        </th>

                        <th class="border px-4 py-2">
                            Fecha programada
                        </th>

                        <th class="border px-4 py-2">
                            Fecha Inicio
                        </th>

                        <th class="border px-4 py-2">
                            Fecha Fin
                        </th>

                        <th class="border px-4 py-2">
                            Estado
                        </th>

                        <th class="border px-4 py-2">
                            Fecha Cancelación
                        </th>

                        <th class="border px-4 py-2">
                            Motivo Cancelación
                        </th>

                        <th class="border px-4 py-2">
                            Acciones
                        </th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($citas as $cita)
                    
                    <tr>
                        <td class="border px-4 py-2">{{ $cita->id }}</td>

                        <td class="border px-4 py-2">
                            {{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}
                        </td>
                        
                        <td class="border px-4 py-2">
                            {{ $cita->detalleServicios->pluck('servicio.nombreServicio')->implode(', ') }}
                        </td>

                        <td class="border px-4 py-2">

                            {{ $cita->fecha_programada 
                                ? \Carbon\Carbon::parse($cita->fecha_programada)->format('d/m/Y h:i A') 
                                : 'No programada' 
                            }}

                        </td>

                        <td class="border px-4 py-2">
                            @if($cita->estado === 'cancelada')
                                No iniciada
                            @elseif($cita->fecha_inicio)
                                {{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('d/m/Y h:i A') }}
                            @else
                                Pendiente
                            @endif
                        </td>

                        <td class="border px-4 py-2">
                            @if($cita->estado === 'cancelada')
                                No finalizada
                            @elseif($cita->fecha_fin)
                                {{ \Carbon\Carbon::parse($cita->fecha_fin)->format('d/m/Y h:i A') }}
                            @elseif($cita->estado === 'iniciada')
                                En curso
                            @else
                                Pendiente
                            @endif
                        </td>
                        <td class="border px-4 py-2">{{ $cita->estado }}</td>
                    
                        <td class="border px-4 py-2">
                            {{ $cita->fechaCancelacion
                                ? \Carbon\Carbon::parse($cita->fechaCancelacion)->format('d/m/Y h:i A')
                                : '—' }}
                        </td>
            
                        <td class="border px-4 py-2">{{ $cita->motivoCancelacion }}</td>
                    
                       <td class="border px-4 py-2">
                            <div class="flex gap-2">
                                @if($cita->estado === 'cancelada')

                                   
                                    <a href="{{ route('citas.motivo.form', $cita) }}"
                                    title="Editar motivo de cancelación"
                                    aria-label="Editar motivo de cancelación"
                                    class="inline-flex items-center justify-center bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg p-2">
                                        <span class="w-5 h-5" aria-hidden="true">
                                            @include('layouts.partials.icons', ['icon' => 'pencil'])
                                        </span>
                                    </a>

                                @else

                
                                    <a href="{{ route('citas.edit', $cita->id) }}"
                                    title="Editar cita"
                                    aria-label="Editar cita"
                                    class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white rounded-lg p-2">
                                        <span class="w-5 h-5" aria-hidden="true">
                                            @include('layouts.partials.icons', ['icon' => 'pencil'])
                                        </span>
                                    </a>

                                    @if(in_array($cita->estado, ['pendiente', 'confirmada']))
                                        <a href="{{ route('citas.cancelar.form', $cita) }}"
                                        title="Cancelar cita"
                                        aria-label="Cancelar cita"
                                        class="inline-flex items-center justify-center bg-red-500 hover:bg-red-700 text-white rounded-lg p-2">
                                            <span class="w-5 h-5" aria-hidden="true">
                                                @include('layouts.partials.icons', ['icon' => 'x-circle'])
                                            </span>
                                        </a>
                                    @endif

                                @endif

                                <a href="{{ route('citas.factura', $cita->id) }}"
                                title="Ver factura"
                                aria-label="Ver factura"
                                class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-700 text-white rounded-lg p-2">
                                    <span class="w-5 h-5" aria-hidden="true">
                                        @include('layouts.partials.icons', ['icon' => 'eye'])
                                    </span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection