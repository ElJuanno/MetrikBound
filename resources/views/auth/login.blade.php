<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MetrikBound</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --primary:#6366f1;
            --primary-2:#8b5cf6;
            --primary-hover:#4f46e5;
            --bg:#ffffff;
            --bg-soft:#f8fafc;
            --bg-section:#f5f7fb;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e2e8f0;
            --line:#edf2f7;
            --success:#10b981;
            --danger:#ef4444;
            --shadow:0 10px 30px rgba(15,23,42,.08);
            --shadow-lg:0 25px 60px rgba(15,23,42,.12);
            --radius:18px;
            --radius-sm:12px;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            min-height:100vh;
            font-family:'Inter',sans-serif;
            background:
                radial-gradient(circle at top left, rgba(99,102,241,.10), transparent 28%),
                radial-gradient(circle at top right, rgba(139,92,246,.08), transparent 24%),
                linear-gradient(180deg,#ffffff,#f8fafc);
            color:var(--text);
            display:flex;
            flex-direction:column;
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        .page{
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }

        .topbar{
            width:100%;
            padding:20px 28px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:.8rem;
            font-weight:800;
            font-size:1.1rem;
            letter-spacing:-.5px;
        }

        .brand-mark{
            width:40px;
            height:40px;
            border-radius:12px;
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            display:grid;
            place-items:center;
            color:#fff;
            box-shadow:0 10px 25px rgba(99,102,241,.25);
            flex-shrink:0;
        }

        .topbar-right a{
            color:var(--muted);
            font-weight:600;
            transition:.2s ease;
        }

        .topbar-right a:hover{
            color:var(--primary);
        }

        .auth-wrap{
            flex:1;
            display:grid;
            grid-template-columns:1.05fr .95fr;
            align-items:center;
            gap:2rem;
            width:min(1180px, calc(100% - 2rem));
            margin:0 auto;
            padding:2rem 0 3rem;
        }

        .auth-copy{
            padding:2rem 1rem 2rem 0;
        }

        .eyebrow{
            display:inline-flex;
            align-items:center;
            gap:.55rem;
            padding:.45rem .9rem;
            border-radius:999px;
            background:#eef2ff;
            color:var(--primary);
            font-size:.88rem;
            font-weight:700;
            border:1px solid rgba(99,102,241,.12);
            margin-bottom:1.2rem;
        }

        .auth-copy h1{
            font-size:3.8rem;
            line-height:1.02;
            letter-spacing:-2.4px;
            margin-bottom:1rem;
        }

        .auth-copy h1 .gradient{
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .auth-copy p{
            color:var(--muted);
            font-size:1.06rem;
            max-width:580px;
            margin-bottom:1.8rem;
        }

        .points{
            display:grid;
            gap:1rem;
            max-width:520px;
        }

        .point{
            display:flex;
            gap:.9rem;
            align-items:flex-start;
            background:rgba(255,255,255,.75);
            border:1px solid var(--border);
            border-radius:16px;
            padding:1rem 1rem;
            box-shadow:var(--shadow);
        }

        .point-icon{
            width:42px;
            height:42px;
            border-radius:12px;
            display:grid;
            place-items:center;
            background:#eef2ff;
            color:var(--primary);
            flex-shrink:0;
        }

        .point h3{
            font-size:1rem;
            margin-bottom:.2rem;
        }

        .point p{
            margin:0;
            font-size:.93rem;
            color:var(--muted);
        }

        .auth-card{
            background:rgba(255,255,255,.92);
            backdrop-filter:blur(12px);
            border:1px solid rgba(226,232,240,.85);
            border-radius:28px;
            box-shadow:var(--shadow-lg);
            padding:2rem;
            max-width:480px;
            width:100%;
            margin-left:auto;
        }

        .card-badge{
            width:56px;
            height:56px;
            border-radius:18px;
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            color:#fff;
            display:grid;
            place-items:center;
            box-shadow:0 14px 28px rgba(99,102,241,.24);
            margin-bottom:1.2rem;
        }

        .auth-card h2{
            font-size:2rem;
            line-height:1.05;
            letter-spacing:-1.2px;
            margin-bottom:.55rem;
        }

        .auth-card .sub{
            color:var(--muted);
            margin-bottom:1.6rem;
        }

        .session-status{
            margin-bottom:1rem;
            padding:.95rem 1rem;
            border-radius:14px;
            background:rgba(16,185,129,.08);
            border:1px solid rgba(16,185,129,.18);
            color:#047857;
            font-size:.95rem;
            font-weight:600;
        }

        .alert-error{
            margin-bottom:1rem;
            padding:.95rem 1rem;
            border-radius:14px;
            background:rgba(239,68,68,.08);
            border:1px solid rgba(239,68,68,.18);
            color:#b91c1c;
            font-size:.95rem;
        }

        form{
            display:grid;
            gap:1rem;
        }

        .field-group{
            display:grid;
            gap:.45rem;
        }

        .field-label{
            font-size:.95rem;
            font-weight:700;
            color:var(--text);
        }

        .input-wrap{
            position:relative;
        }

        .input-icon{
            position:absolute;
            top:50%;
            left:14px;
            transform:translateY(-50%);
            color:#94a3b8;
            width:18px;
            height:18px;
        }

        .form-input{
            width:100%;
            height:52px;
            border:1px solid var(--border);
            border-radius:14px;
            background:#fff;
            padding:0 14px 0 44px;
            font-size:.97rem;
            color:var(--text);
            outline:none;
            transition:.22s ease;
        }

        .form-input:focus{
            border-color:#a5b4fc;
            box-shadow:0 0 0 4px rgba(99,102,241,.12);
        }

        .form-input.is-invalid{
            border-color:#fca5a5;
            box-shadow:0 0 0 4px rgba(239,68,68,.08);
        }

        .field-error{
            font-size:.88rem;
            color:var(--danger);
            font-weight:600;
            padding-left:.1rem;
        }

        .row-between{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:1rem;
            flex-wrap:wrap;
            margin-top:.25rem;
        }

        .remember{
            display:flex;
            align-items:center;
            gap:.6rem;
            color:var(--muted);
            font-size:.94rem;
            font-weight:500;
        }

        .remember input{
            width:16px;
            height:16px;
            accent-color:var(--primary);
        }

        .link{
            color:var(--primary);
            font-weight:700;
            font-size:.93rem;
        }

        .link:hover{
            color:var(--primary-hover);
        }

        .btn{
            width:100%;
            height:54px;
            border:none;
            border-radius:14px;
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            color:#fff;
            font-weight:700;
            font-size:1rem;
            cursor:pointer;
            transition:.25s ease;
            box-shadow:0 14px 28px rgba(99,102,241,.22);
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:.65rem;
            margin-top:.3rem;
        }

        .btn:hover{
            transform:translateY(-2px);
            box-shadow:0 20px 34px rgba(99,102,241,.28);
        }

        .bottom-text{
            text-align:center;
            margin-top:1.2rem;
            color:var(--muted);
            font-size:.95rem;
        }

        .bottom-text a{
            color:var(--primary);
            font-weight:700;
        }

        @media (max-width: 980px){
            .auth-wrap{
                grid-template-columns:1fr;
                padding-top:1rem;
            }

            .auth-copy{
                padding:0;
            }

            .auth-copy h1{
                font-size:2.8rem;
                letter-spacing:-1.8px;
            }

            .auth-card{
                margin:0 auto;
            }
        }

        @media (max-width: 640px){
            .topbar{
                padding:18px 16px;
            }

            .auth-wrap{
                width:min(100%, calc(100% - 1rem));
            }

            .auth-card{
                padding:1.35rem;
                border-radius:22px;
            }

            .auth-copy h1{
                font-size:2.2rem;
            }

            .row-between{
                flex-direction:column;
                align-items:flex-start;
            }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="topbar">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-mark">
                <i data-lucide="sparkles"></i>
            </span>
            <span>MetrikBound</span>
        </a>

        <div class="topbar-right">
            <a href="{{ route('register') }}">Crear cuenta</a>
        </div>
    </header>

    <main class="auth-wrap">
        <section class="auth-copy">
            <span class="eyebrow">
                <i data-lucide="wand-sparkles" style="width:16px;height:16px;"></i>
                Accede a tu espacio creativo
            </span>

            <h1>
                Bienvenido de nuevo a <span class="gradient">MetrikBound</span>
            </h1>

            <p>
                Inicia sesión para crear encuestas visuales, administrar respuestas
                y revisar métricas desde un panel moderno, limpio y profesional.
            </p>

            <div class="points">
                <div class="point">
                    <div class="point-icon"><i data-lucide="layout-dashboard"></i></div>
                    <div>
                        <h3>Dashboard centralizado</h3>
                        <p>Consulta tus encuestas, respuestas y actividad reciente en un solo lugar.</p>
                    </div>
                </div>

                <div class="point">
                    <div class="point-icon"><i data-lucide="blocks"></i></div>
                    <div>
                        <h3>Builder visual</h3>
                        <p>Diseña formularios con una experiencia intuitiva tipo drag & drop.</p>
                    </div>
                </div>

                <div class="point">
                    <div class="point-icon"><i data-lucide="bar-chart-3"></i></div>
                    <div>
                        <h3>Analítica en tiempo real</h3>
                        <p>Observa el rendimiento de tus formularios y mejora la conversión.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="auth-card">
            <div class="card-badge">
                <i data-lucide="log-in"></i>
            </div>

            <h2>Iniciar sesión</h2>
            <p class="sub">Accede con tu correo y contraseña.</p>

            @if (session('status'))
                <div class="session-status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    Revisa los datos ingresados. Hay campos que necesitan corrección.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    <label for="email" class="field-label">Correo electrónico</label>
                    <div class="input-wrap">
                        <i data-lucide="mail" class="input-icon"></i>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="form-input @error('email') is-invalid @enderror"
                            placeholder="tu@correo.com"
                        >
                    </div>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password" class="field-label">Contraseña</label>
                    <div class="input-wrap">
                        <i data-lucide="lock" class="input-icon"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="form-input @error('password') is-invalid @enderror"
                            placeholder="Ingresa tu contraseña"
                        >
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row-between">
                    <label for="remember_me" class="remember">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="link" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn">
                    <i data-lucide="arrow-right"></i>
                    Entrar a MetrikBound
                </button>
            </form>

            <div class="bottom-text">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}">Crear una ahora</a>
            </div>
        </section>
    </main>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>