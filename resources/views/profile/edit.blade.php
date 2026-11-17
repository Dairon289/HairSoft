@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 max-w-4xl text-[#ECE7DE]">

    {{-- Encabezado --}}
    <div class="pb-4 border-b border-[#363C44]">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-[#ECE7DE]">
            Mi Perfil
        </h1>
        <p class="text-xs sm:text-sm mt-1 text-[#9BA1A8]">
            Administra tu información personal, datos profesionales y seguridad de tu cuenta.
        </p>
    </div>

    {{-- Notificaciones de éxito --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border"
             style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); border-color: rgba(74, 160, 110, 0.35); color: var(--hs-ok-texto, #9BE0BA);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('success_password'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border"
             style="background: var(--hs-ok-fondo, rgba(74, 160, 110, 0.16)); border-color: rgba(74, 160, 110, 0.35); color: var(--hs-ok-texto, #9BE0BA);">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success_password') }}</span>
        </div>
    @endif

    {{-- Sección 1: Información Personal y Profesional --}}
    <div class="p-5 sm:p-6 rounded-2xl border shadow-sm"
         style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
        
        <div class="mb-5 pb-3 border-b border-[#2D333B] flex items-center justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-[#ECE7DE]">Datos de la Cuenta</h2>
                <p class="text-xs text-[#9BA1A8]">Actualiza tu nombre y correo electrónico de acceso.</p>
            </div>
            @if($empleado)
                <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                      style="background: rgba(201, 162, 77, 0.15); color: #C9A24D; border: 1px solid rgba(201, 162, 77, 0.35);">
                    Perfil de Empleado
                </span>
            @endif
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-[#9BA1A8]">
                        Nombre Completo
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" required
                           class="w-full text-xs sm:text-sm rounded-xl px-3.5 py-2.5 border transition focus:outline-none focus:border-[#C9A24D]"
                           style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                    @error('name')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Correo Electrónico --}}
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-[#9BA1A8]">
                        Correo Electrónico
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" required
                           class="w-full text-xs sm:text-sm rounded-xl px-3.5 py-2.5 border transition focus:outline-none focus:border-[#C9A24D]"
                           style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                    @error('email')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campos específicos de Empleado si aplica --}}
                @if($empleado)
                    <div>
                        <label for="telefono" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-[#9BA1A8]">
                            Teléfono de Contacto
                        </label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $empleado->telefonoEmpleado) }}"
                               placeholder="Ej: 3001234567"
                               class="w-full text-xs sm:text-sm rounded-xl px-3.5 py-2.5 border transition focus:outline-none focus:border-[#C9A24D]"
                               style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                        @error('telefono')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="especialidad" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-[#9BA1A8]">
                            Especialidad / Cargo
                        </label>
                        <input type="text" name="especialidad" id="especialidad" value="{{ old('especialidad', $empleado->especialidad) }}"
                               placeholder="Ej: Barbero Senior, Colorista..."
                               class="w-full text-xs sm:text-sm rounded-xl px-3.5 py-2.5 border transition focus:outline-none focus:border-[#C9A24D]"
                               style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                        @error('especialidad')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            <div class="pt-3 flex justify-end">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-md transition hover:brightness-105"
                        style="background: #C9A24D; color: #14171A;">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Sección 2: Seguridad y Cambio de Contraseña --}}
    <div class="p-5 sm:p-6 rounded-2xl border shadow-sm"
         style="background: var(--emp-panel, #1F2328); border-color: var(--emp-linea, #363C44);">
        
        <div class="mb-5 pb-3 border-b border-[#2D333B]">
            <h2 class="text-base sm:text-lg font-semibold text-[#ECE7DE]">Seguridad de la Cuenta</h2>
            <p class="text-xs text-[#9BA1A8]">Asegúrate de utilizar una contraseña segura y difícil de adivinar.</p>
        </div>

        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-4 max-w-lg">
                {{-- Contraseña Actual --}}
                <div>
                    <label for="current_password" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-[#9BA1A8]">
                        Contraseña Actual
                    </label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full text-xs sm:text-sm rounded-xl px-3.5 py-2.5 border transition focus:outline-none focus:border-[#C9A24D]"
                           style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                    @error('current_password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nueva Contraseña --}}
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-[#9BA1A8]">
                        Nueva Contraseña
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full text-xs sm:text-sm rounded-xl px-3.5 py-2.5 border transition focus:outline-none focus:border-[#C9A24D]"
                           style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                    @error('password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmar Contraseña --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-[#9BA1A8]">
                        Confirmar Nueva Contraseña
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full text-xs sm:text-sm rounded-xl px-3.5 py-2.5 border transition focus:outline-none focus:border-[#C9A24D]"
                           style="background: #14171A; border-color: #363C44; color: #ECE7DE;">
                </div>
            </div>

            <div class="pt-3 flex justify-end">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-md transition hover:brightness-105 border border-[#363C44] text-[#ECE7DE] hover:bg-[#252A32]"
                        style="background: #14171A;">
                    Actualizar Contraseña
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
