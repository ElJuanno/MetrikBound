@extends('layouts.app')

@section('title', 'Panel de encuestas')

@section('content')
@php
    $totalResponses = max($responsesCount, 0);
    $responseRateHint = $surveysCount > 0 ? round($responsesCount / max($surveysCount, 1), 1) : 0;
    $publishedPercent = $surveysCount > 0 ? round(($publishedCount / $surveysCount) * 100) : 0;
@endphp

<div class="page-shell">
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="grid gap-0 xl:grid-cols-[minmax(0,1fr)_380px]">
            <div class="p-6 sm:p-8 lg:p-10">
                <div class="inline-flex items-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-teal-700">
                    <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                    Panel de encuestas
                </div>

                <div class="mt-5 max-w-3xl">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Crea preguntas, comparte el enlace y revisa lo que respondieron.
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                        Este es tu lugar de trabajo para convertir una idea en una encuesta activa: borradores, publicaciones y respuestas en un solo flujo.
                    </p>
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('surveys.create') }}" class="btn-primary rounded-lg bg-teal-600 shadow-none hover:bg-teal-700 hover:shadow-none">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/>
                        </svg>
                        Nueva encuesta
                    </a>
                    <a href="{{ route('surveys.index') }}" class="btn-secondary rounded-lg">
                        Mis encuestas
                    </a>
                </div>
            </div>

            <div class="border-t border-slate-200 bg-slate-50 p-6 sm:p-8 xl:border-l xl:border-t-0">
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estado del workspace</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">Listo para recolectar datos</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-100 text-teal-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">Encuestas publicadas</span>
                                <span class="font-semibold text-slate-950">{{ $publishedPercent }}%</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-teal-500" style="width: {{ $publishedPercent }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Borradores</p>
                                <p class="mt-1 text-2xl font-bold text-slate-950">{{ $draftCount }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Promedio resp.</p>
                                <p class="mt-1 text-2xl font-bold text-slate-950">{{ $responseRateHint }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Encuestas creadas</p>
                <span class="rounded-md bg-teal-50 px-2 py-1 text-xs font-semibold text-teal-700">Total</span>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $surveysCount }}</p>
            <p class="mt-1 text-sm text-slate-500">Formularios disponibles para editar o publicar.</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Respuestas recibidas</p>
                <span class="rounded-md bg-sky-50 px-2 py-1 text-xs font-semibold text-sky-700">Datos</span>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $totalResponses }}</p>
            <p class="mt-1 text-sm text-slate-500">Participaciones acumuladas en tus encuestas.</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Publicadas</p>
                <span class="rounded-md bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">En linea</span>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $publishedCount }}</p>
            <p class="mt-1 text-sm text-slate-500">Encuestas listas para compartir con participantes.</p>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-[minmax(0,1fr)_360px]">
        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-teal-700">Trabajo reciente</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-950">Encuestas en movimiento</h2>
                </div>
                <a href="{{ route('surveys.index') }}" class="btn-secondary rounded-lg px-4 py-2">Ver todas</a>
            </div>

            <div class="p-6">
                @if($recentSurveys->isEmpty())
                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-white text-teal-700 shadow-sm">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6M9 9h6M9 13h3M7 3h10a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 012-2z"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-slate-950">Todavia no hay encuestas</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                            Empieza con una pregunta clara. Luego podras agregar opciones, publicarla y revisar respuestas aqui.
                        </p>
                        <a href="{{ route('surveys.create') }}" class="btn-primary mt-5 rounded-lg bg-teal-600 shadow-none hover:bg-teal-700 hover:shadow-none">
                            Crear primera encuesta
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($recentSurveys as $survey)
                            <article class="flex flex-col gap-4 py-4 first:pt-0 last:pb-0 lg:flex-row lg:items-center lg:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="truncate text-base font-semibold text-slate-950">{{ $survey->title }}</h3>
                                        <span class="rounded-md {{ $survey->status === 'published' ? 'bg-teal-50 text-teal-700' : 'bg-slate-100 text-slate-600' }} px-2 py-1 text-xs font-semibold">
                                            {{ $survey->status === 'published' ? 'Publicada' : 'Borrador' }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm leading-6 text-slate-600">
                                        {{ $survey->description ? Str::limit($survey->description, 115) : 'Sin descripcion. Agrega contexto para que el objetivo de la encuesta sea claro.' }}
                                    </p>

                                    <div class="mt-3 flex flex-wrap gap-3 text-xs text-slate-500">
                                        <span>{{ $survey->responses_count }} respuestas</span>
                                        <span>Actualizada {{ $survey->updated_at?->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="flex shrink-0 flex-wrap gap-2">
                                    <a href="{{ route('builder.edit', $survey) }}" class="btn-secondary rounded-lg px-4 py-2">Editar</a>
                                    @if($survey->status === 'published' && $survey->share_token)
                                        <a href="{{ route('surveys.public.show', $survey->share_token) }}" target="_blank" class="btn-primary rounded-lg bg-slate-900 px-4 py-2 shadow-none hover:bg-slate-800 hover:shadow-none">
                                            Abrir
                                        </a>
                                    @endif
                                    <button
                                        onclick="confirmDelete({{ $survey->id }}, '{{ addslashes($survey->title) }}')"
                                        class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                                        aria-label="Eliminar encuesta"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <aside class="grid gap-6 md:grid-cols-2 2xl:block 2xl:space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-teal-700">Siguiente paso</p>
                <h2 class="mt-1 text-xl font-bold text-slate-950">Flujo normal de una encuesta</h2>

                <div class="mt-5 space-y-4">
                    @foreach([
                        ['label' => '1', 'title' => 'Escribe preguntas', 'desc' => 'Define que necesitas saber antes de compartir.'],
                        ['label' => '2', 'title' => 'Publica el enlace', 'desc' => 'Activa la encuesta y mandala a tus participantes.'],
                        ['label' => '3', 'title' => 'Lee resultados', 'desc' => 'Revisa respuestas para decidir el siguiente movimiento.'],
                    ] as $step)
                        <div class="flex gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">
                                {{ $step['label'] }}
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-950">{{ $step['title'] }}</h3>
                                <p class="mt-1 text-sm leading-5 text-slate-600">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-teal-700">Acciones rapidas</p>
                <div class="mt-4 grid gap-2">
                    <a href="{{ route('surveys.create') }}" class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-800 transition hover:border-teal-300 hover:bg-teal-50">
                        Crear encuesta
                        <span class="text-teal-700">+</span>
                    </a>
                    <a href="{{ route('templates.index') }}" class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-800 transition hover:border-teal-300 hover:bg-teal-50">
                        Usar plantilla
                        <span class="text-teal-700">></span>
                    </a>
                    <a href="{{ route('results.index') }}" class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-800 transition hover:border-teal-300 hover:bg-teal-50">
                        Ver respuestas
                        <span class="text-teal-700">></span>
                    </a>
                </div>
            </section>
        </aside>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14L12 5 5 19z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-950">Eliminar encuesta</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Vas a eliminar "<span id="deleteSurveyTitle" class="font-semibold"></span>". Esta accion no se puede deshacer.
                </p>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button onclick="closeDeleteModal()" class="btn-secondary flex-1 rounded-lg">Cancelar</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(surveyId, surveyTitle) {
    document.getElementById('deleteSurveyTitle').textContent = surveyTitle;
    document.getElementById('deleteForm').action = `/surveys/${surveyId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
