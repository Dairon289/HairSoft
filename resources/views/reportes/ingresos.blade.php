@extends('layouts.app')

@section('title')

    Reporte de Ingresos
    
@endsection

@section('content')

<x-card>

    <h1 class="text-3xl font-bold text-center mb-4">Reporte de Ingresos</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('reportes.ingresos') }}" class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label for="desde" class="block font-semibold mb-1">Desde</label>
            <input type="date" name="desde" id="desde" value="{{ old('desde', $desde) }}"
                   class="border border-gray-200 rounded-lg px-4 py-2">
        </div>
        <div>
            <label for="hasta" class="block font-semibold mb-1">Hasta</label>
            <input type="date" name="hasta" id="hasta" value="{{ old('hasta', $hasta) }}"
                   class="border border-gray-200 rounded-lg px-4 py-2">
        </div>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">
            Filtrar
        </button>
    </form>

    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-6">
        <span class="font-semibold">Total de ingresos:</span>
        <span class="text-2xl font-bold">$ {{ number_format($total, 0, ',', '.') }}</span>
    </div>

    <table class="w-full text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Servicio</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($porServicio as $fila)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $fila->nombreServicio }}</td>
                    <td class="px-4 py-2">{{ $fila->cantidad }}</td>
                    <td class="px-4 py-2">$ {{ number_format($fila->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                        No hay pagos registrados en este rango de fechas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</x-card>

@endsection