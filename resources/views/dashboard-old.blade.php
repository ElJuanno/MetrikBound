@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- HERO --}}
    <section class="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 px-6 py-7 text-white shadow-[0_25px_60px_rgba(15,23,42,.20)] sm:px-8 sm:py-8">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(139,92,246,.35),transparent_28%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,.22),transparent_24%)]"></div>

        <div class="relative flex flex-col gap-8 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-3xl">
                <span class="inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[.18em] text-indigo-200">
                    Dashboard
                </span>

                <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight sm:text-4xl">
                    Bienvenido a tu espacio de
                    <span class="bg-gradient-to-r from-indigo-300 to-violet-300 bg-clip-text text-transparent">
                        encuestas visuales
                    </span>
                </h2>

                <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
                    Diseña formularios, comparte enlaces y revisa resultados desde un panel más limpio,
                    moderno y pensado para verse realmente bien.
                </p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('surveys.create') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                        + Nueva encuesta
                    </a>

                    <a href="{{ route('surveys.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/15">
                        Ver mis encuestas
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 xl:w-[440px]">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <div class="text-[11px] uppercase tracking-[.18em] text-slate-300 font-semibold">Encuestas</div>
                    <div class="mt-2 text-3xl font-bold">{{ $surveysCount }}</div>
                    <div class="mt-1 text-sm text-slate-300">Creadas</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <div class="text-[11px] uppercase tracking-[.18em] text-slate-300 font-semibold">Respuestas</div>
                    <div class="mt-2 text-3xl font-bold">{{ $responsesCount }}</div>
                    <div class="mt-1 text-sm text-slate-300">Totales</div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <div class="text-[11px] uppercase tracking-[.18em] text-slate-300 font-semibold">Plan</div>
                    <div class="mt-2 text-3xl font-bold capitalize">{{ auth()->user()->plan ?? 'free' }}</div>
                    <div class="mt-1 text-sm text-slate-300">Actual</div>
                </div>
            </div>
        </div>
    </section>

    {{-- GRID PRINCIPAL --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.2fr_.8fr]">

        {{-- IZQUIERDA --}}
        <div class="space-y-6">
            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">
                            Tus encuestas
                        </div>
                        <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                            Actividad reciente
                        </h3>
                    </div>

                    <a href="{{ route('surveys.index') }}"
                       class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Ver todo
                    </a>
                </div>

<div class="mt-6 rounded-[24px] bg-slate-50 p-6">
    @if($recentSurveys->isEmpty())
        <div class="flex min-h-[300px] flex-col items-center justify-center rounded-[20px] border border-dashed border-slate-300 bg-white px-6 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-500 text-2xl font-bold text-white shadow-lg">
                +
            </div>

            <h4 class="mt-5 text-2xl font-bold tracking-tight text-slate-900">
                Aún no tienes encuestas creadas
            </h4>

            <p class="mt-3 max-w-md text-sm leading-7 text-slate-500">
                Empieza creando tu primera encuesta y aquí verás su actividad,
                respuestas y accesos más importantes.
            </p>

            <a href="{{ route('surveys.create') }}"
               class="mt-6 inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_14px_30px_rgba(99,102,241,.25)] transition hover:-translate-y-0.5">
                Crear mi primera encuesta
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($recentSurveys as $survey)
                <div class="rounded-[20px] border border-slate-200 bg-white p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-bold text-slate-900">
                                {{ $survey->title }}
                            </div>

                            @if(!empty($survey->description))
                                <div class="mt-1 text-sm text-slate-500">
                                    {{ $survey->description }}
                                </div>
                            @endif

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    Estado: {{ ucfirst($survey->status) }}
                                </span>

                                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    Respuestas: {{ $survey->responses_count }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row">
                            <a href="{{ route('builder.edit', $survey) }}"
                               class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                Editar
                            </a>

                            @if($survey->status === 'published' && $survey->share_token)
                                <a href="{{ route('surveys.public.show', $survey->share_token) }}"
                                   target="_blank"
                                   class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                    Ver encuesta
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
            </div>

            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">
                    Flujo
                </div>
                <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                    Cómo funciona
                </h3>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-[22px] bg-slate-50 p-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-bold text-white">1</div>
                        <div class="mt-4 text-lg font-bold text-slate-900">Crea</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Diseña una encuesta con estilo visual claro.
                        </p>
                    </div>

                    <div class="rounded-[22px] bg-slate-50 p-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-bold text-white">2</div>
                        <div class="mt-4 text-lg font-bold text-slate-900">Comparte</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Envía el enlace por donde quieras.
                        </p>
                    </div>

                    <div class="rounded-[22px] bg-slate-50 p-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-bold text-white">3</div>
                        <div class="mt-4 text-lg font-bold text-slate-900">Analiza</div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Revisa respuestas y resultados rápidamente.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- DERECHA --}}
        <div class="space-y-6">
            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">
                    Acciones rápidas
                </div>
                <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                    Empieza más rápido
                </h3>

                <div class="mt-6 space-y-3">
                    <a href="{{ route('surveys.create') }}"
                       class="block rounded-[22px] bg-slate-50 p-4 transition hover:bg-slate-100">
                        <div class="text-base font-semibold text-slate-900">Nueva encuesta</div>
                        <div class="mt-1 text-sm text-slate-500">Comienza desde cero</div>
                    </a>

                    <a href="{{ route('templates.index') }}"
                       class="block rounded-[22px] bg-slate-50 p-4 transition hover:bg-slate-100">
                        <div class="text-base font-semibold text-slate-900">Usar plantilla</div>
                        <div class="mt-1 text-sm text-slate-500">Ahorra tiempo con una base lista</div>
                    </a>

                    <a href="{{ route('surveys.index') }}"
                       class="block rounded-[22px] bg-slate-50 p-4 transition hover:bg-slate-100">
                        <div class="text-base font-semibold text-slate-900">Mis encuestas</div>
                        <div class="mt-1 text-sm text-slate-500">Administra lo que hayas creado</div>
                    </a>
                </div>
            </div>

            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">
                    Plantillas
                </div>
                <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                    Sugeridas para ti
                </h3>

                @php
                    $quick = [
                        ['title' => 'Encuesta de satisfacción', 'desc' => 'Calificación + comentario', 'bg' => 'from-indigo-500 to-violet-500'],
                        ['title' => 'Registro rápido', 'desc' => 'Nombre, correo y mensaje', 'bg' => 'from-cyan-500 to-sky-500'],
                        ['title' => 'NPS', 'desc' => '0–10 + razón', 'bg' => 'from-emerald-500 to-green-500'],
                    ];
                @endphp

                <div class="mt-6 space-y-4">
                    @foreach($quick as $q)
                        <a href="{{ route('surveys.create') }}"
                           class="flex items-start gap-4 rounded-[22px] bg-slate-50 p-4 transition hover:bg-slate-100">
                            <div class="h-12 w-12 shrink-0 rounded-2xl bg-gradient-to-br {{ $q['bg'] }}"></div>
                            <div>
                                <div class="text-base font-semibold text-slate-900">{{ $q['title'] }}</div>
                                <div class="mt-1 text-sm text-slate-500">{{ $q['desc'] }}</div>
                                <div class="mt-3 text-sm font-semibold text-indigo-600">Usar plantilla →</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection