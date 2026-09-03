<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Servicio</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">

            Nuevo Servicio
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

        
        <form action="{{route ('servicio.update', $servicios->id)}}" method="post">

            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Nombre Servicio</label>
                <input type="text" name="nombreServicio" value="{{$servicios->nombreServicio}}"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Duracion servicio</label>
                <input type="time" name="duracionServicio" value="{{ $servicios->duracionServicio}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Descripcion Servicio</label>
                <input type="text" name="descripcionServicio" value="{{ $servicios->descripcionServicio}}"class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Precio Servicio</label>
                <input type="number" name="precioServicio" value="{{ $servicios->precioServicio}}" class="w-full border rounded px-3 py-2">
            </div>

        
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Estado servicio</label>
                
                <select name="estadoServicio">
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