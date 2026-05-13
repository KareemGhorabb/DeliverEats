<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rider — DeliverEats')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
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

    {{-- ── Mobile-first rider layout ──────────────────────── --}}
    <div class="max-w-lg mx-auto min-h-screen flex flex-col relative bg-white dark:bg-neutral-900 shadow-xl border-x border-surface-200 dark:border-white/5">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-xl px-4 py-3 flex items-center justify-between border-b border-surface-100 dark:border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-display font-bold text-surface-900 dark:text-white">Deliver<span class="text-brand-500">Eats</span></p>
                    <p class="text-[10px] text-surface-400 dark:text-gray-500 uppercase tracking-wider">Rider Portal</p>
                </div>
            </div>

            {{-- Controls and Profile --}}
            <div class="flex items-center gap-3">
                {{-- Dark Mode Toggle --}}
                <button onclick="toggleDarkMode()" class="p-2 rounded-xl bg-surface-100 dark:bg-white/5 hover:bg-surface-200 dark:hover:bg-white/10 transition-all group">
                    <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4 text-surface-600 group-hover:text-brand-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-4 h-4 text-amber-400 group-hover:text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                {{-- Profile Dropdown --}}
                <div class="relative">
                    <button id="rider-profile-btn" class="relative p-1 rounded-xl hover:bg-surface-100 dark:hover:bg-white/10 transition-colors focus:outline-none border border-surface-200 dark:border-white/10">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white text-xs font-bold" id="header-avatar">R</div>
                    </button>
                    
                    <div id="rider-profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-800 rounded-xl shadow-2xl border border-surface-200 dark:border-white/10 py-1 z-50 animate-in fade-in slide-in-from-top-2">
                        <div class="px-4 py-3 border-b border-surface-100 dark:border-white/5">
                            <p class="text-sm font-semibold text-surface-900 dark:text-white" id="dropdown-name">Rider Account</p>
                            <p class="text-xs text-surface-400" id="dropdown-email"></p>
                        </div>
                        <a href="{{ route('rider.settings') }}" class="block px-4 py-2 text-sm text-surface-700 dark:text-gray-300 hover:bg-surface-50 dark:hover:bg-white/5 transition-colors">Settings</a>
                        <div class="border-t border-surface-100 dark:border-white/5 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main content --}}
        <main class="flex-1 pb-20">
            @yield('content')
        </main>

        {{-- Bottom navigation --}}
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-lg z-40 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-xl border-t border-surface-100 dark:border-white/5">
            <div class="px-6 py-2 flex items-center justify-around">
                <a href="{{ route('rider.dashboard') }}" class="{{ request()->routeIs('rider.dashboard') ? 'text-brand-600 dark:text-brand-500' : 'text-surface-400 dark:text-gray-500' }} flex flex-col items-center gap-1 py-1 px-3 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-[10px] font-medium">Home</span>
                </a>
                <a href="{{ route('rider.delivery', ['id' => 'active']) }}" class="{{ request()->routeIs('rider.delivery') ? 'text-brand-600 dark:text-brand-500' : 'text-surface-400 dark:text-gray-500' }} flex flex-col items-center gap-1 py-1 px-3 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-[10px] font-medium">Delivery</span>
                </a>
                <a href="{{ route('rider.earnings') }}" class="{{ request()->routeIs('rider.earnings') ? 'text-brand-600 dark:text-brand-500' : 'text-surface-400 dark:text-gray-500' }} flex flex-col items-center gap-1 py-1 px-3 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[10px] font-medium">Earnings</span>
                </a>
            </div>
        </nav>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Global Toast System
        window.Toast = {
            show(title, message, icon = 'info') {
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon,
                    title,
                    text: message
                });
            }
        };

        @if(session('success')) window.Toast.show('Success', "{{ session('success') }}", 'success'); @endif
        @if(session('error')) window.Toast.show('Error', "{{ session('error') }}", 'error'); @endif

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            if (!darkIcon || !lightIcon) return;
            if (document.documentElement.classList.contains('dark')) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcons();
            const profileBtn = document.getElementById('rider-profile-btn');
            const profileMenu = document.getElementById('rider-profile-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => { e.stopPropagation(); profileMenu.classList.toggle('hidden'); });
                document.addEventListener('click', () => profileMenu.classList.add('hidden'));
            }

            if (typeof Auth !== 'undefined' && Auth.isLoggedIn()) {
                fetch('/api/v1/user', {
                    headers: { 'Authorization': 'Bearer ' + Auth.getToken(), 'Accept': 'application/json' }
                }).then(res => res.json()).then(user => {
                    if (user && user.name) {
                        const init = user.name.charAt(0).toUpperCase();
                        document.getElementById('header-avatar').textContent = init;
                        document.getElementById('dropdown-name').textContent = user.name;
                        document.getElementById('dropdown-email').textContent = user.email;
                    }
                }).catch(console.error);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>