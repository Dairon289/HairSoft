@extends('layouts.app')

@section('title')
    Servicios
@endsection

@section('content')

<div class="container mx-auto px-4 py-8">

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">

        <div>
            <h2 class="text-3xl font-bold text-gray-800">
                Nuestros servicios
            </h2>

            <p class="text-gray-500 mt-1">
                Administra los servicios disponibles en HairSoft.
            </p>
        </div>

        @can('crear servicios')
        <a href="{{ route('servicio.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl shadow-sm transition">

            <span class="text-xl">+</span>
            Nuevo servicio

        </a>
        @endcan

    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('successedit'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('successedit') }}
        </div>
    @endif

    @if(session('successdelete'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
            {{ session('successdelete') }}
        </div>
    @endif

    @if(session('errordelete'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
            {{ session('errordelete') }}
        </div>
    @endif

    @if($servicios->count() > 0)

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($servicios as $servicio)

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 {{ strtolower($servicio->estadoServicio) == 'activo' ? '' : 'opacity-60' }}">

                    {{-- Nombre e identificación --}}
                    <div class="flex justify-between items-start mb-4">

                        <div class="flex items-center gap-3">

                            <div class="w-12 h-12 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl">

                                <span class="w-7 h-7">
                                    @include('layouts.partials.icons', ['icon' => 'scissors'])
                                </span>

                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-gray-800">
                                    {{ $servicio->nombreServicio }}
                                </h3>

                                <p class="text-xs text-gray-400 mt-1">
                                    Servicio #{{ str_pad($servicio->id, 3, '0', STR_PAD_LEFT) }}
                                </p>
                            </div>

                        </div>

                        @if(strtolower($servicio->estadoServicio) == 'activo')

                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                Activo
                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full">
                                No disponible por ahora
                            </span>

                        @endif

                    </div>

                    {{-- Descripción --}}
                    <div class="mb-5">

                        <p class="text-sm text-gray-500 leading-relaxed">
                            {{ $servicio->descripcionServicio ?: 'Sin descripción disponible.' }}
                        </p>

                    </div>

                    {{-- Duración y precio --}}
                    <div class="grid grid-cols-2 gap-3 mb-5">

                        <div class="bg-gray-50 rounded-xl p-3">

                            <p class="text-xs text-gray-400 mb-1">
                                Duración
                            </p>

                            <p class="text-sm font-semibold text-gray-700">
                                {{ $servicio->duracionServicio }}
                            </p>

                        </div>

                        <div class="bg-blue-50 rounded-xl p-3">

                            <p class="text-xs text-blue-500 mb-1">
                                Precio
                            </p>

                            <p class="text-lg font-bold text-blue-700">
                                ${{ number_format((float) $servicio->precioServicio, 0, ',', '.') }}
                            </p>

                        </div>

                    </div>

                    {{-- Acciones (solo con permiso) --}}
                    @canany(['editar servicios', 'eliminar servicios'])
                    <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4">

                        @can('editar servicios')
                        <a href="{{ route('servicio.edit', $servicio->id) }}"
                            title="Editar servicio"
                            aria-label="Editar servicio"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition text-sm font-medium">

                            <span class="w-4 h-4">
                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                            </span>

                            Editar

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
                                class="inline-flex items-center justify-center p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">

                                <span class="w-5 h-5">
                                    @include('layouts.partials.icons', ['icon' => 'trash'])
                                </span>

                            </button>

                        </form>
                        @endcan

                    </div>
                    @endcanany

                </div>

            @endforeach

        </div>

    @else

        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">

            <div class="text-gray-300 mb-3">
                <span class="inline-block w-12 h-12">
                    @include('layouts.partials.icons', ['icon' => 'scissors'])
                </span>
            </div>

            <h3 class="text-lg font-semibold text-gray-700">
                Todavía no hay servicios registrados
            </h3>

            @can('crear servicios')
            <p class="text-sm text-gray-500 mt-2">
                Comienza agregando los servicios que ofrece tu peluquería.
            </p>

            <a href="{{ route('servicio.create') }}"
                class="inline-block mt-5 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">

                Agregar primer servicio

            </a>
            @endcan

        </div>

    @endif

</div>

@endsection