@extends('layouts.app')

@section('title')
    Anular Pago
@endsection

@section('content')

<x-card>

    <h1 class="text-3xl font-bold text-center mb-4">Anular Pago</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-2 rounded-lg mb-4 text-sm">
        Esta acción no se puede deshacer. El pago quedará anulado y la cita
        podrá recibir un pago nuevo.
    </div>

    <form method="POST" action="{{ route('pagos.anular', $pago->id) }}"
          onsubmit="return confirm('¿Seguro que deseas anular este pago?')">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">

            <div class="mb-4 md:col-span-2">
                <label class="block font-semibold mb-1">Cita</label>
                <input type="text"
                    value="#{{ $pago->cita->id }} - {{ $pago->cita->cliente->nombre }} {{ $pago->cita->cliente->apellido }} ({{ $pago->cita->fecha_programada->format('d/m/Y h:i A') }})"
                    disabled
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-500">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Monto</label>
                <input type="text"
                    value="$ {{ number_format($pago->monto, 0, ',', '.') }}"
                    disabled
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-500">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Estado actual</label>
                <input type="text"
                    value="{{ ucfirst($pago->estado_pago) }}"
                    disabled
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-500">
            </div>

            <div class="mb-4 md:col-span-2">
                <label for="motivo_anulacion" class="block font-semibold mb-1">
                    Motivo de la anulación
                </label>
                <textarea name="motivo_anulacion"
                          id="motivo_anulacion"
                          rows="3"
                          maxlength="500"
                          required
                          placeholder="Explica por qué se anula este pago"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2 @error('motivo_anulacion') border-red-500 @enderror">{{ old('motivo_anulacion') }}</textarea>
            </div>

            <div class="flex justify-between items-center md:col-span-2">
                <a href="{{ route('pagos.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">
                    Volver
                </a>
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg">
                    Anular pago
                </button>
            </div>

        </div>
    </form>

</x-card>

@endsection