@extends('layouts.app')
@section('title', 'Resultados')

@section('content')
@php
    $totalSurveys = $surveys->count();
    $totalResponses = $surveys->sum('responses_count');
    $activeSurveys = $surveys->where('status', 'published')->count();
    $topSurvey = $surveys->sortByDesc('responses_count')->first();
@endphp

<div class="page-shell">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="page-kicker">Resultados</div>
            <h1 class="page-title">Tus encuestas, respuestas y avances.</h1>
            <p class="page-subtitle">Revisa que encuestas ya tienen respuestas, cuales estan publicadas y donde conviene poner atencion.</p>
        </div>

        <a href="{{ route('surveys.create') }}" class="btn-primary shrink-0">Nueva encuesta</a>
    </div>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Respuestas totales</p>
            <p class="mt-3 text-4xl font-black text-slate-950">{{ $totalResponses }}</p>
            <p class="mt-2 text-sm text-slate-500">Recibidas entre todas tus encuestas.</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Encuestas publicadas</p>
            <p class="mt-3 text-4xl font-black text-slate-950">{{ $activeSurveys }}</p>
            <p class="mt-2 text-sm text-slate-500">Disponibles para responder en linea.</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Mas respondida</p>
            <p class="mt-3 truncate text-xl font-black text-slate-950">{{ $topSurvey?->title ?? 'Sin datos aun' }}</p>
            <p class="mt-2 text-sm text-slate-500">{{ $topSurvey ? $topSurvey->responses_count.' '.Str::plural('respuesta', $topSurvey->responses_count).' registradas.' : 'Comparte una encuesta para empezar.' }}</p>
        </div>
    </section>

    @if($surveys->isEmpty())
        <section class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
            <h2 class="text-xl font-bold text-slate-950">Aun no hay encuestas para analizar</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">Crea una encuesta, comparte el enlace y aqui veras los resultados de forma automatica.</p>
            <a href="{{ route('surveys.create') }}" class="btn-primary mt-5">Crear encuesta</a>
        </section>
    @else
        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-100 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-950">Encuestas con resultados</h2>
                    <p class="mt-1 text-sm text-slate-500">Abre una encuesta para ver respuestas por pregunta.</p>
                </div>
                <span class="text-sm font-semibold text-slate-500">{{ $totalSurveys }} encuestas</span>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($surveys as $survey)
                    <a href="{{ route('results.show', $survey) }}" class="group block p-5 transition hover:bg-slate-50 sm:p-6">
                        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_150px_120px] lg:items-center">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full {{ $survey->status === 'published' ? 'bg-teal-50 text-teal-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-bold">
                                        {{ $survey->status === 'published' ? 'Publicada' : 'Borrador' }}
                                    </span>
                                    <span class="text-xs font-semibold text-slate-400">Creada {{ $survey->created_at?->format('d/m/Y') }}</span>
                                </div>

                                <h3 class="mt-3 truncate text-xl font-black text-slate-950 group-hover:text-teal-700">{{ $survey->title }}</h3>
                                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ Str::limit($survey->description ?: 'Sin descripcion.', 180) }}</p>
                            </div>

                            <div class="rounded-lg bg-slate-50 px-4 py-3 lg:text-center">
                                <p class="text-3xl font-black text-slate-950">{{ $survey->responses_count }}</p>
                                <p class="text-sm font-semibold text-slate-500">{{ Str::plural('respuesta', $survey->responses_count) }}</p>
                            </div>

                            <div class="flex lg:justify-end">
                                <span class="inline-flex items-center rounded-lg bg-teal-50 px-4 py-2 text-sm font-black text-teal-700 transition group-hover:bg-teal-600 group-hover:text-white">
                                    Ver detalle
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
