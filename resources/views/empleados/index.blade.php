@extends('layouts.app')


@section('title')
    TITULO
@endsection


@section('content')


    <div class="container mx-auto mt-10">


        <div class="flex justify-between items-center mb-6">

            <h2 class="text-3xl font-bold text-gray-700">
                Empleados
            </h2>

            <a href="{{ route ('empleados.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                Nuevo empleado(a)

            </a>
        </div>

        <div class="overflow-x-auto">

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
                            Nombre Empleado
                        </th>

                        <th class="border px-4 py-2">
                            Apellido Empleado
                        </th>

                        <th class="border px-4 py-2">
                            Especialidad
                        </th>

                        <th class="border px-4 py-2">
                            Correo Empleado
                        </th>

                        <th class="border px-4 py-2">
                            Telefono Empleado
                        </th>

                        <th class="border px-4 py-2">
                            Disponibilidad
                        </th>

                        <th class="border px-4 py-2">
                            Fecha Ingreso
                        </th>

                        <th class="border px-4 py-2">
                            Hora Ingreso
                        </th>

                        <th class="border px-4 py-2">
                            Hora Salida
                        </th>

                        <th class="border px-4 py-2">
                            Estado
                        </th>
                        
                    
                        <th class="border px-4 py-2">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <tbody>
                    @foreach ($empleado as $empleados)
                    
                    <tr>
                        <td class="border px-4 py-2">{{ $empleados->id }}</td>
                        <td class="border px-4 py-2">{{ $empleados->nombreEmpleado}}</td>
                        <td class="border px-4 py-2">{{ $empleados->apellidoEmpleado}}</td>
                        <td class="border px-4 py-2">{{ $empleados->especialidad}}</td>
                        <td class="border px-4 py-2">{{ $empleados->correoEmpleado}}</td>
                        <td class="border px-4 py-2">{{ $empleados->telefonoEmpleado}}</td>
                        <td class="border px-4 py-2">{{ $empleados->disponibilidad}}</td>
                        <td class="border px-4 py-2">{{ $empleados->fechaIngreso}}</td>
                        <td class="border px-4 py-2">{{ $empleados->horaIngreso}}</td>
                        <td class="border px-4 py-2">{{ $empleados->horaSalida}}</td>
                        <td class="border px-4 py-2">{{ $empleados->estadoEmpleado}}</td>
                        
                        <td class="border px-4 py-2">
                            <div class="flex gap-2">
                                
                                <a href="{{ route('empleados.edit', $empleados->id) }}"
                                    title="Editar empleado"
                                    aria-label="Editar empleado"
                                    class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-2">
                                    <span class="w-5 h-5" aria-hidden="true">
                                        @include('layouts.partials.icons', ['icon' => 'pencil'])
                                    </span>
                                </a>

                                <form action="{{ route('empleados.destroy', $empleados->id) }}" method="post"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este empleado?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        title="Eliminar empleado"
                                        aria-label="Eliminar empleado"
                                        class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg p-2">
                                        <span class="w-5 h-5" aria-hidden="true">
                                            @include('layouts.partials.icons', ['icon' => 'trash'])
                                        </span>
                                    </button>
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