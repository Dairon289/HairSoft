@extends('layouts.app')


@section('title')
    TITULO
@endsection


@section('content')


<x-card>

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

        
    
        <form action="{{route ('empleados.store')}}" method="post">

            @csrf
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Nombre Empleado</label>
                <input type="text" name="nombreEmpleado"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Apellido Empleado</label>
                <input type="text" name="apellidoEmpleado"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="correo" class="block mb-2 font-semibold">Correo</label>
                <input type="email" name="correoEmpleado" id="correo" class="w-full border rounded px-3 py-2" placeholder="ejemplo@gmail.com" required>
            </div>
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Especialidad</label>
                <input type="text " name="especialidad"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Teléfono</label>
                <input type="number" name="telefonoEmpleado"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Fecha Ingreso</label>
                <input type="date" name="fechaIngreso"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Hora Ingreso</label>
                <input type="time" name="horaIngreso"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Hora Salida</label>
                <input type="time" name="horaSalida"  class="w-full border rounded px-3 py-2">
            </div>


            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Disponibilidad</label>
                
                <select name="disponibilidad">
                    <option value="disponible">Disponible</option>
                    <option value="no disponible">No Disponible</option>
                </select>

            </div>
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Estado</label>
                
                <select name="estadoEmpleado">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>

            </div>
            
    

            <div>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded px-5 py-2">
                    Guardar Cliente
                </button>
            </div>

        </form>

    </div>

</div>

</x-card>

@endsection