{{-- Set de iconos SVG (Heroicons outline, inline para no depender de paquetes extra) --}}

@switch($icon)

    @case('scissors')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                class="w-full h-full">
            <circle cx="6" cy="6" r="3" stroke-width="2"/>
            <circle cx="6" cy="18" r="3" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M8.2 8.2L20 20M8.2 15.8L20 4"/>
        </svg>
        @break

    @case('calendar')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                class="w-full h-full">
            <rect x="3" y="5" width="18" height="16" rx="2"
                    stroke-width="2"/>
            <path stroke-linecap="round" stroke-width="2"
                    d="M16 3v4M8 3v4M3 10h18"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M8 14h3v3"/>
        </svg>
        @break

    @case('user-group')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                class="w-full h-full">
            <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
            <circle cx="10" cy="7" r="4" stroke-width="2"/>
            <path stroke-linecap="round" stroke-width="2"
                    d="M20 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
        @break

    @case('id-card')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                class="w-full h-full">
            <rect x="3" y="4" width="18" height="16" rx="2"
                    stroke-width="2"/>
            <circle cx="9" cy="10" r="2" stroke-width="2"/>
            <path stroke-linecap="round" stroke-width="2"
                    d="M6 16a3 3 0 016 0m3-7h3m-3 4h3"/>
        </svg>
        @break

    @case('shield-user')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                class="w-full h-full">
            <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <circle cx="12" cy="9" r="2" stroke-width="2"/>
            <path stroke-linecap="round" stroke-width="2"
                    d="M8.5 15a4 4 0 017 0"/>
        </svg>
        @break

    @case('credit-card')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                class="w-full h-full">
            <rect x="2" y="5" width="20" height="14" rx="2"
                    stroke-width="2"/>
            <path stroke-width="2" d="M2 10h20"/>
            <path stroke-linecap="round" stroke-width="2"
                    d="M6 15h4"/>
        </svg>
        @break

    @case('user-settings')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                class="w-full h-full">
            <circle cx="9" cy="8" r="4" stroke-width="2"/>
            <path stroke-linecap="round" stroke-width="2"
                    d="M2 21v-2a7 7 0 0110-6.32"/>
            <circle cx="18" cy="17" r="3" stroke-width="2"/>
            <path stroke-linecap="round" stroke-width="2"
                    d="M18 12v2m0 6v2m5-5h-2m-6 0h-2"/>
        </svg>
        @break

    {{-- ===== Íconos anteriores (restaurados) ===== --}}
    @case('home')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" /></svg>
        @break
    @case('users')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-5.13a4 4 0 100-8 4 4 0 000 8zm6 3a4 4 0 10-8 0" /></svg>
        @break
    @case('shield')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-1a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @break
    @case('tag')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-7.586 7.586a1 1 0 01-1.414 0L4.293 12.293A1 1 0 014 11.586V6a3 3 0 013-3z" /></svg>
        @break
    @case('box')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
        @break
    @case('chart')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
        @break
    @case('cog')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        @break
    @case('search')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
        @break
    @case('bell')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        @break
    @case('menu')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        @break
    @case('chevron-down')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
        @break
    @case('eye')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
        @break
    @case('pencil')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        @break
    @case('trash')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 4h5a1 1 0 011 1v2h-7V5a1 1 0 011-1z" /></svg>
        @break
    @case('x-circle')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @break

    @default
        <span class="text-gray-400">?</span>
@endswitch