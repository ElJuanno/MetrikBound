<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetrikBound | Encuestas claras para tomar decisiones</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f6f8fb] text-slate-950 antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/Logos.png') }}" alt="MetrikBound" class="w-36">
            </a>
            <nav class="hidden items-center gap-6 text-sm font-semibold text-slate-600 md:flex">
                <a href="#producto" class="hover:text-slate-950">Producto</a>
                <a href="#plantillas" class="hover:text-slate-950">Plantillas</a>
                <a href="#flujo" class="hover:text-slate-950">Flujo</a>
            </nav>
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="btn-secondary px-4 py-2">Entrar</a>
                <a href="{{ route('register') }}" class="btn-primary px-4 py-2">Crear cuenta</a>
            </div>
        </div>
    </header>

    <main>
        <section class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[minmax(0,1fr)_500px] lg:px-8 lg:py-20">
            <div class="flex flex-col justify-center">
                <div class="page-kicker w-fit">
                    <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                    Plataforma para encuestas
                </div>
                <h1 class="mt-5 max-w-4xl text-5xl font-bold tracking-tight text-slate-950 sm:text-6xl">
                    Pregunta mejor, comparte rapido y entiende las respuestas.
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                    MetrikBound te ayuda a crear encuestas, publicarlas con enlace y revisar resultados sin perderte en herramientas genericas.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="btn-primary">Crear mi primera encuesta</a>
                    <a href="{{ route('login') }}" class="btn-secondary">Ya tengo cuenta</a>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xl">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Encuesta activa</p>
                            <p class="mt-1 font-bold text-slate-950">Satisfaccion del cliente</p>
                        </div>
                        <span class="rounded-full bg-teal-100 px-3 py-1 text-xs font-semibold text-teal-700">Publicada</span>
                    </div>

                    <div class="mt-4 space-y-3 rounded-xl bg-white p-4">
                        <p class="text-sm font-semibold text-slate-900">1. Que tan facil fue completar tu compra?</p>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach([1,2,3,4,5] as $score)
                                <div class="flex h-10 items-center justify-center rounded-lg border {{ $score === 4 ? 'border-teal-500 bg-teal-50 text-teal-700' : 'border-slate-200 bg-white text-slate-500' }} text-sm font-bold">{{ $score }}</div>
                            @endforeach
                        </div>
                        <p class="pt-3 text-sm font-semibold text-slate-900">2. Que deberiamos mejorar?</p>
                        <div class="h-24 rounded-lg border border-slate-200 bg-slate-50"></div>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-3">
                        <div class="rounded-xl bg-white p-3">
                            <p class="text-xs text-slate-500">Respuestas</p>
                            <p class="mt-1 text-2xl font-bold">128</p>
                        </div>
                        <div class="rounded-xl bg-white p-3">
                            <p class="text-xs text-slate-500">Promedio</p>
                            <p class="mt-1 text-2xl font-bold">4.6</p>
                        </div>
                        <div class="rounded-xl bg-white p-3">
                            <p class="text-xs text-slate-500">Tiempo</p>
                            <p class="mt-1 text-2xl font-bold">3m</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="plantillas" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            @php
                $homeTemplates = collect(\App\Http\Controllers\SurveyController::availableTemplates())
                    ->only(['customer_satisfaction', 'event_registration', 'product_feedback', 'market_research', 'course_evaluation', 'post_purchase']);
            @endphp

            <div class="grid gap-8 lg:grid-cols-[430px_minmax(0,1fr)] lg:items-start">
                <div class="lg:sticky lg:top-24">
                    <div class="page-kicker">Plantillas personalizadas</div>
                    <h2 class="mt-4 text-4xl font-bold leading-tight tracking-tight text-slate-950 sm:text-5xl">
                        Prueba nuestras plantillas listas para adaptar.
                    </h2>
                    <p class="mt-4 text-base leading-8 text-slate-600">
                        Empieza con estructuras reales para clientes, eventos, producto, mercado, cursos y post-compra. Cada plantilla ya trae preguntas y opciones listas para editar en el builder.
                    </p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row lg:flex-col xl:flex-row">
                        <a href="{{ route('register') }}" class="btn-primary">Usar una plantilla</a>
                        <a href="{{ route('login') }}" class="btn-secondary">Ver plantillas</a>
                    </div>

                    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Incluyen</p>
                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <div>
                                <p class="text-2xl font-bold text-slate-950">10</p>
                                <p class="mt-1 text-xs font-semibold text-slate-500">plantillas</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-950">60+</p>
                                <p class="mt-1 text-xs font-semibold text-slate-500">preguntas</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-950">1 clic</p>
                                <p class="mt-1 text-xs font-semibold text-slate-500">para crear</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    @foreach($homeTemplates as $template)
                        <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-[48px] opacity-10" style="background: {{ $template['accent'] ?? '#0f766e' }}"></div>
                            <div class="relative">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl text-sm font-bold text-white" style="background: {{ $template['accent'] ?? '#0f766e' }}">
                                        {{ $template['icon'] }}
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $template['tag'] }}
                                    </span>
                                </div>

                                <h3 class="mt-6 text-xl font-bold text-slate-950">{{ $template['title'] }}</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $template['description'] }}</p>

                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                                    <span class="text-sm font-semibold text-slate-500">{{ count($template['questions']) }} preguntas</span>
                                    <span class="text-sm font-bold text-teal-700 transition group-hover:translate-x-1">Probar</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="producto" class="border-y border-slate-200 bg-white">
            <div class="mx-auto grid max-w-7xl gap-4 px-4 py-12 sm:px-6 md:grid-cols-3 lg:px-8">
                @foreach([
                    ['title' => 'Crea formularios', 'desc' => 'Arma preguntas, secciones y campos para recopilar la informacion correcta.'],
                    ['title' => 'Comparte enlaces', 'desc' => 'Publica encuestas anonimas o con registro segun el tipo de respuesta que necesitas.'],
                    ['title' => 'Lee resultados', 'desc' => 'Consulta respuestas, promedios y opciones elegidas desde un panel claro.'],
                ] as $item)
                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-6">
                        <h2 class="text-lg font-bold text-slate-950">{{ $item['title'] }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $item['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="flujo" class="bg-slate-950 px-4 py-20 text-white sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="grid gap-10 lg:grid-cols-[420px_minmax(0,1fr)] lg:items-center">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-teal-300">Flujo simple</p>
                        <h2 class="mt-4 text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                            De una idea a respuestas listas para decidir.
                        </h2>
                        <p class="mt-5 text-base leading-8 text-slate-300">
                            MetrikBound ordena el proceso completo: eliges una plantilla, publicas el enlace y revisas resultados sin cambiar de herramienta.
                        </p>
                        <a href="{{ route('register') }}" class="mt-7 inline-flex min-h-11 items-center justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-slate-100">
                            Empezar el flujo
                        </a>
                    </div>

                    <div class="relative">
                        <div class="absolute left-8 top-12 hidden h-[calc(100%-96px)] w-px bg-white/15 md:block"></div>
                        <div class="space-y-5">
                            @foreach([
                                ['num' => '01', 'title' => 'Elige o crea', 'desc' => 'Parte desde cero o usa una plantilla con preguntas ya pensadas para tu caso.', 'meta' => 'Plantillas listas'],
                                ['num' => '02', 'title' => 'Personaliza y publica', 'desc' => 'Ajusta textos, opciones y acceso. Luego comparte un enlace publico o con registro.', 'meta' => 'Link compartible'],
                                ['num' => '03', 'title' => 'Analiza respuestas', 'desc' => 'Consulta conteos, promedios y respuestas abiertas para entender que esta pasando.', 'meta' => 'Datos claros'],
                            ] as $step)
                                <article class="relative grid gap-4 rounded-2xl border border-white/10 bg-white/[0.06] p-5 shadow-2xl shadow-black/10 backdrop-blur sm:grid-cols-[56px_minmax(0,1fr)_150px] sm:items-center">
                                    <div class="relative z-10 flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-400 text-sm font-black text-slate-950">
                                        {{ $step['num'] }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold">{{ $step['title'] }}</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-300">{{ $step['desc'] }}</p>
                                    </div>
                                    <div class="rounded-xl border border-white/10 bg-slate-950/50 p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Salida</p>
                                        <p class="mt-2 text-sm font-bold text-teal-200">{{ $step['meta'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
