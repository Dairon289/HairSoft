<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - {{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login-hairsoft.css') }}">
</head>

<body class="login-body">

    <main class="login-split-contenedor">

        <!-- LADO IZQUIERDO -->
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
                    Únete a nosotros, <br>
                    <span>agenda tu propio turno.</span>
                </h1>
                <p class="login-banner-desc">
                    Crea tu cuenta en segundos para reservar tus servicios favoritos, conocer a nuestro equipo de barberos y gestionar tus citas desde cualquier lugar.
                </p>

                <ul class="login-beneficios-lista">
                    <li class="login-beneficio-item">
                        <svg class="login-beneficio-icono" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Reserva autónoma 24/7 sin esperas</span>
                    </li>
                    <li class="login-beneficio-item">
                        <svg class="login-beneficio-icono" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Historial de citas y facturas en línea</span>
                    </li>
                </ul>
            </div>
        </section>

        <!-- LADO DERECHO: FORMULARIO DE REGISTRO -->
        <section class="login-lateral-form" style="padding-top: 2rem; padding-bottom: 2rem;">
            <div class="login-caja-interna" style="max-width: 440px;">

                <div class="login-form-header" style=" margin-bottom: 1.5rem;">
                    <h2 class="login-form-titulo">Crear Cuenta</h2>
                    <p class="login-form-subtitulo">
                        Regístrate para comenzar a agendar tus citas
                    </p>
                </div>

                @if($errors->any())
                    <div style="background: rgba(229, 103, 95, 0.15); border: 1px solid rgba(229, 103, 95, 0.4); color: #F4B6B1; padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: 0.85rem;">
                        <ul style="margin: 0; padding-left: 1.2rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.post') }}" class="login-formulario">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div class="login-campo">
                            <label class="login-label">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="login-input" required placeholder="Tu nombre">
                        </div>

                        <div class="login-campo">
                            <label class="login-label">Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}" class="login-input" required placeholder="Tu apellido">
                        </div>
                    </div>

                    <div class="login-campo">
                        <label class="login-label">Correo Electrónico</label>
                        <input type="email" name="correo" value="{{ old('correo') }}" class="login-input" required placeholder="correo@ejemplo.com">
                    </div>

                    <div class="login-campo">
                        <label class="login-label">Teléfono Móvil</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" class="login-input" required placeholder="Ej: 3001234567">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div class="login-campo">
                            <label class="login-label">Contraseña</label>
                            <input type="password" name="password" class="login-input" required placeholder="Mín. 8 caracteres">
                        </div>

                        <div class="login-campo">
                            <label class="login-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="login-input" required placeholder="Repetir">
                        </div>
                    </div>

                    <button type="submit" class="login-boton" style="margin-top: 0.5rem;">
                        Registrarme e Ingresar
                    </button>

                    <a href="{{ route('seguridad.login') }}" class="login-boton-secundario">
                        ¿Ya tienes cuenta? Inicia sesión aquí
                    </a>
                </form>

            </div>
        </section>

    </main>

</body>
</html>
