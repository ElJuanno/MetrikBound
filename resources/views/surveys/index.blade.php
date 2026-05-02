@extends('layouts.app')
@section('title', 'Mis encuestas')

@section('content')
<div class="space-y-6">

    {{-- encabezado --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[.18em] text-indigo-600">
                Encuestas
            </div>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                Mis encuestas
            </h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Crea, organiza, edita y abre tus encuestas desde un solo lugar.
            </p>
        </div>

        <a
            href="{{ route('surveys.create') }}"
            class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_14px_30px_rgba(99,102,241,.25)] transition hover:-translate-y-0.5"
        >
            + Nueva encuesta
        </a>
    </div>

    {{-- listado --}}
    <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
            <h2 class="text-lg font-semibold text-slate-900">Listado</h2>
            <p class="mt-1 text-sm text-slate-500">
                Aquí podrás seguir trabajando en tus formularios.
            </p>
        </div>

        <div class="px-6 py-4 sm:px-8">
            @forelse($surveys as $s)
                <div class="flex flex-col gap-4 border-b border-slate-100 py-5 last:border-b-0 md:flex-row md:items-center md:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="truncate text-base font-semibold text-slate-900">
                                {{ $s->title }}
                            </h3>

                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                {{ $s->status }}
                            </span>

                            <span class="rounded-full {{ $s->is_public ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide">
                                {{ $s->is_public ? 'Pública' : 'Privada' }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            {{ $s->description ?: 'Sin descripción.' }}
                        </p>
                    </div>

                    <div class="flex shrink-0 gap-3">
                        <button
                            onclick="confirmDelete({{ $s->id }}, '{{ addslashes($s->title) }}')"
                            class="inline-flex items-center justify-center rounded-2xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        
                        <a
                            href="{{ route('builder.edit', $s) }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Abrir builder →
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-14 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-500 text-2xl font-bold text-white shadow-lg">
                        +
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-slate-900">
                        Aún no tienes encuestas
                    </h3>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-slate-500">
                        Crea la primera y comienza a diseñarla desde el builder visual.
                    </p>
                    <a
                        href="{{ route('surveys.create') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_14px_30px_rgba(99,102,241,.25)] transition hover:-translate-y-0.5"
                    >
                        Crear primera encuesta
                    </a>
                </div>
            @endforelse
        </div>

        @if($surveys->hasPages())
            <div class="border-t border-slate-100 px-6 py-4 sm:px-8">
                {{ $surveys->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal de confirmación de eliminación --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 animate-fade-in">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900">¿Eliminar encuesta?</h3>
                <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                    Estás a punto de eliminar "<span id="deleteSurveyTitle" class="font-semibold"></span>". Esta acción no se puede deshacer.
                </p>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button onclick="closeDeleteModal()" class="flex-1 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
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
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection