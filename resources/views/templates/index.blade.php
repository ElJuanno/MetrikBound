@extends('layouts.app')
@section('title', 'Plantillas')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <span class="badge-primary">
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 010 2H6v10h4a1 1 0 110 2H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 110 2h-3v10h3a1 1 0 110 2h-4a1 1 0 01-1-1V5z"/>
                </svg>
                Plantillas
            </span>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900">
                Plantillas prediseñadas
            </h1>
            <p class="mt-2 text-base text-slate-600 leading-relaxed max-w-2xl">
                Comienza rápido con plantillas profesionales listas para usar. Personalízalas según tus necesidades.
            </p>
        </div>

        <a href="{{ route('surveys.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Crear desde cero
        </a>
    </div>

    {{-- Categorías --}}
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin">
        <button class="badge-primary whitespace-nowrap">Todas</button>
        <button class="badge-gray whitespace-nowrap hover:bg-slate-200 transition-colors cursor-pointer">Satisfacción</button>
        <button class="badge-gray whitespace-nowrap hover:bg-slate-200 transition-colors cursor-pointer">Registro</button>
        <button class="badge-gray whitespace-nowrap hover:bg-slate-200 transition-colors cursor-pointer">Feedback</button>
        <button class="badge-gray whitespace-nowrap hover:bg-slate-200 transition-colors cursor-pointer">Eventos</button>
        <button class="badge-gray whitespace-nowrap hover:bg-slate-200 transition-colors cursor-pointer">Educación</button>
    </div>

    {{-- Grid de Plantillas --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        
        @php
        $templates = [
            [
                'title' => 'Encuesta de Satisfacción del Cliente',
                'desc' => 'Mide la satisfacción de tus clientes con NPS y preguntas de seguimiento',
                'questions' => 8,
                'time' => '3 min',
                'color' => 'from-indigo-500 to-violet-500',
                'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'category' => 'Satisfacción'
            ],
            [
                'title' => 'Formulario de Registro de Evento',
                'desc' => 'Recopila información de asistentes a eventos, conferencias o webinars',
                'questions' => 6,
                'time' => '2 min',
                'color' => 'from-cyan-500 to-blue-500',
                'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'category' => 'Eventos'
            ],
            [
                'title' => 'Feedback de Producto',
                'desc' => 'Obtén opiniones sobre características, usabilidad y mejoras',
                'questions' => 10,
                'time' => '4 min',
                'color' => 'from-emerald-500 to-green-500',
                'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
                'category' => 'Feedback'
            ],
            [
                'title' => 'Evaluación de Empleados',
                'desc' => 'Evalúa el desempeño y satisfacción de tu equipo de trabajo',
                'questions' => 12,
                'time' => '5 min',
                'color' => 'from-purple-500 to-pink-500',
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'category' => 'Recursos Humanos'
            ],
            [
                'title' => 'Encuesta de Mercado',
                'desc' => 'Investiga preferencias y comportamiento de tu mercado objetivo',
                'questions' => 15,
                'time' => '6 min',
                'color' => 'from-amber-500 to-orange-500',
                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                'category' => 'Marketing'
            ],
            [
                'title' => 'Contacto y Soporte',
                'desc' => 'Formulario simple para consultas, dudas y solicitudes de ayuda',
                'questions' => 5,
                'time' => '2 min',
                'color' => 'from-rose-500 to-red-500',
                'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'category' => 'Soporte'
            ],
            [
                'title' => 'Evaluación de Curso',
                'desc' => 'Recopila feedback de estudiantes sobre cursos y capacitaciones',
                'questions' => 9,
                'time' => '4 min',
                'color' => 'from-teal-500 to-cyan-500',
                'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                'category' => 'Educación'
            ],
            [
                'title' => 'Solicitud de Empleo',
                'desc' => 'Recopila información de candidatos para procesos de selección',
                'questions' => 11,
                'time' => '5 min',
                'color' => 'from-blue-500 to-indigo-500',
                'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'category' => 'Recursos Humanos'
            ],
            [
                'title' => 'Reserva de Cita',
                'desc' => 'Permite a usuarios agendar citas con selección de fecha y hora',
                'questions' => 7,
                'time' => '3 min',
                'color' => 'from-violet-500 to-purple-500',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'category' => 'Servicios'
            ],
            [
                'title' => 'Encuesta Post-Compra',
                'desc' => 'Obtén feedback sobre la experiencia de compra y el producto',
                'questions' => 8,
                'time' => '3 min',
                'color' => 'from-green-500 to-emerald-500',
                'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
                'category' => 'E-commerce'
            ]
        ];
        @endphp

        @foreach($templates as $template)
        <div class="card-hover group cursor-pointer">
            {{-- Preview Image/Icon --}}
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br {{ $template['color'] }} p-8 mb-4">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_50%)]"></div>
                <div class="relative flex items-center justify-center">
                    <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $template['icon'] }}"/>
                    </svg>
                </div>
            </div>

            {{-- Content --}}
            <div>
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                        {{ $template['title'] }}
                    </h3>
                    <span class="badge-gray shrink-0">{{ $template['category'] }}</span>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    {{ $template['desc'] }}
                </p>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $template['questions'] }} preguntas
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $template['time'] }}
                        </span>
                    </div>

                    <a href="{{ route('surveys.create') }}" class="btn-ghost text-indigo-600 hover:bg-indigo-50">
                        Usar plantilla
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    {{-- CTA Section --}}
    <div class="card bg-gradient-to-br from-slate-900 to-indigo-950 text-white border-0">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-xl">
                <h2 class="text-2xl font-bold">¿No encuentras lo que buscas?</h2>
                <p class="mt-2 text-slate-300 leading-relaxed">
                    Crea tu propia encuesta desde cero con nuestro editor visual. Tienes control total sobre cada pregunta y diseño.
                </p>
            </div>
            <a href="{{ route('surveys.create') }}" class="btn-primary bg-white text-slate-900 hover:bg-slate-100 shadow-xl">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear encuesta personalizada
            </a>
        </div>
    </div>

</div>
@endsection
