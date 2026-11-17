<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HairSoft – Tu peluquería, siempre lista</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}" />
</head>
<body>

    <!-- Barra de navegación superior -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('img/logo-hairsoft.png') }}" alt="Logo HairSoft" class="nav-logo" onerror="this.style.display='none'">
            <span>Hair<span>Soft</span></span>
        </div>
        <div class="navbar-links">
            <a href="#servicios">Servicios</a>
            <a href="#como-funciona">Cómo funciona</a>
            <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="btn-nav">Iniciar sesión</a>
        </div>
    </nav>

    <!-- Sección Hero principal -->
    <section class="hero">
        <div class="hero-content">
            <p class="hero-eyebrow">Gestión moderna para barberías</p>
            <h1>
                Tu barbería,<br>
                <em>siempre lista</em><br>
                para el próximo turno
            </h1>
            <p class="hero-desc">
                HairSoft centraliza la agenda de citas, el catálogo de servicios
                y el control de turnos para que tu negocio funcione sin cruces ni fricciones.
            </p>
            <div class="hero-ctas">
                <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="btn-primary">Reservar una cita</a>
                <a href="#servicios" class="btn-secondary">Ver servicios</a>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- Sección Sobre el Espacio y Métricas -->
    <section class="section">
        <div class="about-grid">
            <div class="about-text">
                <p class="section-label">Experiencia en cada corte</p>
                <h2>Un espacio de cuidado<br>y confianza</h2>
                <p>
                    Una peluquería o barbería es mucho más que un lugar para cortarse el cabello.
                    Es un espacio donde los clientes confían su imagen a profesionales
                    capacitados que entienden su estilo y trabajan con máxima precisión.
                </p>
                <p>
                    Con HairSoft, el establecimiento gestiona el ciclo completo:
                    quién atiende, en qué horario, qué servicios se prestan y el historial
                    de citas de cada cliente.
                </p>
            </div>

            <div class="about-visual">
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-desc">Sin cruce de horarios en los turnos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5</div>
                    <div class="stat-desc">Estados de seguimiento por cita</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2</div>
                    <div class="stat-desc">Roles integrados: Administrador y Cliente</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Catálogo de Servicios -->
    <section class="section servicios-bg" id="servicios">
        <p class="section-label">Lo que ofrecemos</p>
        <h2>Nuestros servicios</h2>
        <p class="section-intro">
            Tarifas transparentes y atención personalizada con el estilista de tu preferencia.
        </p>

        <div class="servicios-grid">
            <div class="servicio-card">
                <div class="servicio-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 2v2m0 4a4 4 0 100 8 4 4 0 000-8zm12 10 4 4M6 10v2m0 4v2"/><path d="M19.07 4.93 12 12l-3-3"/></svg>
                </div>
                <h3>Corte de cabello</h3>
                <p>Clásico, moderno o degradado. Asesoría de imagen antes de comenzar cada servicio.</p>
            </div>

            <div class="servicio-card">
                <div class="servicio-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9v3c0 4 3 8 7 9 4-1 7-5 7-9V9c0-4-3-7-7-7z"/><path d="M9 15c1 1.5 5 1.5 6 0"/></svg>
                </div>
                <h3>Arreglo de barba</h3>
                <p>Perfilado, recorte a tijera o máquina y toalla caliente para una barba impecable.</p>
            </div>

            <div class="servicio-card">
                <div class="servicio-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2l1.5 4.5H18l-3.75 2.75L15.75 14 12 11.25 8.25 14l1.5-4.75L6 6.5h4.5z"/></svg>
                </div>
                <h3>Corte + Barba</h3>
                <p>La combinación completa. Ambos servicios en una sola cita coordinada con tu profesional.</p>
            </div>

            <div class="servicio-card">
                <div class="servicio-icon">
                    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h3>Atención personalizada</h3>
                <p>Elige tu barbero de confianza al agendar; tus servicios y preferencias quedan registrados.</p>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- Sección Cómo Funciona -->
    <section class="section" id="como-funciona">
        <p class="section-label">El proceso</p>
        <h2>Reservar es sencillo</h2>
        <p class="section-intro">
            Acompañamos cada etapa para que tu visita sea cómoda y puntual.
        </p>

        <div class="pasos-grid">
            <div class="paso">
                <div class="paso-num">01</div>
                <h3>Inicia sesión</h3>
                <p>Accede con las credenciales que tu peluquería te asignó al registrarte.</p>
            </div>
            <div class="paso">
                <div class="paso-num">02</div>
                <h3>Elige fecha y estilista</h3>
                <p>Selecciona el día, horario disponible y el barbero de tu agrado.</p>
            </div>
            <div class="paso">
                <div class="paso-num">03</div>
                <h3>Confirma tus servicios</h3>
                <p>Suma los cortes o arreglos deseados con tarifas fijas garantizadas.</p>
            </div>
            <div class="paso">
                <div class="paso-num">04</div>
                <h3>Paga en el local</h3>
                <p>El pago es presencial al finalizar la atención. Sin pasarelas ni recargos.</p>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="footer">
        <div class="footer-brand">
            <span>Hair<span>Soft</span></span>
        </div>
        <p>HairSoft · Sistema de gestión y agendamiento para peluquerías y barberías.</p>
    </footer>

</body>
</html>