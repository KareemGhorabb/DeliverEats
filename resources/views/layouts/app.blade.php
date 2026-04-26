<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DeliverEats — Your cravings, delivered. Order from the best local restaurants with real-time tracking.">
    <title>@yield('title', 'DeliverEats — Your cravings, delivered.')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-50 font-body text-surface-900 antialiased">

    {{-- ── Top Navigation ─────────────────────────────────── --}}
    <nav class="fixed top-0 inset-x-0 z-50 glass border-b border-white/30 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center shadow-md shadow-brand-500/25 group-hover:shadow-brand-500/40 transition-shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-lg font-display font-bold tracking-tight">Deliver<span class="text-brand-500">Eats</span></span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('customer.home') }}" class="px-4 py-2 text-sm font-medium text-surface-800/70 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all">Browse</a>
                    <a href="{{ route('customer.orders.index') }}" class="px-4 py-2 text-sm font-medium text-surface-800/70 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all">My Orders</a>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Cart --}}
                    <a href="{{ route('customer.cart') }}" class="relative p-2 rounded-xl hover:bg-brand-50 transition-colors group">
                        <svg class="w-6 h-6 text-surface-800/70 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <span class="cart-badge-wrapper absolute -top-0.5 -right-0.5 hidden">
                            <span data-cart-count class="flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-brand-500 rounded-full shadow-sm">0</span>
                        </span>
                    </a>

                    {{-- Profile Dropdown --}}
                    <div class="relative">
                        <button data-dropdown-toggle="profile-dropdown" class="flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-full hover:bg-surface-100 transition-colors border border-surface-200">
                            <span class="text-sm font-medium hidden sm:block">Ahmed</span>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-xs font-bold">A</div>
                        </button>
                        <div id="profile-dropdown" class="dropdown-menu hidden open:block absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-surface-200/60 py-2 z-50">
                            <div class="px-4 py-3 border-b border-surface-100">
                                <p class="text-sm font-semibold">Ahmed Hassan</p>
                                <p class="text-xs text-surface-300">ahmed@delivereats.com</p>
                            </div>
                            <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-800/80 hover:bg-surface-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                My Profile
                            </a>
                            <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-800/80 hover:bg-surface-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Order History
                            </a>
                            <div class="border-t border-surface-100 my-1"></div>
                            <a href="{{ route('restaurant.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-800/80 hover:bg-surface-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Restaurant Portal
                            </a>
                            <a href="{{ route('rider.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-800/80 hover:bg-surface-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Rider Dashboard
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-800/80 hover:bg-surface-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Admin Panel
                            </a>
                            <div class="border-t border-surface-100 my-1"></div>
                            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </a>
                        </div>
                    </div>

                    {{-- Mobile menu button --}}
                    <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-lg hover:bg-surface-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Mobile menu overlay --}}
    <div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-black/40 z-40 md:hidden"></div>
    <div id="mobile-menu" class="fixed top-0 left-0 bottom-0 w-72 bg-white z-50 transform -translate-x-full transition-transform duration-300 md:hidden shadow-2xl">
        <div class="p-5 border-b border-surface-100 flex items-center justify-between">
            <span class="text-lg font-display font-bold">Deliver<span class="text-brand-500">Eats</span></span>
            <button id="mobile-menu-toggle" class="p-2 -mr-2 rounded-lg hover:bg-surface-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('customer.home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse Restaurants
            </a>
            <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                My Orders
            </a>
            <a href="{{ route('customer.cart') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                Cart
            </a>
        </nav>
    </div>

    {{-- ── Main Content ───────────────────────────────────── --}}
    <main class="pt-16 min-h-screen">
        @yield('content')
    </main>

    {{-- ── Footer ─────────────────────────────────────────── --}}
    @hasSection('hide-footer')
    @else
    <footer class="bg-surface-900 text-white no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-lg font-display font-bold">Deliver<span class="text-brand-400">Eats</span></span>
                    </div>
                    <p class="text-sm text-surface-300 leading-relaxed">Your favourite meals from the best local restaurants, delivered straight to your door.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-surface-300 mb-4">Company</h4>
                    <ul class="space-y-3 text-sm text-surface-200">
                        <li><a href="#" class="hover:text-brand-400 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Press</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-surface-300 mb-4">For You</h4>
                    <ul class="space-y-3 text-sm text-surface-200">
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Partner with Us</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Ride with Us</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Gift Cards</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-surface-300 mb-4">Legal</h4>
                    <ul class="space-y-3 text-sm text-surface-200">
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-surface-300">&copy; {{ date('Y') }} DeliverEats. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-brand-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-brand-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-brand-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    @endif

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
