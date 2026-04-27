@extends('layouts.app')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 24px;">
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 900; color: #0f172a; margin: 0;">
            Resultados de Encuestas
        </h1>
        <p style="color: #64748b; margin-top: 8px;">
            Selecciona una encuesta para ver sus resultados y estadísticas
        </p>
    </div>

    @if($surveys->isEmpty())
        <div style="
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, rgba(99,102,241,.05), rgba(139,92,246,.05));
            border-radius: 20px;
            border: 2px dashed rgba(99,102,241,.2);
        ">
            <div style="font-size: 48px; margin-bottom: 16px;">📊</div>
            <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 8px 0;">
                No hay encuestas todavía
            </h3>
            <p style="color: #64748b; margin-bottom: 24px;">
                Crea tu primera encuesta para empezar a recopilar respuestas
            </p>
            <a href="{{ route('surveys.create') }}" style="
                display: inline-block;
                padding: 12px 24px;
                background: #6366f1;
                color: white;
                text-decoration: none;
                border-radius: 12px;
                font-weight: 700;
                transition: all 0.2s;
            ">
                Crear Encuesta
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
            @foreach($surveys as $survey)
                <a href="{{ route('results.show', $survey) }}" style="
                    display: block;
                    background: white;
                    border-radius: 16px;
                    padding: 24px;
                    border: 1px solid rgba(15,23,42,.08);
                    box-shadow: 0 4px 16px rgba(15,23,42,.04);
                    text-decoration: none;
                    transition: all 0.2s;
                " onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 32px rgba(15,23,42,.12)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 16px rgba(15,23,42,.04)';">
                    
                    <div style="display: flex; align-items: start; justify-content: space-between; margin-bottom: 16px;">
                        <div style="
                            width: 48px;
                            height: 48px;
                            background: linear-gradient(135deg, #6366f1, #8b5cf6);
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 24px;
                        ">
                            📊
                        </div>
                        
                        <div style="
                            padding: 6px 12px;
                            border-radius: 999px;
                            font-size: 12px;
                            font-weight: 700;
                            @if($survey->status === 'published')
                                background: rgba(16,185,129,.1);
                                color: #059669;
                            @else
                                background: rgba(100,116,139,.1);
                                color: #475569;
                            @endif
                        ">
                            {{ $survey->status === 'published' ? 'Publicada' : 'Borrador' }}
                        </div>
                    </div>

                    <h3 style="
                        font-size: 18px;
                        font-weight: 800;
                        color: #0f172a;
                        margin: 0 0 8px 0;
                        line-height: 1.3;
                    ">
                        {{ $survey->title }}
                    </h3>

                    @if($survey->description)
                        <p style="
                            color: #64748b;
                            font-size: 14px;
                            line-height: 1.5;
                            margin: 0 0 16px 0;
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                        ">
                            {{ $survey->description }}
                        </p>
                    @endif

                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 16px;
                        padding-top: 16px;
                        border-top: 1px solid rgba(15,23,42,.06);
                    ">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="
                                width: 36px;
                                height: 36px;
                                background: rgba(99,102,241,.1);
                                border-radius: 10px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 18px;
                            ">
                                👥
                            </div>
                            <div>
                                <div style="font-size: 20px; font-weight: 900; color: #0f172a; line-height: 1;">
                                    {{ $survey->responses_count }}
                                </div>
                                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">
                                    Respuestas
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="
                                width: 36px;
                                height: 36px;
                                background: rgba(34,211,238,.1);
                                border-radius: 10px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 18px;
                            ">
                                📅
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 700; color: #0f172a; line-height: 1;">
                                    {{ $survey->created_at->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">
                                    Creada
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
