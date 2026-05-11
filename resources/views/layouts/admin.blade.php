<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — DeliverEats')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @livewireStyles
</head>
<body class="min-h-screen bg-surface-50 dark:bg-neutral-950 font-body text-surface-900 dark:text-gray-100 antialiased transition-colors duration-300">
    <div class="flex min-h-screen">
        {{-- ── Sidebar ────────────────────────────────────── --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-neutral-900 border-r border-surface-200 dark:border-white/10 flex flex-col max-lg:-translate-x-full transition-transform shadow-sm" id="admin-sidebar">
            <div class="h-16 flex items-center gap-3 px-5 border-b border-surface-100 dark:border-white/5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-display font-bold text-surface-900 dark:text-white">Deliver<span class="text-brand-500">Eats</span></p>
                    <p class="text-[10px] text-surface-400 dark:text-gray-500 font-medium uppercase tracking-wider">Admin Panel</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.control-tower') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.control-tower') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Control Tower
                    <span class="ml-auto flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] text-emerald-600 font-medium">LIVE</span>
                    </span>
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.restaurants') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.restaurants') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Restaurants
                </a>
                <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.orders') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Orders
                </a>
                <a href="{{ route('admin.riders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.riders') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/></svg>
                    Riders
                </a>
                <a href="{{ route('admin.reviews') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.reviews') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Reviews
                </a>
                <a href="{{ route('admin.payouts') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.payouts') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/></svg>
                    Payouts
                </a>
                <a href="{{ route('admin.surge-pricing') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.surge-pricing') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Surge Pricing
                </a>
            </nav>

            <div class="p-4 border-t border-surface-100 dark:border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-surface-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-surface-400 dark:text-gray-500 uppercase">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ── Main Content ───────────────────────────────── --}}
        <div class="flex-1 lg:ml-64 transition-all duration-300">
            <header class="sticky top-0 z-30 h-16 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-xl border-b border-surface-100 dark:border-white/10 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-surface-100 dark:hover:bg-white/5" onclick="document.getElementById('admin-sidebar').classList.toggle('max-lg:-translate-x-full')">
                        <svg class="w-5 h-5 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg font-display font-bold text-surface-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">


                    {{-- Profile Dropdown --}}
                    <div class="relative ml-2">
                        <button id="admin-profile-btn" class="flex items-center gap-2 p-1 pl-3 pr-2 rounded-full border border-surface-200 dark:border-white/10 hover:bg-surface-50 dark:hover:bg-white/5 transition-colors focus:outline-none">
                            <span class="text-sm font-medium text-surface-900 dark:text-white hidden sm:block">{{ auth()->user()->name }}</span>
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        </button>
                        
                        <div id="admin-profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-900 rounded-xl shadow-xl border border-surface-200 dark:border-white/10 py-1 z-50 animate-in fade-in slide-in-from-top-2">
                            <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-surface-700 dark:text-gray-300 hover:bg-surface-50 dark:hover:bg-white/5 transition-colors">Settings</a>
                            <div class="border-t border-surface-100 dark:border-white/5 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.Toast = {
            show(title, message, icon = 'info') {
                Swal.fire({ toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, icon, title, text: message });
            }
        };

        @if(session('success')) window.Toast.show('Success', "{{ session('success') }}", 'success'); @endif
        @if(session('error')) window.Toast.show('Error', "{{ session('error') }}", 'error'); @endif

        document.addEventListener('DOMContentLoaded', () => {
            const profileBtn = document.getElementById('admin-profile-btn');
            const profileMenu = document.getElementById('admin-profile-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => { e.stopPropagation(); profileMenu.classList.toggle('hidden'); });
                document.addEventListener('click', () => profileMenu.classList.add('hidden'));
            }
        });
    </script>
    @stack('scripts')
    @livewireScripts
    <script>
        function initGoogleMaps() {
            window.dispatchEvent(new Event('google-maps-loaded'));
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initGoogleMaps&libraries=places&v=weekly" defer></script>
</body>
</html>