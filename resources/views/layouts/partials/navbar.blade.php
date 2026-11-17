<header class="sticky top-0 z-20 hs-header">

    <div class="h-20 px-4 sm:px-6 lg:px-8
                flex items-center justify-between gap-4">

        <!-- Título y menú móvil -->
        <div class="flex items-center gap-4 min-w-0">

            <button
                @click="sidebarOpen = !sidebarOpen"
                type="button"
                class="lg:hidden p-2 rounded-lg hs-icono-btn"
                aria-label="Abrir menú lateral"
            >
                <span class="w-6 h-6 block">
                    @include('layouts.partials.icons', ['icon' => 'menu'])
                </span>
            </button>

            <div>
                <h1 class="hs-titulo text-xl sm:text-2xl truncate">
                    {{ trim($__env->yieldContent('page-title')) ?: (trim($__env->yieldContent('title')) ?: 'Dashboard') }}
                </h1>

                <p class="hidden sm:block text-xs mt-1 hs-humo">
                    Bienvenido al sistema HairSoft
                </p>
            </div>

        </div>


        <!-- Acciones de usuario -->
        <div class="flex items-center gap-2 sm:gap-4">

            <!-- Notificaciones -->
            <div x-data="{ open: false }" class="relative">

                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    type="button"

                    class="relative p-2.5 rounded-xl
                           hs-icono-btn transition"

                    aria-label="Ver notificaciones"
                >

                    <span class="w-5 h-5 block">
                        @include('layouts.partials.icons', ['icon' => 'bell'])
                    </span>

                </button>


                <div
                    x-show="open"
                    x-cloak
                    x-transition

                    class="absolute right-0 mt-3 w-80
                           rounded-xl shadow-xl overflow-hidden hs-menu"
                >

                    <div class="px-4 py-3 border-b hs-menu-borde
                                font-semibold text-sm hs-texto">

                        Notificaciones

                    </div>

                    <ul class="max-h-72 overflow-y-auto hs-menu-lista">

                        @forelse (($notifications ?? []) as $notification)

                            <li class="px-4 py-3 text-sm hs-menu-item">

                                <p>
                                    {{ $notification['message'] }}
                                </p>

                                <p class="text-xs mt-1 hs-humo">
                                    {{ $notification['time'] }}
                                </p>

                            </li>

                        @empty

                            <li class="px-4 py-6 text-sm text-center hs-humo">
                                Sin notificaciones nuevas
                            </li>

                        @endforelse

                    </ul>

                </div>

            </div>


            <!-- Perfil del usuario -->
            <div x-data="{ open: false }" class="relative">

                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    type="button"

                    class="flex items-center gap-2
                           px-2 py-1.5 rounded-xl
                           hs-icono-btn transition"

                    aria-haspopup="true"
                    :aria-expanded="open.toString()"
                >

                    <img
                        class="w-9 h-9 rounded-full object-cover hs-avatar"

                        src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?background=C9A24D&color=14171A&bold=true&name=' . urlencode(auth()->user()->name ?? 'Admin') }}"

                        alt="Avatar de {{ auth()->user()->name ?? 'Usuario' }}"
                    >

                    <span class="hidden sm:block text-left">

                        <span class="block text-sm font-semibold hs-texto">
                            {{ auth()->user()->name ?? 'Administrador' }}
                        </span>

                        <span class="block text-xs hs-humo">
                            Usuario
                        </span>

                    </span>

                    <span class="w-4 h-4 hs-humo hidden sm:block">
                        @include('layouts.partials.icons', ['icon' => 'chevron-down'])
                    </span>

                </button>


                <!-- Opciones del perfil -->
                <div
                    x-show="open"
                    x-cloak
                    x-transition

                    class="absolute right-0 mt-3 w-52
                           rounded-xl shadow-xl overflow-hidden py-1 hs-menu"
                >

                    <a
                        href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}"
                        class="block px-4 py-3 text-sm hs-menu-item"
                    >
                        Mi perfil
                    </a>

                    @if(Route::has('settings.index'))
                        <a
                            href="{{ route('settings.index') }}"
                            class="block px-4 py-3 text-sm hs-menu-item"
                        >
                            Configuración
                        </a>
                    @endif

                    <hr class="my-1 hs-menu-borde">

                    <form method="POST"
                          action="{{ Route::has('logout') ? route('logout') : '#' }}">

                        @csrf

                        <button
                            type="submit"
                            class="w-full text-left px-4 py-3
                                   text-sm hs-menu-item hs-menu-item--salir"
                        >
                            Cerrar sesión
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>