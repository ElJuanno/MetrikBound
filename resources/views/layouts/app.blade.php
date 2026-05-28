<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MetrikBound')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fb] text-slate-950 antialiased">
    <div class="min-h-screen lg:pl-[280px]">
        <aside class="fixed inset-y-0 left-0 z-50 hidden w-[280px] border-r border-slate-200 bg-white lg:flex lg:flex-col">
            <div class="border-b border-slate-100 px-6 py-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/Logos.png') }}" alt="MetrikBound" class="w-40">
                </a>
            </div>

            <div class="px-4 py-5">
                <a href="{{ route('surveys.create') }}" class="flex items-center justify-between rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-semibold text-teal-800 transition hover:border-teal-300 hover:bg-teal-100">
                    <span>Nueva encuesta</span>
                    <span class="text-lg leading-none">+</span>
                </a>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 pb-6">
                @php
                    $links = [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Panel', 'dot' => 'bg-teal-500'],
                        ['route' => 'surveys.index', 'active' => 'surveys.*', 'label' => 'Encuestas', 'dot' => 'bg-sky-500'],
                        ['route' => 'templates.index', 'active' => 'templates.*', 'label' => 'Plantillas', 'dot' => 'bg-amber-500'],
                        ['route' => 'results.index', 'active' => 'results.*', 'label' => 'Resultados', 'dot' => 'bg-emerald-500'],
                        ['route' => 'profile.edit', 'active' => 'profile.*', 'label' => 'Cuenta', 'dot' => 'bg-slate-500'],
                    ];
                @endphp

                @foreach($links as $link)
                    <a href="{{ route($link['route']) }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs($link['active']) ? 'bg-slate-950 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">
                        <span class="h-2.5 w-2.5 rounded-full {{ $link['dot'] }}"></span>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

        </aside>

        <div class="min-w-0">
            <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-10">
                    <div class="flex min-w-0 items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="lg:hidden">
                            <img src="{{ asset('images/Logos.png') }}" alt="MetrikBound" class="w-32">
                        </a>
                        <div class="hidden min-w-0 lg:block">
                            <p class="truncate text-sm font-semibold text-slate-950">@yield('title', 'Panel')</p>
                            <p class="text-xs text-slate-500">Encuestas, respuestas y decisiones en un solo lugar</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
                            <div class="hidden items-center gap-3 rounded-lg border border-slate-200 bg-white px-3 py-2 shadow-sm sm:flex">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-teal-700">
                                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email ?? 'U', 0, 1)) }}
                                </div>
                                <div class="leading-tight">
                                    <p class="max-w-[150px] truncate text-sm font-semibold text-slate-950">{{ auth()->user()->name ?? 'Usuario' }}</p>
                                    <p class="text-xs capitalize text-slate-500">{{ auth()->user()->plan ?? 'free' }}</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-secondary px-4 py-2">Salir</button>
                            </form>
                        @endauth
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto border-t border-slate-100 px-4 py-2 sm:px-6 lg:hidden">
                    @foreach($links ?? [] as $link)
                        <a href="{{ route($link['route']) }}" class="shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold {{ request()->routeIs($link['active']) ? 'bg-slate-950 text-white' : 'bg-slate-100 text-slate-600' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>
            </header>

            <main class="px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </div>
</body>
</html>
