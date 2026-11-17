@extends('layouts.app')

@section('title')

    Nuevo Pago
    
@endsection

@section('content')

    <div class="max-w-3xl mx-auto">

        <div class="bg-white shadow-lg rounded-xl p-4 sm:p-5">

            <h2 class="text-xl font-bold text-center mb-3">
                Nuevo Pago
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

            <form action="{{ route('pagos.store') }}" method="post">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2.5">

                    <div class="md:col-span-2">
                        <label for="cita_id" class="block text-xs font-semibold mb-1">Cita a Cobrar</label>
                        <select id="cita_id" name="cita_id" class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('cita_id') border-red-500 @enderror">
                            <option value="" data-total="">Seleccione una cita</option>
                            @foreach($citas as $cita)
                                <option value="{{ $cita->id }}"
                                    data-total="{{ $cita->total }}"
                                    @selected(old('cita_id', $citaSeleccionada ?? '') == $cita->id)>
                                    #{{ $cita->id }} - {{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}
                                    ({{ $cita->fecha_programada->format('d/m/Y h:i A') }})
                                </option>
                            @endforeach
                        </select>
                        @error('cita_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="monto" class="block text-xs font-semibold mb-1">Monto a Pagar</label>
                        <input type="text" id="monto" readonly placeholder="Seleccione una cita"
                            class="w-full border rounded-lg px-2.5 py-1.5 text-sm bg-gray-100 text-green-700 font-bold">
                    </div>

                    <div>
                        <label for="metodo_pago" class="block text-xs font-semibold mb-1">Método de Pago</label>
                        <select id="metodo_pago" name="metodo_pago" class="w-full border rounded-lg px-2.5 py-1.5 text-sm">
                            <option value="efectivo" @selected(old('metodo_pago') === 'efectivo')>Efectivo</option>
                            <option value="transferencia" @selected(old('metodo_pago') === 'transferencia')>Transferencia</option>
                        </select>
                    </div>

                    <div>
                        <label for="estado_pago" class="block text-xs font-semibold mb-1">Estado del Pago</label>
                        <select id="estado_pago" name="estado_pago" class="w-full border rounded-lg px-2.5 py-1.5 text-sm">
                            <option value="pendiente" @selected(old('estado_pago') === 'pendiente')>Pendiente</option>
                            <option value="pagado" @selected(old('estado_pago', 'pagado') === 'pagado')>Pagado</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-2 md:col-span-2">
                        <a href="{{ route('pagos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white rounded-lg px-4 py-1.5 text-sm">
                            Volver
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-5 py-1.5 text-sm">
                            Registrar Pago
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <script>
        const selectCita = document.getElementById('cita_id');
        const inputMonto = document.getElementById('monto');

        function mostrarMonto() {
            const total = selectCita.selectedOptions[0].dataset.total;

            inputMonto.value = total
                ? '$ ' + Number(total).toLocaleString('es-CO')
                : '';
        }

        selectCita.addEventListener('change', mostrarMonto);
        mostrarMonto();
    </script>

@endsection