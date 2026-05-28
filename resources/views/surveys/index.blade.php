@extends('layouts.app')
@section('title', 'Encuestas')

@section('content')
<div class="page-shell">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="page-kicker">Encuestas</div>
            <h1 class="page-title">Tus formularios de captura.</h1>
            <p class="page-subtitle">Administra borradores, abre el builder y revisa que encuestas ya estan listas para compartir.</p>
        </div>
        <a href="{{ route('surveys.create') }}" class="btn-primary">Nueva encuesta</a>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="grid grid-cols-1 border-b border-slate-100 text-sm font-semibold text-slate-500 lg:grid-cols-[minmax(0,1fr)_140px_140px_220px]">
            <div class="px-6 py-4">Encuesta</div>
            <div class="hidden px-6 py-4 lg:block">Estado</div>
            <div class="hidden px-6 py-4 lg:block">Acceso</div>
            <div class="hidden px-6 py-4 text-right lg:block">Acciones</div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($surveys as $s)
                <article class="grid gap-4 px-6 py-5 lg:grid-cols-[minmax(0,1fr)_140px_140px_220px] lg:items-center">
                    <div class="min-w-0">
                        <h2 class="truncate text-base font-bold text-slate-950">{{ $s->title }}</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ Str::limit($s->description ?: 'Sin descripcion. Agrega contexto en el builder para que sea clara.', 130) }}</p>
                    </div>

                    <div>
                        <span class="rounded-full {{ $s->status === 'published' ? 'bg-teal-50 text-teal-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-semibold">
                            {{ $s->status === 'published' ? 'Publicada' : 'Borrador' }}
                        </span>
                    </div>

                    <div>
                        <span class="rounded-full {{ $s->is_public ? 'bg-sky-50 text-sky-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-semibold">
                            {{ $s->is_public ? 'Publica' : 'Privada' }}
                        </span>
                    </div>

                    <div class="flex flex-wrap justify-start gap-2 lg:justify-end">
                        <a href="{{ route('builder.edit', $s) }}" class="btn-secondary px-4 py-2">Editar</a>
                        <button onclick="confirmDelete({{ $s->id }}, '{{ addslashes($s->title) }}')" class="rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50" aria-label="Eliminar encuesta">
                            Eliminar
                        </button>
                    </div>
                </article>
            @empty
                <div class="p-10 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-teal-50 text-2xl font-bold text-teal-700">+</div>
                    <h2 class="mt-4 text-xl font-bold text-slate-950">Todavia no tienes encuestas</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">Crea la primera y abre el builder para agregar preguntas.</p>
                    <a href="{{ route('surveys.create') }}" class="btn-primary mt-5">Crear primera encuesta</a>
                </div>
            @endforelse
        </div>

        @if($surveys->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $surveys->links() }}
            </div>
        @endif
    </section>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl">
        <h3 class="text-lg font-bold text-slate-950">Eliminar encuesta</h3>
        <p class="mt-2 text-sm leading-6 text-slate-600">Vas a eliminar "<span id="deleteSurveyTitle" class="font-semibold"></span>". Esta accion no se puede deshacer.</p>
        <div class="mt-6 flex gap-3">
            <button onclick="closeDeleteModal()" class="btn-secondary flex-1">Cancelar</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">Eliminar</button>
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
</script>
@endsection
