@extends('layouts.app')

@section('title')

    Pagos
    
@endsection

@section('content')

    <div class="container mx-auto mt-10">

        <div class="overflow-x-auto">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-3xl font-bold text-gray-700">
                    Pagos
                </h2>

                <a href="{{ route('pagos.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Registrar pago
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
                        <th class="border px-4 py-2">Cita</th>
                        <th class="border px-4 py-2">Servicios</th>
                        <th class="border px-4 py-2">Fecha Fin</th>
                        <th class="border px-4 py-2">Monto</th>
                        <th class="border px-4 py-2">Método de Pago</th>
                        <th class="border px-4 py-2">Estado del Pago</th>
                        <th class="border px-4 py-2">Fecha del Pago</th>
                        <th class="border px-4 py-2">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pagos as $pago)
                        <tr>
                            <td class="border px-4 py-2">#{{ $pago->cita->id }}</td>
                                <td class="border px-4 py-2">
                                    {{ $pago->cita->detalleServicios->pluck('servicio.nombreServicio')->implode(', ') }}
                                </td>
                                <td class="border px-4 py-2">{{ $pago->cita->fecha_fin?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="border px-4 py-2">${{ number_format($pago->monto, 2) }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($pago->metodo_pago) }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($pago->estado_pago) }}</td>
                                <td class="border px-4 py-2">{{ $pago->fecha_pago?->format('d/m/Y H:i') ?? '—' }}</td>

                                <td class="border px-4 py-2">
                                    
                                <div class="flex gap-2">
                                    <a href="{{ route('citas.factura', $pago->cita->id) }}"
                                        title="Ver factura" aria-label="Ver factura"
                                        class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-700 text-white rounded-lg p-2">
                                        <span class="w-5 h-5" aria-hidden="true">
                                            @include('layouts.partials.icons', ['icon' => 'eye'])
                                        </span>
                                    </a>

                                    @unless($pago->estaCerrado())
                                        <a href="{{ route('pagos.edit', $pago->id) }}"
                                            title="Editar pago" aria-label="Editar pago"
                                            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-2">
                                            <span class="w-5 h-5" aria-hidden="true">
                                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                                            </span>
                                        </a>
                                    @endunless

                                    @unless($pago->estaAnulado())
                                        <a href="{{ route('pagos.anular.form', $pago->id) }}"
                                            title="Anular pago" aria-label="Anular pago"
                                            class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg p-2">
                                            <span class="w-5 h-5" aria-hidden="true">
                                                @include('layouts.partials.icons', ['icon' => 'x-circle'])
                                            </span>
                                        </a>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border px-4 py-2 text-center text-gray-500">
                                No hay pagos registrados.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

@endsection