@extends('layouts.app')

@section('title')

    Editar Empleado

@endsection

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white shadow-lg rounded-xl p-4 sm:p-5">

        <h2 class="text-xl font-bold text-center mb-3">
            Editar Empleado
        </h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-lg mb-3 text-xs">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('empleados.update', $empleado->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2.5">

                <div>
                    <label for="nombreEmpleado" class="block text-xs font-semibold mb-1">Nombre Empleado</label>
                    <input type="text" id="nombreEmpleado" name="nombreEmpleado"
                        value="{{ old('nombreEmpleado', $empleado->nombreEmpleado) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('nombreEmpleado') border-red-500 @enderror">
                </div>

                <div>
                    <label for="apellidoEmpleado" class="block text-xs font-semibold mb-1">Apellido Empleado</label>
                    <input type="text" id="apellidoEmpleado" name="apellidoEmpleado"
                        value="{{ old('apellidoEmpleado', $empleado->apellidoEmpleado) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('apellidoEmpleado') border-red-500 @enderror">
                </div>

                <div>
                    <label for="correoEmpleado" class="block text-xs font-semibold mb-1">Correo Empleado</label>
                    <input type="email" id="correoEmpleado" name="correoEmpleado"
                        value="{{ old('correoEmpleado', $empleado->correoEmpleado) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('correoEmpleado') border-red-500 @enderror">
                </div>

                <div>
                    <label for="telefonoEmpleado" class="block text-xs font-semibold mb-1">Teléfono Empleado</label>
                    <input type="text" id="telefonoEmpleado" name="telefonoEmpleado"
                        value="{{ old('telefonoEmpleado', $empleado->telefonoEmpleado) }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('telefonoEmpleado') border-red-500 @enderror">
                </div>

                <div>
                    <label for="fechaIngreso" class="block text-xs font-semibold mb-1">Fecha Ingreso</label>
                    <input type="date" id="fechaIngreso" name="fechaIngreso"
                        value="{{ old('fechaIngreso', $empleado->fechaIngreso ? \Carbon\Carbon::parse($empleado->fechaIngreso)->format('Y-m-d') : '') }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('fechaIngreso') border-red-500 @enderror">
                </div>

                <div>
                    <label for="disponibilidad" class="block text-xs font-semibold mb-1">Disponibilidad</label>
                    <select id="disponibilidad" name="disponibilidad" class="w-full border rounded-lg px-2.5 py-1.5 text-sm">
                        <option value="disponible" @selected(old('disponibilidad', $empleado->disponibilidad) === 'disponible')>Disponible</option>
                        <option value="no disponible" @selected(old('disponibilidad', $empleado->disponibilidad) === 'no disponible')>No Disponible</option>
                    </select>
                </div>

                <div>
                    <label for="horaIngreso" class="block text-xs font-semibold mb-1">Hora Ingreso</label>
                    <input type="time" id="horaIngreso" name="horaIngreso"
                        value="{{ old('horaIngreso', $empleado->horaIngreso ? \Carbon\Carbon::parse($empleado->horaIngreso)->format('H:i') : '') }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('horaIngreso') border-red-500 @enderror">
                </div>

                <div>
                    <label for="horaSalida" class="block text-xs font-semibold mb-1">Hora Salida</label>
                    <input type="time" id="horaSalida" name="horaSalida"
                        value="{{ old('horaSalida', $empleado->horaSalida ? \Carbon\Carbon::parse($empleado->horaSalida)->format('H:i') : '') }}"
                        class="w-full border rounded-lg px-2.5 py-1.5 text-sm @error('horaSalida') border-red-500 @enderror">
                </div>

                <div class="md:col-span-2">
                    <label for="estadoEmpleado" class="block text-xs font-semibold mb-1">Estado Empleado</label>
                    <select id="estadoEmpleado" name="estadoEmpleado" class="w-full border rounded-lg px-2.5 py-1.5 text-sm">
                        <option value="activo" @selected(old('estadoEmpleado', $empleado->estadoEmpleado) === 'activo')>Activo</option>
                        <option value="inactivo" @selected(old('estadoEmpleado', $empleado->estadoEmpleado) === 'inactivo')>Inactivo</option>
                    </select>
                </div>

                <div class="md:col-span-2"
                     x-data="selectorServiciosEmpleado(@js($servicios->map(fn ($s) => ['id' => $s->id, 'nombre' => $s->nombreServicio])->values()), @js(old('servicios', $serviciosAsignados)))">
                    <label class="block text-xs font-semibold mb-1">Servicios Ofrecidos</label>

                    <div class="relative">
                        <button type="button" @click="abierto = !abierto"
                                class="w-full border rounded-lg px-2.5 py-1.5 text-left flex justify-between items-center bg-white text-sm @error('servicios') border-red-500 @enderror">
                            <span x-text="elegidos.length ? elegidos.length + ' servicio(s) seleccionado(s)' : 'Seleccionar servicios'"></span>
                            <span aria-hidden="true">&#9662;</span>
                        </button>

                        <div x-show="abierto" @click.outside="abierto = false" style="display: none;"
                             class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-48 overflow-y-auto">
                            @foreach($servicios as $servicio)
                                <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer text-sm">
                                    <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                           x-model="seleccionados" class="mr-2">
                                    {{ $servicio->nombreServicio }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                        <template x-for="s in elegidos" :key="s.id">
                            <span class="inline-flex items-center bg-blue-100 text-blue-800 rounded px-2 py-0.5 text-xs">
                                <span x-text="s.nombre"></span>
                                <button type="button" @click="quitar(s.id)" class="ml-1.5 font-bold hover:text-red-600" aria-label="Quitar servicio">&times;</button>
                            </span>
                        </template>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-2 md:col-span-2">
                    <a href="{{ route('empleados.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white rounded-lg px-4 py-1.5 text-sm">
                        Volver
                    </a>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-5 py-1.5 text-sm">
                        Guardar
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>
<script>
    function selectorServiciosEmpleado(servicios, seleccionados) {
        return {
            abierto: false,
            servicios: servicios,
            seleccionados: seleccionados.map(String),
            get elegidos() {
                return this.servicios.filter(s => this.seleccionados.includes(String(s.id)));
            },
            quitar(id) {
                this.seleccionados = this.seleccionados.filter(x => x !== String(id));
            }
        };
    }
</script>

@endsection