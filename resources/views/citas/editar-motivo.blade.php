@extends('layouts.app')

@section('titulo')
    Editar motivo de cancelación
@endsection

@section('content')

<x-card>

    <h1 class="text-3xl font-bold text-center mb-6">Editar motivo de cancelación</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-5">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('citas.motivo', $cita) }}">
        @csrf
        @method('PATCH')

        <div class="mb-6">
            <label class="block font-semibold mb-2">Número de Cita</label>
            <input type="text"
                value="#{{ $cita->id }}"
                disabled
                class="w-full border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 text-gray-500"
            >
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-2">Estado de la cita</label>
            <input type="text"
                value="{{ $cita->estado }}"
                disabled
                class="w-full border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 text-gray-500"
            >
        </div>

        <div class="mb-6">
            <label for="motivoCancelacion" class="block font-semibold mb-2">
                Motivo de cancelación
            </label>
            <textarea name="motivoCancelacion"
                      id="motivoCancelacion"
                      rows="4"
                      required
                      class="w-full border border-gray-200 rounded-lg px-4 py-3">{{ old('motivoCancelacion', $cita->motivoCancelacion) }}</textarea>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('citas.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg">
                Volver
            </a>
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg">
                Guardar motivo
            </button>
        </div>
    </form>

</x-card>

@endsection