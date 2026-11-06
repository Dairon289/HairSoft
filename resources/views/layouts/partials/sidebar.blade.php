{{--
    Sidebar principal de HairSoft.
    Organiza las opciones por grupos y resalta la ruta activa.
--}}
<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-950
           text-slate-300 transform transition-transform
           duration-200 ease-in-out lg:translate-x-0"
    aria-label="Menú principal"
>
    <div class="h-full flex flex-col">

        <!-- Logo -->
        <div class="flex items-center gap-3 h-20 px-6 border-b border-slate-800">

            <div class="w-10 h-10 rounded-xl bg-primary-600
                        flex items-center justify-center
                        text-white text-lg font-bold shadow-md">
                H
            </div>

            <span class="text-white font-bold text-xl tracking-tight">
                {{ config('app.name', 'HairSoft') }}
            </span>

        </div>

        <!-- Navegación -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-7"
             aria-label="Navegación lateral">

            @php
                $groups = [
                    [
                        'title' => 'PRINCIPAL',
                        'links' => [
                            ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'home'],
                        ],
                    ],
                    [
                        'title' => 'GESTIÓN',
                        'links' => [
                            ['label' => 'Servicios', 'route' => 'servicio.index', 'icon' => 'scissors', 'can' => ['ver servicios']],
                            ['label' => 'Citas', 'route' => 'citas.index', 'icon' => 'calendar', 'can' => ['ver citas']],
                            ['label' => 'Clientes', 'route' => 'cliente.index', 'icon' => 'user-group', 'can' => ['ver clientes']],
                            ['label' => 'Empleados', 'route' => 'empleados.index', 'icon' => 'id-card', 'can' => ['ver empleados']],
                            ['label' => 'Pagos', 'route' => 'pagos.index', 'icon' => 'credit-card', 'can' => ['ver pagos']],
                        ],
                    ],

                    [
                        'title' => 'ADMINISTRACIÓN',
                        'links' => [
                            [
                                'label' => 'Perfiles y roles',
                                'route' => 'seguridad.roles.index',
                                'icon' => 'shield-user',
                                'can' => ['gestionar seguridad'],
                            ],
                            [
                                'label' => 'Configuración',
                                'route' => 'settings.index',
                                'icon' => 'cog',
                                'can' => ['gestionar seguridad'],
                            ],
                            [
                                'label' => 'Gestión de usuarios',
                                'route' => 'seguridad.permisos.index',
                                'icon' => 'user-settings',
                                'can' => ['gestionar seguridad'],
                            ],
                        ],
                    ],
                ];
            @endphp

            @foreach ($groups as $group)

                @php
                    $visibleLinks = collect($group['links'])
                        ->filter(function ($link) {
                            $routeExists = Route::has($link['route']);

                            $hasPermission = empty($link['can'])
                                || (auth()->check() && auth()->user()->canAny($link['can']));

                            return $routeExists && $hasPermission;
                        });
                @endphp

                @if ($visibleLinks->isNotEmpty())

                    <div class="space-y-2">

                        <!-- Nombre del grupo -->
                        <p class="px-3 text-xs font-bold tracking-wider text-slate-500">
                            {{ $group['title'] }}
                        </p>

                        @foreach ($visibleLinks as $link)

                            @php
                                $active = request()->routeIs(
                                    explode('.', $link['route'])[0] . '.*'
                                );
                            @endphp

                            <a
                                href="{{ route($link['route']) }}"
                                @click="sidebarOpen = false"
                                @if ($active) aria-current="page" @endif
                                class="group flex items-center gap-3
                                       px-4 py-3 rounded-xl text-sm
                                       font-medium transition duration-200
                                       {{ $active
                                            ? 'bg-primary-600 text-white shadow-md shadow-primary-900/30'
                                            : 'text-slate-400 hover:bg-slate-800 hover:text-white'
                                       }}"
                            >

                                <span class="w-5 h-5 flex-shrink-0"
                                      aria-hidden="true">
                                    @include('layouts.partials.icons', [
                                        'icon' => $link['icon']
                                    ])
                                </span>

                                <span>{{ $link['label'] }}</span>

                            </a>

                        @endforeach

                    </div>

                @endif

            @endforeach

        </nav>

        <!-- Cerrar sesión -->
        <div class="px-4 py-5 border-t border-slate-800">

            @if (Route::has('logout'))

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3
                               rounded-xl text-sm font-medium
                               text-slate-400 hover:bg-red-500/10
                               hover:text-red-400 transition duration-200
                               focus:outline-none focus:ring-2
                               focus:ring-primary-500"
                        aria-label="Cerrar sesión"
                    >

                        <svg class="w-5 h-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>

                        </svg>

                        Cerrar sesión

                    </button>

                </form>

            @endif

        </div>
    </div>
</aside>
