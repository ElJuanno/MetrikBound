@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- HERO SECTION REDISEÑADO --}}
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-indigo-950 to-violet-950 p-8 text-white shadow-xl">
        {{-- Decoración de fondo --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(139,92,246,.25),transparent_40%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,.15),transparent_35%)]"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjAzIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-30"></div>

        <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <span class="badge inline-flex items-center gap-1.5 bg-white/10 text-white backdrop-blur-sm">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </span>

                <h1 class="mt-4 text-4xl font-bold leading-tight tracking-tight lg:text-5xl">
                    Bienvenido a tu espacio de
                    <span class="text-gradient-primary block mt-1">encuestas visuales</span>
                </h1>

                <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-300">
                    Diseña formularios profesionales, comparte enlaces y analiza resultados desde un panel moderno y fácil de usar.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('surveys.create') }}" class="btn-primary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva encuesta
                    </a>

                    <a href="{{ route('surveys.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/20 active:scale-[0.98]">
                        Ver mis encuestas
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-3 gap-3 lg:w-auto">
                <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm transition-all duration-200 hover:bg-white/15">
                    <div class="text-[10px] uppercase tracking-wider text-slate-300 font-bold">Encuestas</div>
                    <div class="mt-2 text-3xl font-bold">{{ $surveysCount }}</div>
                    <div class="mt-1 text-xs text-slate-400">Creadas</div>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm transition-all duration-200 hover:bg-white/15">
                    <div class="text-[10px] uppercase tracking-wider text-slate-300 font-bold">Respuestas</div>
                    <div class="mt-2 text-3xl font-bold">{{ $responsesCount }}</div>
                    <div class="mt-1 text-xs text-slate-400">Totales</div>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm transition-all duration-200 hover:bg-white/15">
                    <div class="text-[10px] uppercase tracking-wider text-slate-300 font-bold">Plan</div>
                    <div class="mt-2 text-2xl font-bold capitalize">{{ auth()->user()->plan ?? 'free' }}</div>
                    <div class="mt-1 text-xs text-slate-400">Actual</div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- COLUMNA PRINCIPAL (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Encuestas Recientes --}}
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="badge-primary">Tus encuestas</span>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900">Actividad reciente</h2>
                    </div>

                    <a href="{{ route('surveys.index') }}" class="btn-secondary">
                        Ver todo
                    </a>
                </div>

                <div class="mt-6">
                    @if($recentSurveys->isEmpty())
                        <div class="flex min-h-[320px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-2xl font-bold text-white shadow-lg">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>

                            <h3 class="mt-5 text-xl font-bold text-slate-900">
                                Aún no tienes encuestas
                            </h3>

                            <p class="mt-2 max-w-sm text-sm text-slate-600 leading-relaxed">
                                Crea tu primera encuesta y comienza a recopilar respuestas de forma profesional.
                            </p>

                            <a href="{{ route('surveys.create') }}" class="btn-primary mt-6">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Crear primera encuesta
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($recentSurveys as $survey)
                                <div class="card-hover">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-lg font-bold text-slate-900">
                                                {{ $survey->title }}
                                            </h3>

                                            @if(!empty($survey->description))
                                                <p class="mt-1 text-sm text-slate-600">
                                                    {{ Str::limit($survey->description, 100) }}
                                                </p>
                                            @endif

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <span class="badge-gray">
                                                    {{ ucfirst($survey->status) }}
                                                </span>

                                                <span class="badge-primary">
                                                    {{ $survey->responses_count }} respuestas
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="{{ route('builder.edit', $survey) }}" class="btn-secondary">
                                                Editar
                                            </a>

                                            @if($survey->status === 'published' && $survey->share_token)
                                                <a href="{{ route('surveys.public.show', $survey->share_token) }}"
                                                   target="_blank"
                                                   class="btn-primary">
                                                    Ver
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

            {{-- Cómo funciona --}}
            <div class="card">
                <span class="badge-primary">Guía rápida</span>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Cómo funciona</h2>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    @foreach([
                        ['num' => '1', 'title' => 'Crea', 'desc' => 'Diseña tu encuesta con el editor visual'],
                        ['num' => '2', 'title' => 'Comparte', 'desc' => 'Envía el enlace por cualquier canal'],
                        ['num' => '3', 'title' => 'Analiza', 'desc' => 'Revisa respuestas en tiempo real']
                    ] as $step)
                        <div class="rounded-xl bg-slate-50 p-5 transition-all duration-200 hover:bg-slate-100">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-sm font-bold text-white">
                                {{ $step['num'] }}
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- SIDEBAR (1/3) --}}
        <div class="space-y-6">
            {{-- Acciones Rápidas --}}
            <div class="card">
                <span class="badge-primary">Acciones</span>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Acceso rápido</h2>

                <div class="mt-6 space-y-2">
                    @foreach([
                        ['route' => 'surveys.create', 'icon' => 'M12 4v16m8-8H4', 'title' => 'Nueva encuesta', 'desc' => 'Desde cero'],
                        ['route' => 'templates.index', 'icon' => 'M4 5a1 1 0 011-1h4a1 1 0 010 2H6v10h4a1 1 0 110 2H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 110 2h-3v10h3a1 1 0 110 2h-4a1 1 0 01-1-1V5z', 'title' => 'Plantillas', 'desc' => 'Usa una base'],
                        ['route' => 'surveys.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Mis encuestas', 'desc' => 'Administrar']
                    ] as $action)
                        <a href="{{ route($action['route']) }}" class="flex items-center gap-3 rounded-xl bg-slate-50 p-4 transition-all duration-200 hover:bg-slate-100 hover:scale-[1.02] active:scale-[0.98]">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm">
                                <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $action['title'] }}</div>
                                <div class="text-xs text-slate-600">{{ $action['desc'] }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Plantillas Sugeridas --}}
            <div class="card">
                <span class="badge-primary">Plantillas</span>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Sugeridas</h2>

                <div class="mt-6 space-y-3">
                    @foreach([
                        ['title' => 'Satisfacción', 'desc' => 'NPS + comentarios', 'color' => 'from-indigo-500 to-violet-500'],
                        ['title' => 'Registro', 'desc' => 'Datos de contacto', 'color' => 'from-cyan-500 to-blue-500'],
                        ['title' => 'Feedback', 'desc' => 'Opinión rápida', 'color' => 'from-emerald-500 to-green-500']
                    ] as $template)
                        <a href="{{ route('surveys.create') }}" class="flex items-center gap-3 rounded-xl bg-slate-50 p-3 transition-all duration-200 hover:bg-slate-100 hover:scale-[1.02] active:scale-[0.98]">
                            <div class="h-12 w-12 shrink-0 rounded-lg bg-gradient-to-br {{ $template['color'] }} shadow-sm"></div>
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-slate-900">{{ $template['title'] }}</div>
                                <div class="text-xs text-slate-600">{{ $template['desc'] }}</div>
                            </div>
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
