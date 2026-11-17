@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Tarjetas estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
        <x-stat-card
            label="Ingresos del mes"
            :value="'$' . number_format($totalRevenue, 2)"
            description="Pagos cobrados en el mes actual."
            :change="$revenueChange"
            :trend="$revenueTrend"
            icon="tag"
        />
        <x-stat-card
            label="Pagos pendientes"
            :value="'$' . number_format($pagosPendientes, 2)"
            description="Total por cobrar de pagos aún no pagados."
            icon="tag"
        />
        <x-stat-card
            label="Citas de hoy"
            :value="$citasHoy"
            description="Citas programadas para hoy, sin las canceladas."
            icon="tag"
        />
        <x-stat-card
            label="Clientes nuevos del mes"
            :value="$clientesNuevos"
            description="Clientes registrados durante este mes."
            icon="tag"
        />
    </div>

    {{-- Gráficos --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:gap-6">
        <x-card title="Ventas mensuales" subtitle="Últimos 6 meses">
            <canvas id="salesChart" height="110" role="img" aria-label="Gráfico de ventas mensuales"></canvas>
        </x-card>
    </div>

    {{-- Últimas citas --}}
    <div class="mt-6">
        <x-card title="Últimas citas" subtitle="Las 5 citas creadas más recientemente" :padding="false">

            <x-table :headers="['Cliente', 'Estado', 'Fecha programada']">
                @forelse ($ultimasCitas as $cita)
                    @php
                        $nombreCliente = trim($cita->nombre . ' ' . $cita->apellido);
                        $estilosEstado = [
                            'pendiente'  => 'bg-amber-50 text-amber-700 border-amber-200',
                            'confirmada' => 'bg-sky-50 text-sky-700 border-sky-200',
                            'iniciada'   => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'finalizada' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'cancelada'  => 'bg-red-50 text-red-700 border-red-200',
                        ];
                        $estilo = $estilosEstado[$cita->estado] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <img
                                    src="https://ui-avatars.com/api/?name={{ urlencode($nombreCliente) }}"
                                    class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-100"
                                    alt="Avatar de {{ $nombreCliente }}"
                                >
                                <span class="font-medium text-slate-700 whitespace-nowrap">{{ $nombreCliente }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $estilo }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($cita->fecha_programada)->format('d/m/Y h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-10 text-center text-sm text-slate-400">
                            No hay citas registradas.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthLabels = @json($chartData['months']);

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Ventas',
                    data: @json($chartData['sales']),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79,70,229,0.08)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
            }
        });

        new Chart(document.getElementById('usersChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Usuarios registrados',
                    data: @json($chartData['users']),
                    backgroundColor: '#818cf8',
                    borderRadius: 6,
                    maxBarThickness: 36,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
            }
        });

    });
</script>
@endpush
