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
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(99,102,241,.18), transparent 60%),
                radial-gradient(ellipse 70% 50% at 85% 5%,  rgba(139,92,246,.14), transparent 55%),
                radial-gradient(ellipse 60% 80% at 50% 100%, rgba(34,211,238,.10), transparent 60%),
                linear-gradient(160deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #1e1b4b 100%);
            background-attachment: fixed;
        }

        .survey-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 48px 20px 80px;
        }

        /* Hoja */
        .survey-sheet {
            position: relative;
            width: 794px;
            min-height: 1123px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,.06),
                0 32px 80px rgba(0,0,0,.45),
                0 8px 24px rgba(0,0,0,.25);
            overflow: visible;
            padding-bottom: 60px;
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

        /* Overlay de bloqueo */
        .lock-overlay {
            position: absolute;
            inset: 0;
            border-radius: 12px;
            background: linear-gradient(
                to bottom,
                transparent 0%,
                transparent 35%,
                rgba(255,255,255,.60) 52%,
                rgba(255,255,255,.96) 65%,
                #ffffff 80%
            );
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 60px;
            z-index: 10;
        }

        .lock-card {
            background: #ffffff;
            border: 1px solid rgba(99,102,241,.18);
            border-radius: 20px;
            padding: 36px 40px;
            text-align: center;
            box-shadow:
                0 0 0 6px rgba(99,102,241,.06),
                0 20px 50px rgba(15,23,42,.14);
            max-width: 420px;
            width: 100%;
        }

        .lock-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, rgba(99,102,241,.12), rgba(139,92,246,.08));
            border: 1.5px solid rgba(99,102,241,.20);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .lock-title {
            font-size: 20px;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 10px;
            letter-spacing: -.02em;
        }

        .lock-sub {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin: 0 0 28px;
        }

        .lock-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 24px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(99,102,241,.35);
            transition: .18s ease;
            letter-spacing: .01em;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(99,102,241,.45);
        }

        .btn-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: transparent;
            color: #6366f1;
            font-size: 14px;
            font-weight: 700;
            border: 1.5px solid rgba(99,102,241,.25);
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: .18s ease;
        }

        .btn-secondary:hover {
            background: rgba(99,102,241,.05);
            border-color: rgba(99,102,241,.40);
        }

        .lock-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            margin: 4px 0;
        }

        .lock-divider::before,
        .lock-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Preview inputs deshabilitados */
        .preview-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid rgba(15,23,42,.08);
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            background: #f8fafc;
            color: #94a3b8;
            pointer-events: none;
            user-select: none;
        }

        .preview-select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid rgba(15,23,42,.08);
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            background: #f8fafc;
            color: #94a3b8;
            pointer-events: none;
        }

        .preview-yesno {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .preview-yesno-btn {
            flex: 1;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            pointer-events: none;
            user-select: none;
        }

        .preview-scale {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .preview-scale-btn {
            width: 36px;
            height: 36px;
            border: 1.5px solid rgba(15,23,42,.08);
            border-radius: 10px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            color: #94a3b8;
            pointer-events: none;
        }

        /* Marca de agua flotante */
        .watermark {
            position: fixed;
            bottom: 18px;
            right: 24px;
            font-size: 12px;
            font-weight: 700;
            color: rgba(255,255,255,.28);
            letter-spacing: .04em;
            pointer-events: none;
            user-select: none;
            z-index: 100;
        }

        @media (max-width: 860px) {
            .survey-sheet { width: 100%; }
            .survey-wrap { padding: 24px 12px 60px; }
            .lock-card { padding: 28px 24px; }
        }
    </style>
</head>
<body>
<div class="survey-wrap">

    @php
        $page = $state['page'] ?? [];
        $bg = '#ffffff';
        if (($page['bg']['type'] ?? null) === 'solid' && !empty($page['bg']['color'])) {
            $bg = $page['bg']['color'];
        }
    @endphp

    <div class="survey-sheet" style="background:{{ $bg }};">

        <!-- Marca de agua en la hoja -->
        <div class="sheet-watermark">MetrikBound.com</div>

        <!-- Bloques en preview (no interactivos) -->
        @foreach($nodes as $i => $node)
            @php
                $kind      = $node['kind'] ?? '';
                $props     = $node['props'] ?? [];
                $html      = $props['html'] ?? $props['label'] ?? 'Pregunta';
                $x         = $node['x'] ?? 0;
                $y         = $node['y'] ?? 0;
                $w         = $node['w'] ?? 320;
                $h         = $node['h'] ?? 120;
                $fontSize  = $props['fontSize'] ?? 14;
                $align     = $props['align'] ?? 'left';
                $color     = $props['color'] ?? '#0f172a';
                $options   = $props['options'] ?? [];
                $required  = !empty($props['required']);
            @endphp

            <div style="
                position:absolute;
                left:{{ $x }}px;
                top:{{ $y }}px;
                width:{{ $w }}px;
                @if(!in_array($kind, ['q_radio','q_check','q_select'])) height:{{ $h }}px; @endif
                background:transparent;
                border:none;
                padding:16px;
                overflow:visible;
            ">
                @if($kind === 'title')
                    <div style="font-size:{{ $fontSize * 1.6 }}px;font-weight:900;line-height:1.12;text-align:{{ $align }};color:{{ $color }};letter-spacing:-0.45px;">{{ $html }}</div>

                @elseif($kind === 'text')
                    <div style="font-size:{{ $fontSize }}px;line-height:1.65;text-align:{{ $align }};color:{{ $color }};">{{ $html }}</div>

                @elseif($kind === 'divider')
                    <div style="height:2px;background:#cbd5e1;border-radius:999px;margin:8px 0;"></div>

                @elseif($kind === 'img')
                    @if(!empty($props['img']))
                        <img src="{{ $props['img'] }}" alt="Imagen" style="max-width:100%;height:auto;border-radius:12px;">
                    @endif

                @elseif(str_starts_with($kind, 'q_'))
                    <div style="font-weight:900;font-size:{{ $fontSize * 1.15 }}px;margin-bottom:10px;color:{{ $color }};">
                        {{ $html }} @if($required)<span style="color:#ef4444">*</span>@endif
                    </div>

                    @if($kind === 'q_text')
                        <div class="preview-input">Escribe tu respuesta…</div>

                    @elseif($kind === 'q_radio' || $kind === 'q_check')
                        <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px;">
                            @foreach($options as $option)
                                <div style="display:flex;align-items:center;gap:10px;font-size:{{ $fontSize }}px;color:#94a3b8;">
                                    <div style="width:14px;height:14px;border-radius:{{ $kind === 'q_radio' ? '999px' : '4px' }};border:2px solid #cbd5e1;flex-shrink:0;"></div>
                                    <span>{{ $option }}</span>
                                </div>
                            @endforeach
                        </div>

                    @elseif($kind === 'q_select')
                        <div class="preview-select">Selecciona una opción…</div>

                    @elseif($kind === 'q_date')
                        <div class="preview-input">DD / MM / AAAA</div>

                    @elseif($kind === 'q_scale')
                        @php $min = (int)($props['min'] ?? 1); $max = (int)($props['max'] ?? 5); @endphp
                        <div class="preview-scale">
                            @for($r = $min; $r <= $max; $r++)
                                <div class="preview-scale-btn">{{ $r }}</div>
                            @endfor
                        </div>

                    @elseif($kind === 'q_yesno')
                        <div class="preview-yesno">
                            <div class="preview-yesno-btn" style="border:2px solid rgba(34,197,94,.20);color:#166534;background:rgba(34,197,94,.06);">Sí</div>
                            <div class="preview-yesno-btn" style="border:2px solid rgba(239,68,68,.20);color:#991b1b;background:rgba(239,68,68,.06);">No</div>
                        </div>

                    @elseif($kind === 'q_stars')
                        @php $stars = (int)($props['stars'] ?? 5); @endphp
                        <div style="display:flex;gap:6px;margin-top:10px;justify-content:center;">
                            @for($s = 1; $s <= $stars; $s++)
                                <svg style="width:28px;height:28px;fill:#e5e7eb;" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                        </div>

                    @elseif($kind === 'q_numeric')
                        @php $min = (int)($props['min'] ?? 1); $max = (int)($props['max'] ?? 10); @endphp
                        <div class="preview-scale">
                            @for($n = $min; $n <= $max; $n++)
                                <div class="preview-scale-btn">{{ $n }}</div>
                            @endfor
                        </div>
                    @endif
                @endif
            </div>
        @endforeach

        <!-- Overlay de bloqueo -->
        <div class="lock-overlay">
            <div class="lock-card">
                <div class="lock-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h2 class="lock-title">Inicia sesión para responder</h2>
                <p class="lock-sub">
                    Esta encuesta requiere que tengas una cuenta.<br>
                    Es rápido y gratuito.
                </p>
                <div class="lock-actions">
                    <a href="{{ route('login') }}" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Iniciar sesión
                    </a>
                    <div class="lock-divider">o</div>
                    <a href="{{ route('register') }}" class="btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        Crear cuenta gratis
                    </a>
                </div>
            </div>
        </div>

    </div>{{-- /survey-sheet --}}

</div>{{-- /survey-wrap --}}

<div class="watermark">MetrikBound.com</div>

</body>
</html>
