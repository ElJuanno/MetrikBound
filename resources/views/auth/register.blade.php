<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Cuenta - MetrikBound</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #8b5cf6;
            --cyan: #58DBF0;
            --dark: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --soft: #f8fafc;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            color: var(--dark);
            overflow-x: hidden;
            background:
                radial-gradient(circle at top right, rgba(99, 102, 241, .10), transparent 34%),
                radial-gradient(circle at bottom left, rgba(88, 219, 240, .12), transparent 30%),
                #f8fafc;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .register-page {
            width: 100%;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr minmax(420px, 540px);
            gap: 22px;
            padding: 22px;
        }

        .hero-panel {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
            min-height: calc(100vh - 44px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 70px;
            background:
                radial-gradient(circle at 18% 18%, rgba(88, 219, 240, .40), transparent 28%),
                radial-gradient(circle at 82% 14%, rgba(255, 255, 255, .22), transparent 22%),
                radial-gradient(circle at 60% 88%, rgba(139, 92, 246, .50), transparent 30%),
                linear-gradient(135deg, #4f46e5 0%, #6d5dfc 42%, #8b5cf6 100%);
            box-shadow: 0 24px 70px rgba(79, 70, 229, .22);
        }

        .hero-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.09) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.09) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(circle at center, black, transparent 72%);
            opacity: .45;
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.20);
            bottom: -140px;
            left: -120px;
        }

        .hero-content {
            width: 100%;
            max-width: 760px;
            color: white;
            position: relative;
            z-index: 2;
        }

        .brand-floating {
            width: fit-content;
            padding: 13px 16px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .20);
            backdrop-filter: blur(20px);
            box-shadow: 0 18px 38px rgba(15, 23, 42, .14);
            margin-bottom: 40px;
        }

        .brand-floating-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: white;
            font-size: 21px;
            background: rgba(255,255,255,.17);
        }

        .brand-floating strong {
            display: block;
            font-size: 14px;
            font-weight: 900;
        }

        .brand-floating span {
            display: block;
            margin-top: 2px;
            font-size: 12px;
            font-weight: 650;
            opacity: .82;
        }

        .hero-title {
            font-size: clamp(48px, 5.2vw, 84px);
            line-height: .96;
            letter-spacing: -3.5px;
            font-weight: 950;
            margin-bottom: 24px;
        }

        .hero-title span {
            color: #c9f8ff;
        }

        .hero-description {
            max-width: 650px;
            font-size: 18px;
            line-height: 1.75;
            font-weight: 550;
            opacity: .92;
            margin-bottom: 38px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 38px;
        }

        .stat-card {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.16);
            backdrop-filter: blur(16px);
            transition: .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            background: rgba(255,255,255,.16);
        }

        .stat-card strong {
            display: block;
            font-size: 24px;
            font-weight: 950;
            letter-spacing: -1px;
        }

        .stat-card span {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            line-height: 1.35;
            font-weight: 750;
            opacity: .82;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .feature-item {
            padding: 16px;
            border-radius: 20px;
            background: rgba(255,255,255,.11);
            border: 1px solid rgba(255,255,255,.15);
            backdrop-filter: blur(16px);
            display: flex;
            align-items: center;
            gap: 13px;
            transition: .25s ease;
        }

        .feature-item:hover {
            transform: translateY(-3px);
            background: rgba(255,255,255,.16);
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.16);
            flex-shrink: 0;
            font-size: 18px;
        }

        .feature-item span {
            font-size: 14px;
            font-weight: 850;
        }

        .form-panel {
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(226, 232, 240, .85);
            border-radius: 32px;
            box-shadow:
                0 24px 70px rgba(15, 23, 42, .08),
                inset 0 1px 0 rgba(255, 255, 255, .85);
            padding: 34px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .form-panel::before {
            content: "";
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 999px;
            background: rgba(99, 102, 241, .08);
            top: -95px;
            right: -95px;
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: white;
            font-size: 20px;
            background:
                linear-gradient(135deg, rgba(88, 219, 240, .95), rgba(99, 102, 241, .95) 48%, rgba(139, 92, 246, .95));
            box-shadow: 0 14px 28px rgba(99, 102, 241, .28);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: var(--dark);
        }

        .brand-small {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            margin-top: 3px;
        }

        .form-area {
            width: 100%;
            max-width: 410px;
            margin: auto;
            padding: 34px 0 24px;
            position: relative;
            z-index: 2;
        }

        .badge {
            width: fit-content;
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(99, 102, 241, .08);
            border: 1px solid rgba(99, 102, 241, .14);
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        h1 {
            font-size: 34px;
            line-height: 1.05;
            font-weight: 900;
            letter-spacing: -1.4px;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            line-height: 1.6;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 16px;
            margin-bottom: 18px;
            font-size: 13px;
            font-weight: 650;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-info {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .form-grid {
            display: grid;
            gap: 15px;
        }

        .form-group {
            margin: 0;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 800;
            color: #172033;
            margin-bottom: 8px;
            letter-spacing: -0.15px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 50px;
            padding: 0 15px 0 45px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: rgba(255, 255, 255, .92);
            color: var(--dark);
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            transition: .2s ease;
            outline: none;
            box-shadow: 0 1px 0 rgba(15, 23, 42, .02);
        }

        input[type="text"]:hover,
        input[type="email"]:hover,
        input[type="password"]:hover {
            border-color: #cbd5e1;
            background: white;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary);
            box-shadow:
                0 0 0 4px rgba(99, 102, 241, .12),
                0 12px 24px rgba(99, 102, 241, .08);
            background: white;
        }

        input::placeholder {
            color: #a8b3c5;
            font-weight: 500;
        }

        .password-note {
            margin-top: 8px;
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        .btn-primary {
            width: 100%;
            height: 52px;
            border: 0;
            border-radius: 16px;
            color: white;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
            font-family: inherit;
            background:
                linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            box-shadow:
                0 16px 30px rgba(99, 102, 241, .30),
                inset 0 1px 0 rgba(255, 255, 255, .2);
            transition: .2s ease;
            margin-top: 20px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow:
                0 22px 38px rgba(99, 102, 241, .38),
                inset 0 1px 0 rgba(255, 255, 255, .2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 24px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, #dbe3ef, transparent);
        }

        .divider-text {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 800;
        }

        .btn-google {
            width: 100%;
            height: 50px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: white;
            color: var(--dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            font-size: 13px;
            font-weight: 850;
            transition: .2s ease;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(15, 23, 42, .06);
        }

        .login-link {
            margin-top: auto;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }

        .login-link a {
            color: var(--primary-dark);
            font-weight: 900;
            text-decoration: none;
            margin-left: 4px;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 1180px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1100px) {
            .register-page {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                display: none;
            }

            .form-panel {
                min-height: calc(100vh - 44px);
            }
        }

        @media (max-width: 560px) {
            .register-page {
                padding: 0;
            }

            .form-panel {
                border-radius: 0;
                min-height: 100vh;
                padding: 26px 22px;
                border: none;
            }

            .form-area {
                padding-top: 42px;
            }

            h1 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>
    <main class="register-page">

        <!-- PANEL IZQUIERDO / VISUAL -->
        <section class="hero-panel">
            <div class="hero-content">
                <div class="brand-floating">
                    <div class="brand-floating-icon">🚀</div>
                    <div>
                        <strong>MetrikBound</strong>
                        <span>Diseña, publica y analiza encuestas visuales</span>
                    </div>
                </div>

                <h2 class="hero-title">
                    Empieza a crear <span>encuestas profesionales</span>
                </h2>

                <p class="hero-description">
                    Regístrate gratis y construye formularios modernos con preguntas ilimitadas, enlaces compartibles y resultados claros en tiempo real.
                </p>

                <div class="stats-grid">
                    <div class="stat-card">
                        <strong>∞</strong>
                        <span>Preguntas ilimitadas</span>
                    </div>

                    <div class="stat-card">
                        <strong>Live</strong>
                        <span>Análisis en tiempo real</span>
                    </div>

                    <div class="stat-card">
                        <strong>1–5</strong>
                        <span>Escalas de medición</span>
                    </div>

                    <div class="stat-card">
                        <strong>Link</strong>
                        <span>Encuestas compartibles</span>
                    </div>
                </div>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">✨</div>
                        <span>Editor visual tipo Canva</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <span>Gráficas simples y claras</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <span>Modo público o privado</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">⚡</div>
                        <span>Publicación en segundos</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- PANEL DERECHO / FORMULARIO -->
        <section class="form-panel">
            <div class="brand">
                <div class="brand-icon">▣</div>
                <div class="brand-text">
                    <span class="brand-name">MetrikBound</span>
                    <span class="brand-small">Survey Builder</span>
                </div>
            </div>

            <div class="form-area">
                <div class="badge">
                    <span>●</span>
                    Registro gratuito
                </div>

                <h1>Crear cuenta</h1>

                <p class="subtitle">
                    @if(session('message'))
                        {{ session('message') }}
                    @else
                        Crea tu espacio de trabajo y comienza a diseñar encuestas en segundos.
                    @endif
                </p>

                @if(session('message'))
                    <div class="alert alert-info">
                        <span style="font-size: 18px;">🔐</span>
                        <span>Esta encuesta requiere autenticación para continuar.</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Nombre completo</label>
                            <div class="input-wrap">
                                <span class="input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    placeholder="Juan Pérez"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <div class="input-wrap">
                                <span class="input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 6.5h16v11H4v-11Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="m4.5 7 7.5 6 7.5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="tu@correo.com"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="input-wrap">
                                <span class="input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M7 10V8a5 5 0 0 1 10 0v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M6 10h12v10H6V10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M12 14v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="Mínimo 8 caracteres"
                                >
                            </div>
                            <p class="password-note">Usa una contraseña segura para proteger tus encuestas.</p>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <div class="input-wrap">
                                <span class="input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M20 7 10 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    placeholder="Repite tu contraseña"
                                >
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        Crear cuenta gratis
                    </button>
                </form>

                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">O regístrate con</span>
                    <div class="divider-line"></div>
                </div>

                <a href="{{ route('auth.google') }}" class="btn-google">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuar con Google
                </a>
            </div>

            <div class="login-link">
                ¿Ya tienes una cuenta?
                <a href="{{ route('login') }}">Iniciar sesión</a>
            </div>
        </section>
    </main>
</body>
</html>