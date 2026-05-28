@extends('layouts.app')
@section('title', 'Detalle de resultados')

@section('content')
@php
    $responseCount = $responses->count();
    $questionCount = count($questions);
    $answeredQuestions = collect($stats)->where('total_responses', '>', 0)->count();
    $latestResponse = $responses->first();
    $questionTypes = [
        'q_text' => 'Texto abierto',
        'q_radio' => 'Opcion unica',
        'q_check' => 'Seleccion multiple',
        'q_select' => 'Lista desplegable',
        'q_yesno' => 'Si / No',
        'q_scale' => 'Escala',
        'q_numeric' => 'Numero',
        'q_stars' => 'Estrellas',
        'q_date' => 'Fecha',
    ];
@endphp

<div class="page-shell">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="{{ route('results.index') }}" class="text-sm font-bold text-teal-700 hover:text-teal-800">Volver a resultados</a>
            <h1 class="page-title">{{ $survey->title }}</h1>
            <p class="page-subtitle">{{ $survey->description ?: 'Resultados recibidos para esta encuesta.' }}</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($survey->share_token)
                <a href="{{ route('surveys.public.show', $survey->share_token) }}?mode=anonymous" target="_blank" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm hover:border-teal-200 hover:text-teal-700">
                    Ver encuesta
                </a>
            @endif
            <a href="{{ route('builder.edit', $survey) }}" class="btn-primary">Editar encuesta</a>
        </div>
    </div>

    <section class="grid gap-4" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Respuestas</p>
                    <p class="mt-2 text-3xl font-black text-slate-950">{{ $responseCount }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-teal-50 text-sm font-black text-teal-700">R</div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Preguntas</p>
                    <p class="mt-2 text-3xl font-black text-slate-950">{{ $questionCount }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-sky-50 text-sm font-black text-sky-700">P</div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Con datos</p>
                    <p class="mt-2 text-3xl font-black text-slate-950">{{ $answeredQuestions }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-sm font-black text-emerald-700">D</div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Ultima respuesta</p>
                    <p class="mt-2 truncate text-lg font-black text-slate-950">{{ $latestResponse?->completed_at?->diffForHumans() ?? 'Sin respuestas' }}</p>
                    @if($latestResponse?->completed_at)
                        <p class="mt-1 text-sm text-slate-500">{{ $latestResponse->completed_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-sm font-black text-amber-700">U</div>
            </div>
        </div>
    </section>

    @if($responses->isEmpty())
        <section class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
            <h2 class="text-xl font-bold text-slate-950">Aun no hay respuestas</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">Cuando alguien responda, aqui veras porcentajes, promedios y respuestas abiertas por pregunta.</p>
            @if($survey->share_token)
                <a href="{{ route('surveys.public.show', $survey->share_token) }}?mode=anonymous" target="_blank" class="btn-primary mt-5">Abrir encuesta</a>
            @endif
        </section>
    @else
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-950">Lectura rapida</h2>
                    <p class="mt-1 text-sm text-slate-500">Resumen general antes de revisar pregunta por pregunta.</p>
                </div>
                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-700">{{ $responseCount }} respuestas recibidas</span>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-3">
                <div class="rounded-lg bg-slate-50 p-4">
                    <p class="text-sm font-bold text-slate-700">Participacion</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $answeredQuestions }} de {{ $questionCount }} preguntas ya tienen al menos una respuesta.</p>
                </div>
                <div class="rounded-lg bg-slate-50 p-4">
                    <p class="text-sm font-bold text-slate-700">Tipo de encuesta</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $survey->response_mode === 'registered' ? 'Con registro de usuario.' : 'Anonima o de acceso libre.' }}</p>
                </div>
                <div class="rounded-lg bg-slate-50 p-4">
                    <p class="text-sm font-bold text-slate-700">Estado</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $survey->status === 'published' ? 'Publicada y lista para recibir respuestas.' : 'Esta en borrador, pero puedes verla en modo preview.' }}</p>
                </div>
            </div>
        </section>

        <div class="space-y-5">
            @foreach($stats as $position => $stat)
                @php
                    $typeLabel = $questionTypes[$stat['kind']] ?? $stat['kind'];
                    $topOption = collect($stat['data'] ?? [])->sortByDesc('count')->first();
                    $isChoice = in_array($stat['kind'], ['q_radio', 'q_check', 'q_select', 'q_yesno'], true);
                    $isNumber = in_array($stat['kind'], ['q_scale', 'q_numeric', 'q_stars'], true);
                @endphp

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50/70 p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-black text-slate-500 shadow-sm">Pregunta {{ $position + 1 }}</span>
                                    <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black text-teal-700">{{ $typeLabel }}</span>
                                </div>
                                <h2 class="mt-3 text-xl font-black leading-7 text-slate-950">{{ $stat['question'] }}</h2>
                            </div>
                            <div class="shrink-0 text-left sm:text-right">
                                <p class="text-2xl font-black text-slate-950">{{ $stat['total_responses'] }}</p>
                                <p class="text-sm font-semibold text-slate-500">respuestas</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($stat['total_responses'] === 0)
                            <p class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">Todavia nadie respondio esta pregunta.</p>
                        @elseif($isChoice)
                            @if($topOption)
                                <div class="mb-5 rounded-lg bg-teal-50 p-4">
                                    <p class="text-sm font-bold text-teal-900">Respuesta mas elegida</p>
                                    <p class="mt-1 text-lg font-black text-teal-950">{{ $topOption['label'] }} <span class="text-sm font-bold text-teal-700">({{ $topOption['percentage'] }}%)</span></p>
                                </div>
                            @endif

                            <div class="space-y-4">
                                @foreach($stat['data'] as $option)
                                    <div>
                                        <div class="mb-2 flex items-center justify-between gap-4 text-sm">
                                            <span class="font-bold text-slate-900">{{ $option['label'] }}</span>
                                            <span class="shrink-0 font-black text-slate-700">{{ $option['count'] }} resp. · {{ $option['percentage'] }}%</span>
                                        </div>
                                        <div class="h-3 rounded-full bg-slate-100">
                                            <div class="h-3 rounded-full bg-teal-500" style="width: {{ $option['percentage'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($isNumber)
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
                                <div class="rounded-xl bg-slate-950 p-5 text-white lg:w-52">
                                    <p class="text-sm font-bold text-slate-300">Promedio</p>
                                    <p class="mt-2 text-5xl font-black">{{ $stat['average'] }}</p>
                                    <p class="mt-2 text-sm text-slate-300">sobre la escala configurada.</p>
                                </div>

                                <div class="grid flex-1 grid-cols-2 gap-3 sm:grid-cols-5 lg:grid-cols-10">
                                    @foreach($stat['data'] as $scale)
                                        <div class="rounded-lg border border-slate-200 bg-white p-3 text-center shadow-sm">
                                            <p class="text-xs font-black uppercase tracking-wide text-slate-400">{{ $scale['label'] }}</p>
                                            <p class="mt-2 text-2xl font-black text-slate-950">{{ $scale['count'] }}</p>
                                            <p class="mt-1 text-xs font-bold text-slate-500">{{ $scale['percentage'] }}%</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="grid gap-3">
                                @forelse($stat['data'] as $answer)
                                    <article class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-sm leading-6 text-slate-800">{{ $answer['value'] ?: '(Sin respuesta)' }}</p>
                                        @if(isset($answer['date']))
                                            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-slate-400">{{ \Carbon\Carbon::parse($answer['date'])->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </article>
                                @empty
                                    <p class="text-sm text-slate-500">No hay respuestas para esta pregunta.</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>

        @php
            $hasRegistered = $responses->contains(fn($r) => $r->user_id !== null || !empty($r->meta_json['user_email']));
        @endphp

        @if($hasRegistered)
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-black text-slate-950">Respondentes registrados</h2>
                <p class="mt-1 text-sm text-slate-500">Personas identificadas que contestaron esta encuesta.</p>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="py-3 pr-4">Nombre</th>
                                <th class="py-3 pr-4">Correo</th>
                                <th class="py-3 pr-4">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($responses as $resp)
                                @php
                                    $rName  = $resp->user?->name  ?? ($resp->meta_json['user_name']  ?? null);
                                    $rEmail = $resp->user?->email ?? ($resp->meta_json['user_email'] ?? null);
                                @endphp
                                @if($rName || $rEmail)
                                    <tr>
                                        <td class="py-3 pr-4 font-bold text-slate-950">{{ $rName ?? '-' }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ $rEmail ?? '-' }}</td>
                                        <td class="py-3 pr-4 text-slate-500">{{ $resp->completed_at ? $resp->completed_at->format('d/m/Y H:i') : '-' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    @endif
</div>
@endsection
