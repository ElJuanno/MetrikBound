<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MetrikBound')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    <div class="min-h-screen lg:grid lg:grid-cols-[280px_minmax(0,1fr)]">

        {{-- SIDEBAR REDISEÑADO --}}
        <aside class="hidden lg:flex lg:min-h-screen lg:flex-col bg-slate-900 text-white">
            {{-- Logo --}}
            <div class="flex items-center justify-center px-2 py-0 border-b border-white/10">
                <img src="{{ asset('images/logo.png') }}" alt="MetrikBound" class="w-full h-auto max-w-[200px]">
            </div>

            {{-- CTA Card --}}
            <div class="px-5 py-6">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-600 p-5 shadow-xl">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_50%)]"></div>
                    
                    <div class="relative">
                        <div class="text-[10px] uppercase tracking-[.2em] text-white/70 font-bold">
                            Workspace
                        </div>
                        <div class="mt-2 text-lg font-bold">Panel Principal</div>
                        <div class="mt-1 text-sm text-white/90 leading-relaxed">
                            Crea y analiza mejor
                        </div>
                        <a href="{{ route('surveys.create') }}"
                           class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-white/20 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/30 hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nueva encuesta
                        </a>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="group {{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="nav-icon bg-indigo-400 group-hover:scale-125"></span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('surveys.index') }}" class="group {{ request()->routeIs('surveys.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="nav-icon bg-cyan-400 group-hover:scale-125"></span>
                    <span>Encuestas</span>
                </a>

                <a href="{{ route('templates.index') }}" class="group {{ request()->routeIs('templates.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="nav-icon bg-emerald-400 group-hover:scale-125"></span>
                    <span>Plantillas</span>
                </a>

                <a href="{{ route('results.index') }}" class="group {{ request()->routeIs('results.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                    <span class="nav-icon bg-pink-400 group-hover:scale-125"></span>
                    <span>Resultados</span>
                </a>
            </nav>

            {{-- User Card --}}
            <div class="px-5 py-6 border-t border-white/10">
                <div class="rounded-xl bg-white/5 p-4 backdrop-blur-sm border border-white/10">
                    <div class="text-[10px] uppercase tracking-[.2em] text-slate-400 font-bold">Plan Actual</div>
                    <div class="mt-2 text-lg font-bold capitalize">{{ auth()->user()->plan ?? 'free' }}</div>
                    <div class="mt-1 text-sm text-slate-400">Listo para crear</div>
                    
                    @if((auth()->user()->plan ?? 'free') === 'free')
                        <button class="mt-3 w-full rounded-lg bg-gradient-to-r from-amber-500 to-orange-500 px-3 py-2 text-xs font-semibold text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                            Mejorar plan
                        </button>
                    @endif
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="min-w-0 flex flex-col min-h-screen">
            {{-- TOPBAR REDISEÑADO --}}
            <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur-xl">
                <div class="flex items-center justify-between px-6 py-4 lg:px-8">
                    {{-- Mobile Logo --}}
                    <div class="flex items-center gap-3 lg:hidden">
                        <img src="{{ asset('images/logo.png') }}" alt="MetrikBound" class="h-10 w-auto">
                    </div>

                    {{-- Page Title --}}
                    <div class="hidden lg:block">
                        <h1 class="text-sm font-semibold text-slate-600">@yield('title', 'Dashboard')</h1>
                    </div>

                    {{-- User Actions --}}
                    <div class="flex items-center gap-3">
                        @auth
                        {{-- User Info --}}
                        <div class="hidden sm:flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-xs font-bold text-indigo-700">
                                {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email ?? 'U', 0, 1)) }}
                            </div>
                            <div class="leading-tight">
                                <div class="max-w-[160px] truncate text-sm font-semibold text-slate-900">
                                    {{ auth()->user()->name ?? 'Usuario' }}
                                </div>
                                <div class="text-xs text-slate-500 capitalize">
                                    {{ auth()->user()->plan ?? 'free' }}
                                </div>
                            </div>
                        </div>
                        @endauth

                        @auth
                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-secondary">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Salir
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- MAIN CONTENT AREA --}}
            <main class="flex-1 px-6 py-8 lg:px-8 animate-fade-in">
                @yield('content')
            </main>

            {{-- FOOTER --}}
            <footer class="border-t border-slate-200 bg-white px-6 py-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-slate-500">
                        © {{ date('Y') }} MetrikBound. Encuestas visuales modernas.
                    </div>
                    <div class="flex gap-6 text-sm">
                        <a href="#" class="text-slate-500 hover:text-slate-900 transition-colors">Ayuda</a>
                        <a href="#" class="text-slate-500 hover:text-slate-900 transition-colors">Términos</a>
                        <a href="#" class="text-slate-500 hover:text-slate-900 transition-colors">Privacidad</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

</body>
</html>