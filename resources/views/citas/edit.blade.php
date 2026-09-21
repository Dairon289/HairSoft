<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Cita</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">

            Nueva Cita
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

        
        <form action="{{route ('citas.update', $citas->id)}}" method="post">

            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Cliente</label>
                <select name="cliente_id" class="w-full border rounded px-3 py-2">
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }} {{ $cliente->apellido }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Empleado</label>
                <select name="empleado_id" class="w-full border rounded px-3 py-2">
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->nombreEmpleado }} {{ $empleado->apellidoEmpleado }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-5">
                <label class="block mb-2 font-semibold">
                    Fecha programada
                </label>

                <input 
                    type="datetime-local" 
                    id="fecha_programada"
                    name="fecha_programada"
                    value="{{ old('fecha_programada', $citas->fecha_programada) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
            </div>
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Fecha de Inicio</label>
                <input 
                    type="datetime-local"
                    name="fecha_inicio"
                    value="{{ old('fecha_inicio', $citas->fecha_inicio) }}"  
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Fecha fin</label>
                <input
                    type="datetime-local"
                    name="fecha_fin"
                    value="{{ old('fecha_fin', $citas->fecha_fin) }}"  
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Estado Cita</label>
                
                <select name="estado">
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="cancelada">cancelada</option>
                    <option value="iniciada">Iniciada</option>
                    <option value="finalizada">Finalizada</option>
                </select>

            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Servicios</label>
                @foreach($servicios as $servicio)
                    <div>
                        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" id="servicio_{{ $servicio->id }}">
                        <label for="servicio_{{ $servicio->id }}">{{ $servicio->nombreServicio }}</label>
                    </div>
                @endforeach
            </div>


            <div>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                    Guardar
                </button>
            </div>

        </form>

    </div>

</div>

</body>

</html>