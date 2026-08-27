@extends('layouts.app')


@section('title')
    TITULO
@endsection


@section('content')


<x-card>

    <div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">

            Nuevo cliente

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

        
    
        <form action="{{route ('cliente.store')}}" method="post">

            @csrf
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Nombre Cliente</label>
                <input type="text" name="nombre"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Apellido Cliente</label>
                <input type="text" name="apellido"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="correo" class="block mb-2 font-semibold">Correo</label>
                <input type="email" name="correo" id="correo" class="w-full border rounded px-3 py-2" placeholder="ejemplo@gmail.com" required>
            </div>
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Contraseña</label>
                <input type="password" name="password"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Teléfono</label>
                <input type="number" name="telefono"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Rol</label>
                
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
                    Guardar Cliente
                </button>
            </div>

        </form>

    </div>

</div>

</x-card>

@endsection