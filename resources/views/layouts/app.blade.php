<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MetrikBound')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f7fb] text-slate-900 antialiased">

    <div class="min-h-screen lg:grid lg:grid-cols-[270px_minmax(0,1fr)]">

        {{-- SIDEBAR --}}
        <aside class="hidden lg:flex lg:min-h-screen lg:flex-col bg-[#0f172a] text-white">
            <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-bold shadow-lg">
                    M
                </div>
                <div>
                    <div class="text-base font-semibold tracking-tight">MetrikBound</div>
                    <div class="text-xs text-slate-400">encuestas visuales</div>
                </div>
            </div>

            <div class="px-4 py-5">
                <div class="rounded-3xl bg-gradient-to-br from-indigo-500 to-violet-500 p-5 shadow-[0_20px_40px_rgba(99,102,241,.30)]">
                    <div class="text-[11px] uppercase tracking-[.2em] text-white/70 font-semibold">
                        Espacio de trabajo
                    </div>
                    <div class="mt-2 text-xl font-bold">Panel principal</div>
                    <div class="mt-1 text-sm text-white/80">
                        Crea, comparte y analiza mejor.
                    </div>
                    <a href="{{ route('surveys.create') }}"
                       class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-white/15 px-4 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                        + Nueva encuesta
                    </a>
                </div>
            </div>

            @php
                $base = 'group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition';
                $active = 'bg-white/10 text-white';
                $normal = 'text-slate-300 hover:bg-white/5 hover:text-white';
            @endphp

            <nav class="px-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="{{ $base }} {{ request()->routeIs('dashboard') ? $active : $normal }}">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-indigo-400"></span>
                    Dashboard
                </a>

                <a href="{{ route('surveys.index') }}" class="{{ $base }} {{ request()->routeIs('surveys.*') ? $active : $normal }}">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-cyan-400"></span>
                    Encuestas
                </a>

                <a href="{{ route('templates.index') }}" class="{{ $base }} {{ request()->routeIs('templates.*') ? $active : $normal }}">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                    Plantillas
                </a>

                <a href="{{ route('results.index') }}" class="{{ $base }} {{ request()->routeIs('results.*') ? $active : $normal }}">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-pink-400"></span>
                    Resultados
                </a>
            </nav>

            <div class="mt-auto px-4 py-6">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-xs uppercase tracking-[.18em] text-slate-400">Plan actual</div>
                    <div class="mt-2 text-lg font-semibold capitalize">{{ auth()->user()->plan ?? 'free' }}</div>
                    <div class="mt-1 text-sm text-slate-400">Listo para crear encuestas.</div>
                </div>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="min-w-0">
            {{-- TOPBAR --}}
            <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/85 backdrop-blur-xl">
                <div class="flex items-center justify-between px-5 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3 lg:hidden">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-bold text-white">
                            M
                        </div>
                        <div>
                            <div class="text-sm font-semibold">MetrikBound</div>
                            <div class="text-xs text-slate-500">encuestas visuales</div>
                        </div>
                    </div>

                    <div class="hidden lg:block">
                        <h1 class="text-sm font-semibold text-slate-500">@yield('title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-50 to-violet-50 text-xs font-bold text-indigo-600">
                                {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                            </div>
                            <div class="leading-tight">
                                <div class="max-w-[180px] truncate text-sm font-semibold text-slate-900">
                                    {{ auth()->user()->name ?? 'Usuario' }}
                                </div>
                                <div class="text-xs text-slate-500 capitalize">
                                    Plan {{ auth()->user()->plan ?? 'free' }}
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="px-5 py-6 sm:px-6 lg:px-8 lg:py-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>