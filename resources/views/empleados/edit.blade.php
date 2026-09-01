<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Empleado</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">

            Nuevo Empleado
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

        
        <form action="{{route ('empleados.update', $empleado->id)}}" method="post">

            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Nombre Empleado</label>
                <input type="text" name="nombreEmpleado" value="{{$empleado->nombreEmpleado}}"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Apellido Empleado</label>
                <input type="text" name="apellidoEmpleado" value="{{ $empleado->apellidoEmpleado}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Correo empleado</label>
                <input type="text" name="correoEmpleado" value="{{ $empleado->correoEmpleado}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Especialidad</label>
                <input type="text" name="especialidad" value="{{ $empleado->especialidad}}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Teléfono empleado</label>
                <input type="number" name="telefonoEmpleado" value="{{ $empleado->telefonoEmpleado}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Disponibilidad </label>
                
                <select name="disponiblidad">
                    <option value="disponible">Disponible</option>
                    <option value="no disponible">No Disponible</option>
                </select>
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Estado empleado</label>
                
                <select name="estadoEmpleado">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Servicios que ofrece</label>
                @foreach ($servicios as $servicio)
                    <div>
                        <input 
                            type="checkbox" 
                            name="servicios[]" 
                            value="{{ $servicio->id }}"
                            id="servicio_{{ $servicio->id }}"
                            {{ in_array($servicio->id, $serviciosAsignados) ? 'checked' : '' }}
                        >
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