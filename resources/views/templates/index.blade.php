@extends('layouts.app')
@section('title', 'Plantillas')

@section('content')
@php
    $templates = \App\Http\Controllers\SurveyController::availableTemplates();
@endphp

<div class="page-shell">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="page-kicker">Plantillas</div>
            <h1 class="page-title">Plantillas listas para usar.</h1>
            <p class="page-subtitle">Cada plantilla crea una encuesta real con preguntas, opciones y diseno base compatible con el builder.</p>
        </div>
        <a href="{{ route('surveys.create') }}" class="btn-primary">Crear desde cero</a>
    </div>

    <div class="grid gap-5 md:grid-cols-2 2xl:grid-cols-3">
        @foreach($templates as $id => $template)
            <article class="flex min-h-[260px] flex-col rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl text-sm font-bold text-white" style="background: {{ $template['accent'] ?? '#0f766e' }}">
                        {{ $template['icon'] ?? str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $template['tag'] }}</span>
                </div>

                <h2 class="mt-6 text-lg font-bold text-slate-950">{{ $template['title'] }}</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $template['description'] }}</p>

                <div class="mt-auto flex items-center justify-between gap-4 border-t border-slate-100 pt-5">
                    <div class="text-sm">
                        <span class="font-semibold text-slate-950">{{ count($template['questions']) }}</span>
                        <span class="text-slate-500">preguntas</span>
                    </div>
                    <button onclick="openTemplateModal('{{ $id }}', '{{ addslashes($template['title']) }}')" class="btn-secondary px-4 py-2">
                        Usar plantilla
                    </button>
                </div>
            </article>
        @endforeach
    </div>
</div>

<div id="templateModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
        <h3 class="text-lg font-bold text-slate-950">Crear encuesta desde plantilla</h3>
        <p class="mt-1 text-sm text-slate-600" id="templateName"></p>

        <form id="templateForm" method="POST" action="{{ route('surveys.createFromTemplate') }}" class="mt-5 space-y-4">
            @csrf
            <input type="hidden" name="template_id" id="templateId">

            <div>
                <label for="surveyTitle" class="mb-2 block text-sm font-semibold text-slate-800">Nombre de la encuesta</label>
                <input id="surveyTitle" name="title" type="text" required maxlength="200" class="input" placeholder="Ej. Satisfaccion Q1">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeTemplateModal()" class="btn-secondary flex-1">Cancelar</button>
                <button type="submit" class="btn-primary flex-1">Crear</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTemplateModal(templateId, templateTitle) {
    document.getElementById('templateId').value = templateId;
    document.getElementById('templateName').textContent = templateTitle;
    document.getElementById('surveyTitle').value = templateTitle;
    document.getElementById('templateModal').classList.remove('hidden');
    document.getElementById('templateModal').classList.add('flex');
    document.getElementById('surveyTitle').focus();
}

function closeTemplateModal() {
    document.getElementById('templateModal').classList.add('hidden');
    document.getElementById('templateModal').classList.remove('flex');
    document.getElementById('templateForm').reset();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTemplateModal();
    }
});
</script>
@endsection
