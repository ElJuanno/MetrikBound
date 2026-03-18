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
@endsection