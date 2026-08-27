<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Cliente</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">

            Nuevo Cliente
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

        
        <form action="{{route ('cliente.update', $cliente->id)}}" method="post">

            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Nombre Cliente</label>
                <input type="text" name="nombre" value="{{$cliente->nombre}}"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Apellido Cliente</label>
                <input type="text" name="apellido" value="{{ $cliente->apellido}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Correo cliente</label>
                <input type="text" name="correo" value="{{ $cliente->correo}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" placeholder="Dejar en blanco para no cambiarla">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Teléfono Cliente</label>
                <input type="text" name="telefono" value="{{ $cliente->telefono}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Rol Cliente</label>
        
                <select name="rol">
                    <option value="cliente">Cliente</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Estado Cliente</label>
                
                <select name="estado">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
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