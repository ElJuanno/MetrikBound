<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', config('app.name').' — Builder')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&family=Montserrat:wght@400;500;600;700;800&family=Nunito:wght@400;600;700;800&family=Open+Sans:wght@400;600;700&family=Lato:wght@400;700&family=Raleway:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#eef3fb;
      --bg2:#f8fbff;
      --panel:#ffffffcc;
      --panel2:#ffffffef;
      --card:#ffffff;
      --line:rgba(15,23,42,.08);
      --line2:rgba(15,23,42,.12);
      --ink:#0f172a;
      --muted:#64748b;
      --brand:#6366f1;
      --brand2:#8b5cf6;
      --cyan:#22d3ee;
      --good:#10b981;
      --warn:#f59e0b;
      --danger:#ef4444;
      --shadow:0 20px 60px rgba(15,23,42,.10);
      --shadow-sm:0 12px 26px rgba(15,23,42,.08);
      --radius:24px;
      --radius-sm:18px;
    }

    body[data-theme="dark"]{
      --bg:#0b1220;
      --bg2:#111827;
      --panel:rgba(17,24,39,.84);
      --panel2:rgba(17,24,39,.95);
      --card:#0f172a;
      --line:rgba(255,255,255,.08);
      --line2:rgba(255,255,255,.14);
      --ink:#f8fafc;
      --muted:#94a3b8;
      --shadow:0 24px 70px rgba(0,0,0,.35);
      --shadow-sm:0 14px 30px rgba(0,0,0,.28);
    }

    *{ box-sizing:border-box; }

    html,body{
      margin:0;
      padding:0;
      height:100%;
      font-family:'Inter',system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
      color:var(--ink);
      background:
        radial-gradient(760px 420px at 10% 0%, rgba(99,102,241,.12), transparent 60%),
        radial-gradient(760px 420px at 100% 10%, rgba(34,211,238,.10), transparent 58%),
        linear-gradient(180deg, var(--bg2), var(--bg));
    }

    .builderShell{
      min-height:100vh;
      display:flex;
      flex-direction:column;
    }

    .builderTopbar{
      position:sticky;
      top:0;
      z-index:80;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:14px;
      padding:12px 18px;
      border-bottom:1px solid var(--line);
      background:linear-gradient(180deg, rgba(255,255,255,.78), rgba(255,255,255,.62));
      backdrop-filter: blur(18px);
    }

    body[data-theme="dark"] .builderTopbar{
      background:linear-gradient(180deg, rgba(15,23,42,.92), rgba(15,23,42,.84));
    }

    .topLeft,
    .topCenter,
    .topRight{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .brandBadge{
      display:flex;
      align-items:center;
      gap:12px;
      padding:10px 14px;
      border-radius:18px;
      background:var(--panel2);
      border:1px solid var(--line);
      box-shadow:var(--shadow-sm);
      min-width:0;
    }

    .brandDot{
      width:12px;
      height:12px;
      border-radius:999px;
      background:linear-gradient(135deg,var(--brand),var(--cyan));
      box-shadow:0 0 0 5px rgba(99,102,241,.12);
      flex:0 0 auto;
    }

    .brandText{
      display:flex;
      flex-direction:column;
      min-width:0;
    }

    .brandTitle{
      font-size:13px;
      font-weight:900;
      line-height:1.1;
      color:var(--ink);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:260px;
    }

    .brandSub{
      font-size:11px;
      color:var(--muted);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:260px;
    }

    .zoomWrap{
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 14px;
      border:1px solid var(--line);
      border-radius:18px;
      background:var(--panel2);
      box-shadow:var(--shadow-sm);
    }

    .zoomWrap label{
      font-size:12px;
      color:var(--muted);
      font-weight:800;
    }

    .zoomWrap input[type="range"]{
      width:120px;
      accent-color: var(--brand);
    }

    .zoomVal{
      font-size:12px;
      font-weight:900;
      color:var(--ink);
      min-width:42px;
      text-align:right;
    }

    .cloudStatus{
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 14px;
      border-radius:18px;
      border:1px solid var(--line);
      background:var(--panel2);
      box-shadow:var(--shadow-sm);
      white-space:nowrap;
    }

    .cloudDot{
      width:10px;
      height:10px;
      border-radius:999px;
      background:var(--good);
      box-shadow:0 0 0 4px rgba(16,185,129,.18);
      flex:0 0 auto;
    }

    .cloudText{
      font-size:12px;
      font-weight:900;
      color:var(--ink);
    }

    .cloudTime{
      font-size:12px;
      color:var(--muted);
      font-weight:700;
    }

    .topBtn{
      border:none;
      background:var(--panel2);
      color:var(--ink);
      border:1px solid var(--line);
      padding:10px 14px;
      border-radius:16px;
      cursor:pointer;
      font-weight:900;
      box-shadow:var(--shadow-sm);
      transition:.18s ease;
    }

    .topBtn:hover{
      transform:translateY(-1px);
      border-color:rgba(99,102,241,.22);
    }

    .topBtn.primary{
      background:linear-gradient(135deg, rgba(99,102,241,.15), rgba(34,211,238,.12));
    }

    .shareWrap{
      position:relative;
    }

    .shareMenu{
      display:none;
      position:absolute;
      right:0;
      top:calc(100% + 10px);
      width:310px;
      background:var(--panel2);
      border:1px solid var(--line2);
      border-radius:22px;
      box-shadow:var(--shadow);
      padding:14px;
      z-index:120;
      backdrop-filter: blur(16px);
    }

    .shareMenu.show{
      display:block;
    }

    .shareMenuTitle{
      font-size:14px;
      font-weight:900;
      color:var(--ink);
      margin-bottom:10px;
    }

    .shareModeBtn{
      width:100%;
      text-align:left;
      border:1px solid var(--line);
      border-radius:16px;
      padding:12px;
      background:rgba(255,255,255,.78);
      color:var(--ink);
      margin-bottom:10px;
      cursor:pointer;
      font-weight:900;
      transition:.16s ease;
    }

    body[data-theme="dark"] .shareModeBtn{
      background:rgba(255,255,255,.03);
    }

    .shareModeBtn:hover,
    .shareModeBtn.active{
      border-color:rgba(99,102,241,.26);
      box-shadow:0 0 0 4px rgba(99,102,241,.08);
    }

    .shareModeSub{
      font-size:12px;
      font-weight:700;
      color:var(--muted);
      margin-top:5px;
      line-height:1.4;
    }

    .shareLine{
      height:1px;
      background:var(--line);
      margin:12px 0;
    }

    .shareLabel{
      display:block;
      font-size:12px;
      font-weight:900;
      color:var(--muted);
      margin-bottom:7px;
    }

    .shareInput{
      width:100%;
      border:1px solid var(--line2);
      border-radius:14px;
      padding:10px 12px;
      font-size:12px;
      background:rgba(255,255,255,.72);
      color:var(--ink);
      outline:none;
    }

    body[data-theme="dark"] .shareInput{
      background:rgba(255,255,255,.03);
    }

    .shareCopyBtn{
      margin-top:10px;
      width:100%;
      border:none;
      border-radius:14px;
      padding:11px 12px;
      background:linear-gradient(135deg,var(--brand),var(--brand2));
      color:#fff;
      font-weight:900;
      cursor:pointer;
    }

    .builderViewport{
      flex:1;
      min-height:0;
    }

    @media (max-width: 1180px){
      .topCenter{ display:none; }
      .brandTitle,.brandSub{ max-width:180px; }
    }

    @media (max-width: 860px){
      .builderTopbar{
        flex-wrap:wrap;
        justify-content:center;
      }
      .topLeft,.topCenter,.topRight{
        width:100%;
        justify-content:center;
      }
      .brandBadge{ width:100%; justify-content:center; }
      .shareMenu{
        left:50%;
        right:auto;
        transform:translateX(-50%);
      }
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="builderShell">
    <header class="builderTopbar">
      <div class="topLeft">
        <div class="brandBadge">
          <div class="brandDot"></div>
          <div class="brandText">
            <div class="brandTitle">@yield('subtitle', 'Builder')</div>
            <div class="brandSub">Editor visual de encuestas</div>
          </div>
        </div>
      </div>

      <div class="topCenter">
        <div class="zoomWrap">
          <label for="builderZoom">Zoom</label>
          <input id="builderZoom" type="range" min="60" max="140" value="100">
          <div class="zoomVal" id="builderZoomVal">100%</div>
        </div>

        <div class="cloudStatus">
          <span class="cloudDot" id="cloudDot"></span>
          <span class="cloudText" id="cloudText">Guardado</span>
          <span class="cloudTime" id="cloudTime">ahora</span>
        </div>
      </div>

      <div class="topRight">
        <button type="button" class="topBtn" id="themeToggle">Oscuro</button>

        <a href="{{ url()->previous() }}" class="topBtn" style="text-decoration:none;">Volver</a>

        @if(isset($survey) && !empty($survey->token) && \Illuminate\Support\Facades\Route::has('surveys.public.show'))
          <a href="{{ route('surveys.public.show', $survey->token) }}" target="_blank" class="topBtn primary" style="text-decoration:none;">
            Vista previa
          </a>
        @else
          <button type="button" class="topBtn primary" onclick="alert('Esta encuesta aún no tiene token público disponible.')">
            Vista previa
          </button>
        @endif

        <div class="shareWrap">
          <button type="button" class="topBtn" id="shareToggle">Compartir</button>

          <div class="shareMenu" id="shareMenu">
            <div class="shareMenuTitle">¿Cómo deseas compartir?</div>

            <button type="button" class="shareModeBtn active" data-mode="anonymous">
              Responder de forma anónima
              <div class="shareModeSub">No se solicita inicio de sesión para contestar.</div>
            </button>

            <button type="button" class="shareModeBtn" data-mode="registered">
              Responder con registro
              <div class="shareModeSub">El usuario debe iniciar sesión antes de responder.</div>
            </button>

            <div class="shareLine"></div>

            <label class="shareLabel">Enlace público</label>
            <input id="shareLinkOutput" class="shareInput" type="text" readonly>

            <button type="button" class="shareCopyBtn" id="copyShareLink">Copiar enlace</button>
          </div>
        </div>
      </div>
    </header>

    <div class="builderViewport">
      @yield('content')
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const root = document.body;
      const themeToggle = document.getElementById('themeToggle');
      const savedTheme = localStorage.getItem('builder_theme') || 'light';

      if (savedTheme === 'dark') {
        root.setAttribute('data-theme', 'dark');
        if (themeToggle) themeToggle.textContent = 'Claro';
      }

      if (themeToggle) {
        themeToggle.addEventListener('click', () => {
          const isDark = root.getAttribute('data-theme') === 'dark';

          if (isDark) {
            root.removeAttribute('data-theme');
            localStorage.setItem('builder_theme', 'light');
            themeToggle.textContent = 'Oscuro';
          } else {
            root.setAttribute('data-theme', 'dark');
            localStorage.setItem('builder_theme', 'dark');
            themeToggle.textContent = 'Claro';
          }
        });
      }

      const zoomInput = document.getElementById('builderZoom');
      const zoomVal = document.getElementById('builderZoomVal');
      const paper = document.getElementById('paper');

      function applyZoom() {
        if (!zoomInput || !zoomVal || !paper) return;
        const z = Number(zoomInput.value || 100);
        zoomVal.textContent = `${z}%`;
        paper.style.transform = `scale(${z / 100})`;
        paper.style.transformOrigin = 'top center';
      }

      if (zoomInput) {
        zoomInput.addEventListener('input', applyZoom);
        setTimeout(applyZoom, 50);
      }

      const shareToggle = document.getElementById('shareToggle');
      const shareMenu = document.getElementById('shareMenu');
      const shareLinkOutput = document.getElementById('shareLinkOutput');
      const copyShareLink = document.getElementById('copyShareLink');
      const shareButtons = document.querySelectorAll('.shareModeBtn');

      let shareMode = 'anonymous';

      function buildShareUrl() {
        @if(isset($survey) && !empty($survey->token) && \Illuminate\Support\Facades\Route::has('surveys.public.show'))
          const base = `{{ route('surveys.public.show', $survey->token) }}`;
        @elseif(isset($survey))
          const base = `{{ url('/s/'.($survey->token ?? 'sin-token')) }}`;
        @else
          const base = window.location.href;
        @endif

        const url = new URL(base, window.location.origin);
        url.searchParams.set('mode', shareMode);
        return url.toString();
      }

      function refreshShareLink() {
        if (shareLinkOutput) {
          shareLinkOutput.value = buildShareUrl();
        }
      }

      if (shareToggle && shareMenu) {
        shareToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          shareMenu.classList.toggle('show');
          refreshShareLink();
        });
      }

      shareButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
          shareMode = btn.dataset.mode || 'anonymous';
          shareButtons.forEach((b) => b.classList.remove('active'));
          btn.classList.add('active');
          refreshShareLink();
        });
      });

      if (copyShareLink) {
        copyShareLink.addEventListener('click', async () => {
          try {
            await navigator.clipboard.writeText(shareLinkOutput.value);
            const old = copyShareLink.textContent;
            copyShareLink.textContent = 'Enlace copiado';
            setTimeout(() => copyShareLink.textContent = old, 1200);
          } catch (e) {
            shareLinkOutput.select();
            document.execCommand('copy');
          }
        });
      }

      document.addEventListener('click', (e) => {
        if (!e.target.closest('.shareWrap') && shareMenu) {
          shareMenu.classList.remove('show');
        }
      });

      refreshShareLink();
    });
  </script>

  @stack('scripts')
</body>
</html>