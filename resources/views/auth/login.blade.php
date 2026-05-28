<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar - MetrikBound</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fb] text-slate-950 antialiased">
    <main class="grid min-h-screen xl:grid-cols-[minmax(0,1fr)_560px]">
        <section class="hidden bg-slate-950 p-12 text-white xl:flex xl:flex-col xl:justify-between">
            <a href="{{ route('welcome') }}"><img src="{{ asset('images/logo.png') }}" alt="MetrikBound" class="h-12 w-auto brightness-0 invert"></a>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-teal-300">Tu espacio de encuestas</p>
                <h1 class="mt-4 max-w-2xl text-5xl font-bold leading-tight tracking-tight">Vuelve a tus preguntas, respuestas y decisiones.</h1>
                <div class="mt-8 grid max-w-xl grid-cols-3 gap-3">
                    @foreach(['Crear', 'Publicar', 'Analizar'] as $item)
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4 text-sm font-semibold">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
            <p class="text-sm text-slate-400">MetrikBound para encuestas claras.</p>
        </section>

        <section class="flex items-center justify-center px-4 py-10 sm:px-6 lg:py-14">
            <div class="w-full max-w-md">
                <div class="mb-8 xl:hidden">
                    <a href="{{ route('welcome') }}"><img src="{{ asset('images/Logos.png') }}" alt="MetrikBound" class="w-40"></a>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
                    <div class="page-kicker">Acceso seguro</div>
                    <h1 class="page-title text-3xl sm:text-4xl">Iniciar sesion</h1>
                    <p class="page-subtitle">Entra para crear encuestas, compartir enlaces y revisar respuestas.</p>

                    @if (session('status'))
                        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-800">Correo</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="input" placeholder="tu@correo.com">
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-800">Contrasena</label>
                            <input id="password" name="password" type="password" required class="input" placeholder="Tu contrasena">
                        </div>

                        <div class="flex flex-col gap-3 text-sm sm:flex-row sm:items-center sm:justify-between">
                            <label class="inline-flex items-center gap-2 font-medium text-slate-600">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                Recordarme
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="font-semibold text-teal-700 hover:text-teal-800">Olvide mi contrasena</a>
                            @endif
                        </div>

                        <button type="submit" class="btn-primary w-full">Entrar</button>
                    </form>

                    <a href="{{ route('auth.google') }}" class="btn-secondary mt-4 w-full">Continuar con Google</a>

                    <p class="mt-6 text-center text-sm text-slate-600">
                        No tienes cuenta?
                        <a href="{{ route('register') }}" class="font-semibold text-teal-700">Crear cuenta</a>
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
