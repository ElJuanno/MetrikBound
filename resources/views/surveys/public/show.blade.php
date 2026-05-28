<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $survey->title }} — MetrikBound</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;
            background:
                radial-gradient(ellipse 70% 50% at 20% 0%, rgba(20,184,166,.16), transparent 60%),
                radial-gradient(ellipse 70% 50% at 85% 5%, rgba(14,165,233,.10), transparent 55%),
                linear-gradient(180deg, #f8fafc 0%, #eef4f7 100%);
            background-attachment: fixed;
        }

        .survey-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 48px 20px 80px;
        }

        /* Marca de agua */
        .watermark {
            position: fixed;
            bottom: 18px;
            right: 24px;
            font-size: 12px;
            font-weight: 700;
            color: rgba(15,23,42,.24);
            letter-spacing: .04em;
            pointer-events: none;
            user-select: none;
            z-index: 100;
        }

        /* Hoja */
        .survey-sheet {
            position: relative;
            width: 794px;
            min-height: 1123px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow:
                0 0 0 1px rgba(15,23,42,.06),
                0 24px 60px rgba(15,23,42,.14),
                0 8px 24px rgba(15,23,42,.08);
            overflow: visible;
        }

        /* Marca de agua en la hoja */
        .sheet-watermark {
            position: absolute;
            bottom: 18px;
            right: 24px;
            font-size: 11px;
            font-weight: 700;
            color: rgba(15,23,42,.12);
            letter-spacing: .06em;
            pointer-events: none;
            user-select: none;
        }

        /* Botón enviar */
        .submit-wrap {
            margin-top: 32px;
            text-align: center;
        }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 36px;
            background: #0f766e;
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(15,118,110,.24);
            transition: .18s ease;
            letter-spacing: .02em;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(15,118,110,.30);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Alertas */
        .alert {
            width: 794px;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-success {
            background: rgba(220,252,231,.95);
            color: #166534;
            border: 1px solid rgba(34,197,94,.25);
        }

        .alert-error {
            background: rgba(254,226,226,.95);
            color: #991b1b;
            border: 1px solid rgba(239,68,68,.25);
        }

        /* Inputs interactivos */
        .q-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid rgba(15,23,42,.12);
            border-radius: 12px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            background: linear-gradient(180deg, #f8fafc, #fff);
            transition: border-color .15s, box-shadow .15s;
        }

        .q-input:focus {
            border-color: rgba(99,102,241,.40);
            box-shadow: 0 0 0 3px rgba(99,102,241,.10);
        }

        .q-select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid rgba(15,23,42,.12);
            border-radius: 12px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            background: #fff;
            cursor: pointer;
            transition: border-color .15s;
        }

        .q-select:focus {
            border-color: rgba(99,102,241,.40);
            box-shadow: 0 0 0 3px rgba(99,102,241,.10);
        }

        .yesno-btn {
            flex: 1;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s;
            user-select: none;
        }

        .yesno-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,.10);
        }

        .yesno-btn.yes {
            border: 2px solid rgba(34,197,94,.25);
            color: #166534;
            background: linear-gradient(135deg, rgba(34,197,94,.08), rgba(34,197,94,.04));
        }

        .yesno-btn.no {
            border: 2px solid rgba(239,68,68,.25);
            color: #991b1b;
            background: linear-gradient(135deg, rgba(239,68,68,.08), rgba(239,68,68,.04));
        }

        .yesno-btn input:checked ~ span,
        .yesno-btn.selected {
            background: #0f766e !important;
            border-color: transparent !important;
            color: #fff !important;
            box-shadow: 0 4px 14px rgba(99,102,241,.35);
        }

        .scale-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1.5px solid rgba(15,23,42,.12);
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            font-weight: 800;
            font-size: 13px;
            transition: all .15s;
        }

        .scale-label:hover {
            border-color: rgba(99,102,241,.35);
            background: rgba(99,102,241,.06);
        }

        .scale-label input:checked + span {
            /* handled via JS */
        }

        .scale-label.selected {
            background: #0f766e;
            border-color: transparent;
            color: #fff;
            box-shadow: 0 3px 10px rgba(99,102,241,.30);
        }

        .star-label {
            cursor: pointer;
            transition: transform .12s;
        }

        .star-label:hover {
            transform: scale(1.15);
        }

        @media (max-width: 860px) {
            .survey-sheet, .alert { width: 100%; }
            .survey-wrap { padding: 24px 12px 60px; }
        }
    </style>
</head>
<body>
<div class="survey-wrap">
<style>
/* Estilos para botones interactivos */
.yesno-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}

.numeric-btn:hover,
.scale-btn:hover {
    background: rgba(20,184,166,.10) !important;
    border-color: rgba(20,184,166,.3) !important;
    transform: translateY(-1px);
}

.numeric-btn input:checked + span,
.scale-btn input:checked + span {
    background: #0f766e;
    color: white;
}
</style>

@php
    $page = $state['page'] ?? [];
    $pageWidth = 794;
    $pageHeight = 1123;

    $bg = '#ffffff';
    if (($page['bg']['type'] ?? null) === 'solid' && !empty($page['bg']['color'])) {
        $bg = $page['bg']['color'];
    }
@endphp

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('surveys.public.submit', $survey->share_token) }}" method="POST">
        @csrf
        @if(request()->has('mode'))
            <input type="hidden" name="mode" value="{{ request()->get('mode') }}">
        @endif

        <div class="survey-sheet" style="background:{{ $bg }};">
            <!-- Marca de agua en la hoja -->
            <div class="sheet-watermark">MetrikBound.com</div>
            @foreach($nodes as $i => $node)
                @php
                    $kind = $node['kind'] ?? '';
                    $props = $node['props'] ?? [];
                    $originalIndex = $node['originalIndex'] ?? $i;
                    
                    // Obtener el texto/título del bloque
                    $html = $props['html'] ?? $props['label'] ?? 'Pregunta';

                    $x = $node['x'] ?? 0;
                    $y = $node['y'] ?? 0;
                    $w = $node['w'] ?? 320;
                    $h = $node['h'] ?? 120;

                    $required = !empty($props['required']);
                    $inputName = "answers[".$originalIndex."]";
                    $options = $props['options'] ?? [];
                    $fontSize = $props['fontSize'] ?? 14;
                    $align = $props['align'] ?? 'left';
                    $color = $props['color'] ?? '#0f172a';
                @endphp

                @php
                    // Determinar si el bloque necesita padding
                    $noPadding = in_array($kind, ['header_band', 'footer_band', 'shape_rect', 'shape_triangle', 'shape_diagonal', 'img']);
                    
                    // Determinar si el bloque necesita altura fija
                    $needsHeight = !in_array($kind, ['q_radio', 'q_check', 'q_select', 'q_text', 'q_yesno', 'q_stars', 'q_numeric', 'q_date', 'q_scale']);
                    
                    // Obtener rotación si existe
                    $rotation = $props['rotation'] ?? 0;
                @endphp

                <div style="
                    position:absolute;
                    left: {{ $x }}px;
                    top: {{ $y }}px;
                    width: {{ $w }}px;
                    @if($needsHeight)
                    height: {{ $h }}px;
                    @endif
                    background:transparent;
                    border:none;
                    @if(!$noPadding)
                    padding:16px;
                    @endif
                    overflow:visible;
                ">
                    @if($kind === 'title')
                        <div style="
                            font-size: {{ $fontSize * 1.6 }}px;
                            font-weight: 900;
                            line-height: 1.12;
                            text-align: {{ $align }};
                            color: {{ $color }};
                            letter-spacing: -0.45px;
                        ">
                            {{ $html }}
                        </div>

                    @elseif($kind === 'text')
                        <div style="
                            font-size: {{ $fontSize }}px;
                            line-height: 1.65;
                            text-align: {{ $align }};
                            color: {{ $color }};
                        ">
                            {{ $html }}
                        </div>

                    @elseif($kind === 'divider')
                        <div style="
                            height: 2px;
                            background: #cbd5e1;
                            border-radius: 999px;
                            margin: 8px 0;
                        "></div>

                    @elseif($kind === 'divider')
                        @php
                            $variant = $props['dividerVariant'] ?? 'simple';
                            $dividerColor = $props['dividerColor'] ?? '#cbd5e1';
                            $thickness = $props['dividerThickness'] ?? 2;
                        @endphp
                        @if($variant === 'simple')
                            <div style="
                                height: {{ $thickness }}px;
                                background: {{ $dividerColor }};
                                border-radius: 999px;
                                margin: 8px 0;
                            "></div>
                        @elseif($variant === 'gradient')
                            <div style="
                                height: {{ $thickness }}px;
                                background: linear-gradient(90deg, transparent, {{ $dividerColor }}, transparent);
                                border-radius: 999px;
                                margin: 8px 0;
                            "></div>
                        @elseif($variant === 'dashed')
                            <div style="
                                height: {{ $thickness }}px;
                                background: repeating-linear-gradient(90deg, {{ $dividerColor }} 0, {{ $dividerColor }} 8px, transparent 8px, transparent 14px);
                                border-radius: 999px;
                                margin: 8px 0;
                            "></div>
                        @elseif($variant === 'double')
                            <div style="margin: 8px 0;">
                                <div style="height: {{ $thickness }}px; background: {{ $dividerColor }}; border-radius: 999px;"></div>
                                <div style="height: {{ max(1, $thickness - 1) }}px; background: {{ $dividerColor }}; border-radius: 999px; opacity: 0.5; margin-top: 3px;"></div>
                            </div>
                        @endif

                    @elseif($kind === 'img')
                        @if(!empty($props['img']))
                            <div style="
                                width: 100%;
                                height: 100%;
                                border-radius: 12px;
                                overflow: hidden;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <img src="{{ $props['img'] }}" alt="Imagen" style="
                                    width: 100%;
                                    height: 100%;
                                    object-fit: contain;
                                    display: block;
                                ">
                            </div>
                        @endif

                    @elseif($kind === 'q_text')
                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <input
                            type="text"
                            name="{{ $inputName }}"
                            placeholder="{{ $props['placeholder'] ?? 'Escribe tu respuesta' }}"
                            {{ $required ? 'required' : '' }}
                            class="q-input"
                        >

                    @elseif($kind === 'q_radio')
                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <div style="display:flex; flex-direction:column; gap:10px; margin-top:8px;">
                            @foreach($options as $option)
                                <label style="display:flex; align-items:center; gap:10px; font-size:{{ $fontSize }}px; cursor:pointer;">
                                    <input type="radio" name="{{ $inputName }}" value="{{ $option }}" {{ $required ? 'required' : '' }} style="width:14px; height:14px;">
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>

                    @elseif($kind === 'q_check')
                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <div style="display:flex; flex-direction:column; gap:10px; margin-top:8px;">
                            @foreach($options as $option)
                                <label style="display:flex; align-items:center; gap:10px; font-size:{{ $fontSize }}px; cursor:pointer;">
                                    <input type="checkbox" name="{{ $inputName }}[]" value="{{ $option }}" style="width:14px; height:14px;">
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>

                    @elseif($kind === 'q_select')
                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <select
                            name="{{ $inputName }}"
                            {{ $required ? 'required' : '' }}
                            class="q-select"
                        >
                            <option value="" disabled selected>Selecciona una opción</option>
                            @foreach($options as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>

                    @elseif($kind === 'q_date')
                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <input
                            type="date"
                            name="{{ $inputName }}"
                            {{ $required ? 'required' : '' }}
                            class="q-input"
                        >

                    @elseif($kind === 'q_scale')
                        @php
                            $min = (int)($props['min'] ?? 1);
                            $max = (int)($props['max'] ?? 5);
                        @endphp

                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:10px;">
                            @for($r = $min; $r <= $max; $r++)
                                <label class="scale-label">
                                    <input type="radio" name="{{ $inputName }}" value="{{ $r }}" style="display:none;" {{ $required ? 'required' : '' }}>
                                    <span>{{ $r }}</span>
                                </label>
                            @endfor
                        </div>

                    @elseif($kind === 'q_yesno')
                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <div style="display:flex; gap:10px; margin-top:10px;">
                            <label class="yesno-btn yes">
                                <input type="radio" name="{{ $inputName }}" value="Sí" style="display:none;" {{ $required ? 'required' : '' }}>
                                <span>Sí</span>
                            </label>
                            <label class="yesno-btn no">
                                <input type="radio" name="{{ $inputName }}" value="No" style="display:none;" {{ $required ? 'required' : '' }}>
                                <span>No</span>
                            </label>
                        </div>

                    @elseif($kind === 'q_stars')
                        @php
                            $stars = (int)($props['stars'] ?? 5);
                        @endphp

                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        <div style="display:flex; gap:6px; margin-top:10px; justify-content:center;">
                            @for($s = 1; $s <= $stars; $s++)
                                <label class="star-label">
                                    <input type="radio" name="{{ $inputName }}" value="{{ $s }}" style="display:none;" {{ $required ? 'required' : '' }}>
                                    <svg style="width:32px;height:32px;fill:#e5e7eb;transition:fill 0.2s;" class="star-icon" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>

                    @elseif($kind === 'q_numeric')
                        @php
                            $min = (int)($props['min'] ?? 1);
                            $max = (int)($props['max'] ?? 10);
                            $range = $max - $min + 1;
                        @endphp

                        <label style="display:block; font-weight:900; font-size:{{ $fontSize * 1.15 }}px; margin-bottom:10px; color:{{ $color }};">
                            {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                        </label>

                        @if($range <= 15)
                            <div style="display:flex; gap:6px; flex-wrap:wrap; margin-top:10px;">
                                @for($n = $min; $n <= $max; $n++)
                                    <label class="scale-label numeric-btn">
                                        <input type="radio" name="{{ $inputName }}" value="{{ $n }}" style="display:none;" {{ $required ? 'required' : '' }}>
                                        <span>{{ $n }}</span>
                                    </label>
                                @endfor
                            </div>
                        @else
                            <div style="margin-top:10px;">
                                <input 
                                    type="range" 
                                    name="{{ $inputName }}" 
                                    min="{{ $min }}" 
                                    max="{{ $max }}" 
                                    value="{{ $min }}"
                                    {{ $required ? 'required' : '' }}
                                    style="width:100%;accent-color:#0f766e;"
                                    oninput="this.nextElementSibling.querySelector('span').textContent = this.value"
                                >
                                <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:13px;color:#64748b;font-weight:800;">
                                    <span>{{ $min }}</span>
                                    <span>{{ $min }}</span>
                                    <span>{{ $max }}</span>
                                </div>
                            </div>
                        @endif

                    @elseif($kind === 'header_band')
                        @php
                            $bgColor = $props['bg'] ?? '#3f73c9';
                            $textColor = $props['color'] ?? '#ffffff';
                            $rotation = $props['rotation'] ?? 0;
                        @endphp
                        <div style="
                            width: 100%;
                            height: 100%;
                            background: {{ $bgColor }};
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            padding: 20px;
                            font-size: {{ $fontSize }}px;
                            font-weight: 900;
                            color: {{ $textColor }};
                            text-align: center;
                            letter-spacing: 0.5px;
                            transform: rotate({{ $rotation }}deg);
                        ">
                            {{ $html }}
                        </div>

                    @elseif($kind === 'footer_band')
                        @php
                            $bgColor = $props['bg'] ?? '#3f73c9';
                            $rotation = $props['rotation'] ?? 0;
                        @endphp
                        <div style="
                            width: 100%;
                            height: 100%;
                            background: {{ $bgColor }};
                            transform: rotate({{ $rotation }}deg);
                        "></div>

                    @elseif($kind === 'shape_rect')
                        @php
                            $bgColor = $props['bg'] ?? '#3f73c9';
                            $borderColor = $props['borderColor'] ?? '#3f73c9';
                            $borderWidth = $props['borderWidth'] ?? 0;
                            $rotation = $props['rotation'] ?? 0;
                        @endphp
                        <div style="
                            width: 100%;
                            height: 100%;
                            background: {{ $bgColor }};
                            border: {{ $borderWidth }}px solid {{ $borderColor }};
                            border-radius: 8px;
                            transform: rotate({{ $rotation }}deg);
                        "></div>

                    @elseif($kind === 'shape_triangle')
                        @php
                            $bgColor = $props['bg'] ?? '#3f73c9';
                            $rotation = $props['rotation'] ?? 0;
                        @endphp
                        <div style="
                            width: 100%;
                            height: 100%;
                            transform: rotate({{ $rotation }}deg);
                        ">
                            <svg viewBox="0 0 100 100" style="width:100%;height:100%;" preserveAspectRatio="none">
                                <polygon points="50,10 90,90 10,90" fill="{{ $bgColor }}" />
                            </svg>
                        </div>

                    @elseif($kind === 'shape_diagonal')
                        @php
                            $bgColor = $props['bg'] ?? '#3f73c9';
                            $rotation = $props['rotation'] ?? 0;
                        @endphp
                        <div style="
                            width: 100%;
                            height: 100%;
                            transform: rotate({{ $rotation }}deg);
                        ">
                            <svg viewBox="0 0 100 100" style="width:100%;height:100%;" preserveAspectRatio="none">
                                <polygon points="0,0 100,0 100,100" fill="{{ $bgColor }}" />
                            </svg>
                        </div>

                    @endif
                </div>
            @endforeach
        </div>{{-- /survey-sheet --}}

        <div class="submit-wrap">
            <button type="submit" class="submit-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Enviar respuestas
            </button>
        </div>
    </form>

    <!-- Marca de agua flotante -->
    <div class="watermark">MetrikBound.com</div>

</div>{{-- /survey-wrap --}}

<script>
// Interactividad: scale, yesno, numeric, stars
document.addEventListener('DOMContentLoaded', () => {

    // Scale / numeric: toggle selected class
    document.querySelectorAll('.scale-label, .numeric-btn').forEach(label => {
        label.addEventListener('click', () => {
            const input = label.querySelector('input[type="radio"]');
            if (!input) return;
            const name = input.name;
            document.querySelectorAll(`input[name="${CSS.escape(name)}"]`).forEach(r => {
                r.closest('.scale-label, .numeric-btn')?.classList.remove('selected');
            });
            label.classList.add('selected');
        });
    });

    // Yesno: toggle selected
    document.querySelectorAll('.yesno-btn').forEach(label => {
        label.addEventListener('click', () => {
            const input = label.querySelector('input[type="radio"]');
            if (!input) return;
            const name = input.name;
            document.querySelectorAll(`input[name="${CSS.escape(name)}"]`).forEach(r => {
                r.closest('.yesno-btn')?.classList.remove('selected');
            });
            label.classList.add('selected');
        });
    });

    // Stars: hover + click
    document.querySelectorAll('.star-label').forEach((label, idx, all) => {
        const input = label.querySelector('input[type="radio"]');
        if (!input) return;
        const name = input.name;
        const group = Array.from(document.querySelectorAll(`input[name="${CSS.escape(name)}"]`))
            .map(i => i.closest('.star-label'));

        label.addEventListener('mouseenter', () => {
            group.forEach((l, i) => {
                const svg = l.querySelector('.star-icon');
                if (svg) svg.style.fill = i <= idx ? '#fbbf24' : '#e5e7eb';
            });
        });

        label.addEventListener('mouseleave', () => {
            const checked = group.findIndex(l => l.querySelector('input')?.checked);
            group.forEach((l, i) => {
                const svg = l.querySelector('.star-icon');
                if (svg) svg.style.fill = (checked >= 0 && i <= checked) ? '#fbbf24' : '#e5e7eb';
            });
        });

        label.addEventListener('click', () => {
            group.forEach((l, i) => {
                const svg = l.querySelector('.star-icon');
                if (svg) svg.style.fill = i <= idx ? '#fbbf24' : '#e5e7eb';
            });
        });
    });
});
</script>
</body>
</html>
