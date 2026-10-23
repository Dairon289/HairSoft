@extends('layouts.app')

@section('title')

    Nuevo Pago
    
@endsection

@section('content')

    <div class="container mx-auto mt-10">

        <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

            <h2 class="text-3xl font-bold text-center mb-6">
                Nuevo Pago
            </h2>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pagos.store') }}" method="post">
                @csrf

                <div class="mb-5">
                    <label for="cita_id" class="block mb-2 font-semibold">Cita</label>
                    <select id="cita_id" name="cita_id" class="w-full border rounded px-3 py-2">
                        <option value="" data-total="">Seleccione una cita</option>
                        @foreach($citas as $cita)
                            <option value="{{ $cita->id }}"
                                data-total="{{ $cita->total }}"
                                @selected(old('cita_id') == $cita->id)>
                                #{{ $cita->id }} - {{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}
                                ({{ $cita->fecha_programada->format('d/m/Y h:i A') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                
                <div class="mb-5">
                    <label for="monto" class="block mb-2 font-semibold">Monto</label>
                    <input type="text" id="monto" readonly placeholder="Seleccione una cita"
                        class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-700">
                </div>

                <div class="mb-5">
                    <label for="metodo_pago" class="block mb-2 font-semibold">Método de Pago</label>
                    <select id="metodo_pago" name="metodo_pago" class="w-full border rounded px-3 py-2">
                        <option value="efectivo" @selected(old('metodo_pago') === 'efectivo')>Efectivo</option>
                        <option value="transferencia" @selected(old('metodo_pago') === 'transferencia')>Transferencia</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label for="estado_pago" class="block mb-2 font-semibold">Estado del Pago</label>
                    <select id="estado_pago" name="estado_pago" class="w-full border rounded px-3 py-2">
                        <option value="pendiente" @selected(old('estado_pago') === 'pendiente')>Pendiente</option>
                        <option value="pagado" @selected(old('estado_pago') === 'pagado')>Pagado</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                        Guardar Pago
                    </button>
                    <a href="{{ route('pagos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white rounded px-5 py-2">
                        Volver
                    </a>
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