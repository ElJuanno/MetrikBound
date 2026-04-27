@if($survey->response_mode === 'anonymous')
    @extends('layouts.public')
@else
    @extends('layouts.app')
@endif

@section('content')
<style>
/* Estilos para botones interactivos */
.yesno-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}

.numeric-btn:hover,
.scale-btn:hover {
    background: linear-gradient(135deg, rgba(99,102,241,.12), rgba(139,92,246,.08)) !important;
    border-color: rgba(99,102,241,.3) !important;
    transform: translateY(-1px);
}

.numeric-btn input:checked + span,
.scale-btn input:checked + span {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
}
</style>

@php
    $page = $state['page'] ?? [];
    $pageWidth = 900;
    $pageHeight = 1300;

    if (($page['preset'] ?? null) === 'a4') {
        $pageWidth = 900;
        $pageHeight = 1273;
    }

    $bg = '#ffffff';
    if (($page['bg']['type'] ?? null) === 'solid' && !empty($page['bg']['color'])) {
        $bg = $page['bg']['color'];
    }
@endphp

<div class="container" style="max-width: 1200px; margin: 30px auto;">
    <div style="margin-bottom: 18px;">
        <h1 style="margin:0;">{{ $survey->title }}</h1>
        @if(!empty($survey->description))
            <p style="margin-top:6px; color:#6b7280;">{{ $survey->description }}</p>
        @endif
    </div>

    @if(session('success'))
        <div style="padding:12px 16px; background:#dcfce7; color:#166534; border-radius:12px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:12px 16px; background:#fee2e2; color:#991b1b; border-radius:12px; margin-bottom:16px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('surveys.public.submit', $survey->share_token) }}" method="POST">
        @csrf
        @if(request()->has('mode'))
            <input type="hidden" name="mode" value="{{ request()->get('mode') }}">
        @endif

        <div style="
            position: relative;
            width: {{ $pageWidth }}px;
            min-height: {{ $pageHeight }}px;
            margin: 0 auto 20px auto;
            background: {{ $bg }};
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(15,23,42,.10);
            overflow: visible;
            padding-bottom: 40px;
        ">
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

                <div style="
                    position:absolute;
                    left: {{ $x }}px;
                    top: {{ $y }}px;
                    width: {{ $w }}px;
                    @if(!in_array($kind, ['q_radio', 'q_check', 'q_select']))
                    height: {{ $h }}px;
                    @endif
                    background:transparent;
                    border:none;
                    padding:16px;
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

                    @elseif($kind === 'img')
                        @if(!empty($props['img']))
                            <img src="{{ $props['img'] }}" alt="Imagen" style="
                                max-width: 100%;
                                height: auto;
                                border-radius: 12px;
                            ">
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
                            style="
                                width:100%;
                                padding:12px 14px;
                                border:1px solid rgba(15,23,42,.12);
                                border-radius:14px;
                                outline:none;
                                font-size:{{ $fontSize }}px;
                                background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
                            "
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
                            style="
                                width:100%;
                                padding:12px 14px;
                                border:1px solid rgba(15,23,42,.12);
                                border-radius:14px;
                                outline:none;
                                font-size:{{ $fontSize }}px;
                                background: #fff;
                            "
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
                            style="
                                width:100%;
                                padding:12px 14px;
                                border:1px solid rgba(15,23,42,.12);
                                border-radius:14px;
                                outline:none;
                                font-size:{{ $fontSize }}px;
                            "
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
                                <label style="
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    width:34px;
                                    height:34px;
                                    border:1px solid rgba(15,23,42,.12);
                                    border-radius:12px;
                                    background:#fff;
                                    cursor:pointer;
                                    font-size:{{ $fontSize }}px;
                                    transition: all 0.2s;
                                ">
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
                            <label class="yesno-btn" style="
                                flex:1;
                                height:44px;
                                border-radius:12px;
                                border:2px solid rgba(34,197,94,.20);
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                font-size:{{ $fontSize }}px;
                                font-weight:800;
                                color:#166534;
                                background:linear-gradient(135deg,rgba(34,197,94,.08),rgba(34,197,94,.04));
                                cursor:pointer;
                                transition: all 0.2s;
                            ">
                                <input type="radio" name="{{ $inputName }}" value="Sí" style="display:none;" {{ $required ? 'required' : '' }}>
                                <span>Sí</span>
                            </label>
                            <label class="yesno-btn" style="
                                flex:1;
                                height:44px;
                                border-radius:12px;
                                border:2px solid rgba(239,68,68,.20);
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                font-size:{{ $fontSize }}px;
                                font-weight:800;
                                color:#991b1b;
                                background:linear-gradient(135deg,rgba(239,68,68,.08),rgba(239,68,68,.04));
                                cursor:pointer;
                                transition: all 0.2s;
                            ">
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
                                <label style="cursor:pointer;">
                                    <input type="radio" name="{{ $inputName }}" value="{{ $s }}" style="display:none;" {{ $required ? 'required' : '' }}>
                                    <svg style="width:32px;height:32px;fill:#e5e7eb;transition:fill 0.2s;" class="star-icon" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>

                        <script>
                            (function() {
                                const container = document.currentScript.previousElementSibling;
                                const labels = container.querySelectorAll('label');
                                const stars = container.querySelectorAll('.star-icon');
                                
                                labels.forEach((label, index) => {
                                    label.addEventListener('mouseenter', () => {
                                        stars.forEach((star, i) => {
                                            star.style.fill = i <= index ? '#fbbf24' : '#e5e7eb';
                                        });
                                    });
                                    
                                    label.addEventListener('click', () => {
                                        stars.forEach((star, i) => {
                                            star.style.fill = i <= index ? '#fbbf24' : '#e5e7eb';
                                        });
                                    });
                                });
                                
                                container.addEventListener('mouseleave', () => {
                                    const checked = container.querySelector('input:checked');
                                    if (checked) {
                                        const checkedIndex = Array.from(labels).findIndex(l => l.querySelector('input') === checked);
                                        stars.forEach((star, i) => {
                                            star.style.fill = i <= checkedIndex ? '#fbbf24' : '#e5e7eb';
                                        });
                                    } else {
                                        stars.forEach(star => star.style.fill = '#e5e7eb');
                                    }
                                });
                            })();
                        </script>

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
                                    <label class="numeric-btn" style="
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        width:36px;
                                        height:36px;
                                        border:1px solid rgba(15,23,42,.12);
                                        border-radius:10px;
                                        background:#fff;
                                        cursor:pointer;
                                        font-size:{{ $fontSize }}px;
                                        font-weight:800;
                                        transition: all 0.2s;
                                    ">
                                        <input type="radio" name="{{ $inputName }}" value="{{ $n }}" style="display:none;" {{ $required ? 'required' : '' }}>
                                        <span style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;border-radius:10px;">{{ $n }}</span>
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
                                    style="width:100%;accent-color:#6366f1;"
                                    oninput="this.nextElementSibling.querySelector('span').textContent = this.value"
                                >
                                <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:13px;color:#64748b;font-weight:800;">
                                    <span>{{ $min }}</span>
                                    <span>{{ $min }}</span>
                                    <span>{{ $max }}</span>
                                </div>
                            </div>
                        @endif

                    @endif
                </div>
            @endforeach
        </div>

        <div style="text-align:center;">
            <button
                type="submit"
                style="
                    padding:14px 24px;
                    border:none;
                    border-radius:14px;
                    background:#6366f1;
                    color:#fff;
                    font-weight:700;
                    cursor:pointer;
                    font-size:16px;
                "
            >
                Enviar respuestas
            </button>
        </div>
    </form>
</div>
@endsection
