@extends('layouts.app')

@section('title')
    Editar Pago
@endsection

@section('content')

    <div class="container mx-auto mt-4">

        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">

            <h2 class="text-3xl font-bold text-center mb-6">
                Editar Pago
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

            <form action="{{ route('pagos.update', $pago->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <p class="block mb-2 font-semibold">Cita</p>
                    <p class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-700">
                        #{{ $pago->cita->id }} - {{ $pago->cita->cliente->nombre }} {{ $pago->cita->cliente->apellido }}
                        ({{ $pago->cita->fecha_programada->format('d/m/Y h:i A') }})
                    </p>
                </div>

                <div class="mb-5">
                    <p class="block mb-2 font-semibold">Monto</p>
                    <p class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-700">
                        $ {{ number_format($pago->monto, 0, ',', '.') }}
                    </p>
                </div>

                <div class="mb-5">
                    <label for="metodo_pago" class="block mb-2 font-semibold">Método de Pago</label>
                    <select id="metodo_pago" name="metodo_pago"
                        class="w-full border rounded px-3 py-2 @error('metodo_pago') border-red-500 @enderror">
                        <option value="efectivo" @selected(old('metodo_pago', $pago->metodo_pago) === 'efectivo')>Efectivo</option>
                        <option value="transferencia" @selected(old('metodo_pago', $pago->metodo_pago) === 'transferencia')>Transferencia</option>
                    </select>
                    @error('metodo_pago')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="estado_pago" class="block mb-2 font-semibold">Estado del Pago</label>
                    <select id="estado_pago" name="estado_pago"
                        class="w-full border rounded px-3 py-2 @error('estado_pago') border-red-500 @enderror">
                        <option value="pendiente" @selected(old('estado_pago', $pago->estado_pago) === 'pendiente')>Pendiente</option>
                        <option value="pagado" @selected(old('estado_pago', $pago->estado_pago) === 'pagado')>Pagado</option>
                    </select>
                    @error('estado_pago')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                        Guardar
                    </button>
                    <a href="{{ route('pagos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white rounded px-5 py-2">
                        Volver
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection