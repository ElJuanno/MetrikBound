@extends('layouts.app')

@section('content')
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

        <div style="
            position: relative;
            width: {{ $pageWidth }}px;
            height: {{ $pageHeight }}px;
            margin: 0 auto 20px auto;
            background: {{ $bg }};
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(15,23,42,.10);
            overflow: hidden;
        ">
            @foreach($nodes as $i => $node)
                @php
                    $kind = $node['kind'] ?? '';
                    $title = $node['title'] ?? 'Pregunta';
                    $props = $node['props'] ?? [];

                    $x = $node['x'] ?? 0;
                    $y = $node['y'] ?? 0;
                    $w = $node['w'] ?? 320;
                    $h = $node['h'] ?? 120;

                    $required = !empty($props['required']);
                    $inputName = "answers[".$i."]";
                    $options = $props['options'] ?? [];
                @endphp

                <div style="
                    position:absolute;
                    left: {{ $x }}px;
                    top: {{ $y }}px;
                    width: {{ $w }}px;
                    height: {{ $h }}px;
                    background:#fff;
                    border:1px solid #e5e7eb;
                    border-radius:22px;
                    padding:16px;
                    box-shadow: 0 8px 24px rgba(15,23,42,.05);
                    overflow:hidden;
                ">
                    @if($kind === 'title')
                        @php
                            $html = $props['html'] ?? $title;
                            $fontSize = $props['fontSize'] ?? 28;
                            $align = $props['align'] ?? 'left';
                        @endphp

                        <div style="
                            font-size: {{ $fontSize }}px;
                            font-weight: 800;
                            line-height: 1.1;
                            text-align: {{ $align }};
                            color:#0f172a;
                        ">
                            {!! $html !!}
                        </div>

                    @elseif($kind === 'q_text')
                        <label style="display:block; font-weight:700; margin-bottom:10px;">
                            {{ $title }} @if($required)<span style="color:red">*</span>@endif
                        </label>

                        <input
                            type="text"
                            name="{{ $inputName }}"
                            {{ $required ? 'required' : '' }}
                            style="
                                width:100%;
                                padding:12px 14px;
                                border:1px solid #d1d5db;
                                border-radius:14px;
                                outline:none;
                            "
                        >

                    @elseif($kind === 'q_textarea')
                        <label style="display:block; font-weight:700; margin-bottom:10px;">
                            {{ $title }} @if($required)<span style="color:red">*</span>@endif
                        </label>

                        <textarea
                            name="{{ $inputName }}"
                            rows="5"
                            {{ $required ? 'required' : '' }}
                            style="
                                width:100%;
                                height: calc(100% - 42px);
                                padding:12px 14px;
                                border:1px solid #d1d5db;
                                border-radius:14px;
                                outline:none;
                                resize:none;
                            "
                        ></textarea>

                    @elseif($kind === 'q_date')
                        <label style="display:block; font-weight:700; margin-bottom:10px;">
                            {{ $title }} @if($required)<span style="color:red">*</span>@endif
                        </label>

                        <input
                            type="date"
                            name="{{ $inputName }}"
                            {{ $required ? 'required' : '' }}
                            style="
                                width:100%;
                                padding:12px 14px;
                                border:1px solid #d1d5db;
                                border-radius:14px;
                                outline:none;
                            "
                        >

                    @elseif($kind === 'q_check')
                        <label style="display:block; font-weight:700; margin-bottom:10px;">
                            {{ $title }} @if($required)<span style="color:red">*</span>@endif
                        </label>

                        <div style="display:flex; flex-direction:column; gap:10px;">
                            @foreach($options as $option)
                                @php
                                    $label = is_array($option) ? ($option['label'] ?? 'Opción') : $option;
                                    $value = is_array($option) ? ($option['value'] ?? $label) : $option;
                                @endphp

                                <label style="display:flex; align-items:center; gap:10px;">
                                    <input type="checkbox" name="{{ $inputName }}[]" value="{{ $value }}">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                    @elseif($kind === 'q_radio')
                        <label style="display:block; font-weight:700; margin-bottom:10px;">
                            {{ $title }} @if($required)<span style="color:red">*</span>@endif
                        </label>

                        <div style="display:flex; flex-direction:column; gap:10px;">
                            @foreach($options as $option)
                                @php
                                    $label = is_array($option) ? ($option['label'] ?? 'Opción') : $option;
                                    $value = is_array($option) ? ($option['value'] ?? $label) : $option;
                                @endphp

                                <label style="display:flex; align-items:center; gap:10px;">
                                    <input type="radio" name="{{ $inputName }}" value="{{ $value }}" {{ $required ? 'required' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                    @elseif($kind === 'q_scale')
                        @php
                            $max = (int)($props['max'] ?? 5);
                            if ($max <= 0) $max = 5;
                        @endphp

                        <label style="display:block; font-weight:700; margin-bottom:10px;">
                            {{ $title }} @if($required)<span style="color:red">*</span>@endif
                        </label>

                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            @for($r = 1; $r <= $max; $r++)
                                <label style="
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    width:42px;
                                    height:42px;
                                    border:1px solid #d1d5db;
                                    border-radius:12px;
                                    background:#fff;
                                    cursor:pointer;
                                ">
                                    <input type="radio" name="{{ $inputName }}" value="{{ $r }}" style="display:none;" {{ $required ? 'required' : '' }}>
                                    <span>{{ $r }}</span>
                                </label>
                            @endfor
                        </div>

                    @else
                        <div>
                            <strong>{{ $title }}</strong>
                            <div style="color:#6b7280; margin-top:6px;">
                                Bloque no soportado todavía: {{ $kind }}
                            </div>
                        </div>
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
                "
            >
                Enviar respuestas
            </button>
        </div>
    </form>
</div>
@endsection