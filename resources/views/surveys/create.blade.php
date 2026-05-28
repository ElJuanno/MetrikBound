@extends('layouts.app')
@section('title', 'Nueva encuesta')

@section('content')
<div class="page-shell max-w-5xl">
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <section>
            <div class="page-kicker">Crear encuesta</div>
            <h1 class="page-title">Dale nombre a la encuesta.</h1>
            <p class="page-subtitle">Primero define el objetivo. Despues abriras el builder para agregar preguntas, diseno y opciones de publicacion.</p>

            <form method="POST" action="{{ route('surveys.store') }}" class="mt-7 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="title" class="mb-2 block text-sm font-semibold text-slate-800">Titulo de la encuesta</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" required placeholder="Ej. Satisfaccion despues de compra" class="input">
                        @error('title')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-slate-800">Descripcion <span class="font-normal text-slate-400">(opcional)</span></label>
                        <textarea id="description" name="description" rows="5" placeholder="Explica para que sirve y que quieres medir." class="input">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <input type="checkbox" name="is_public" value="1" checked class="mt-1 h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">Preparar para compartir con enlace</span>
                            <span class="mt-1 block text-sm text-slate-600">Podras publicar o ajustar el acceso antes de enviarla.</span>
                        </span>
                    </label>
                </div>

                <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('surveys.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Crear y abrir builder</button>
                </div>
            </form>
        </section>

        <aside class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-teal-700">Checklist</p>
            <div class="mt-5 space-y-4">
                @foreach([
                    ['title' => 'Objetivo', 'desc' => 'Que necesitas aprender?'],
                    ['title' => 'Audiencia', 'desc' => 'Quien respondera?'],
                    ['title' => 'Accion', 'desc' => 'Que haras con los datos?'],
                ] as $item)
                    <div class="flex gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-teal-500"></span>
                        <div>
                            <p class="text-sm font-semibold text-slate-950">{{ $item['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>
    </div>
</div>
@endsection
