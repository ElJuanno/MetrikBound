@extends('layouts.app')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 24px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <a href="{{ route('results.index') }}" style="
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 16px;
        ">
            ← Volver a resultados
        </a>
        
        <h1 style="font-size: 32px; font-weight: 900; color: #0f172a; margin: 0;">
            {{ $survey->title }}
        </h1>
        
        @if($survey->description)
            <p style="color: #64748b; margin-top: 8px;">
                {{ $survey->description }}
            </p>
        @endif
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div style="
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 16px;
            padding: 24px;
            color: white;
        ">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px; font-weight: 600;">
                Total de Respuestas
            </div>
            <div style="font-size: 36px; font-weight: 900;">
                {{ $responses->count() }}
            </div>
        </div>

        <div style="
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(15,23,42,.08);
            box-shadow: 0 4px 16px rgba(15,23,42,.04);
        ">
            <div style="font-size: 14px; color: #64748b; margin-bottom: 8px; font-weight: 600;">
                Preguntas
            </div>
            <div style="font-size: 36px; font-weight: 900; color: #0f172a;">
                {{ count($questions) }}
            </div>
        </div>

        <div style="
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(15,23,42,.08);
            box-shadow: 0 4px 16px rgba(15,23,42,.04);
        ">
            <div style="font-size: 14px; color: #64748b; margin-bottom: 8px; font-weight: 600;">
                Última Respuesta
            </div>
            <div style="font-size: 18px; font-weight: 900; color: #0f172a;">
                @if($responses->isNotEmpty())
                    {{ $responses->first()->completed_at->diffForHumans() }}
                @else
                    Sin respuestas
                @endif
            </div>
        </div>
    </div>

    @if($responses->isEmpty())
        <div style="
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, rgba(99,102,241,.05), rgba(139,92,246,.05));
            border-radius: 20px;
            border: 2px dashed rgba(99,102,241,.2);
        ">
            <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
            <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 8px 0;">
                Aún no hay respuestas
            </h3>
            <p style="color: #64748b;">
                Comparte tu encuesta para empezar a recibir respuestas
            </p>
        </div>
    @else
        <!-- Results by Question -->
        @foreach($stats as $stat)
            <div style="
                background: white;
                border-radius: 16px;
                padding: 28px;
                margin-bottom: 24px;
                border: 1px solid rgba(15,23,42,.08);
                box-shadow: 0 4px 16px rgba(15,23,42,.04);
            ">
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">
                        {{ $stat['question'] }}
                    </h3>
                    <div style="font-size: 14px; color: #64748b;">
                        {{ $stat['total_responses'] }} respuestas
                    </div>
                </div>

                @if(in_array($stat['kind'], ['q_radio', 'q_check', 'q_select', 'q_yesno']))
                    <!-- Multiple Choice Results -->
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($stat['data'] as $option)
                            <div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <span style="font-weight: 600; color: #0f172a;">{{ $option['label'] }}</span>
                                    <span style="font-weight: 700; color: #6366f1;">
                                        {{ $option['count'] }} ({{ $option['percentage'] }}%)
                                    </span>
                                </div>
                                <div style="
                                    height: 12px;
                                    background: rgba(15,23,42,.06);
                                    border-radius: 999px;
                                    overflow: hidden;
                                ">
                                    <div style="
                                        height: 100%;
                                        width: {{ $option['percentage'] }}%;
                                        background: linear-gradient(90deg, #6366f1, #8b5cf6);
                                        border-radius: 999px;
                                        transition: width 0.3s ease;
                                    "></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @elseif(in_array($stat['kind'], ['q_scale', 'q_numeric', 'q_stars']))
                    <!-- Scale Results -->
                    <div style="margin-bottom: 20px;">
                        <div style="
                            display: inline-block;
                            padding: 12px 20px;
                            background: linear-gradient(135deg, rgba(99,102,241,.1), rgba(139,92,246,.1));
                            border-radius: 12px;
                            margin-bottom: 16px;
                        ">
                            <span style="font-size: 14px; color: #64748b; font-weight: 600;">Promedio: </span>
                            <span style="font-size: 24px; font-weight: 900; color: #6366f1;">{{ $stat['average'] }}</span>
                            @if($stat['kind'] === 'q_stars')
                                <span style="font-size: 20px; margin-left: 4px;">⭐</span>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: end;">
                        @foreach($stat['data'] as $scale)
                            <div style="flex: 1; text-align: center;">
                                <div style="
                                    height: {{ $scale['percentage'] * 2 }}px;
                                    min-height: 20px;
                                    background: linear-gradient(180deg, #6366f1, #8b5cf6);
                                    border-radius: 8px 8px 0 0;
                                    margin-bottom: 8px;
                                    transition: height 0.3s ease;
                                "></div>
                                <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    {{ $scale['count'] }}
                                </div>
                                <div style="
                                    width: 32px;
                                    height: 32px;
                                    margin: 0 auto;
                                    background: rgba(99,102,241,.1);
                                    border-radius: 8px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-weight: 700;
                                    color: #6366f1;
                                ">
                                    {{ $scale['label'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <!-- Open-ended Results -->
                    <div style="
                        max-height: 400px;
                        overflow-y: auto;
                        display: flex;
                        flex-direction: column;
                        gap: 12px;
                    ">
                        @forelse($stat['data'] as $answer)
                            <div style="
                                padding: 16px;
                                background: rgba(248,250,252,.8);
                                border-radius: 12px;
                                border: 1px solid rgba(15,23,42,.06);
                            ">
                                <div style="
                                    display: flex;
                                    align-items: start;
                                    gap: 12px;
                                ">
                                    <div style="
                                        width: 36px;
                                        height: 36px;
                                        background: linear-gradient(135deg, #6366f1, #8b5cf6);
                                        border-radius: 10px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                        font-weight: 700;
                                        flex-shrink: 0;
                                    ">
                                        #{{ $loop->iteration }}
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="
                                            color: #0f172a;
                                            font-size: 15px;
                                            line-height: 1.6;
                                            margin-bottom: 8px;
                                        ">
                                            {{ $answer['value'] ?: '(Sin respuesta)' }}
                                        </div>
                                        @if(isset($answer['date']))
                                            <div style="font-size: 12px; color: #94a3b8;">
                                                {{ \Carbon\Carbon::parse($answer['date'])->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 20px; color: #94a3b8;">
                                No hay respuestas para esta pregunta
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
