<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MetrikBound | Diseña encuestas visuales</title>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --primary:#6366f1;
      --primary-2:#8b5cf6;
      --primary-hover:#4f46e5;
      --bg:#ffffff;
      --bg-soft:#f8fafc;
      --bg-section:#f5f7fb;
      --text:#0f172a;
      --muted:#64748b;
      --border:#e2e8f0;
      --line:#edf2f7;
      --success:#10b981;
      --shadow:0 10px 30px rgba(15,23,42,.08);
      --shadow-lg:0 25px 60px rgba(15,23,42,.12);
      --radius:18px;
      --radius-sm:12px;
      --container:1180px;
    }

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
    }

    html{
      scroll-behavior:smooth;
    }

    body{
      font-family:'Inter',sans-serif;
      background:
        radial-gradient(circle at top left, rgba(99,102,241,.10), transparent 28%),
        radial-gradient(circle at top right, rgba(139,92,246,.08), transparent 24%),
        var(--bg);
      color:var(--text);
      line-height:1.6;
      overflow-x:hidden;
    }

    a{
      text-decoration:none;
      color:inherit;
    }

    .container{
      width:min(var(--container), calc(100% - 2rem));
      margin:0 auto;
    }

    .btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:.6rem;
      padding:.95rem 1.35rem;
      border-radius:12px;
      font-weight:600;
      transition:.25s ease;
      border:1px solid transparent;
      cursor:pointer;
      white-space:nowrap;
    }

    .btn-primary{
      background:linear-gradient(135deg, var(--primary), var(--primary-2));
      color:#fff;
      box-shadow:0 12px 24px rgba(99,102,241,.25);
    }

    .btn-primary:hover{
      transform:translateY(-2px);
      box-shadow:0 18px 30px rgba(99,102,241,.30);
    }

    .btn-secondary{
      background:#fff;
      border:1px solid var(--border);
      color:var(--text);
    }

    .btn-secondary:hover{
      background:var(--bg-soft);
    }

    .section{
      padding:88px 0;
    }

    .eyebrow{
      display:inline-flex;
      align-items:center;
      gap:.55rem;
      padding:.45rem .9rem;
      border-radius:999px;
      background:#eef2ff;
      color:var(--primary);
      font-size:.88rem;
      font-weight:700;
      border:1px solid rgba(99,102,241,.12);
    }

    .section-head{
      text-align:center;
      max-width:720px;
      margin:0 auto 3.5rem;
    }

    .section-head h2{
      font-size:2.4rem;
      line-height:1.1;
      letter-spacing:-1.4px;
      margin-bottom:.9rem;
    }

    .section-head p{
      color:var(--muted);
      font-size:1.05rem;
    }

    /* NAV */
    nav{
      position:sticky;
      top:0;
      z-index:1000;
      backdrop-filter:blur(14px);
      background:rgba(255,255,255,.78);
      border-bottom:1px solid rgba(226,232,240,.8);
    }

    .nav-wrap{
      min-height:74px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:1rem;
    }

    .brand{
      display:flex;
      align-items:center;
      gap:.8rem;
      font-weight:800;
      font-size:1.15rem;
      letter-spacing:-.5px;
    }

    .brand-mark{
      width:38px;
      height:38px;
      border-radius:12px;
      background:linear-gradient(135deg, var(--primary), var(--primary-2));
      display:grid;
      place-items:center;
      color:#fff;
      box-shadow:0 10px 25px rgba(99,102,241,.25);
    }

    .nav-links{
      display:flex;
      align-items:center;
      gap:1.4rem;
      color:var(--muted);
      font-weight:500;
    }

    .nav-actions{
      display:flex;
      align-items:center;
      gap:.85rem;
    }

    /* HERO */
    .hero{
      padding:84px 0 54px;
    }

    .hero-grid{
      display:grid;
      grid-template-columns:1.05fr .95fr;
      gap:2.8rem;
      align-items:center;
    }

    .hero-copy h1{
      margin-top:1.15rem;
      font-size:4.25rem;
      line-height:1.02;
      letter-spacing:-2.8px;
      margin-bottom:1.15rem;
    }

    .hero-copy h1 .gradient{
      background:linear-gradient(135deg, var(--primary), var(--primary-2));
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
    }

    .hero-copy p{
      max-width:620px;
      color:var(--muted);
      font-size:1.12rem;
      margin-bottom:1.7rem;
    }

    .hero-actions{
      display:flex;
      align-items:center;
      gap:1rem;
      flex-wrap:wrap;
      margin-bottom:1rem;
    }

    .hero-meta{
      display:flex;
      flex-wrap:wrap;
      gap:1rem;
      color:var(--muted);
      font-size:.95rem;
    }

    .hero-meta span{
      display:flex;
      align-items:center;
      gap:.45rem;
    }

    /* Mockup */
    .hero-visual{
      position:relative;
    }

    .mock-shell{
      position:relative;
      background:linear-gradient(180deg, #ffffff, #f8fbff);
      border:1px solid var(--border);
      border-radius:28px;
      padding:18px;
      box-shadow:var(--shadow-lg);
      overflow:hidden;
    }

    .mock-toolbar{
      height:54px;
      border:1px solid var(--line);
      background:#fff;
      border-radius:16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:0 1rem;
      margin-bottom:14px;
    }

    .toolbar-left, .toolbar-right{
      display:flex;
      gap:.65rem;
      align-items:center;
    }

    .dot{
      width:10px;
      height:10px;
      border-radius:50%;
      background:#cbd5e1;
    }

    .tool-pill{
      height:34px;
      min-width:72px;
      border:1px solid var(--line);
      border-radius:10px;
      background:#fff;
    }

    .builder{
      display:grid;
      grid-template-columns:190px 1fr 220px;
      gap:14px;
      min-height:470px;
    }

    .panel{
      background:#fff;
      border:1px solid var(--line);
      border-radius:18px;
      padding:16px;
    }

    .tool{
      height:44px;
      border:1px dashed #dbe3ef;
      border-radius:12px;
      margin-bottom:10px;
      background:#fbfdff;
    }

    .canvas{
      background:linear-gradient(180deg,#f8fafc,#f3f6fb);
      border:1px solid var(--line);
      border-radius:18px;
      padding:20px;
      display:flex;
      justify-content:center;
      align-items:flex-start;
    }

    .survey-card{
      width:100%;
      max-width:380px;
      background:#fff;
      border-radius:22px;
      border:1px solid #edf2f7;
      box-shadow:0 18px 35px rgba(15,23,42,.08);
      padding:22px;
    }

    .survey-badge{
      width:52px;
      height:52px;
      border-radius:16px;
      background:linear-gradient(135deg, var(--primary), var(--primary-2));
      margin-bottom:18px;
    }

    .line{
      height:12px;
      border-radius:999px;
      background:#d8e0ea;
      margin-bottom:10px;
    }

    .line.dark{
      background:#0f172a;
    }

    .field{
      height:44px;
      border:1px solid #e2e8f0;
      border-radius:12px;
      background:#fff;
      margin-top:12px;
    }

    .setting{
      height:16px;
      border-radius:999px;
      background:#e2e8f0;
      margin-bottom:12px;
    }

    .floating-stat{
      position:absolute;
      background:rgba(255,255,255,.92);
      border:1px solid rgba(226,232,240,.9);
      box-shadow:var(--shadow);
      backdrop-filter:blur(10px);
      border-radius:16px;
      padding:.85rem 1rem;
      min-width:160px;
    }

    .floating-stat strong{
      display:block;
      font-size:1.1rem;
      margin-bottom:.1rem;
    }

    .stat-1{
      top:-14px;
      right:-12px;
    }

    .stat-2{
      left:-18px;
      bottom:36px;
    }

    /* logos */
    .logos{
      padding-top:18px;
    }

    .logos-row{
      display:grid;
      grid-template-columns:repeat(5,1fr);
      gap:1rem;
      margin-top:1.5rem;
    }

    .logo-box{
      border:1px solid var(--border);
      border-radius:16px;
      padding:1rem;
      text-align:center;
      font-weight:700;
      color:#94a3b8;
      background:#fff;
    }

    /* benefits */
    .cards{
      display:grid;
      grid-template-columns:repeat(4,1fr);
      gap:1.25rem;
    }

    .card{
      background:#fff;
      border:1px solid var(--border);
      border-radius:20px;
      padding:1.6rem;
      transition:.25s ease;
      box-shadow:0 8px 20px rgba(15,23,42,.04);
    }

    .card:hover{
      transform:translateY(-6px);
      border-color:#c7d2fe;
      box-shadow:0 18px 35px rgba(15,23,42,.08);
    }

    .icon-wrap{
      width:52px;
      height:52px;
      border-radius:14px;
      display:grid;
      place-items:center;
      background:#eef2ff;
      color:var(--primary);
      margin-bottom:1rem;
    }

    .card h3{
      font-size:1.05rem;
      margin-bottom:.55rem;
    }

    .card p{
      color:var(--muted);
      font-size:.96rem;
    }

    /* steps */
    .steps-wrap{
      background:var(--bg-section);
      border:1px solid var(--border);
      border-radius:32px;
      padding:2rem;
    }

    .steps{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:1rem;
    }

    .step{
      background:#fff;
      border:1px solid var(--border);
      border-radius:22px;
      padding:1.5rem;
    }

    .step-number{
      width:44px;
      height:44px;
      border-radius:14px;
      display:grid;
      place-items:center;
      background:var(--text);
      color:#fff;
      font-weight:700;
      margin-bottom:1rem;
    }

    .step h3{
      margin-bottom:.45rem;
    }

    .step p{
      color:var(--muted);
    }

    /* CTA */
    .cta{
      padding-top:30px;
    }

    .cta-box{
      border-radius:32px;
      padding:3rem 2rem;
      background:
        radial-gradient(circle at top left, rgba(139,92,246,.35), transparent 24%),
        radial-gradient(circle at bottom right, rgba(99,102,241,.30), transparent 26%),
        linear-gradient(135deg, #0f172a, #111827);
      color:#fff;
      text-align:center;
      box-shadow:var(--shadow-lg);
    }

    .cta-box h2{
      font-size:3rem;
      line-height:1.05;
      letter-spacing:-1.8px;
      margin-bottom:1rem;
    }

    .cta-box p{
      max-width:700px;
      margin:0 auto 1.6rem;
      color:rgba(255,255,255,.78);
    }

    /* footer */
    footer{
      padding:42px 0 50px;
      color:var(--muted);
    }

    .footer-row{
      display:flex;
      justify-content:space-between;
      gap:1.2rem;
      flex-wrap:wrap;
      border-top:1px solid var(--border);
      padding-top:28px;
    }

    .footer-links{
      display:flex;
      flex-wrap:wrap;
      gap:1rem;
    }

    /* reveal */
    .reveal{
      opacity:0;
      transform:translateY(24px);
      transition:opacity .7s ease, transform .7s ease;
    }

    .reveal.active{
      opacity:1;
      transform:translateY(0);
    }

    @media (max-width: 1100px){
      .hero-grid,
      .builder,
      .cards,
      .steps{
        grid-template-columns:1fr;
      }

      .builder{
        min-height:auto;
      }

      .floating-stat{
        position:static;
        margin-top:1rem;
      }

      .logos-row{
        grid-template-columns:repeat(2,1fr);
      }
    }

    @media (max-width: 768px){
      .nav-links{
        display:none;
      }

      .nav-actions .btn-secondary{
        display:none;
      }

      .hero{
        padding-top:56px;
      }

      .hero-copy h1{
        font-size:2.7rem;
        letter-spacing:-1.8px;
      }

      .hero-copy p{
        font-size:1rem;
      }

      .hero-actions{
        flex-direction:column;
        align-items:stretch;
      }

      .hero-actions .btn{
        width:100%;
      }

      .section{
        padding:72px 0;
      }

      .section-head h2,
      .cta-box h2{
        font-size:2rem;
      }

      .logos-row{
        grid-template-columns:1fr;
      }

      .mock-shell{
        padding:12px;
      }

      .panel:last-child{
        display:none;
      }
    }
  </style>
</head>
<body>
  <nav>
    <div class="container nav-wrap">
      <a href="#" class="brand">
        <span class="brand-mark"><i data-lucide="sparkles"></i></span>
        <span>MetrikBound</span>
      </a>

      <div class="nav-links">
        <a href="#">Producto</a>
        <a href="#">Plantillas</a>
        <a href="#">Precios</a>
        <a href="#">Demo</a>
      </div>

      <div class="nav-actions">
        <a href="/login" class="btn btn-secondary">Iniciar sesión</a>
        <a href="/register" class="btn btn-primary">Crear cuenta</a>
      </div>
    </div>
  </nav>

  <main>
    <section class="hero">
      <div class="container hero-grid">
        <div class="hero-copy reveal">
          <span class="eyebrow">
            <i data-lucide="wand-sparkles" style="width:16px;height:16px;"></i>
            Builder visual para encuestas modernas
          </span>
          <h1>
            Crea encuestas <span class="gradient">visuales</span><br>
            que sí dan ganas de responder.
          </h1>
          <p>
            Diseña formularios con una experiencia tipo Canva, comparte en segundos
            y analiza respuestas en tiempo real desde un dashboard limpio y potente.
          </p>

          <div class="hero-actions">
            <a href="#" class="btn btn-primary">
              <i data-lucide="rocket"></i>
              Crear encuesta gratis
            </a>
            <a href="#" class="btn btn-secondary">
              <i data-lucide="play-circle"></i>
              Ver demo
            </a>
          </div>

          <div class="hero-meta">
            <span><i data-lucide="badge-check" style="width:16px;height:16px;"></i> Sin tarjeta</span>
            <span><i data-lucide="layout-template" style="width:16px;height:16px;"></i> Plantillas listas</span>
            <span><i data-lucide="bar-chart-3" style="width:16px;height:16px;"></i> Analíticas en vivo</span>
          </div>
        </div>

        <div class="hero-visual reveal">
          <div class="mock-shell">
            <div class="mock-toolbar">
              <div class="toolbar-left">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
              </div>
              <div class="toolbar-right">
                <div class="tool-pill"></div>
                <div class="tool-pill"></div>
              </div>
            </div>

            <div class="builder">
              <aside class="panel">
                <div class="tool"></div>
                <div class="tool"></div>
                <div class="tool"></div>
                <div class="tool"></div>
                <div class="tool"></div>
              </aside>

              <div class="canvas">
                <div class="survey-card">
                  <div class="survey-badge"></div>
                  <div class="line dark" style="width:80%;"></div>
                  <div class="line" style="width:56%;"></div>
                  <div class="field"></div>
                  <div class="field"></div>
                  <div class="field" style="height:64px;"></div>
                </div>
              </div>

              <aside class="panel">
                <div class="setting" style="width:80%;"></div>
                <div class="setting" style="width:65%;"></div>
                <div class="setting" style="width:90%;"></div>
                <div class="setting" style="width:74%;"></div>
                <div class="setting" style="width:58%;"></div>
              </aside>
            </div>
          </div>

          <div class="floating-stat stat-1">
            <strong>+128 respuestas</strong>
            <span style="color:var(--muted); font-size:.92rem;">en las últimas 24h</span>
          </div>

          <div class="floating-stat stat-2">
            <strong>+34% conversión</strong>
            <span style="color:var(--muted); font-size:.92rem;">con formularios visuales</span>
          </div>
        </div>
      </div>

      <div class="container logos reveal">
        <div class="logos-row">
          <div class="logo-box">StudioFlow</div>
          <div class="logo-box">NovaLab</div>
          <div class="logo-box">PixelRoot</div>
          <div class="logo-box">ScaleGrid</div>
          <div class="logo-box">FormAxis</div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="section-head reveal">
          <h2>Todo lo que necesitas para captar mejores respuestas</h2>
          <p>No solo creas formularios. Construyes experiencias visuales que conectan con tu marca y convierten mejor.</p>
        </div>

        <div class="cards">
          <article class="card reveal">
            <div class="icon-wrap"><i data-lucide="blocks"></i></div>
            <h3>Drag & Drop real</h3>
            <p>Mueve preguntas, bloques, títulos y secciones con una experiencia visual fluida y natural.</p>
          </article>

          <article class="card reveal">
            <div class="icon-wrap"><i data-lucide="palette"></i></div>
            <h3>Diseño a tu estilo</h3>
            <p>Colores, tipografías, espaciado y layouts para que cada encuesta se sienta parte de tu marca.</p>
          </article>

          <article class="card reveal">
            <div class="icon-wrap"><i data-lucide="activity"></i></div>
            <h3>Resultados en vivo</h3>
            <p>Revisa respuestas al instante y detecta patrones sin esperar a exportar ni ordenar datos manualmente.</p>
          </article>

          <article class="card reveal">
            <div class="icon-wrap"><i data-lucide="send"></i></div>
            <h3>Comparte donde quieras</h3>
            <p>Publica con link, incrusta en tu web o distribuye tu encuesta desde cualquier canal.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="section-head reveal">
          <h2>Así de simple funciona</h2>
          <p>Diseña, publica y entiende tus resultados sin complicarte.</p>
        </div>

        <div class="steps-wrap">
          <div class="steps">
            <div class="step reveal">
              <div class="step-number">1</div>
              <h3>Crea</h3>
              <p>Empieza desde cero o elige una plantilla y personalízala con tu identidad visual.</p>
            </div>

            <div class="step reveal">
              <div class="step-number">2</div>
              <h3>Comparte</h3>
              <p>Genera un enlace, publícalo o insértalo directamente en tu sitio o plataforma.</p>
            </div>

            <div class="step reveal">
              <div class="step-number">3</div>
              <h3>Analiza</h3>
              <p>Observa respuestas, métricas y tendencias con un dashboard claro y bonito.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section cta">
      <div class="container">
        <div class="cta-box reveal">
          <h2>Deja atrás los formularios sin alma.</h2>
          <p>
            Empieza hoy con MetrikBound y convierte tus encuestas en una experiencia visual
            más atractiva, más profesional y más efectiva.
          </p>
          <a href="#" class="btn btn-primary">Empieza gratis hoy</a>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="container footer-row">
      <div class="brand">
        <span class="brand-mark"><i data-lucide="sparkles"></i></span>
        <span>MetrikBound</span>
      </div>

      <div>© 2026 MetrikBound. Todos los derechos reservados.</div>

      <div class="footer-links">
        <a href="#">Privacidad</a>
        <a href="#">Términos</a>
        <a href="#">LinkedIn</a>
        <a href="#">X / Twitter</a>
      </div>
    </div>
  </footer>

  <script>
    lucide.createIcons();

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("active");
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll(".reveal").forEach((el) => observer.observe(el));
  </script>
</body>
</html>