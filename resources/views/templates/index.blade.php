@extends('layouts.app')
@section('title', 'Resultados')

@section('content')
<div class="space-y-6">
    <div>
        <div class="inline-flex rounded-full bg-pink-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[.18em] text-pink-600">
            Resultados
        </div>
        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
            Resultados de encuestas
        </h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Aquí podrás visualizar gráficas, métricas y respuestas por encuesta.
        </p>
    </div>

    <div class="rounded-[28px] border border-slate-200 bg-white p-8 shadow-sm">
        <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[20px] bg-gradient-to-br from-pink-500 to-violet-500 text-2xl font-bold text-white shadow-lg">
                %
            </div>

            <h2 class="mt-5 text-2xl font-bold tracking-tight text-slate-900">
                Módulo de resultados en progreso
            </h2>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
                Aquí irán las gráficas, resúmenes visuales y estadísticas de cada encuesta.
            </p>
        </div>
    </div>
</div>
@endsection