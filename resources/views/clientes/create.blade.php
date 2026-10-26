@extends('layouts.app')


@section('title')

    Crear Cliente
    
@endsection


@section('content')


<x-card>

    <div class="container mx-auto mt-10">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-3xl font-bold text-center mb-6">

            Nuevo cliente

        </h2>

    
        <form action="{{route ('cliente.store')}}" method="post">

            @csrf
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Nombre Cliente</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border rounded px-3 py-2
                @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class= "mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror 
            </div>

            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Apellido Cliente</label>
                <input type="text" name="apellido" value="{{ old('apellido')}}" class="w-full border rounded px-3 py-2 
                @error('apellido') border-red-500 @enderror">
                @error('apellido')
                    <p class= "mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="correo" class="block mb-2 font-semibold">Correo</label>
                <input type="email" name="correo" id="correo" value="{{ old('correo')}}" class="w-full border rounded px-3 py-2 @error('correo') border-red-500 @enderror" placeholder="ejemplo@gmail.com" required>
                @error('correo')
                    <p class= "mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-5">
                <label for="" class="block mb-2 font-semibold ">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class= "mt-1 text-sm text-red-600" >{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Teléfono</label>
                <input type="number" name="telefono" value="{{ old('telefono')}}" class="w-full border rounded px-3 py-2 @error('telefono') border-red-500 @enderror">
                @error('telefono')
                    <p class= "mt-1 text-sm text-red-600" >{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block mb-2 font-semibold">Rol</label>
                <select name="rol" class="w-full border rounded px-3 py-2 @error('rol') border-red-500 @enderror">
                    <option value="cliente" @selected(old('rol') === 'cliente')>Cliente</option>
                    <option value="administrador" @selected(old('rol') === 'administrador')>Administrador</option>
                </select>
                @error('rol')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="estado" class="block mb-2 font-semibold">Estado Cliente</label>
                <select id="estado" name="estado" class="w-full border rounded px-3 py-2 @error('estado') border-red-500 @enderror">
                    <option value="activo" @selected(old('estado') === 'activo')>Activo</option>
                    <option value="inactivo" @selected(old('estado') === 'inactivo')>Inactivo</option>
                </select>
                @error('estado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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