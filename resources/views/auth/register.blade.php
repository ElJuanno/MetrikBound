<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | MetrikBound</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --primary:#6366f1;
            --primary-2:#8b5cf6;
            --primary-hover:#4f46e5;
            --bg:#ffffff;
            --bg-soft:#f8fafc;
            --text:#0f172a;
            --muted:#64748b;
            --border:#e2e8f0;
            --line:#edf2f7;
            --danger:#ef4444;
            --shadow:0 10px 30px rgba(15,23,42,.08);
            --shadow-lg:0 25px 60px rgba(15,23,42,.12);
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
            width:min(1180px, calc(100% - 2rem));
            margin:0 auto;
            padding:20px 0;
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
            width:min(1180px, calc(100% - 2rem));
            margin:0 auto;
            display:grid;
            grid-template-columns:1.02fr .98fr;
            gap:2rem;
            align-items:center;
            padding:2rem 0 3rem;
        }

        .auth-copy{
            padding-right:1rem;
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
            font-size:3.4rem;
            line-height:1.02;
            letter-spacing:-2px;
            margin-bottom:1rem;
        }

        .auth-copy h1 .gradient{
            background:linear-gradient(135deg, var(--primary), var(--primary-2));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .auth-copy p{
            color:var(--muted);
            font-size:1.05rem;
            max-width:580px;
            margin-bottom:1.6rem;
        }

        .benefits{
            display:grid;
            gap:1rem;
            max-width:520px;
        }

        .benefit{
            display:flex;
            gap:.9rem;
            align-items:flex-start;
            background:#fff;
            border:1px solid var(--border);
            border-radius:16px;
            padding:1rem;
            box-shadow:var(--shadow);
        }

        .benefit-icon{
            width:42px;
            height:42px;
            border-radius:12px;
            display:grid;
            place-items:center;
            background:#eef2ff;
            color:var(--primary);
            flex-shrink:0;
        }

        .benefit h3{
            font-size:1rem;
            margin-bottom:.2rem;
        }

        .benefit p{
            margin:0;
            font-size:.93rem;
            color:var(--muted);
        }

        .auth-card{
            width:100%;
            max-width:500px;
            margin-left:auto;
            background:rgba(255,255,255,.94);
            border:1px solid rgba(226,232,240,.9);
            border-radius:28px;
            box-shadow:var(--shadow-lg);
            padding:2rem;
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
            margin-bottom:.5rem;
        }

        .auth-card .sub{
            color:var(--muted);
            margin-bottom:1.5rem;
        }

        .alert-error{
            margin-bottom:1rem;
            padding:.95rem 1rem;
            border-radius:14px;
            background:rgba(239,68,68,.08);
            border:1px solid rgba(239,68,68,.18);
            color:#b91c1c;
            font-size:.95rem;
            font-weight:600;
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
            margin-top:.35rem;
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

        @media (max-width: 960px){
            .auth-wrap{
                grid-template-columns:1fr;
            }

            .auth-copy{
                padding-right:0;
            }

            .auth-copy h1{
                font-size:2.5rem;
            }

            .auth-card{
                margin:0 auto;
            }
        }

        @media (max-width: 640px){
            .topbar,
            .auth-wrap{
                width:min(100%, calc(100% - 1rem));
            }

            .auth-card{
                padding:1.35rem;
                border-radius:22px;
            }

            .auth-copy h1{
                font-size:2rem;
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
            <a href="{{ route('login') }}">Ya tengo cuenta</a>
        </div>
    </header>

    <main class="auth-wrap">
        <section class="auth-copy">
            <span class="eyebrow">
                <i data-lucide="rocket" style="width:16px;height:16px;"></i>
                Empieza gratis hoy
            </span>

            <h1>
                Diseña encuestas <span class="gradient">visuales</span> desde tu primera cuenta
            </h1>

            <p>
                Regístrate en MetrikBound para comenzar a crear formularios modernos,
                compartirlos fácilmente y analizar respuestas desde un solo panel.
            </p>

            <div class="benefits">
                <div class="benefit">
                    <div class="benefit-icon"><i data-lucide="blocks"></i></div>
                    <div>
                        <h3>Builder visual</h3>
                        <p>Crea formularios con una experiencia más intuitiva y atractiva.</p>
                    </div>
                </div>

                <div class="benefit">
                    <div class="benefit-icon"><i data-lucide="send"></i></div>
                    <div>
                        <h3>Comparte en segundos</h3>
                        <p>Publica tus encuestas por enlace o intégralas en tu sitio.</p>
                    </div>
                </div>

                <div class="benefit">
                    <div class="benefit-icon"><i data-lucide="bar-chart-3"></i></div>
                    <div>
                        <h3>Analíticas claras</h3>
                        <p>Consulta respuestas y métricas de forma rápida y visual.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="auth-card">
            <div class="card-badge">
                <i data-lucide="user-plus"></i>
            </div>

            <h2>Crear cuenta</h2>
            <p class="sub">Completa los datos para registrarte.</p>

            @if ($errors->any())
                <div class="alert-error">
                    Revisa los datos ingresados. Hay campos con errores.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field-group">
                    <label for="name" class="field-label">Nombre</label>
                    <div class="input-wrap">
                        <i data-lucide="user" class="input-icon"></i>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            class="form-input @error('name') is-invalid @enderror"
                            placeholder="Tu nombre completo"
                        >
                    </div>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

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
                            autocomplete="new-password"
                            class="form-input @error('password') is-invalid @enderror"
                            placeholder="Crea una contraseña"
                        >
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password_confirmation" class="field-label">Confirmar contraseña</label>
                    <div class="input-wrap">
                        <i data-lucide="shield-check" class="input-icon"></i>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="form-input @error('password_confirmation') is-invalid @enderror"
                            placeholder="Repite tu contraseña"
                        >
                    </div>
                    @error('password_confirmation')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn">
                    <i data-lucide="sparkles"></i>
                    Registrarme
                </button>
            </form>

            <div class="bottom-text">
                ¿Ya estás registrado?
                <a href="{{ route('login') }}">Inicia sesión</a>
            </div>
        </section>
    </main>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>