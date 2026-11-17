@extends('layouts.app')


@section('title')

    Editar Servicios 
    
@endsection


@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white shadow-lg rounded-xl p-4 sm:p-5">

        <h2 class="text-xl font-bold text-center mb-3">
            Editar Servicio
        </h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-lg mb-3 text-xs">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('servicio.update', $servicios->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2.5">

                <div>
                    <label for="nombreServicio" class="block text-xs font-semibold mb-1">Nombre Servicio</label>
                    <input type="text" id="nombreServicio" name="nombreServicio" value="{{ old('nombreServicio', $servicios->nombreServicio) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('nombreServicio') border-red-500 @enderror">
                    @error('nombreServicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duracionServicio" class="block text-xs font-semibold mb-1">Duración Servicio</label>
                    <input type="time" id="duracionServicio" name="duracionServicio" value="{{ old('duracionServicio', $servicios->duracionServicio) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('duracionServicio') border-red-500 @enderror">
                    @error('duracionServicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="precioServicio" class="block text-xs font-semibold mb-1">Precio Servicio</label>
                    <input type="number" id="precioServicio" name="precioServicio" value="{{ old('precioServicio', $servicios->precioServicio) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('precioServicio') border-red-500 @enderror">
                    @error('precioServicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estadoServicio" class="block text-xs font-semibold mb-1">Estado Servicio</label>
                    <select id="estadoServicio" name="estadoServicio" class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('estadoServicio') border-red-500 @enderror">
                        <option value="activo" @selected(old('estadoServicio', $servicios->estadoServicio) === 'activo')>Activo</option>
                        <option value="inactivo" @selected(old('estadoServicio', $servicios->estadoServicio) === 'inactivo')>Inactivo</option>
                    </select>
                    @error('estadoServicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="descripcionServicio" class="block text-xs font-semibold mb-1">Descripción Servicio</label>
                    <input type="text" id="descripcionServicio" name="descripcionServicio" value="{{ old('descripcionServicio', $servicios->descripcionServicio) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('descripcionServicio') border-red-500 @enderror">
                    @error('descripcionServicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 mt-2 md:col-span-2">
                    <a href="{{ route('servicio.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white rounded-lg px-4 py-1.5 text-sm">
                        Volver
                    </a>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-5 py-1.5 text-sm">
                        Guardar
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection