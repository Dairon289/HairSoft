
@extends('layouts.app')


@section('title')
    TITULO
@endsection


@section('content')


    <div class="container mx-auto mt-10">

        <div class="overflow-x-auto">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-3xl font-bold text-gray-700">
                    Citas
                </h2>

                <a href="{{ route ('citas.create') }}"
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


            <table class="min-w-full border border-gray-300">

                <thead class="bg-gray-200">

                    <tr>
                        <th class="border px-4 py-2">
                            ID
                        </th>

                        <th class="border px-4 py-2">
                            Fecha programada
                        </th>

                        <th class="border px-4 py-2">
                            Servicios
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

                            {{ $cita->fecha_programada 
                                ? \Carbon\Carbon::parse($cita->fecha_programada)->format('d/m/Y h:i A') 
                                : 'No programada' 
                            }}

                        </td>

                        <td class="border px-4 py-2">
                            {{ $cita->detalleServicios->pluck('servicio.nombreServicio')->implode(', ') }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ $cita->fecha_inicio 
                            ? \Carbon\Carbon::parse($cita->fecha_inicio)->format('d/m/Y h:i A') 
                            : 'Pendiente' 
                            }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ $cita->fecha_fin 
                            ? \Carbon\Carbon::parse($cita->fecha_fin)->format('d/m/Y h:i A') 
                            : 'Pendiente' 
                            }}
                        </td>
                        <td class="border px-4 py-2">{{ $cita->estado }}</td>
                    
                        <td class="border px-4 py-2">{{ $cita->fechaCancelacion }}</td>
                        
            
                        <td class="border px-4 py-2">{{ $cita->motivoCancelacion }}</td>
                    
                        <td class="border px-4 py-2">
                            <div class="flex gap-2">
                                
                                <a href="{{ route('citas.edit', $cita->id)}}" class="bg-red-400 hover:bg-red-600 text-white rounded-lg px-5 py-2">Editar Cita</a>

            

                                <form action="{{ route('citas.cancelar', $cita->id)}}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-400 hover:bg-red-600 text-white rounded-lg px-3 py-2">Cancelar Cita</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

@endsection