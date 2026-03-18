@extends('layouts.app')
@section('title', 'Nueva encuesta')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    {{-- encabezado --}}
    <div>
        <div class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[.18em] text-indigo-600">
            Crear encuesta
        </div>
        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
            Nueva encuesta
        </h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Se guardará como borrador y después te llevará directo al builder para seguir diseñándola.
        </p>
    </div>

    {{-- card principal --}}
    <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
            <h2 class="text-lg font-semibold text-slate-900">Información básica</h2>
            <p class="mt-1 text-sm text-slate-500">
                Define el nombre y una breve descripción para identificar tu encuesta.
            </p>
        </div>

        <form method="POST" action="{{ route('surveys.store') }}" class="px-6 py-6 sm:px-8 sm:py-8">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="title" class="mb-2 block text-sm font-semibold text-slate-800">
                        Título
                    </label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                        required
                        placeholder="Ej. Encuesta de satisfacción"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 outline-none transition focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-100"
                    >
                    @error('title')
                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-800">
                        Descripción <span class="font-normal text-slate-400">(opcional)</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="¿Para qué es esta encuesta?"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 outline-none transition focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-100"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <label class="flex items-start gap-3">
                        <input
                            type="checkbox"
                            name="is_public"
                            value="1"
                            checked
                            class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <span>
                            <span class="block text-sm font-semibold text-slate-800">
                                Encuesta pública
                            </span>
                            <span class="block text-sm text-slate-500">
                                Permitirá compartirla mediante enlace cuando esté lista.
                            </span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('surveys.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_14px_30px_rgba(99,102,241,.25)] transition hover:-translate-y-0.5"
                >
                    Crear y abrir builder →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection