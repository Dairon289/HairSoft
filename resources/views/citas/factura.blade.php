@extends('layouts.app')

@section('title')
    Factura #{{ $cita->id }}
@endsection

@push('styles')
    <style>
        @media print {
            aside, nav, header, footer, .no-print { display: none !important; }
        }
    </style>
@endpush

@section('content')

    @php
        $esFinal = $cita->estado === 'finalizada';
        $moneda = fn ($valor) => '$ ' . number_format($valor, 0, ',', '.');
    @endphp

    <div class="container mx-auto mt-10 max-w-3xl">

        <div class="bg-white border border-gray-300 rounded-lg p-8">

            {{-- Encabezado --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-700">
                        {{ $esFinal ? 'Factura' : 'Valor estimado' }} #{{ $cita->id }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Estado de la cita: {{ ucfirst($cita->estado) }}
                    </p>
                </div>

                <div class="text-right text-sm text-gray-600">
                    <p class="font-semibold">Fecha programada</p>
                    <p>{{ \Carbon\Carbon::parse($cita->fecha_programada)->format('d/m/Y h:i A') }}</p>
                </div>
            </div>

            {{-- Cliente y empleado --}}
            <div class="grid grid-cols-2 gap-6 mb-8 text-sm">
                <div>
                    <p class="font-semibold text-gray-700 mb-1">Cliente</p>
                    <p>{{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}</p>
                    <br>
                    <p class="font-semibold text-gray-700 mb-1">Correo</p>
                    <p class="text-gray-500">{{ $cita->cliente->correo }}</p>
                    <br>
                    <p class="font-semibold text-gray-700 mb-1">Teléfono</p>
                    <p class="text-gray-500">{{ $cita->cliente->telefono }}</p>
                </div>

                <div>
                    <p class="font-semibold text-gray-700 mb-1">Atendido por</p>
                    <p>{{ $cita->empleado->nombreEmpleado }} {{ $cita->empleado->apellidoEmpleado }}</p>
                </div>
            </div>

            {{-- Servicios --}}
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2 text-left">Servicio</th>
                        <th class="border px-4 py-2 text-right">Precio</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($cita->detalleServicios as $detalle)
                        <tr>
                            <td class="border px-4 py-2">{{ $detalle->servicio->nombreServicio }}</td>
                            <td class="border px-4 py-2 text-right">{{ $moneda($detalle->precioServicio) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="border px-4 py-2 text-center text-gray-500">
                                Esta cita no tiene servicios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr class="bg-gray-100 font-bold">
                        <td class="border px-4 py-2 text-right">Total</td>
                        <td class="border px-4 py-2 text-right">{{ $moneda($cita->total) }}</td>
                    </tr>
                </tfoot>
            </table>

            @unless ($esFinal)
                <p class="text-xs text-gray-500 mt-4">
                    Este valor es informativo: se confirma cuando la cita se finaliza.
                </p>
            @endunless
        </div>

        {{-- Acciones --}}
        <div class="no-print flex gap-3 mt-6">
            <a href="{{ route('citas.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white rounded-lg px-5 py-2">
                Volver
            </a>

            <button type="button" onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-5 py-2">
                Imprimir
            </button>
        </div>
    </div>

@endsection