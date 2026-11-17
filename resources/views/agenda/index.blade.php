@extends('layouts.app')

@section('title')
    Agenda
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('css/agenda.css') }}">

    <div class="container mx-auto px-4 py-2 max-w-7xl">
        <!-- Encabezado compacto y Leyenda de Estados en una sola línea -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2">
            <div>
                <h2 class="text-2xl font-bold text-[var(--hs-hueso,#F8F4ED)] tracking-tight">Agenda de Citas</h2>
                <p class="text-xs text-[var(--hs-humo,#9CA3AF)]">
                    Gestión visual e interactiva de turnos | 
                    <span class="inline-flex items-center gap-1 font-semibold text-blue-400">
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Línea azul brillante indica la hora actual
                    </span>
                </p>
            </div>

            <!-- Leyenda con chips reducidos -->
            <div class="flex flex-wrap items-center gap-1.5 bg-[var(--hs-panel,#1A1E26)] border border-[var(--hs-linea,#363C44)] px-2.5 py-1.5 rounded-lg text-[11px]">
                <span class="text-[var(--hs-humo,#9CA3AF)] font-semibold mr-0.5">Estados:</span>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-amber-500/15 text-amber-300 border border-amber-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Pendiente
                </span>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-blue-500/15 text-blue-300 border border-blue-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Confirmada
                </span>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Iniciada
                </span>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-gray-500/15 text-gray-300 border border-gray-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Finalizada
                </span>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-red-500/15 text-red-300 border border-red-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Cancelada / Vencida
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 px-3 py-2 rounded-lg mb-2 text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Contenedor adaptado a la altura de la pantalla -->
        <div class="hs-agenda hs-agenda-contenedor p-3.5 rounded-xl shadow-lg">
            <div id="calendario"></div>
        </div>
    </div>

    <!-- Modal Detalle de Cita -->
    <div id="ventana-cita" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="hs-modal-ventana rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between border-b border-[var(--hs-linea,#363C44)] pb-3 mb-4">
                <h3 class="text-xl font-bold flex items-center gap-2 text-[var(--hs-hueso,#F8F4ED)]">
                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--hs-laton,#D8C3A5)]"></span>
                    Detalle de la Cita
                </h3>
                <span id="det-badge-estado" class="text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider"></span>
            </div>

            <div class="space-y-3 text-sm">
                <div class="hs-modal-caja-dato p-3 rounded-xl space-y-2">
                    <p class="flex justify-between">
                        <span class="text-[var(--hs-humo,#9CA3AF)] font-medium">Cliente:</span>
                        <span id="det-cliente" class="font-semibold text-[var(--hs-hueso,#FFFFFF)]"></span>
                    </p>
                    <p class="flex justify-between">
                        <span class="text-[var(--hs-humo,#9CA3AF)] font-medium">Servicios:</span>
                        <span id="det-servicios" class="font-semibold text-[var(--hs-laton,#D8C3A5)] text-right"></span>
                    </p>
                    <p class="flex justify-between">
                        <span class="text-[var(--hs-humo,#9CA3AF)] font-medium">Estado:</span>
                        <span id="det-estado" class="font-semibold text-[var(--hs-hueso,#FFFFFF)] capitalize"></span>
                    </p>
                </div>

                <div class="hs-modal-caja-dato p-3 rounded-xl space-y-2">
                    <p class="flex justify-between">
                        <span class="text-[var(--hs-humo,#9CA3AF)] font-medium">Programada:</span>
                        <span id="det-programada" class="font-semibold text-[var(--hs-hueso,#FFFFFF)]"></span>
                    </p>
                    <p class="flex justify-between">
                        <span class="text-[var(--hs-humo,#9CA3AF)] font-medium">Inicio:</span>
                        <span id="det-inicio" class="font-semibold text-[var(--hs-hueso,#FFFFFF)]"></span>
                    </p>
                    <p class="flex justify-between">
                        <span class="text-[var(--hs-humo,#9CA3AF)] font-medium">Fin:</span>
                        <span id="det-fin" class="font-semibold text-[var(--hs-hueso,#FFFFFF)]"></span>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2.5 mt-5 pt-3 border-t border-[var(--hs-linea,#363C44)]">
                <form id="form-iniciar" method="POST" class="hidden">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm px-4 py-2 rounded-xl transition shadow">
                        Iniciar cita
                    </button>
                </form>

                <form id="form-finalizar" method="POST" class="hidden"
                      onsubmit="return confirm('¿Seguro que deseas finalizar esta cita? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="bg-[#8B654A] hover:bg-[#6e4e37] text-white font-semibold text-sm px-4 py-2 rounded-xl transition shadow">
                        Finalizar cita
                    </button>
                </form>

                <button type="button" id="cerrar-ventana"
                        class="bg-[var(--hs-grafito,#22262D)] hover:bg-[var(--hs-linea,#363C44)] text-[var(--hs-humo,#9CA3AF)] hover:text-white font-medium text-sm px-4 py-2 rounded-xl transition border border-[var(--hs-linea,#363C44)]">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/es.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ventana = document.getElementById('ventana-cita');
            var formIniciar = document.getElementById('form-iniciar');
            var formFinalizar = document.getElementById('form-finalizar');
            var badgeEstado = document.getElementById('det-badge-estado');

            function cerrarVentana() {
                ventana.classList.add('hidden');
                ventana.classList.remove('flex');
            }

            function mostrarFormulario(formulario, url) {
                if (url) {
                    formulario.action = url;
                    formulario.classList.remove('hidden');
                } else {
                    formulario.classList.add('hidden');
                }
            }

            function estilizarBadge(estado) {
                var est = (estado || '').toLowerCase().trim();
                badgeEstado.className = 'text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider ';

                if (est === 'pendiente') {
                    badgeEstado.className += 'bg-amber-500/20 text-amber-300 border border-amber-500/40';
                } else if (est === 'confirmada') {
                    badgeEstado.className += 'bg-blue-500/20 text-blue-300 border border-blue-500/40';
                } else if (est === 'iniciada') {
                    badgeEstado.className += 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
                } else if (est === 'finalizada') {
                    badgeEstado.className += 'bg-gray-500/20 text-gray-300 border border-gray-500/40';
                } else if (est === 'cancelada') {
                    badgeEstado.className += 'bg-red-500/20 text-red-300 border border-red-500/40';
                } else {
                    badgeEstado.className += 'bg-gray-700 text-gray-200';
                }
                badgeEstado.textContent = estado;
            }

            document.getElementById('cerrar-ventana').addEventListener('click', cerrarVentana);

            ventana.addEventListener('click', function(e) {
                if (e.target === ventana) {
                    cerrarVentana();
                }
            });

            var calendario = new FullCalendar.Calendar(document.getElementById('calendario'), {
                locale: 'es',
                initialView: 'timeGridWeek',
                height: 'calc(100vh - 195px)', // Se ajusta exactamente al alto de la pantalla del monitor
                nowIndicator: true,
                allDaySlot: false,
                slotMinTime: '07:00:00',      // Horario comercial de peluquería
                slotMaxTime: '21:00:00',      // Termina a las 9:00 PM
                scrollTime: '08:00:00',       // La vista salta automáticamente a las 8:00 AM
                slotDuration: '00:30:00',
                expandRows: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay,dayGridMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    week: 'Semana',
                    day: 'Día',
                    month: 'Mes'
                },
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },
                events: "{{ route('agenda.eventos') }}",
                eventClick: function (info) {
                    var cita = info.event.extendedProps;

                    document.getElementById('det-cliente').textContent = cita.cliente || 'No especificado';
                    document.getElementById('det-servicios').textContent = cita.servicios || 'Sin servicios';
                    document.getElementById('det-estado').textContent = cita.estado || '';
                    document.getElementById('det-programada').textContent = cita.programada || 'N/A';
                    document.getElementById('det-inicio').textContent = cita.inicio || 'No iniciada';
                    document.getElementById('det-fin').textContent = cita.fin || 'No finalizada';

                    estilizarBadge(cita.estado);

                    mostrarFormulario(formIniciar, cita.urlIniciar);
                    mostrarFormulario(formFinalizar, cita.urlFinalizar);

                    ventana.classList.remove('hidden');
                    ventana.classList.add('flex');
                }
            });

            calendario.render();
        });
    </script>
@endpush