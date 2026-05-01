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
    background:#fafafa;
    padding:14px;
    overflow:auto;
  }

  body[data-theme="dark"] .leftPanel{
    background:#0f172a;
  }

  .leftHead{
    border:1px solid var(--line);
    border-radius:12px;
    background: #ffffff;
    padding:14px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    margin-bottom:12px;
  }

  body[data-theme="dark"] .leftHead{
    background: #1e293b;
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
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:8px;
    margin-top:10px;
  }

  /* Tarjeta visual compacta tipo Canva */
  .toolCard{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:0;
    padding:0;
    border-radius:12px;
    border:1px solid var(--line);
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(10px);
    cursor:grab;
    user-select:none;
    transition:.15s ease;
    box-shadow: 0 2px 8px rgba(2,6,23,.04);
    -webkit-user-drag: element;
    position:relative;
    overflow:hidden;
    min-height:72px;
  }

  /* Tarjeta de ancho completo (separadores) */
  .toolCard.full{
    grid-column: span 2;
    flex-direction:row;
    min-height:44px;
    padding:0 12px;
    gap:10px;
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
    pointer-events:none;
  }

  .toolCard:hover{
    border-color: color-mix(in oklab, var(--brandA) 32%, var(--line));
    box-shadow: 0 8px 24px rgba(99,102,241,.12);
    transform: translateY(-1px);
  }

  .toolCard:hover::before{
    opacity:1;
  }

  .toolCard:active{
    cursor:grabbing;
    transform: scale(.97);
  }

  /* Zona de preview dentro de la tarjeta */
  .tPreview{
    width:100%;
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:10px 8px 6px;
    position:relative;
    z-index:1;
  }

  .toolCard.full .tPreview{
    flex:1;
    padding:0;
    justify-content:flex-start;
  }

  /* Nombre debajo del preview */
  .tLabel{
    width:100%;
    font-size:10px;
    font-weight:800;
    color:var(--muted);
    text-align:center;
    padding:4px 6px 7px;
    letter-spacing:.02em;
    position:relative;
    z-index:1;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
  }

  .toolCard.full .tLabel{
    width:auto;
    flex-shrink:0;
    padding:0;
    font-size:11px;
    color:var(--ink);
    font-weight:700;
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

  /* Estilos tipo Canva para previsualizaciones */
  .stylePreview{
    border-radius:12px;
    border:1px solid var(--line);
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(10px);
    cursor:pointer;
    user-select:none;
    transition:.15s ease;
    box-shadow: 0 2px 8px rgba(2,6,23,.04);
    overflow:hidden;
    position:relative;
  }

  body[data-theme="dark"] .stylePreview{
    background: rgba(17,24,39,.78);
  }

  .stylePreview::before{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(34,211,238,.05));
    opacity:0;
    transition:.15s ease;
    pointer-events:none;
  }

  .stylePreview:hover{
    border-color: color-mix(in oklab, var(--brandA) 32%, var(--line));
    box-shadow: 0 8px 24px rgba(99,102,241,.12);
    transform: translateY(-1px);
  }

  .stylePreview:hover::before{
    opacity:1;
  }

  .previewBox{
    padding:12px 10px;
    min-height:60px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
    position:relative;
    z-index:1;
  }

  body[data-theme="dark"] .previewBox{
    background: linear-gradient(180deg, rgba(15,23,42,.95), rgba(17,24,39,.95));
  }

  .previewTitle,
  .previewText{
    text-align:center;
    word-break:break-word;
  }

  .styleName{
    padding:6px 10px;
    font-size:10px;
    font-weight:800;
    color:var(--muted);
    text-align:center;
    border-top:1px solid var(--line);
    position:relative;
    z-index:1;
    letter-spacing:.02em;
  }

  .questionPreview{
    border-radius:12px;
    border:1px solid var(--line);
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(10px);
    cursor:grab;
    user-select:none;
    transition:.15s ease;
    box-shadow: 0 2px 8px rgba(2,6,23,.04);
    overflow:hidden;
    position:relative;
    -webkit-user-drag: element;
  }

  body[data-theme="dark"] .questionPreview{
    background: rgba(17,24,39,.78);
  }

  .questionPreview::before{
    content:"";
    position:absolute;
    inset:0;
    background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(34,211,238,.05));
    opacity:0;
    transition:.15s ease;
    pointer-events:none;
  }

  .questionPreview:hover{
    border-color: color-mix(in oklab, var(--brandA) 32%, var(--line));
    box-shadow: 0 8px 24px rgba(99,102,241,.12);
    transform: translateY(-1px);
  }

  .questionPreview:hover::before{
    opacity:1;
  }

  .questionPreview:active{
    cursor:grabbing;
    transform: scale(.97);
  }

  .qPreviewBox{
    padding:10px 10px 8px;
    background: linear-gradient(180deg, rgba(248,250,252,.95), rgba(255,255,255,.95));
    position:relative;
    z-index:1;
  }

  body[data-theme="dark"] .qPreviewBox{
    background: linear-gradient(180deg, rgba(15,23,42,.95), rgba(17,24,39,.95));
  }

  .qPreviewLabel{
    font-size:11px;
    font-weight:800;
    color:var(--ink);
    margin-bottom:6px;
    line-height:1.3;
  }

  .qPreviewInput{
    height:24px;
    border-radius:8px;
    border:1px solid var(--line);
    background: rgba(255,255,255,.90);
  }

  .qPreviewOption{
    display:flex;
    align-items:center;
    gap:6px;
    font-size:11px;
    color:var(--muted);
    margin-bottom:4px;
  }

  .qPreviewDot{
    width:10px;
    height:10px;
    border-radius:999px;
    border:2px solid var(--muted);
    flex-shrink:0;
  }

  .qPreviewCheck{
    width:10px;
    height:10px;
    border-radius:3px;
    border:2px solid var(--muted);
    flex-shrink:0;
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
    border-radius:8px;
    background:#ffffff;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
    border:1px solid rgba(15,23,42,.06);
    overflow:visible;
  }

  .paper.drag-over{
    box-shadow: 0 0 0 2px #6366f1, 0 8px 24px rgba(0,0,0,.12);
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
    border-radius:8px;
    border:1px solid transparent;
    background:transparent;
    box-shadow: none;
    padding:16px;
    cursor:move;
    will-change: transform;
    text-align:left;
    /* Optimizaciones críticas de rendimiento */
    transform: translate3d(0,0,0);
    backface-visibility: hidden;
    -webkit-font-smoothing: antialiased;
    /* REMOVIDO: contain: layout style paint; - esto cortaba el handle */
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    /* Permitir que el handle de rotación salga del bloque */
    overflow: visible !important;
  }

  /* Formas decorativas sin padding */
  .block[data-kind="shape_rect"],
  .block[data-kind="shape_triangle"],
  .block[data-kind="shape_diagonal"],
  .block[data-kind="header_band"],
  .block[data-kind="footer_band"]{
    padding:0;
    background:transparent !important;
  }

  /* Wrapper para contenido rotado de formas */
  .shapeContent{
    position:relative;
    pointer-events:auto;
  }

  .block:hover{
    border-color: rgba(99,102,241,.15);
  }

  body[data-theme="dark"] .block{
    background:transparent;
    border-color: transparent;
    box-shadow: none;
  }

  body[data-theme="dark"] .block:hover{
    border-color: rgba(99,102,241,.20);
  }

  .block.selected{
    border-color: #6366f1;
    box-shadow: 0 0 0 1px rgba(99,102,241,.20);
    background: rgba(99,102,241,.02);
  }

  .block.dragging{
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
    opacity: 0.95;
    cursor: grabbing;
    background: rgba(255,255,255,.95);
  }

  /* Vista limpia - sin bloques visuales */
  .block.clean-view{
    border:1px dashed rgba(15,23,42,.12);
    background:transparent;
    box-shadow: none;
    backdrop-filter: none;
  }

  body[data-theme="dark"] .block.clean-view{
    background:transparent;
    border-color: rgba(255,255,255,.12);
    box-shadow: none;
  }

  .block.clean-view.selected{
    border-color: rgba(99,102,241,.40);
    box-shadow: 0 0 0 2px rgba(99,102,241,.08);
  }

  .block.clean-view .blockTopRow{
    opacity: 0.3;
    transition: opacity 0.2s ease;
  }

  .block.clean-view:hover .blockTopRow,
  .block.clean-view.selected .blockTopRow{
    opacity: 1;
  }

  .block.clean-view .fakeInput,
  .block.clean-view .fakeSelect,
  .block.clean-view .scale span,
  .block.clean-view .dateBox{
    background: rgba(248,250,252,.50);
    border-color: rgba(15,23,42,.10);
  }

  .block.locked{
    opacity:.9;
  }

  .blockTopRow{
    display:none;
  }

  .handleDot{
    display:none;
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
    right:0;
    bottom:0;
    width:20px;
    height:20px;
    background: transparent;
    border:none;
    cursor:nwse-resize;
    opacity:0;
    transition: opacity 0.15s ease;
  }

  .resizer::before{
    content: '';
    position: absolute;
    right: 2px;
    bottom: 2px;
    width: 12px;
    height: 12px;
    border-right: 2px solid #6366f1;
    border-bottom: 2px solid #6366f1;
    border-bottom-right-radius: 2px;
  }

  .resizer::after{
    content: '';
    position: absolute;
    right: 6px;
    bottom: 6px;
    width: 6px;
    height: 6px;
    border-right: 2px solid #6366f1;
    border-bottom: 2px solid #6366f1;
    opacity: 0.5;
  }

  .block:hover .resizer,
  .block.selected .resizer{
    opacity:1;
  }

  /* Handle de rotación tipo Canva */
  .rotateHandle{
    position:absolute !important;
    left:50% !important;
    top:-50px !important;
    width:32px !important;
    height:32px !important;
    background: #ffffff !important;
    border:2px solid #6366f1 !important;
    border-radius:50% !important;
    cursor:grab !important;
    opacity:0 !important;
    transition: opacity 0.15s ease !important;
    display:flex !important;
    align-items:center !important;
    justify-content:center !important;
    box-shadow: 0 4px 12px rgba(99,102,241,.25) !important;
    z-index:1000 !important;
    pointer-events: auto !important;
  }

  .rotateHandle:active{
    cursor:grabbing !important;
  }

  .rotateHandle::before{
    content: '↻';
    font-size:18px;
    font-weight:900;
    color:#6366f1;
    line-height:1;
  }

  .rotateHandle::after{
    content: '';
    position: absolute;
    top:100%;
    left:50%;
    transform:translateX(-50%);
    width:2px;
    height:18px;
    background:#6366f1;
  }

  /* Solo mostrar handle de rotación cuando el bloque está seleccionado (NO en hover) */
  .block[data-kind="shape_rect"].selected .rotateHandle,
  .block[data-kind="shape_triangle"].selected .rotateHandle,
  .block[data-kind="shape_diagonal"].selected .rotateHandle,
  .block[data-kind="header_band"].selected .rotateHandle,
  .block[data-kind="footer_band"].selected .rotateHandle{
    opacity:1 !important;
  }

  .rightPanel{
    border-left:1px solid var(--line);
    background:#fafafa;
    padding:16px;
    overflow:auto;
  }

  body[data-theme="dark"] .rightPanel{
    background:#0f172a;
  }

  .propHead{
    border:1px solid var(--line);
    border-radius:12px;
    background: #ffffff;
    padding:16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    margin-bottom:14px;
  }

  body[data-theme="dark"] .propHead{
    background: #1e293b;
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
    border-radius:12px;
    background: #ffffff;
    padding:14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
  }

  body[data-theme="dark"] .propCard{
    background: #1e293b;
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
    white-space:pre-wrap;
    overflow-wrap:anywhere;
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

  /* Etiqueta de sección en el panel izquierdo */
  .sectionLabel{
    font-size:10px;
    font-weight:900;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:var(--muted);
    padding:4px 2px 2px;
    border-bottom:1px solid var(--line);
    margin-bottom:4px;
    grid-column: span 2;
  }

  .builderContextMenu{
    position:fixed;
    min-width:180px;
    background:rgba(15,23,42,.96);
    color:#fff;
    border:1px solid rgba(255,255,255,.10);
    border-radius:14px;
    padding:8px;
    box-shadow:0 24px 60px rgba(2,6,23,.35);
    backdrop-filter:blur(14px);
    z-index:9999;
  }

  .builderContextMenu button{
    width:100%;
    text-align:left;
    border:none;
    background:transparent;
    color:#fff;
    padding:12px 12px;
    border-radius:10px;
    cursor:pointer;
    font-weight:800;
    font-size:13px;
  }

  .builderContextMenu button:hover{
    background:rgba(99,102,241,.16);
  }

  .builderContextMenu button.danger:hover{
    background:rgba(239,68,68,.16);
    color:#fecaca;
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

    <div class="railBtn" data-tab="templates">
      <div class="railIcon">📋</div>
      <div class="railLabel">Plantillas</div>
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

        <!-- ── Textos (span 2 cols cada uno) ── -->
        <div class="sectionLabel" style="grid-column:span 2;">Textos</div>

        <div class="stylePreview" data-style="title-bold" style="grid-column:span 2;">
          <div class="previewBox" style="min-height:52px;">
            <div class="previewTitle" style="font-weight:900;font-size:26px;color:#0f172a;line-height:1;">Título Bold</div>
          </div>
          <div class="styleName">Título Bold</div>
        </div>

        <div class="stylePreview" data-style="title-elegant">
          <div class="previewBox" style="min-height:52px;">
            <div class="previewTitle" style="font-family:Georgia,serif;font-size:20px;color:#1e293b;line-height:1;">Serif</div>
          </div>
          <div class="styleName">Título Serif</div>
        </div>

        <div class="stylePreview" data-style="subtitle">
          <div class="previewBox" style="min-height:52px;">
            <div class="previewTitle" style="font-weight:600;font-size:16px;color:#475569;line-height:1;">Subtítulo</div>
          </div>
          <div class="styleName">Subtítulo</div>
        </div>

        <div class="stylePreview" data-style="text-normal">
          <div class="previewBox" style="min-height:52px;">
            <div class="previewText" style="font-size:12px;line-height:1.5;color:#64748b;">Texto de párrafo normal.</div>
          </div>
          <div class="styleName">Texto Normal</div>
        </div>

        <div class="stylePreview" data-style="text-small">
          <div class="previewBox" style="min-height:52px;">
            <div class="previewText" style="font-size:10px;line-height:1.4;color:#94a3b8;">Nota o aclaración pequeña.</div>
          </div>
          <div class="styleName">Texto Pequeño</div>
        </div>

        <!-- ── Separadores (ancho completo) ── -->
        <div class="sectionLabel" style="grid-column:span 2;margin-top:4px;">Separadores</div>

        <div class="toolCard full tool" draggable="true" data-type="divider">
          <div class="tPreview" style="padding:0;">
            <div style="width:100%;height:2px;border-radius:999px;background:#cbd5e1;"></div>
          </div>
          <div class="tLabel">Simple</div>
        </div>

        <div class="toolCard full tool" draggable="true" data-type="divider_double">
          <div class="tPreview" style="padding:0;flex-direction:column;gap:3px;">
            <div style="width:100%;height:2px;border-radius:999px;background:#cbd5e1;"></div>
            <div style="width:100%;height:1px;border-radius:999px;background:#cbd5e1;opacity:.5;"></div>
          </div>
          <div class="tLabel">Doble</div>
        </div>

        <div class="toolCard full tool" draggable="true" data-type="divider_dashed">
          <div class="tPreview" style="padding:0;">
            <div style="width:100%;height:2px;background:repeating-linear-gradient(90deg,#94a3b8 0,#94a3b8 8px,transparent 8px,transparent 14px);border-radius:999px;"></div>
          </div>
          <div class="tLabel">Punteada</div>
        </div>

        <div class="toolCard full tool" draggable="true" data-type="divider_gradient">
          <div class="tPreview" style="padding:0;">
            <div style="width:100%;height:3px;background:linear-gradient(90deg,transparent,#6366f1,transparent);border-radius:999px;"></div>
          </div>
          <div class="tLabel">Degradado</div>
        </div>

        <div class="toolCard full tool" draggable="true" data-type="divider_text">
          <div class="tPreview" style="padding:0;gap:6px;">
            <div style="flex:1;height:1px;background:#e2e8f0;"></div>
            <span style="font-size:9px;font-weight:700;color:#94a3b8;white-space:nowrap;">SECCIÓN</span>
            <div style="flex:1;height:1px;background:#e2e8f0;"></div>
          </div>
          <div class="tLabel">Con texto</div>
        </div>

        <div class="toolCard full tool" draggable="true" data-type="divider_dots">
          <div class="tPreview" style="padding:0;gap:5px;">
            <div style="width:5px;height:5px;border-radius:50%;background:#94a3b8;flex-shrink:0;"></div>
            <div style="width:5px;height:5px;border-radius:50%;background:#94a3b8;opacity:.6;flex-shrink:0;"></div>
            <div style="width:5px;height:5px;border-radius:50%;background:#94a3b8;opacity:.35;flex-shrink:0;"></div>
          </div>
          <div class="tLabel">Puntos</div>
        </div>

        <!-- ── Cajas decorativas ── -->
        <div class="sectionLabel" style="grid-column:span 2;margin-top:4px;">Cajas</div>

        <div class="toolCard tool" draggable="true" data-type="highlight_box">
          <div class="tPreview">
            <div style="width:100%;border-left:3px solid #3b82f6;background:#eff6ff;border-radius:0 8px 8px 0;padding:6px 8px;font-size:10px;color:#1e40af;line-height:1.4;">ℹ️ Información importante</div>
          </div>
          <div class="tLabel">Info</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="highlight_success">
          <div class="tPreview">
            <div style="width:100%;border-left:3px solid #22c55e;background:#f0fdf4;border-radius:0 8px 8px 0;padding:6px 8px;font-size:10px;color:#166534;line-height:1.4;">✅ Confirmación</div>
          </div>
          <div class="tLabel">Éxito</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="highlight_warning">
          <div class="tPreview">
            <div style="width:100%;border-left:3px solid #f59e0b;background:#fffbeb;border-radius:0 8px 8px 0;padding:6px 8px;font-size:10px;color:#92400e;line-height:1.4;">⚠️ Advertencia</div>
          </div>
          <div class="tLabel">Aviso</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="highlight_danger">
          <div class="tPreview">
            <div style="width:100%;border-left:3px solid #ef4444;background:#fef2f2;border-radius:0 8px 8px 0;padding:6px 8px;font-size:10px;color:#991b1b;line-height:1.4;">🚨 Peligro</div>
          </div>
          <div class="tLabel">Alerta</div>
        </div>

        <!-- ── Citas y callouts ── -->
        <div class="sectionLabel" style="grid-column:span 2;margin-top:4px;">Citas y callouts</div>

        <div class="toolCard tool" draggable="true" data-type="quote">
          <div class="tPreview">
            <div style="width:100%;border-left:3px solid #6366f1;padding:4px 8px;position:relative;">
              <div style="font-size:22px;color:#6366f1;opacity:.2;font-family:Georgia,serif;line-height:1;position:absolute;top:0;left:8px;">"</div>
              <div style="font-size:10px;color:#334155;font-style:italic;padding-left:10px;line-height:1.4;">Texto de cita aquí</div>
            </div>
          </div>
          <div class="tLabel">Cita</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="callout">
          <div class="tPreview">
            <div style="width:100%;border:1.5px solid #6366f1;border-radius:8px;overflow:hidden;">
              <div style="background:#6366f115;padding:3px 6px;font-size:9px;font-weight:800;color:#6366f1;border-bottom:1px solid #6366f120;">💡 Nota</div>
              <div style="padding:4px 6px;font-size:9px;color:#1e293b;line-height:1.3;">Contenido del callout</div>
            </div>
          </div>
          <div class="tLabel">Callout</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="callout_tip">
          <div class="tPreview">
            <div style="width:100%;border:1.5px solid #22c55e;border-radius:8px;overflow:hidden;">
              <div style="background:#22c55e15;padding:3px 6px;font-size:9px;font-weight:800;color:#16a34a;border-bottom:1px solid #22c55e20;">✅ Consejo</div>
              <div style="padding:4px 6px;font-size:9px;color:#1e293b;line-height:1.3;">Tip útil aquí</div>
            </div>
          </div>
          <div class="tLabel">Tip</div>
        </div>

        <!-- ── Badges y numeración ── -->
        <div class="sectionLabel" style="grid-column:span 2;margin-top:4px;">Badges</div>

        <div class="toolCard tool" draggable="true" data-type="badge">
          <div class="tPreview">
            <div style="padding:5px 14px;border-radius:999px;background:#eef2ff;border:1.5px solid #c7d2fe;font-size:11px;font-weight:800;color:#4f46e5;">Etiqueta</div>
          </div>
          <div class="tLabel">Pill</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="badge_solid">
          <div class="tPreview">
            <div style="padding:5px 14px;border-radius:8px;background:#6366f1;font-size:11px;font-weight:800;color:#fff;">Etiqueta</div>
          </div>
          <div class="tLabel">Sólido</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="number_heading" style="grid-column:span 2;">
          <div class="tPreview" style="gap:10px;padding:10px 14px 6px;">
            <span style="font-size:32px;font-weight:900;color:#6366f1;opacity:.25;line-height:1;letter-spacing:-2px;">01</span>
            <span style="font-size:13px;font-weight:800;color:#0f172a;">Título de sección</span>
          </div>
          <div class="tLabel" style="text-align:left;padding-left:14px;">Sección numerada</div>
        </div>

      </div>
    </div>

    <div id="tab_questions" class="tab" style="display:none;">
      <div class="toolGrid">

        <div class="questionPreview tool" draggable="true" data-type="q_yesno" style="grid-column:span 2;">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">¿Estás de acuerdo?</div>
            <div style="display:flex;gap:8px;margin-top:8px;">
              <div style="flex:1;height:28px;border-radius:8px;border:1.5px solid var(--line);display:grid;place-items:center;font-size:11px;font-weight:800;color:var(--ink);background:rgba(34,197,94,.06);">Sí</div>
              <div style="flex:1;height:28px;border-radius:8px;border:1.5px solid var(--line);display:grid;place-items:center;font-size:11px;font-weight:800;color:var(--ink);background:rgba(239,68,68,.06);">No</div>
            </div>
          </div>
          <div class="styleName">Sí / No</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_stars" style="grid-column:span 2;">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">Califica tu experiencia</div>
            <div style="display:flex;gap:4px;margin-top:8px;justify-content:center;">
              <svg style="width:18px;height:18px;fill:#fbbf24;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              <svg style="width:18px;height:18px;fill:#fbbf24;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              <svg style="width:18px;height:18px;fill:#fbbf24;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              <svg style="width:18px;height:18px;fill:#e5e7eb;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              <svg style="width:18px;height:18px;fill:#e5e7eb;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
          </div>
          <div class="styleName">Estrellas</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_numeric" style="grid-column:span 2;">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">Del 1 al 10</div>
            <div style="display:flex;gap:4px;margin-top:8px;flex-wrap:wrap;">
              <div style="width:24px;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);background:rgba(255,255,255,.90);">1</div>
              <div style="width:24px;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);background:rgba(255,255,255,.90);">2</div>
              <div style="width:24px;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);background:rgba(255,255,255,.90);">3</div>
              <div style="width:24px;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);">…</div>
              <div style="width:24px;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);background:rgba(255,255,255,.90);">10</div>
            </div>
          </div>
          <div class="styleName">Escala 1-10</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_text">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">¿Cuál es tu nombre?</div>
            <div class="qPreviewInput"></div>
          </div>
          <div class="styleName">Respuesta corta</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_radio">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">Opción múltiple</div>
            <div class="qPreviewOption"><div class="qPreviewDot"></div><span>Opción 1</span></div>
            <div class="qPreviewOption"><div class="qPreviewDot"></div><span>Opción 2</span></div>
          </div>
          <div class="styleName">Radio</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_check">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">Varias opciones</div>
            <div class="qPreviewOption"><div class="qPreviewCheck"></div><span>Opción A</span></div>
            <div class="qPreviewOption"><div class="qPreviewCheck"></div><span>Opción B</span></div>
          </div>
          <div class="styleName">Checkbox</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_select">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">Lista desplegable</div>
            <div class="qPreviewInput" style="padding:5px 8px;font-size:10px;color:var(--muted);">Selecciona…</div>
          </div>
          <div class="styleName">Combo</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_scale">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">Escala 1–5</div>
            <div style="display:flex;gap:4px;margin-top:6px;">
              <div style="width:22px;height:22px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);">1</div>
              <div style="width:22px;height:22px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);">2</div>
              <div style="width:22px;height:22px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);">3</div>
              <div style="width:22px;height:22px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);">4</div>
              <div style="width:22px;height:22px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:10px;font-weight:800;color:var(--muted);">5</div>
            </div>
          </div>
          <div class="styleName">Escala 1-5</div>
        </div>

        <div class="questionPreview tool" draggable="true" data-type="q_date">
          <div class="qPreviewBox">
            <div class="qPreviewLabel">Fecha</div>
            <div style="display:flex;gap:4px;margin-top:6px;">
              <div style="flex:1;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:9px;color:var(--muted);">DD</div>
              <div style="flex:1;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:9px;color:var(--muted);">MM</div>
              <div style="flex:1;height:24px;border-radius:6px;border:1px solid var(--line);display:grid;place-items:center;font-size:9px;color:var(--muted);">AAAA</div>
            </div>
          </div>
          <div class="styleName">Fecha</div>
        </div>

      </div>
    </div>

    <div id="tab_media" class="tab" style="display:none;">
      <div class="toolGrid">
        <div class="toolCard tool" draggable="true" data-type="img" style="grid-column:span 2;min-height:90px;">
          <div class="tPreview" style="flex-direction:column;gap:6px;padding:14px 14px 8px;">
            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,rgba(99,102,241,.14),rgba(34,211,238,.10));display:grid;place-items:center;font-size:22px;">🖼️</div>
            <div style="font-size:11px;color:var(--muted);font-weight:700;">Arrastra para agregar</div>
          </div>
          <div class="tLabel" style="text-align:center;padding-bottom:10px;">Imagen</div>
        </div>
      </div>
      <p class="panelSub" style="margin-top:12px;padding:0 2px;">
        Haz clic en el bloque de imagen en el canvas para seleccionar un archivo.
      </p>

      <div class="sectionLabel" style="grid-column:span 2;margin-top:16px;">Formas</div>
      <div class="toolGrid">
        <div class="toolCard tool" draggable="true" data-type="shape_rect">
          <div class="tPreview">
            <div style="width:50px;height:30px;background:#3f73c9;border-radius:4px;"></div>
          </div>
          <div class="tLabel">Rectángulo</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="shape_triangle">
          <div class="tPreview">
            <svg width="40" height="35" viewBox="0 0 40 35"><polygon points="20,5 35,30 5,30" fill="#3f73c9"/></svg>
          </div>
          <div class="tLabel">Triángulo</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="shape_diagonal" style="grid-column:span 2;">
          <div class="tPreview" style="padding:10px 14px 6px;">
            <svg width="80" height="30" viewBox="0 0 80 30" preserveAspectRatio="none"><polygon points="0,0 80,0 80,30" fill="#3f73c9"/></svg>
          </div>
          <div class="tLabel">Diagonal</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="header_band" style="grid-column:span 2;">
          <div class="tPreview" style="padding:10px 14px 6px;">
            <div style="width:100%;height:24px;background:#3f73c9;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:8px;color:#fff;font-weight:800;">ENCABEZADO</div>
          </div>
          <div class="tLabel">Banda superior</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="footer_band" style="grid-column:span 2;">
          <div class="tPreview" style="padding:10px 14px 6px;">
            <div style="width:100%;height:20px;background:#3f73c9;border-radius:4px;"></div>
          </div>
          <div class="tLabel">Banda inferior</div>
        </div>
      </div>

      <div class="sectionLabel" style="grid-column:span 2;margin-top:16px;">Campos</div>
      <div class="toolGrid">
        <div class="toolCard tool" draggable="true" data-type="field_line" style="grid-column:span 2;">
          <div class="tPreview" style="padding:10px 14px 6px;flex-direction:column;align-items:flex-start;gap:4px;">
            <div style="font-size:9px;color:#0f172a;font-weight:600;">Campo:</div>
            <div style="width:100%;height:1px;background:#0f172a;"></div>
          </div>
          <div class="tLabel">Línea de campo</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="field_date_line">
          <div class="tPreview" style="flex-direction:column;align-items:flex-start;gap:4px;">
            <div style="font-size:9px;color:#0f172a;font-weight:600;">Fecha:</div>
            <div style="width:100%;height:1px;background:#0f172a;"></div>
          </div>
          <div class="tLabel">Fecha</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="field_signature">
          <div class="tPreview" style="flex-direction:column;align-items:flex-start;gap:4px;">
            <div style="font-size:9px;color:#0f172a;font-weight:600;">Firma:</div>
            <div style="width:100%;height:1px;background:#0f172a;margin-top:8px;"></div>
          </div>
          <div class="tLabel">Firma</div>
        </div>

        <div class="toolCard tool" draggable="true" data-type="document_note" style="grid-column:span 2;">
          <div class="tPreview" style="padding:10px 14px 6px;">
            <div style="font-size:8px;color:#64748b;font-style:italic;line-height:1.3;">Nota del documento...</div>
          </div>
          <div class="tLabel">Nota</div>
        </div>
      </div>
    </div>

    <div id="tab_templates" class="tab" style="display:none;">
      <div class="leftHead">
        <h3 class="panelTitle">Plantillas</h3>
        <p class="panelSub">Inserta plantillas prediseñadas para comenzar rápidamente.</p>
      </div>

      <div class="toolGrid" style="grid-template-columns:1fr;">
        <div class="stylePreview template-card" data-template="customer_satisfaction" style="cursor:pointer;">
          <div class="previewBox" style="min-height:120px;flex-direction:column;gap:8px;padding:16px;">
            <div style="width:100%;height:20px;background:#3f73c9;border-radius:4px;"></div>
            <div style="width:80%;height:12px;background:#e2e8f0;border-radius:2px;"></div>
            <div style="width:60%;height:8px;background:#e2e8f0;border-radius:2px;"></div>
            <div style="display:flex;gap:4px;width:100%;margin-top:4px;">
              <div style="width:8px;height:8px;border-radius:50%;border:2px solid #64748b;"></div>
              <div style="flex:1;height:6px;background:#e2e8f0;border-radius:2px;"></div>
            </div>
            <div style="display:flex;gap:4px;width:100%;">
              <div style="width:8px;height:8px;border-radius:50%;border:2px solid #64748b;"></div>
              <div style="flex:1;height:6px;background:#e2e8f0;border-radius:2px;"></div>
            </div>
          </div>
          <div class="styleName">Encuesta de Satisfacción del Cliente</div>
        </div>
      </div>

      <p class="panelSub" style="margin-top:12px;padding:0 2px;">
        Haz clic en una plantilla para insertarla en el documento. Si ya tienes bloques, se te pedirá confirmación.
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

      <!-- Propiedades de separador -->
      <div id="propDividerWrap" style="display:none;">
        <div class="field">
          <label>Color de línea</label>
          <div class="colorChip" id="propDividerColorBtn">
            <input class="colorNative" id="propDividerColor" type="color" value="#e2e8f0">
            <span class="colorSwatch" id="propDividerColorSw"></span>
            <span class="colorText" id="propDividerColorTxt">#e2e8f0</span>
          </div>
        </div>
        <div class="field">
          <label>Grosor</label>
          <div class="rangeRow">
            <input id="propDividerThickness" type="range" min="1" max="8" value="2">
            <span class="mini" id="propDividerThicknessVal">2px</span>
          </div>
        </div>
      </div>

      <!-- Propiedades de highlight box -->
      <div id="propHighlightWrap" style="display:none;">
        <div class="field">
          <label>Color de borde</label>
          <div class="colorChip" id="propBorderColorBtn">
            <input class="colorNative" id="propBorderColor" type="color" value="#3b82f6">
            <span class="colorSwatch" id="propBorderColorSw"></span>
            <span class="colorText" id="propBorderColorTxt">#3b82f6</span>
          </div>
        </div>
      </div>

      <!-- Propiedades de quote -->
      <div id="propQuoteWrap" style="display:none;">
        <div class="field">
          <label>Color de acento</label>
          <div class="colorChip" id="propQuoteColorBtn">
            <input class="colorNative" id="propQuoteColor" type="color" value="#6366f1">
            <span class="colorSwatch" id="propQuoteColorSw"></span>
            <span class="colorText" id="propQuoteColorTxt">#6366f1</span>
          </div>
        </div>
      </div>

      <!-- Propiedades de callout -->
      <div id="propCalloutWrap" style="display:none;">
        <div class="row2">
          <div class="field">
            <label>Ícono</label>
            <input class="input" id="propCalloutIcon" type="text" value="💡" maxlength="4" style="text-align:center;font-size:20px;">
          </div>
          <div class="field">
            <label>Título</label>
            <input class="input" id="propCalloutTitle" type="text" value="Nota" placeholder="Nota">
          </div>
        </div>
        <div class="field">
          <label>Color de borde</label>
          <div class="colorChip" id="propCalloutColorBtn">
            <input class="colorNative" id="propCalloutColor" type="color" value="#6366f1">
            <span class="colorSwatch" id="propCalloutColorSw"></span>
            <span class="colorText" id="propCalloutColorTxt">#6366f1</span>
          </div>
        </div>
      </div>

      <!-- Propiedades de number_heading -->
      <div id="propNumberWrap" style="display:none;">
        <div class="row2">
          <div class="field">
            <label>Número</label>
            <input class="input" id="propSectionNumber" type="text" value="01" maxlength="4" style="text-align:center;font-weight:900;">
          </div>
          <div class="field">
            <label>Color número</label>
            <div class="colorChip" id="propNumberColorBtn">
              <input class="colorNative" id="propNumberColor" type="color" value="#6366f1">
              <span class="colorSwatch" id="propNumberColorSw"></span>
              <span class="colorText" id="propNumberColorTxt">#6366f1</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Propiedades de formas decorativas -->
      <div id="propShapeWrap" style="display:none;">
        <div class="field">
          <label>Color de forma</label>
          <div class="colorChip" id="propShapeColorBtn">
            <input class="colorNative" id="propShapeColor" type="color" value="#3f73c9">
            <span class="colorSwatch" id="propShapeColorSw"></span>
            <span class="colorText" id="propShapeColorTxt">#3f73c9</span>
          </div>
        </div>
        <div class="field" id="propShapeBorderWrap">
          <label>Borde</label>
          <div class="row2">
            <div class="colorChip" id="propShapeBorderColorBtn">
              <input class="colorNative" id="propShapeBorderColor" type="color" value="#3f73c9">
              <span class="colorSwatch" id="propShapeBorderColorSw"></span>
              <span class="colorText" id="propShapeBorderColorTxt">#3f73c9</span>
            </div>
            <div class="rangeRow">
              <input id="propShapeBorderWidth" type="range" min="0" max="10" value="0">
              <span class="mini" id="propShapeBorderWidthVal">0px</span>
            </div>
          </div>
        </div>
        <div class="field">
          <label>Rotación</label>
          <div class="rangeRow">
            <input id="propShapeRotation" type="range" min="0" max="360" value="0">
            <span class="mini" id="propShapeRotationVal">0°</span>
          </div>
        </div>
      </div>

      <!-- Propiedades de campos de formulario -->
      <div id="propFieldWrap" style="display:none;">
        <div class="field">
          <label>Color de línea</label>
          <div class="colorChip" id="propFieldLineColorBtn">
            <input class="colorNative" id="propFieldLineColor" type="color" value="#0f172a">
            <span class="colorSwatch" id="propFieldLineColorSw"></span>
            <span class="colorText" id="propFieldLineColorTxt">#0f172a</span>
          </div>
        </div>
        <div class="field" id="propFieldLineWidthWrap">
          <label>Ancho de línea</label>
          <div class="rangeRow">
            <input id="propFieldLineWidth" type="range" min="50" max="400" value="200">
            <span class="mini" id="propFieldLineWidthVal">200px</span>
          </div>
        </div>
        <div class="field">
          <label>Grosor de línea</label>
          <div class="rangeRow">
            <input id="propFieldLineThickness" type="range" min="1" max="5" value="1">
            <span class="mini" id="propFieldLineThicknessVal">1px</span>
          </div>
        </div>
      </div>

      <!-- Selector de estilo visual para preguntas -->
      <div id="propVisualStyleWrap" style="display:none;">
        <div class="field">
          <label>Estilo visual</label>
          <div class="seg" id="propVisualStyle">
            <button type="button" data-style="modern" class="active">Moderno</button>
            <button type="button" data-style="document">Documento</button>
          </div>
          <div class="mini" style="margin-top:8px;">Moderno: UI interactiva. Documento: estilo impreso.</div>
        </div>
      </div>

    </div>

    <div class="propCard" style="margin-top:14px;">
      <div class="field">
        <label>Modo de vista</label>
        <div class="seg" id="viewModeToggle">
          <button type="button" data-mode="block" class="active">Editar</button>
          <button type="button" data-mode="clean">Vista Final</button>
        </div>
        <div class="mini" style="margin-top:8px;">Cambia entre modo edición o vista final lista para imprimir.</div>
      </div>
    </div>
  </aside>
</div>

<div id="builderContextMenu" class="builderContextMenu" style="display:none;">
  <button type="button" data-action="duplicate">Duplicar</button>
  <button type="button" data-action="delete" class="danger">Eliminar</button>
</div>

<script>
  window.__SURVEY_ID__ = @json($survey->id);
  window.__BUILDER_STATE__ = @json($builderState);
  window.__AUTOSAVE_URL__ = @json(route('builder.autosave', ['survey' => $survey->id]));
  window.__BLOCK_CREATE_URL__ = @json(route('builder.blocks.store', ['survey' => $survey->id]));
  window.__BLOCK_UPDATE_URL_TEMPLATE__ = @json(url('/builder/'.$survey->id.'/blocks/__BLOCK_ID__'));
  window.__BLOCK_DELETE_URL_TEMPLATE__ = @json(url('/builder/'.$survey->id.'/blocks/__BLOCK_ID__'));
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

    // Toggle de modo de vista (bloques vs limpio)
    const viewModeToggle = document.getElementById('viewModeToggle');
    const paper = document.getElementById('paper');
    
    if (viewModeToggle && paper) {
      // Cargar preferencia guardada
      const savedMode = localStorage.getItem('builderViewMode') || 'block';
      
      if (savedMode === 'clean') {
        paper.classList.add('clean-mode');
        document.querySelectorAll('.block').forEach(block => {
          block.classList.add('clean-view');
        });
        viewModeToggle.querySelector('[data-mode="clean"]').classList.add('active');
        viewModeToggle.querySelector('[data-mode="block"]').classList.remove('active');
      }

      viewModeToggle.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        const mode = btn.dataset.mode;
        
        // Actualizar botones activos
        viewModeToggle.querySelectorAll('button').forEach(b => {
          b.classList.toggle('active', b === btn);
        });

        // Aplicar o quitar clase clean-view a todos los bloques
        const allBlocks = paper.querySelectorAll('.block');
        
        if (mode === 'clean') {
          paper.classList.add('clean-mode');
          allBlocks.forEach(block => {
            block.classList.add('clean-view');
          });
        } else {
          paper.classList.remove('clean-mode');
          allBlocks.forEach(block => {
            block.classList.remove('clean-view');
          });
        }

        // Guardar preferencia
        localStorage.setItem('builderViewMode', mode);
      });

      // Observar nuevos bloques agregados
      const observer = new MutationObserver((mutations) => {
        const currentMode = localStorage.getItem('builderViewMode') || 'block';
        if (currentMode === 'clean') {
          mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
              if (node.classList && node.classList.contains('block')) {
                node.classList.add('clean-view');
              }
            });
          });
        }
      });

      observer.observe(paper, { childList: true });
    }

    // Manejar clics en previsualizaciones de estilos
    document.querySelectorAll('.stylePreview').forEach((preview) => {
      preview.addEventListener('click', async () => {
        const style = preview.dataset.style;
        const paper = document.getElementById('paper');
        if (!paper) return;

        let blockType = 'title';
        let props = {};

        // Mapear estilos a configuraciones
        if (style === 'title-bold') {
          blockType = 'title';
          props = { html: 'Título Principal', fontSize: 32, color: '#0f172a', font: 'system' };
        } else if (style === 'title-elegant') {
          blockType = 'title';
          props = { html: 'Título Elegante', fontSize: 28, color: '#1e293b', font: 'merriweather' };
        } else if (style === 'subtitle') {
          blockType = 'title';
          props = { html: 'Subtítulo', fontSize: 20, color: '#475569', font: 'system' };
        } else if (style === 'text-normal') {
          blockType = 'text';
          props = { html: 'Este es un párrafo de texto normal para describir o explicar algo.', fontSize: 14, color: '#64748b' };
        } else if (style === 'text-small') {
          blockType = 'text';
          props = { html: 'Texto pequeño para notas o aclaraciones adicionales.', fontSize: 12, color: '#94a3b8' };
        }

        // Crear el bloque usando el sistema existente
        const event = new DragEvent('drop', {
          clientX: paper.offsetLeft + paper.offsetWidth / 2,
          clientY: paper.offsetTop + 150,
          dataTransfer: new DataTransfer()
        });
        
        event.dataTransfer.setData('application/x-builder-block', blockType);
        paper.dispatchEvent(event);

        // Esperar un momento y aplicar los props personalizados
        setTimeout(() => {
          const blocks = paper.querySelectorAll('.block');
          const lastBlock = blocks[blocks.length - 1];
          if (lastBlock) {
            const blockId = lastBlock.dataset.id;
            if (window.updateBlock) {
              window.updateBlock(blockId, { props });
              if (window.renderCanvas) window.renderCanvas();
              if (window.selectBlockById) window.selectBlockById(blockId);
            }
          }
        }, 100);
      });
    });

    // ── Helpers para color chips de nuevos controles ──
    function bindColorChip(inputId, swatchId, textId, propKey, extraFn) {
      const input = document.getElementById(inputId);
      const swatch = document.getElementById(swatchId);
      const text = document.getElementById(textId);
      if (!input) return;

      input.addEventListener('input', () => {
        if (swatch) swatch.style.background = input.value;
        if (text) text.textContent = input.value;
        if (window.updateBlock && window.getSelectedBlock) {
          const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
          if (!block) return;
          const patch = { props: {} };
          patch.props[propKey] = input.value;
          window.updateBlock(block.id, patch);
          if (extraFn) extraFn(block, input.value);
          if (window.renderCanvas) window.renderCanvas();
          if (window.selectBlockById) window.selectBlockById(block.id);
          if (window.builderAutosave) window.builderAutosave();
          if (window.builderPersistSelected) window.builderPersistSelected();
        }
      });
      input.dispatchEvent(new Event('input'));
    }

    // Separador: color de línea
    const propDividerColor = document.getElementById('propDividerColor');
    const propDividerColorSw = document.getElementById('propDividerColorSw');
    const propDividerColorTxt = document.getElementById('propDividerColorTxt');
    if (propDividerColor) {
      propDividerColor.addEventListener('input', () => {
        if (propDividerColorSw) propDividerColorSw.style.background = propDividerColor.value;
        if (propDividerColorTxt) propDividerColorTxt.textContent = propDividerColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { dividerColor: propDividerColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propDividerColor.dispatchEvent(new Event('input'));
    }

    // Separador: grosor
    const propDividerThickness = document.getElementById('propDividerThickness');
    const propDividerThicknessVal = document.getElementById('propDividerThicknessVal');
    if (propDividerThickness) {
      propDividerThickness.addEventListener('input', () => {
        if (propDividerThicknessVal) propDividerThicknessVal.textContent = `${propDividerThickness.value}px`;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { dividerThickness: Number(propDividerThickness.value) } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    // Highlight: color de borde
    const propBorderColor = document.getElementById('propBorderColor');
    const propBorderColorSw = document.getElementById('propBorderColorSw');
    const propBorderColorTxt = document.getElementById('propBorderColorTxt');
    if (propBorderColor) {
      propBorderColor.addEventListener('input', () => {
        if (propBorderColorSw) propBorderColorSw.style.background = propBorderColor.value;
        if (propBorderColorTxt) propBorderColorTxt.textContent = propBorderColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { borderColor: propBorderColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propBorderColor.dispatchEvent(new Event('input'));
    }

    // Quote: color de acento
    const propQuoteColor = document.getElementById('propQuoteColor');
    const propQuoteColorSw = document.getElementById('propQuoteColorSw');
    const propQuoteColorTxt = document.getElementById('propQuoteColorTxt');
    if (propQuoteColor) {
      propQuoteColor.addEventListener('input', () => {
        if (propQuoteColorSw) propQuoteColorSw.style.background = propQuoteColor.value;
        if (propQuoteColorTxt) propQuoteColorTxt.textContent = propQuoteColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { quoteColor: propQuoteColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propQuoteColor.dispatchEvent(new Event('input'));
    }

    // Callout: ícono
    const propCalloutIcon = document.getElementById('propCalloutIcon');
    if (propCalloutIcon) {
      propCalloutIcon.addEventListener('input', () => {
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { calloutIcon: propCalloutIcon.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    // Callout: título
    const propCalloutTitle = document.getElementById('propCalloutTitle');
    if (propCalloutTitle) {
      propCalloutTitle.addEventListener('input', () => {
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { calloutTitle: propCalloutTitle.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    // Callout: color de borde
    const propCalloutColor = document.getElementById('propCalloutColor');
    const propCalloutColorSw = document.getElementById('propCalloutColorSw');
    const propCalloutColorTxt = document.getElementById('propCalloutColorTxt');
    if (propCalloutColor) {
      propCalloutColor.addEventListener('input', () => {
        if (propCalloutColorSw) propCalloutColorSw.style.background = propCalloutColor.value;
        if (propCalloutColorTxt) propCalloutColorTxt.textContent = propCalloutColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { borderColor: propCalloutColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propCalloutColor.dispatchEvent(new Event('input'));
    }

    // Number heading: número
    const propSectionNumber = document.getElementById('propSectionNumber');
    if (propSectionNumber) {
      propSectionNumber.addEventListener('input', () => {
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { sectionNumber: propSectionNumber.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    // Number heading: color de número
    const propNumberColor = document.getElementById('propNumberColor');
    const propNumberColorSw = document.getElementById('propNumberColorSw');
    const propNumberColorTxt = document.getElementById('propNumberColorTxt');
    if (propNumberColor) {
      propNumberColor.addEventListener('input', () => {
        if (propNumberColorSw) propNumberColorSw.style.background = propNumberColor.value;
        if (propNumberColorTxt) propNumberColorTxt.textContent = propNumberColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { numberColor: propNumberColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propNumberColor.dispatchEvent(new Event('input'));
    }

    // Template insertion
    document.querySelectorAll('.template-card').forEach((card) => {
      card.addEventListener('click', async () => {
        const templateId = card.dataset.template;
        if (!templateId) return;

        // Dynamically import templates module
        const { insertTemplate } = await import('/resources/js/builder/templates.js');
        
        const success = await insertTemplate(templateId);
        if (success) {
          // Switch to text tab to see the result
          const textTab = document.querySelector('[data-tab="text"]');
          if (textTab) textTab.click();
        }
      });
    });

    // ── Propiedades de formas decorativas ──
    const propShapeColor = document.getElementById('propShapeColor');
    const propShapeColorSw = document.getElementById('propShapeColorSw');
    const propShapeColorTxt = document.getElementById('propShapeColorTxt');
    if (propShapeColor) {
      propShapeColor.addEventListener('input', () => {
        if (propShapeColorSw) propShapeColorSw.style.background = propShapeColor.value;
        if (propShapeColorTxt) propShapeColorTxt.textContent = propShapeColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { bg: propShapeColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propShapeColor.dispatchEvent(new Event('input'));
    }

    const propShapeBorderColor = document.getElementById('propShapeBorderColor');
    const propShapeBorderColorSw = document.getElementById('propShapeBorderColorSw');
    const propShapeBorderColorTxt = document.getElementById('propShapeBorderColorTxt');
    if (propShapeBorderColor) {
      propShapeBorderColor.addEventListener('input', () => {
        if (propShapeBorderColorSw) propShapeBorderColorSw.style.background = propShapeBorderColor.value;
        if (propShapeBorderColorTxt) propShapeBorderColorTxt.textContent = propShapeBorderColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { borderColor: propShapeBorderColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propShapeBorderColor.dispatchEvent(new Event('input'));
    }

    const propShapeBorderWidth = document.getElementById('propShapeBorderWidth');
    const propShapeBorderWidthVal = document.getElementById('propShapeBorderWidthVal');
    if (propShapeBorderWidth) {
      propShapeBorderWidth.addEventListener('input', () => {
        if (propShapeBorderWidthVal) propShapeBorderWidthVal.textContent = `${propShapeBorderWidth.value}px`;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { borderWidth: Number(propShapeBorderWidth.value) } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    const propShapeRotation = document.getElementById('propShapeRotation');
    const propShapeRotationVal = document.getElementById('propShapeRotationVal');
    if (propShapeRotation) {
      propShapeRotation.addEventListener('input', () => {
        if (propShapeRotationVal) propShapeRotationVal.textContent = `${propShapeRotation.value}°`;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { rotation: Number(propShapeRotation.value) } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    // ── Propiedades de campos de formulario ──
    const propFieldLineColor = document.getElementById('propFieldLineColor');
    const propFieldLineColorSw = document.getElementById('propFieldLineColorSw');
    const propFieldLineColorTxt = document.getElementById('propFieldLineColorTxt');
    if (propFieldLineColor) {
      propFieldLineColor.addEventListener('input', () => {
        if (propFieldLineColorSw) propFieldLineColorSw.style.background = propFieldLineColor.value;
        if (propFieldLineColorTxt) propFieldLineColorTxt.textContent = propFieldLineColor.value;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { lineColor: propFieldLineColor.value } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
      propFieldLineColor.dispatchEvent(new Event('input'));
    }

    const propFieldLineWidth = document.getElementById('propFieldLineWidth');
    const propFieldLineWidthVal = document.getElementById('propFieldLineWidthVal');
    if (propFieldLineWidth) {
      propFieldLineWidth.addEventListener('input', () => {
        if (propFieldLineWidthVal) propFieldLineWidthVal.textContent = `${propFieldLineWidth.value}px`;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { lineWidth: Number(propFieldLineWidth.value) } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    const propFieldLineThickness = document.getElementById('propFieldLineThickness');
    const propFieldLineThicknessVal = document.getElementById('propFieldLineThicknessVal');
    if (propFieldLineThickness) {
      propFieldLineThickness.addEventListener('input', () => {
        if (propFieldLineThicknessVal) propFieldLineThicknessVal.textContent = `${propFieldLineThickness.value}px`;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;
        window.updateBlock && window.updateBlock(block.id, { props: { lineThickness: Number(propFieldLineThickness.value) } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }

    // ── Selector de estilo visual para preguntas ──
    const propVisualStyle = document.getElementById('propVisualStyle');
    if (propVisualStyle) {
      propVisualStyle.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
        
        const style = btn.dataset.style;
        const block = window.getSelectedBlock ? window.getSelectedBlock() : null;
        if (!block) return;

        // Actualizar botones activos
        propVisualStyle.querySelectorAll('button').forEach(b => {
          b.classList.toggle('active', b === btn);
        });

        window.updateBlock && window.updateBlock(block.id, { props: { visualStyle: style } });
        window.rebuildBlock && window.rebuildBlock(block.id);
        window.builderAutosave && window.builderAutosave();
        window.builderPersistSelected && window.builderPersistSelected();
      });
    }
  });
</script>
@endsection