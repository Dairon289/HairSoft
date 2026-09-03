@extends('layouts.app')


@section('title')
    TITULO
@endsection


@section('content')


<x-card>

    <div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">

            Nuevo servicio

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

        
    
        <form action="{{route ('servicio.store')}}" method="post">

            @csrf
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Nombre servicio</label>
                <input type="text" name="nombreServicio"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Duracion servicio</label>
                <input type="time" name="duracionServicio"  class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Descripcion Servicio</label>
                <input type="text" name="descripcionServicio"  class="w-full border rounded px-3 py-2">
            </div>            
            

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Precio Servicio</label>
                <input type="number" name="precioServicio"  class="w-full border rounded px-3 py-2">
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
                    Guardar servicio
                </button>
            </div>

        </form>

    </div>

</div>

</x-card>

@endsection