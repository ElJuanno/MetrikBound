@extends('layouts.builder')

@section('title', 'Builder — '.$survey->title)
@section('subtitle', $survey->title)

@section('content')
<style>
  :root{
    --line: rgba(15,23,42,.08);
    --line-strong: rgba(15,23,42,.12);
    --soft: #f8fafc;
    --soft-2: #f1f5f9;
    --card: rgba(255,255,255,.78);
    --glass: rgba(255,255,255,.65);
    --shadowSoft: 0 14px 34px rgba(2,6,23,.08);
    --shadowCard: 0 20px 50px rgba(2,6,23,.10);
    --shadowPaper: 0 30px 70px rgba(2,6,23,.12);
    --focus: 0 0 0 4px rgba(99,102,241,.14);
    --brandA: #6366f1;
    --brandB: #8b5cf6;
    --accentCyan: #22d3ee;
    --panel2:#ffffff;
    --ink:#0f172a;
    --muted:#64748b;
  }

  body[data-theme="dark"]{
    --line: rgba(255,255,255,.08);
    --line-strong: rgba(255,255,255,.14);
    --soft: #0f172a;
    --soft-2: #111827;
    --card: rgba(17,24,39,.78);
    --glass: rgba(17,24,39,.64);
    --shadowSoft: 0 18px 40px rgba(0,0,0,.28);
    --shadowCard: 0 18px 50px rgba(0,0,0,.30);
    --shadowPaper: 0 26px 70px rgba(0,0,0,.35);
    --focus: 0 0 0 4px rgba(99,102,241,.20);
    --panel2:#0f172a;
    --ink:#f8fafc;
    --muted:#94a3b8;
  }

  .builderApp{
    height:calc(100vh - 78px);
    display:grid;
    grid-template-columns: 78px 290px 1fr 330px;
    gap:0;
    overflow:hidden;
    background:
      radial-gradient(700px 420px at 14% 10%, rgba(99,102,241,.06), transparent 65%),
      radial-gradient(780px 500px at 90% 6%, rgba(34,211,238,.05), transparent 60%),
      linear-gradient(180deg, rgba(255,255,255,.28), rgba(255,255,255,0));
  }

  .rail{
    border-right:1px solid var(--line);
    background:linear-gradient(180deg, rgba(255,255,255,.80), rgba(255,255,255,.62));
    backdrop-filter: blur(16px);
    padding:12px 8px;
    display:flex;
    flex-direction:column;
    gap:10px;
    align-items:center;
  }

  body[data-theme="dark"] .rail{
    background:
      linear-gradient(180deg, rgba(17,24,39,.92), rgba(17,24,39,.82)),
      linear-gradient(180deg, rgba(99,102,241,.07), rgba(34,211,238,.04));
  }

  .railBtn{
    width:100%;
    border:1px solid var(--line);
    border-radius:20px;
    background: var(--card);
    backdrop-filter: blur(14px);
    box-shadow: 0 10px 24px rgba(2,6,23,.06);
    min-height:88px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:8px;
    cursor:pointer;
    user-select:none;
    transition:.18s ease;
    position:relative;
    overflow:hidden;
    padding:8px 4px;
  }

  .railBtn::before{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(34,211,238,.05));
    opacity:0;
    transition:.18s ease;
  }

  .railBtn:hover{
    transform: translateY(-1px);
    border-color: color-mix(in oklab, var(--brandA) 28%, var(--line));
    box-shadow: 0 16px 36px rgba(2,6,23,.12);
  }

  .railBtn:hover::before{
    opacity:1;
  }

  .railBtn.active{
    border-color: rgba(99,102,241,.22);
    background:
      linear-gradient(180deg, rgba(99,102,241,.10), rgba(139,92,246,.07)),
      rgba(255,255,255,.88);
    box-shadow:
      0 0 0 4px rgba(99,102,241,.08),
      0 14px 30px rgba(2,6,23,.10);
  }

  body[data-theme="dark"] .railBtn.active{
    background:
      linear-gradient(180deg, rgba(99,102,241,.16), rgba(139,92,246,.10)),
      rgba(17,24,39,.92);
  }

  .railIcon{
    width:42px;
    height:42px;
    border-radius:16px;
    display:grid;
    place-items:center;
    background: linear-gradient(135deg, rgba(99,102,241,.14), rgba(34,211,238,.10));
    border:1px solid rgba(99,102,241,.14);
    font-weight:900;
    font-size:18px;
    color: var(--ink);
    position:relative;
    z-index:1;
  }

  .railLabel{
    font-size:12px;
    font-weight:800;
    letter-spacing:.1px;
    color:var(--muted);
    position:relative;
    z-index:1;
    text-align:center;
    line-height:1.15;
  }

  .leftPanel{
    border-right:1px solid var(--line);
    background:linear-gradient(180deg, rgba(255,255,255,.88), rgba(255,255,255,.70));
    backdrop-filter: blur(14px);
    padding:14px;
    overflow:auto;
  }

  body[data-theme="dark"] .leftPanel{
    background:
      linear-gradient(180deg, rgba(17,24,39,.92), rgba(17,24,39,.82)),
      linear-gradient(180deg, rgba(99,102,241,.06), rgba(34,211,238,.04));
  }

  .leftHead{
    border:1px solid var(--line);
    border-radius:20px;
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(10px);
    padding:14px 16px;
    box-shadow: 0 10px 24px rgba(2,6,23,.06);
    margin-bottom:12px;
  }

  body[data-theme="dark"] .leftHead{
    background: rgba(17,24,39,.78);
  }

  .panelTitle{
    margin:0 0 4px;
    font-size:15px;
    font-weight:900;
    letter-spacing:.1px;
    color: var(--ink);
  }

  .panelSub{
    margin:0;
    font-size:12px;
    color:var(--muted);
    line-height:1.45;
  }

  .toolGrid{
    display:flex;
    flex-direction:column;
    gap:12px;
    margin-top:12px;
  }

  .toolCard{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:12px 13px;
    border-radius:16px;
    border:1px solid var(--line);
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(10px);
    cursor:grab;
    user-select:none;
    transition:.15s ease;
    box-shadow: 0 10px 24px rgba(2,6,23,.06);
    -webkit-user-drag: element;
    position:relative;
    overflow:hidden;
  }

  body[data-theme="dark"] .toolCard{
    background: rgba(17,24,39,.78);
  }

  .toolCard::before{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(34,211,238,.05));
    opacity:0;
    transition:.15s ease;
  }

  .toolCard:hover{
    border-color: color-mix(in oklab, var(--brandA) 32%, var(--line));
    box-shadow: 0 18px 40px rgba(99,102,241,.12);
    transform: translateY(-1px);
  }

  .toolCard:hover::before{
    opacity:1;
  }

  .toolCard:active{
    cursor:grabbing;
  }

  .toolCard > *{
    position:relative;
    z-index:1;
  }

  .toolCard .tName{
    font-weight:900;
    color:var(--ink);
    font-size:13px;
    letter-spacing:.05px;
  }

  .toolCard .tHint{
    font-size:12px;
    color:var(--muted);
    margin-top:2px;
  }

  .toolCard .pill{
    padding:6px 10px;
    border-radius:999px;
    border:1px solid var(--line);
    background: rgba(255,255,255,.56);
    font-size:11px;
    font-weight:900;
    color: var(--muted);
    white-space:nowrap;
  }

  body[data-theme="dark"] .toolCard .pill{
    background: rgba(255,255,255,.05);
  }

  .canvasWrap{
    height:100%;
    position:relative;
    overflow:auto;
    padding:18px 18px 120px;
  }

  .canvasBg{
    position:absolute;
    inset:0;
    background:
      radial-gradient(700px 460px at 20% 16%, rgba(99,102,241,.14), transparent 60%),
      radial-gradient(800px 520px at 88% 8%, rgba(34,211,238,.12), transparent 62%),
      linear-gradient(180deg, rgba(255,255,255,.18), transparent);
    pointer-events:none;
    opacity:.9;
  }

  .paper{
    position:relative;
    margin:0 auto;
    width:794px;
    min-height:1123px;
    border-radius:30px;
    background:linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,255,255,.96));
    box-shadow: var(--shadowPaper);
    border:1px solid rgba(15,23,42,.08);
    overflow:visible;
  }

  .paper::before{
    content:"";
    position:absolute;
    inset:0;
    pointer-events:none;
    background:
      linear-gradient(180deg, rgba(99,102,241,.02), transparent 18%),
      radial-gradient(400px 220px at 20% 0%, rgba(99,102,241,.05), transparent 75%);
  }

  .paper.drag-over{
    box-shadow:
      0 0 0 4px rgba(99,102,241,.18),
      0 32px 80px rgba(2,6,23,.18);
  }

  .marginGuide{
    position:absolute;
    inset:42px;
    border:1px dashed rgba(99,102,241,.22);
    border-radius:20px;
    pointer-events:none;
    display:none;
  }

  .guideLine{
    position:absolute;
    background: rgba(99,102,241,.95);
    box-shadow: 0 0 0 4px rgba(99,102,241,.10);
    z-index:5;
    pointer-events:none;
    display:none;
  }

  .guideLine.v{
    width:2px;
    top:0;
    bottom:0;
  }

  .guideLine.h{
    height:2px;
    left:0;
    right:0;
  }

  .block{
    position:absolute;
    left:80px;
    top:120px;
    min-width:220px;
    border-radius:20px;
    border:1px solid rgba(15,23,42,.08);
    background:linear-gradient(180deg, rgba(255,255,255,.95), rgba(255,255,255,.88));
    box-shadow: 0 14px 34px rgba(2,6,23,.08);
    padding:14px 14px 12px;
    cursor:default;
    will-change: transform, left, top, width, height;
    text-align:left;
    backdrop-filter: blur(10px);
  }

  body[data-theme="dark"] .block{
    background:linear-gradient(180deg, rgba(17,24,39,.88), rgba(17,24,39,.84));
    border-color: rgba(255,255,255,.08);
    box-shadow: 0 18px 42px rgba(0,0,0,.30);
  }

  .block.selected{
    border-color: rgba(99,102,241,.72);
    box-shadow:
      0 0 0 4px rgba(99,102,241,.12),
      0 24px 55px rgba(2,6,23,.16);
  }

  .block.locked{
    opacity:.9;
  }

  .blockTopRow{
    display:flex;
    align-items:center;
    justify-content:flex-end;
    gap:10px;
    margin-bottom:10px;
    user-select:none;
  }

  .handleDot{
    width:38px;
    height:10px;
    border-radius:999px;
    background: linear-gradient(90deg, rgba(99,102,241,.24), rgba(34,211,238,.22));
    opacity:.9;
  }

  .editable:focus{
    outline:none;
    box-shadow: var(--focus);
    border-radius:10px;
  }

  .tTitle{
    font-weight:900;
    font-size:28px;
    letter-spacing:-.45px;
    line-height:1.12;
    color: var(--ink);
  }

  .tText{
    font-size:14px;
    line-height:1.65;
    color: color-mix(in oklab, var(--ink) 82%, transparent);
  }

  .qLabel{
    font-weight:900;
    font-size:16px;
    line-height:1.28;
    margin-bottom:8px;
    color: var(--ink);
  }

  .reqTag{
    display:none;
    align-items:center;
    gap:8px;
    font-size:12px;
    line-height:1;
    color: color-mix(in oklab, var(--muted) 92%, transparent);
    margin: 6px 0 10px;
    padding: 7px 10px;
    border-radius: 999px;
    border: 1px solid rgba(239,68,68,.14);
    background: rgba(239,68,68,.05);
    width: fit-content;
    font-weight:800;
  }

  .reqTag.show{
    display:inline-flex;
  }

  .reqTag::before{
    content:"*";
    color: rgba(239,68,68,.95);
    font-weight:900;
  }

  .fakeInput{
    height:46px;
    border-radius:14px;
    border:1px solid color-mix(in oklab, var(--line) 85%, transparent);
    background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
  }

  .optlist{
    display:flex;
    flex-direction:column;
    gap:10px;
    margin-top:8px;
  }

  .opt{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:14px;
    color: var(--optColor, color-mix(in oklab, var(--ink) 80%, transparent));
    line-height:1.45;
    word-break:break-word;
  }

  .dotRadio{
    width:14px;
    height:14px;
    border-radius:999px;
    border:2px solid color-mix(in oklab, var(--muted) 55%, transparent);
    flex:0 0 auto;
  }

  .boxCheck{
    width:14px;
    height:14px;
    border-radius:4px;
    border:2px solid color-mix(in oklab, var(--muted) 55%, transparent);
    flex:0 0 auto;
  }

  .scale{
    display:flex;
    gap:8px;
    margin-top:10px;
    flex-wrap:wrap;
  }

  .scale span{
    width:34px;
    height:34px;
    border-radius:12px;
    display:grid;
    place-items:center;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
    font-weight:900;
    color: color-mix(in oklab, var(--muted) 85%, transparent);
  }

  .dateRow{
    display:flex;
    gap:10px;
    margin-top:10px;
  }

  .dateBox{
    flex:1;
    height:44px;
    border-radius:14px;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
    display:flex;
    align-items:center;
    padding:0 12px;
    color: color-mix(in oklab, var(--muted) 80%, transparent);
    font-weight:800;
    font-size:13px;
  }

  .fakeSelect{
    width:100%;
    height:46px;
    border-radius:14px;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
    padding:0 12px;
    font-weight:800;
    color: color-mix(in oklab, var(--muted) 80%, transparent);
    outline:none;
  }

  .imgBox{
    width:100%;
    min-height:170px;
    border-radius:18px;
    border:1px dashed color-mix(in oklab, var(--line) 85%, transparent);
    background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
    display:grid;
    place-items:center;
    overflow:hidden;
    cursor:pointer;
  }

  .imgBox img{
    width:100%;
    height:auto;
    display:block;
  }

  .imgHint{
    font-size:12px;
    color: var(--muted);
    font-weight:800;
    padding:14px;
    text-align:center;
  }

  .resizer{
    position:absolute;
    right:10px;
    bottom:10px;
    width:16px;
    height:16px;
    border-radius:7px;
    background: rgba(99,102,241,.30);
    border:1px solid rgba(99,102,241,.85);
    cursor:nwse-resize;
  }

  .rightPanel{
    border-left:1px solid var(--line);
    background:
      linear-gradient(180deg, rgba(255,255,255,.84), rgba(255,255,255,.66)),
      linear-gradient(180deg, rgba(99,102,241,.04), rgba(34,211,238,.03));
    backdrop-filter: blur(16px);
    padding:16px;
    overflow:auto;
  }

  body[data-theme="dark"] .rightPanel{
    background:
      linear-gradient(180deg, rgba(17,24,39,.92), rgba(17,24,39,.82)),
      linear-gradient(180deg, rgba(99,102,241,.06), rgba(34,211,238,.04));
  }

  .propHead{
    border:1px solid var(--line);
    border-radius:22px;
    background: var(--glass);
    backdrop-filter: blur(12px);
    padding:16px;
    box-shadow: var(--shadowSoft);
    margin-bottom:14px;
  }

  .propTitle{
    margin:0;
    font-size:16px;
    font-weight:900;
    color: var(--ink);
  }

  .propSub{
    margin:6px 0 0;
    font-size:12px;
    color:var(--muted);
    line-height:1.45;
  }

  .propCard{
    border:1px solid var(--line);
    border-radius:22px;
    background: var(--card);
    backdrop-filter: blur(12px);
    padding:14px;
    box-shadow: var(--shadowSoft);
  }

  .field{
    margin:12px 0;
  }

  .field label{
    display:block;
    font-size:12px;
    font-weight:900;
    color: color-mix(in oklab, var(--muted) 90%, transparent);
    margin-bottom:8px;
    letter-spacing:.08px;
  }

  .input,
  .select,
  .textarea{
    width:100%;
    padding:12px 12px;
    border-radius:14px;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    background: color-mix(in oklab, var(--panel2) 70%, transparent);
    color: var(--ink);
    font:inherit;
    outline:none;
    transition:.14s ease;
  }

  .input:focus,
  .select:focus,
  .textarea:focus{
    border-color: rgba(99,102,241,.30);
    box-shadow: var(--focus);
  }

  .textarea{
    min-height:96px;
    resize:vertical;
  }

  .row2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
  }

  .seg{
    display:flex;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    border-radius:14px;
    overflow:hidden;
    background: color-mix(in oklab, var(--panel2) 65%, transparent);
  }

  .seg button{
    flex:1;
    padding:12px 0;
    background: transparent;
    border:none;
    cursor:pointer;
    font-weight:900;
    color: var(--muted);
    transition:.14s ease;
  }

  .seg button.active{
    background: linear-gradient(135deg, rgba(99,102,241,.16), rgba(34,211,238,.10));
    color: var(--ink);
  }

  .toggleRow{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:12px 12px;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    border-radius:14px;
    background: color-mix(in oklab, var(--panel2) 65%, transparent);
    font-weight:900;
    color: var(--ink);
  }

  .rangeRow{
    display:flex;
    align-items:center;
    gap:12px;
  }

  .rangeRow input[type="range"]{
    width:100%;
    accent-color: var(--brandA);
  }

  .mini{
    font-size:12px;
    color:var(--muted);
    font-weight:800;
  }

  .colorChip{
    width:100%;
    display:flex;
    align-items:center;
    gap:10px;
    border-radius:14px;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    padding:10px 10px;
    background: color-mix(in oklab, var(--panel2) 65%, transparent);
  }

  .colorSwatch{
    width:30px;
    height:22px;
    border-radius:10px;
    border:1px solid color-mix(in oklab, var(--line) 75%, transparent);
    box-shadow: 0 10px 20px rgba(2,6,23,.10);
    background:#9ca3af;
  }

  .colorText{
    font-weight:900;
    font-size:12px;
    color:var(--muted);
    user-select:none;
  }

  .colorNative{
    width:42px;
    height:28px;
    border:none;
    background:transparent;
    padding:0;
    cursor:pointer;
    border-radius:10px;
  }

  .comingSoon{
    border:1px dashed color-mix(in oklab, var(--line) 75%, transparent);
    border-radius:18px;
    padding:16px;
    background: color-mix(in oklab, var(--panel2) 50%, transparent);
  }

  .comingSoonTitle{
    margin:0 0 8px;
    font-weight:900;
    font-size:14px;
    color:var(--ink);
  }

  .comingSoonText{
    margin:0;
    font-size:12px;
    color:var(--muted);
    line-height:1.5;
  }

  @media (max-width: 1360px){
    .builderApp{
      grid-template-columns: 78px 290px 1fr;
    }
    .rightPanel{
      display:none;
    }
  }

  @media (max-width: 980px){
    .builderApp{
      grid-template-columns: 78px 1fr;
    }
    .leftPanel{
      display:none;
    }
    .canvasWrap{
      padding:18px 14px 90px;
    }
    .paper{
      width:min(794px, calc(100vw - 120px));
    }
  }
</style>

<div class="builderApp">
  <aside class="rail">
    <div class="railBtn active" data-tab="text">
      <div class="railIcon">T</div>
      <div class="railLabel">Text</div>
    </div>

    <div class="railBtn" data-tab="questions">
      <div class="railIcon">Q</div>
      <div class="railLabel">Preguntas</div>
    </div>

    <div class="railBtn" data-tab="media">
      <div class="railIcon">▢</div>
      <div class="railLabel">Media</div>
    </div>

    <div class="railBtn" data-tab="page">
      <div class="railIcon">⚙</div>
      <div class="railLabel">Hoja</div>
    </div>
  </aside>

  <aside class="leftPanel">
    <div class="leftHead">
      <h3 class="panelTitle" id="leftTitle">Herramientas</h3>
      <p class="panelSub" id="leftSub">Arrastra elementos al documento para construir tu encuesta.</p>
    </div>

    <div id="tab_text" class="tab">
      <div class="toolGrid">
        <div class="toolCard tool" draggable="true" data-type="title">
          <div>
            <div class="tName">Título</div>
            <div class="tHint">Texto principal</div>
          </div>
          <span class="pill">Text</span>
        </div>

        <div class="toolCard tool" draggable="true" data-type="text">
          <div>
            <div class="tName">Párrafo</div>
            <div class="tHint">Descripción o apoyo</div>
          </div>
          <span class="pill">Text</span>
        </div>

        <div class="toolCard tool" draggable="true" data-type="divider">
          <div>
            <div class="tName">Separador</div>
            <div class="tHint">Ordena secciones</div>
          </div>
          <span class="pill">UI</span>
        </div>
      </div>
    </div>

    <div id="tab_questions" class="tab" style="display:none;">
      <div class="toolGrid">
        <div class="toolCard tool" draggable="true" data-type="q_text">
          <div>
            <div class="tName">Pregunta de texto</div>
            <div class="tHint">Respuesta abierta</div>
          </div>
          <span class="pill">Input</span>
        </div>

        <div class="toolCard tool" draggable="true" data-type="q_radio">
          <div>
            <div class="tName">Opción múltiple</div>
            <div class="tHint">Solo una respuesta</div>
          </div>
          <span class="pill">Radio</span>
        </div>

        <div class="toolCard tool" draggable="true" data-type="q_check">
          <div>
            <div class="tName">Checkbox múltiple</div>
            <div class="tHint">Varias respuestas</div>
          </div>
          <span class="pill">Multi</span>
        </div>

        <div class="toolCard tool" draggable="true" data-type="q_select">
          <div>
            <div class="tName">Combo box</div>
            <div class="tHint">Ideal para muchas opciones</div>
          </div>
          <span class="pill">Select</span>
        </div>

        <div class="toolCard tool" draggable="true" data-type="q_scale">
          <div>
            <div class="tName">Escala 1–5</div>
            <div class="tHint">Nivel o calificación</div>
          </div>
          <span class="pill">Scale</span>
        </div>

        <div class="toolCard tool" draggable="true" data-type="q_date">
          <div>
            <div class="tName">Fecha</div>
            <div class="tHint">Día / Mes / Año</div>
          </div>
          <span class="pill">Date</span>
        </div>
      </div>
    </div>

    <div id="tab_media" class="tab" style="display:none;">
      <div class="toolGrid">
        <div class="toolCard tool" draggable="true" data-type="img">
          <div>
            <div class="tName">Imagen</div>
            <div class="tHint">Sube una imagen al bloque</div>
          </div>
          <span class="pill">Media</span>
        </div>
      </div>

      <p class="panelSub" style="margin-top:14px;">
        Tip: arrastra el bloque de imagen al canvas y después da click para elegir el archivo.
      </p>
    </div>

    <div id="tab_page" class="tab" style="display:none;">
      <div class="leftHead">
        <h3 class="panelTitle" style="margin-bottom:2px;">Hoja</h3>
        <p class="panelSub">Configuración visual de la hoja.</p>
      </div>

      <div class="comingSoon">
        <p class="comingSoonTitle">Configuración de hoja</p>
        <p class="comingSoonText">
          Aquí podrás ajustar tamaño, márgenes, fondos y presets visuales.
          Por ahora el núcleo del builder ya está listo para maquetar y editar bloques.
        </p>
      </div>
    </div>
  </aside>

  <main class="canvasWrap" id="canvasWrap">
    <div class="canvasBg"></div>

    <div class="paper" id="paper">
      <div class="marginGuide" id="marginGuide"></div>
      <div class="guideLine v" id="gV"></div>
      <div class="guideLine h" id="gH"></div>
    </div>
  </main>

  <aside class="rightPanel">
    <div class="propHead">
      <h3 class="propTitle">Propiedades</h3>
      <p class="propSub" id="propHint">Selecciona un bloque para editar su contenido y estilo.</p>
    </div>

    <div id="props" class="propCard" style="display:none;">
      <div class="field">
        <label>Contenido</label>
        <textarea class="textarea" id="propText" placeholder="Escribe aquí..." spellcheck="false"></textarea>
      </div>

      <div class="row2">
        <div class="field">
          <label>Fuente</label>
          <select class="select" id="propFont"></select>
        </div>
        <div class="field">
          <label>Tamaño</label>
          <select class="select" id="propSize"></select>
        </div>
      </div>

      <div class="row2">
        <div class="field">
          <label>Color texto (bloque)</label>
          <div class="colorChip" id="propColorBtn">
            <input class="colorNative" id="propColor" type="color" value="#0b1220">
            <span class="colorSwatch" id="propColorSw"></span>
            <span class="colorText" id="propColorTxt">#0b1220</span>
          </div>
        </div>

        <div class="field">
          <label>Fondo bloque</label>
          <div class="colorChip" id="propBgBtn">
            <input class="colorNative" id="propBg" type="color" value="#ffffff">
            <span class="colorSwatch" id="propBgSw"></span>
            <span class="colorText" id="propBgTxt">#ffffff</span>
          </div>
        </div>
      </div>

      <div class="field" id="propOptColorWrap" style="display:none;">
        <label>Color letra (opciones)</label>
        <div class="colorChip" id="propOptColorBtn">
          <input class="colorNative" id="propOptColor" type="color" value="#0b1220">
          <span class="colorSwatch" id="propOptColorSw"></span>
          <span class="colorText" id="propOptColorTxt">#0b1220</span>
        </div>
        <div class="mini" style="margin-top:8px;">Aplica a Radio / Checkbox / Combo box.</div>
      </div>

      <div class="field">
        <label>Alineación</label>
        <div class="seg" id="propAlign">
          <button type="button" data-align="left" class="active">Izq</button>
          <button type="button" data-align="center">Centro</button>
          <button type="button" data-align="right">Der</button>
        </div>
      </div>

      <div class="field">
        <label>Transparencia</label>
        <div class="rangeRow">
          <input id="propAlpha" type="range" min="0" max="100" value="100">
          <span class="mini" id="propAlphaVal">100%</span>
        </div>
      </div>

      <div class="toggleRow">
        <span>Requerida</span>
        <input type="checkbox" id="propRequired">
      </div>

      <div class="field" id="propOptionsWrap" style="display:none;">
        <label>Opciones (una por línea)</label>
        <textarea class="textarea" id="propOptions" placeholder="Opción 1&#10;Opción 2&#10;Opción 3"></textarea>
      </div>
    </div>
  </aside>
</div>

<script>
  window.__BUILDER_STATE__ = @json($builderState);
  window.__AUTOSAVE_URL__ = @json(route('builder.autosave', $survey));
  window.__CSRF__ = @json(csrf_token());

  window.addEventListener('DOMContentLoaded', () => {
    const myLogoUrl = @json(asset('images/logo.png'));
    window.dispatchEvent(new CustomEvent('builder:setLogo', { detail: { dataUrl: myLogoUrl } }));

    const propColor = document.getElementById('propColor');
    const propBg = document.getElementById('propBg');
    const propOptColor = document.getElementById('propOptColor');

    const propColorSw = document.getElementById('propColorSw');
    const propColorTxt = document.getElementById('propColorTxt');
    const propBgSw = document.getElementById('propBgSw');
    const propBgTxt = document.getElementById('propBgTxt');
    const propOptColorSw = document.getElementById('propOptColorSw');
    const propOptColorTxt = document.getElementById('propOptColorTxt');

    const propAlpha = document.getElementById('propAlpha');
    const propAlphaVal = document.getElementById('propAlphaVal');

    if (propColor) {
      propColor.addEventListener('input', () => {
        if (propColorSw) propColorSw.style.background = propColor.value;
        if (propColorTxt) propColorTxt.textContent = propColor.value;
      });
      propColor.dispatchEvent(new Event('input'));
    }

    if (propBg) {
      propBg.addEventListener('input', () => {
        if (propBgSw) propBgSw.style.background = propBg.value;
        if (propBgTxt) propBgTxt.textContent = propBg.value;
      });
      propBg.dispatchEvent(new Event('input'));
    }

    if (propOptColor) {
      propOptColor.addEventListener('input', () => {
        if (propOptColorSw) propOptColorSw.style.background = propOptColor.value;
        if (propOptColorTxt) propOptColorTxt.textContent = propOptColor.value;
      });
      propOptColor.dispatchEvent(new Event('input'));
    }

    if (propAlpha && propAlphaVal) {
      propAlpha.addEventListener('input', () => {
        propAlphaVal.textContent = `${propAlpha.value}%`;
      });
    }
  });
</script>
@endsection