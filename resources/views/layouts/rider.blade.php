<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rider — DeliverEats')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-900 font-body text-white antialiased">

    {{-- ── Mobile-first rider layout ──────────────────────── --}}
    <div class="max-w-lg mx-auto min-h-screen flex flex-col relative">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 glass-dark px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-display font-bold">Deliver<span class="text-brand-400">Eats</span></p>
                    <p class="text-[10px] text-surface-300 uppercase tracking-wider">Rider</p>
                </div>
            </div>

            {{-- Online toggle --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-surface-300">Online</span>
                    <button id="rider-online-toggle" class="relative w-11 h-6 bg-emerald-500 rounded-full transition-colors cursor-pointer" onclick="this.classList.toggle('bg-emerald-500'); this.classList.toggle('bg-surface-800'); this.querySelector('span').classList.toggle('translate-x-5'); this.querySelector('span').classList.toggle('translate-x-0.5')">
                        <span class="absolute top-0.5 left-0 w-5 h-5 bg-white rounded-full shadow-md transform translate-x-5 transition-transform"></span>
                    </button>
                </div>
                <button class="relative p-2 rounded-xl hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5 text-surface-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-brand-500 rounded-full"></span>
                </button>
            </div>
        </header>

        {{-- Main content --}}
        <main class="flex-1 pb-20">
            @yield('content')
        </main>

        {{-- Bottom navigation --}}
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-lg z-40 mobile-bottom-nav">
            <div class="glass-dark border-t border-white/10 px-6 py-2 flex items-center justify-around">
                <a href="{{ route('rider.dashboard') }}" class="{{ request()->routeIs('rider.dashboard') ? 'active' : '' }} flex flex-col items-center gap-1 py-1 px-3">
                    <svg class="w-5 h-5 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-[10px] text-surface-300 font-medium">Home</span>
                </a>
                <a href="{{ route('rider.delivery', ['id' => 'active']) }}" class="{{ request()->routeIs('rider.delivery') ? 'active' : '' }} flex flex-col items-center gap-1 py-1 px-3">
                    <svg class="w-5 h-5 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-[10px] text-surface-300 font-medium">Delivery</span>
                </a>
                <a href="{{ route('rider.earnings') }}" class="{{ request()->routeIs('rider.earnings') ? 'active' : '' }} flex flex-col items-center gap-1 py-1 px-3">
                    <svg class="w-5 h-5 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[10px] text-surface-300 font-medium">Earnings</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-1 py-1 px-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">M</div>
                    <span class="text-[10px] text-surface-300 font-medium">Profile</span>
                </a>
            </div>
        </nav>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
