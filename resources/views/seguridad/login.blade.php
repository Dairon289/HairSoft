<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión - {{ config('app.name') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-stone-100 via-white to-indigo-100 text-slate-800">

    <main class="min-h-screen flex items-center justify-center
            p-4 bg-gradient-to-br from-slate-900
            via-slate-800 to-stone-900">

        <div class="w-full max-w-md">

            <header class="text-center mb-8">

                <div class="mx-auto mb-4 flex h-16 w-16
                            items-center justify-center
                            rounded-full bg-indigo-600
                            shadow-lg shadow-indigo-500/20">

                    <span class="text-3xl font-bold text-white">
                        H
                    </span>

                </div>

                <h1 class="text-3xl font-bold tracking-wide text-white">
                    {{ config('app.name') }}
                </h1>

                <p class="mt-2 text-sm text-slate-300">
                    Gestión de belleza y bienestar
                </p>

            </header>

            <div class="bg-white rounded-2xl border border-slate-200
                    shadow-2xl p-8 sm:p-9">

                <div class="mb-7">

                    <h2 class="text-2xl font-bold text-slate-900">
                        Bienvenido
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        Ingresa tus credenciales para acceder a HairSoft
                    </p>

                </div>


                <form action="{{ route('login.store') }}"
                      method="POST"
                      class="space-y-5">

                    @csrf


                <div>
                    <label for="email"
                        class="block text-sm font-semibold text-slate-700 mb-2">

                        Correo electrónico

                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="ejemplo@correo.com"
                        required
                        autofocus
                        autocomplete="email"

                        class="w-full bg-slate-50 border border-slate-200
                            rounded-xl px-4 py-3
                            text-sm text-slate-800
                            placeholder:text-slate-400
                            focus:outline-none
                            focus:border-indigo-500
                            focus:ring-2 focus:ring-indigo-100
                            @error('email') border-red-500 @enderror"
                    >

                    @error('email')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror
                </div>



                <div>
                    <label for="password"
                        class="block text-sm font-semibold text-slate-700 mb-2">

                        Contraseña

                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="current-password"

                        class="w-full bg-slate-50 border border-slate-200
                            rounded-xl px-4 py-3
                            text-sm text-slate-800
                            placeholder:text-slate-400
                            focus:outline-none
                            focus:border-indigo-500
                            focus:ring-2 focus:ring-indigo-100
                            @error('password') border-red-500 @enderror"
                    >

                    @error('password')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                    <label class="flex items-center gap-2
                                  text-sm text-slate-600">

                        <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded border-slate-300 text-indigo-600"
                        >

                        Recordarme

                    </label>
                   
                    <button
                        type="submit"

                        class="w-full rounded-xl bg-indigo-600
                            py-3 font-semibold text-white
                            shadow-md shadow-indigo-200
                            transition duration-200
                            hover:bg-indigo-700
                            hover:shadow-lg
                            active:scale-[0.98]"
                    >

                        Ingresar

                    </button>

                </form>
            </div>
            <footer class="mt-6 text-center">

                <p class="text-xs text-slate-400">
                    HairSoft · Gestión de belleza
                </p>

            </footer>
        </div>
    </main>
</body>
</html>
