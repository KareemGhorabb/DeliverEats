<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DeliverEats — Order from 500+ restaurants with real-time tracking. Your cravings, delivered in minutes.">
    <title>DeliverEats — Your cravings, delivered.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-50 font-body antialiased overflow-x-hidden">

    {{-- ── Navbar ──────────────────────────────────────────── --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white border-b border-surface-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center shadow-md shadow-brand-500/25">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-lg font-display font-bold">Deliver<span class="text-brand-500">Eats</span></span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="#how-it-works" class="text-sm font-medium text-surface-800/70 hover:text-brand-600 transition-colors">How It Works</a>
                    <a href="#restaurants" class="text-sm font-medium text-surface-800/70 hover:text-brand-600 transition-colors">Restaurants</a>
                    <a href="#partner" class="text-sm font-medium text-surface-800/70 hover:text-brand-600 transition-colors">Partner With Us</a>
                </div>
                <div class="flex items-center gap-3">
                    <div id="auth-buttons" style="display: none;" class="items-center gap-3">
                        <a href="#" id="dashboard-btn" class="px-4 py-2 text-sm font-medium text-surface-800/80 hover:text-brand-600 transition-colors">Dashboard</a>
                        <button id="logout-btn" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-105 transition-all">Log Out</button>
                    </div>
                    <div id="guest-buttons" style="display: none;" class="items-center gap-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-surface-800/80 hover:text-brand-600 transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-105 transition-all">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── Hero Section ────────────────────────────────────── --}}
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 via-surface-50 to-surface-100"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-brand-200/30 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-10 w-72 h-72 bg-brand-100/40 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="animate-fade-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-brand-100 text-brand-700 rounded-full text-sm font-medium mb-6">
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse-soft"></span>
                        Now delivering across the city
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-display font-extrabold leading-[1.1] tracking-tight">
                        Craving something?
                        <span class="block mt-2 bg-gradient-to-r from-brand-500 to-brand-700 bg-clip-text text-transparent">We'll bring it to you.</span>
                    </h1>
                    <p class="mt-6 text-lg text-surface-800/60 leading-relaxed max-w-lg">
                        From shawarma to sushi — order from 500+ restaurants and track your delivery every step of the way. Freshly prepared, fast delivered.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="flex-1 max-w-md w-full relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input type="text" placeholder="Enter your delivery address..." class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 shadow-sm placeholder:text-surface-300 transition-all">
                        </div>
                        <a href="{{ route('customer.home') }}" class="px-8 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-2xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-[1.03] active:scale-[0.98] transition-all whitespace-nowrap">
                            Find Food
                        </a>
                    </div>
                    <div class="mt-8 flex items-center gap-6 text-sm text-surface-800/50">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Free delivery on first order
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Real-time tracking
                        </div>
                    </div>
                </div>

                <div class="relative animate-fade-up" style="animation-delay: 0.2s">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-brand-500/10">
                        <img src="{{ asset('images/hero-delivery.png') }}" alt="Food delivery" class="w-full object-cover object-center">
                    </div>
                    {{-- Floating order card --}}
                    <div class="absolute -bottom-6 -left-6 glass rounded-2xl p-4 shadow-xl animate-float max-w-[200px]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-surface-900">Order Delivered!</p>
                                <p class="text-[10px] text-surface-300 mt-0.5">12 min ago</p>
                            </div>
                        </div>
                    </div>
                    {{-- Floating rating card --}}
                    <div class="absolute -top-4 -right-4 glass rounded-2xl px-4 py-3 shadow-xl animate-float" style="animation-delay: 1s">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-surface-900">4.9</span>
                            <div class="flex text-amber-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── How It Works ────────────────────────────────────── --}}
    <section id="how-it-works" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="text-sm font-semibold text-brand-600 uppercase tracking-wider mb-3">Simple & Fast</p>
                <h2 class="text-3xl lg:text-4xl font-display font-bold tracking-tight">Three steps to your next meal</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12 stagger-children">
                {{-- Step 1 --}}
                <div class="relative group">
                    <div class="absolute -top-3 -left-3 w-12 h-12 rounded-2xl bg-brand-100 flex items-center justify-center text-brand-600 text-lg font-display font-extrabold group-hover:bg-brand-500 group-hover:text-white transition-colors">1</div>
                    <div class="bg-surface-50 rounded-2xl p-8 pt-12 group-hover:shadow-xl group-hover:shadow-brand-500/5 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-display font-bold mb-2">Browse & Pick</h3>
                        <p class="text-sm text-surface-800/60 leading-relaxed">Explore restaurants near you, filter by cuisine, rating, or delivery time. Find exactly what you're craving.</p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative group">
                    <div class="absolute -top-3 -left-3 w-12 h-12 rounded-2xl bg-brand-100 flex items-center justify-center text-brand-600 text-lg font-display font-extrabold group-hover:bg-brand-500 group-hover:text-white transition-colors">2</div>
                    <div class="bg-surface-50 rounded-2xl p-8 pt-12 group-hover:shadow-xl group-hover:shadow-brand-500/5 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-display font-bold mb-2">Place & Pay</h3>
                        <p class="text-sm text-surface-800/60 leading-relaxed">Secure checkout with Stripe. Split payments handled automatically — you just pay and relax.</p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative group">
                    <div class="absolute -top-3 -left-3 w-12 h-12 rounded-2xl bg-brand-100 flex items-center justify-center text-brand-600 text-lg font-display font-extrabold group-hover:bg-brand-500 group-hover:text-white transition-colors">3</div>
                    <div class="bg-surface-50 rounded-2xl p-8 pt-12 group-hover:shadow-xl group-hover:shadow-brand-500/5 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-display font-bold mb-2">Track & Enjoy</h3>
                        <p class="text-sm text-surface-800/60 leading-relaxed">Watch your rider in real-time on the map. From kitchen to doorstep — every second accounted for.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Featured Restaurants ─────────────────────────────── --}}
    <section id="restaurants" class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <p class="text-sm font-semibold text-brand-600 uppercase tracking-wider mb-3">Top Picks</p>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold tracking-tight">Popular near you</h2>
                </div>
                <a href="{{ route('customer.home') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                    View all
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
                @php
                $featuredRestaurants = ($restaurants ?? collect())->take(6)->values()->map(function ($restaurant, $index) {
                    $gradients = [
                        'from-amber-400 to-orange-500',
                        'from-red-400 to-rose-500',
                        'from-cyan-400 to-blue-500',
                        'from-emerald-400 to-green-500',
                        'from-yellow-400 to-amber-500',
                        'from-violet-400 to-purple-500',
                    ];

                    return [
                        'slug' => $restaurant->slug,
                        'name' => $restaurant->name,
                        'cuisine' => $restaurant->description ?: 'Restaurant',
                        'rating' => number_format((float) ($restaurant->avg_rating ?? 0), 1),
                        'time' => '20-30',
                        'fee' => number_format((float) ($restaurant->min_order_amount ?? 0), 2),
                        'logo' => $restaurant->logo,
                        'gradient' => $gradients[$index % count($gradients)],
                        'emoji' => '🍽️',
                        'orders' => (string) ($restaurant->total_reviews ?? 0),
                    ];
                });
                @endphp

                @foreach($featuredRestaurants as $r)
                <a href="{{ route('customer.restaurant', ['slug' => $r['slug']]) }}" class="restaurant-card bg-white rounded-2xl overflow-hidden border border-surface-200/50 group">
                    <div class="h-40 bg-gradient-to-br {{ $r['gradient'] }} relative overflow-hidden">
                        @if(!empty($r['logo']))
                            <img
                                src="{{ $r['logo'] }}"
                                alt="{{ $r['name'] }} logo"
                                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                                onerror="this.style.display='none';"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-surface-900/40 via-surface-900/10 to-transparent"></div>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-6xl opacity-30 group-hover:scale-110 transition-transform duration-500">{{ $r['emoji'] }}</div>
                        @endif
                        <div class="absolute top-3 left-3 px-2.5 py-1 bg-white/90 backdrop-blur rounded-lg text-[11px] font-semibold text-surface-900 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ $r['rating'] }}
                        </div>
                        <div class="absolute top-3 right-3 px-2.5 py-1 bg-white/90 backdrop-blur rounded-lg text-[11px] font-semibold text-surface-900">{{ $r['orders'] }} reviews</div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-base font-display font-bold group-hover:text-brand-600 transition-colors">{{ $r['name'] }}</h3>
                        <p class="text-xs text-surface-300 mt-0.5">{{ $r['cuisine'] }}</p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-surface-100">
                            <span class="text-xs text-surface-800/60 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $r['time'] }} min
                            </span>
                            <span class="text-xs text-surface-800/60">${{ $r['fee'] }} delivery</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Stats Section ───────────────────────────────────── --}}
    <section class="py-20 bg-surface-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.3\'%3E%3Cpath d=\'M0 0h40v40H0V0zm40 40h40v40H40V40zm0-40h2l-2 2V0zm0 4l4-4h2l-6 6V4zm0 4l8-8h2L40 10V8zm0 4L52 0h2L40 14v-2zm0 4L56 0h2L40 18v-2zm0 4L60 0h2L40 22v-2zm0 4L64 0h2L40 26v-2z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <p class="text-4xl lg:text-5xl font-display font-extrabold bg-gradient-to-r from-brand-300 to-brand-500 bg-clip-text text-transparent stat-number" data-count-to="500" data-duration="1500">0</p>
                    <p class="text-sm text-surface-300 mt-2">Restaurant Partners</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl lg:text-5xl font-display font-extrabold text-black stat-number" data-count-to="50" data-duration="1200">0</p>
                    <p class="text-sm text-surface-300 mt-2">Cities Covered</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl lg:text-5xl font-display font-extrabold text-emerald-400 stat-number" data-count-to="2" data-decimals="1" data-duration="1000">0</p>
                    <p class="text-sm text-surface-300 mt-2">Million Orders Delivered</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl lg:text-5xl font-display font-extrabold text-amber-400 stat-number" data-count-to="15" data-duration="1300">0</p>
                    <p class="text-sm text-surface-300 mt-2">Minute Avg Delivery</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Partner With Us ──────────────────────────────────── --}}
    <section id="partner" class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/restaurant-interior.png') }}" alt="Restaurant partner" class="w-full h-80 lg:h-[450px] object-cover">
                    </div>
                    <div class="absolute -bottom-6 -right-6 glass rounded-2xl p-5 shadow-lg max-w-[220px]">
                        <p class="text-sm font-semibold text-surface-900 mb-1">Average Partner Revenue</p>
                        <p class="text-2xl font-display font-extrabold text-emerald-600">+34%</p>
                        <p class="text-xs text-surface-300 mt-1">increase after joining DeliverEats</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-brand-600 uppercase tracking-wider mb-3">For Businesses</p>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold tracking-tight mb-6">Grow your restaurant with DeliverEats</h2>
                    <p class="text-surface-800/60 leading-relaxed mb-8">Reach thousands of hungry customers in your area. Our platform handles delivery logistics, payment processing, and customer support — so you can focus on what you do best.</p>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold mb-1">Revenue Dashboard</h4>
                                <p class="text-sm text-surface-800/60">Track orders, revenue, and payouts in real-time. Full transparency on every transaction and commission.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold mb-1">Stripe Connect Payouts</h4>
                                <p class="text-sm text-surface-800/60">Automatic split payments. Your earnings are deposited directly, with configurable commission rates.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold mb-1">Full Menu Control</h4>
                                <p class="text-sm text-surface-800/60">Manage categories, items, variants, pricing, and availability. Toggle items on/off in one click.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-2xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.02] transition-all">Become a Partner</a>
                        <a href="#" class="px-6 py-3.5 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">Learn More →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA Section ──────────────────────────────────────── --}}
    <section class="py-20 lg:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-gradient-to-br from-brand-500 via-brand-600 to-brand-700 rounded-3xl p-10 lg:p-16 text-center overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/3"></div>
                <div class="relative">
                    <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-white mb-4">Ready to order?</h2>
                    <p class="text-brand-100 mb-8 max-w-md mx-auto">Join 50,000+ users already enjoying fast delivery from the best restaurants in the city.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('customer.home') }}" class="px-8 py-3.5 text-sm font-semibold text-brand-600 bg-white rounded-2xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">Start Ordering</a>
                        <a href="{{ route('register') }}" class="px-8 py-3.5 text-sm font-semibold text-white border-2 border-white/30 rounded-2xl hover:bg-white/10 transition-all">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Footer ──────────────────────────────────────────── --}}
    <footer class="bg-surface-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
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
                    <ul class="space-y-3 text-sm text-surface-200"><li><a href="#" class="hover:text-brand-400 transition-colors">About</a></li><li><a href="#" class="hover:text-brand-400 transition-colors">Careers</a></li><li><a href="#" class="hover:text-brand-400 transition-colors">Blog</a></li></ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-surface-300 mb-4">Support</h4>
                    <ul class="space-y-3 text-sm text-surface-200"><li><a href="#" class="hover:text-brand-400 transition-colors">Help Center</a></li><li><a href="#" class="hover:text-brand-400 transition-colors">Safety</a></li><li><a href="#" class="hover:text-brand-400 transition-colors">Contact</a></li></ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-surface-300 mb-4">Legal</h4>
                    <ul class="space-y-3 text-sm text-surface-200"><li><a href="#" class="hover:text-brand-400 transition-colors">Privacy</a></li><li><a href="#" class="hover:text-brand-400 transition-colors">Terms</a></li><li><a href="#" class="hover:text-brand-400 transition-colors">Cookies</a></li></ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-12 pt-8 text-center">
                <p class="text-xs text-surface-300">&copy; {{ date('Y') }} DeliverEats. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const token = localStorage.getItem('auth_token');
        const authButtons = document.getElementById('auth-buttons');
        const guestButtons = document.getElementById('guest-buttons');
        
        if (token) {
            authButtons.style.display = 'flex';
            try {
                const res = await fetch('/api/profile', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const user = await res.json();
                    const dashBtn = document.getElementById('dashboard-btn');
                    if (user.role === 'admin') dashBtn.href = '/admin/dashboard';
                    else if (user.role === 'restaurant_owner') dashBtn.href = '/restaurant/dashboard';
                    else if (user.role === 'rider') dashBtn.href = '/rider/dashboard';
                    else dashBtn.href = '/browse';
                } else {
                    localStorage.removeItem('auth_token');
                    authButtons.style.display = 'none';
                    guestButtons.style.display = 'flex';
                }
            } catch(e) {}

            document.getElementById('logout-btn').addEventListener('click', async () => {
                await fetch('/api/logout', { method: 'POST', headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }});
                localStorage.removeItem('auth_token');
                window.location.reload();
            });
        } else {
            guestButtons.style.display = 'flex';
        }
    });
    </script>
</body>
</html>
