<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta - MetrikBound</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fb] text-slate-950 antialiased">
    <main class="grid min-h-screen xl:grid-cols-[minmax(0,1fr)_580px]">
        <section class="hidden bg-slate-950 p-12 text-white xl:flex xl:flex-col xl:justify-between">
            <a href="{{ route('welcome') }}"><img src="{{ asset('images/logo.png') }}" alt="MetrikBound" class="h-12 w-auto brightness-0 invert"></a>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-teal-300">Empieza gratis</p>
                <h1 class="mt-4 max-w-2xl text-5xl font-bold leading-tight tracking-tight">Crea un espacio para preguntar y medir.</h1>
                <div class="mt-8 grid max-w-xl grid-cols-2 gap-3">
                    @foreach(['Encuestas publicas', 'Registro opcional', 'Resultados claros', 'Plantillas listas'] as $item)
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4 text-sm font-semibold">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
            <p class="text-sm text-slate-400">Listo para tu primera encuesta.</p>
        </section>

        <section class="flex items-center justify-center px-4 py-10 sm:px-6 lg:py-14">
            <div class="w-full max-w-md">
                <div class="mb-8 xl:hidden">
                    <a href="{{ route('welcome') }}"><img src="{{ asset('images/Logos.png') }}" alt="MetrikBound" class="w-40"></a>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
                    <div class="page-kicker">Registro</div>
                    <h1 class="page-title text-3xl sm:text-4xl">Crear cuenta</h1>
                    <p class="page-subtitle">
                        @if(session('message'))
                            {{ session('message') }}
                        @else
                            Crea tu workspace y empieza a construir encuestas en minutos.
                        @endif
                    </p>

                    @if ($errors->any())
                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-800">Nombre</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="input" placeholder="Juan Perez">
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-800">Correo</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="input" placeholder="tu@correo.com">
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-800">Contrasena</label>
                            <input id="password" name="password" type="password" required class="input" placeholder="Minimo 8 caracteres">
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-800">Confirmar contrasena</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="input" placeholder="Repite tu contrasena">
                        </div>

                        <button type="submit" class="btn-primary w-full">Crear cuenta</button>
                    </form>

                    <a href="{{ route('auth.google') }}" class="btn-secondary mt-4 w-full">Continuar con Google</a>

                    <p class="mt-6 text-center text-sm text-slate-600">
                        Ya tienes cuenta?
                        <a href="{{ route('login') }}" class="font-semibold text-teal-700">Iniciar sesion</a>
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
