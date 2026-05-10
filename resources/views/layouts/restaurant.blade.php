<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant Dashboard — DeliverEats')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Dark Mode Initialization (Default to Dark)
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="min-h-screen bg-surface-50 dark:bg-neutral-950 font-body text-surface-900 dark:text-gray-100 antialiased transition-colors duration-300">
    <div class="flex min-h-screen">
        {{-- ── Sidebar ────────────────────────────────────── --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-neutral-900 border-r border-surface-200 dark:border-white/10 flex flex-col max-lg:-translate-x-full transition-transform shadow-sm" id="restaurant-sidebar">
            <div class="h-16 flex items-center gap-3 px-5 border-b border-surface-100 dark:border-white/5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-display font-bold text-surface-900 dark:text-white">Deliver<span class="text-brand-500">Eats</span></p>
                    <p class="text-[10px] text-surface-400 dark:text-gray-500 uppercase tracking-wider">Restaurant Portal</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('restaurant.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('restaurant.dashboard') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('restaurant.menu') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('restaurant.menu') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Menu
                </a>
                <a href="{{ route('restaurant.orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('restaurant.orders') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Orders
                </a>
                <a href="{{ route('restaurant.reviews') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('restaurant.reviews') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Reviews
                </a>
                <a href="{{ route('restaurant.payouts') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('restaurant.payouts') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Payouts
                </a>

                <div class="pt-4 mt-4 border-t border-surface-100 dark:border-white/5">
                    <a href="{{ route('restaurant.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('restaurant.settings') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-500' : 'text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Settings
                    </a>
                    <a href="{{ route('customer.home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-600 dark:text-gray-400 hover:bg-surface-50 dark:hover:bg-white/5 hover:text-surface-900 dark:hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back to Store
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-surface-100 dark:border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xs font-bold uppercase">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-surface-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-500 font-medium flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ── Main Content ───────────────────────────────── --}}
        <div class="flex-1 lg:ml-64 transition-all duration-300">
            <header class="sticky top-0 z-30 h-16 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-xl border-b border-surface-100 dark:border-white/10 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-surface-100 dark:hover:bg-white/5" onclick="document.getElementById('restaurant-sidebar').classList.toggle('max-lg:-translate-x-full')">
                        <svg class="w-5 h-5 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg font-display font-bold text-surface-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Dark Mode Toggle --}}
                    <button onclick="toggleDarkMode()" class="p-2 rounded-xl bg-surface-100 dark:bg-white/5 hover:bg-surface-200 dark:hover:bg-white/10 transition-all group">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-surface-600 group-hover:text-brand-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-amber-400 group-hover:text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div class="relative ml-2">
                        <button id="rest-profile-btn" class="flex items-center gap-2 p-1 pl-3 pr-2 rounded-full border border-surface-200 dark:border-white/10 hover:bg-surface-50 dark:hover:bg-white/5 transition-colors focus:outline-none">
                            <span class="text-sm font-medium text-surface-900 dark:text-white hidden sm:block">{{ auth()->user()->name }}</span>
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        </button>
                        
                        <div id="rest-profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-900 rounded-xl shadow-xl border border-surface-200 dark:border-white/10 py-1 z-50 animate-in fade-in slide-in-from-top-2">
                            <a href="{{ route('restaurant.settings') }}" class="block px-4 py-2 text-sm text-surface-700 dark:text-gray-300 hover:bg-surface-50 dark:hover:bg-white/5 transition-colors">Settings</a>
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

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.Toast = {
            show(title, message, icon = 'info') {
                Swal.fire({ toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, icon, title, text: message });
            }
        };

        @if(session('success')) window.Toast.show('Success', "{{ session('success') }}", 'success'); @endif
        @if(session('error')) window.Toast.show('Error', "{{ session('error') }}", 'error'); @endif

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark'); localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark'); localStorage.setItem('color-theme', 'dark');
            }
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            if (!darkIcon || !lightIcon) return;
            if (document.documentElement.classList.contains('dark')) { darkIcon.classList.add('hidden'); lightIcon.classList.remove('hidden'); } 
            else { darkIcon.classList.remove('hidden'); lightIcon.classList.add('hidden'); }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcons();
            const profileBtn = document.getElementById('rest-profile-btn');
            const profileMenu = document.getElementById('rest-profile-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => { e.stopPropagation(); profileMenu.classList.toggle('hidden'); });
                document.addEventListener('click', () => profileMenu.classList.add('hidden'));
            }
        });
    </script>
    @stack('scripts')
</body>
</html>