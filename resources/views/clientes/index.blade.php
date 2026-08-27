
@extends('layouts.app')


@section('title')
    TITULO
@endsection


@section('content')


    <div class="container mx-auto mt-10">

        <div class="overflow-x-auto">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-3xl font-bold text-gray-700">
                    Clientes
                </h2>

                <a href="{{ route ('cliente.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                    Nuevo cliente

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
                            Nombre Cliente
                        </th>

                        <th class="border px-4 py-2">
                            Apellido Cliente
                        </th>

                        <th class="border px-4 py-2">
                            Correo Cliente
                        </th>


                        <th class="border px-4 py-2">
                            telefono Cliente
                        </th>

                        <th class="border px-4 py-2">
                            Rol Cliente
                        </th>

                        <th class="border px-4 py-2">
                            Estado Cliente
                        </th>
                        

                        <th class="border px-4 py-2">
                            Acciones
                        </th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($clientes as $cliente)
                    
                    <tr>
                        <td class="border px-4 py-2">{{ $cliente->id }}</td>
                        <td class="border px-4 py-2">{{ $cliente->nombre}}</td>
                        <td class="border px-4 py-2">{{ $cliente->apellido}}</td>
                        <td class="border px-4 py-2">{{ $cliente->correo}}</td>
                        <td class="border px-4 py-2">{{ $cliente->telefono}}</td>
                        <td class="border px-4 py-2">{{ $cliente->rol}}</td>
                        <td class="border px-4 py-2">{{ $cliente->estado}}</td>
                    
                        <td class="border px-4 py-2">
                            <div class="flex gap-2">
                                
                                <a href="{{ route('cliente.edit', $cliente->id)}}" class="bg-red-400 hover:bg-red-600 text-white rounded-lg px-5 py-2">Editar cliente</a>

            

                                <form action="{{ route('cliente.destroy', $cliente->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-400 hover:bg-red-600 text-white rounded-lg px-3 py-2">Eliminar cliente</button>
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