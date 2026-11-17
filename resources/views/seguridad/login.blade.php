<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión - {{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login-hairsoft.css') }}">
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="login-body">

    <main class="login-split-contenedor">

        <!-- LADO IZQUIERDO: Ambientación de Barbería Urbana y Marca -->
        <section class="login-lateral-banner">
            <div class="login-marca-header">
                <img src="{{ asset('img/logo-hairsoft.png') }}"
                     alt="Logo HairSoft"
                     class="login-marca-logo"
                     onerror="this.style.display='none'">
                <span class="login-marca-nombre">HairSoft</span>
            </div>

            <div class="login-banner-contenido">
                <h1 class="login-banner-titulo">
                    Todo tu salón, <br>
                    <span>en perfecta sincronía.</span>
                </h1>
                <p class="login-banner-desc">
                    Agenda interactiva, catálogo de servicios y control de turnos en una sola plataforma, sin complicaciones.
                </p>

                <ul class="login-beneficios-lista">
                    <li class="login-beneficio-item">
                        <svg class="login-beneficio-icono" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Citas sin cruce de horarios para cada profesional</span>
                    </li>
                    <li class="login-beneficio-item">
                        <svg class="login-beneficio-icono" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                        </svg>
                        <span>Servicios con precios congelados por reserva</span>
                    </li>
                    <li class="login-beneficio-item">
                        <svg class="login-beneficio-icono" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Acceso único con redirección según tu rol asignado</span>
                    </li>
                </ul>
            </div>

            <p class="login-banner-pie">
                HairSoft · Gestión integral para barberías y peluquerías
            </p>
        </section>

        <!-- LADO DERECHO: Formulario de Acceso -->
        <section class="login-lateral-form">
            <div class="login-caja-interna">

                <div class="login-form-cabecera">
                    <h2 class="login-form-titulo">Iniciar sesión</h2>
                    <p class="login-form-subtitulo">
                        Ingresa con el correo y contraseña asignados por tu establecimiento.
                    </p>
                </div>

                <form action="{{ route('login.post') }}" method="POST" id="form-login">
                    @csrf

                    <div class="login-campo">
                        <label for="email" class="login-label">Correo electrónico</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="ejemplo@correo.com"
                            required
                            autofocus
                            autocomplete="email"
                            class="login-input @error('email') is-invalid @enderror"
                        >
                        @error('email')
                            <p class="login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="login-campo">
                        <label for="password" class="login-label">Contraseña</label>
                        <div class="login-input-grupo">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                autocomplete="current-password"
                                class="login-input @error('password') is-invalid @enderror"
                            >
                            <button type="button" id="btn-toggle-password" class="login-btn-ojo" title="Mostrar/ocultar contraseña">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="login-recordar">
                        <input type="checkbox" name="remember" value="1">
                        Recordar mi correo en este equipo
                    </label>

                    <button type="submit" id="btn-login" class="login-boton" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span id="btn-login-texto">Ingresar</span>
                        <svg id="btn-login-spinner" style="display: none; width: 18px; height: 18px; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </button>

                    <a href="{{ url('/') }}" class="login-boton-secundario">
                        Regresar
                    </a>

                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="{{ route('registro') }}" style="color: #C9A24D; font-size: 0.9rem; text-decoration: none; font-weight: 600;">
                            ¿Eres cliente y no tienes cuenta? Regístrate aquí
                        </a>
                    </div>
                </form>

                <div class="login-aviso-seguridad">
                    ¿Olvidaste tu contraseña? Solicítale al administrador que te asigne una nueva.
                </div>

            </div>
        </section>

    </main>

    <script>
        document.getElementById('btn-toggle-password').addEventListener('click', function () {
            var pass = document.getElementById('password');
            pass.type = pass.type === 'password' ? 'text' : 'password';
        });

        document.getElementById('form-login').addEventListener('submit', function () {
            var btn = document.getElementById('btn-login');
            var texto = document.getElementById('btn-login-texto');
            var spinner = document.getElementById('btn-login-spinner');

            btn.disabled = true;
            btn.style.opacity = '0.75';
            btn.style.cursor = 'not-allowed';
            texto.textContent = 'Ingresando...';
            spinner.style.display = 'inline-block';
        });
    </script>

</body>
</html>