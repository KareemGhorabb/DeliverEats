@extends('layouts.app')
@section('title', 'Browse Restaurants — DeliverEats')

@section('content')
<div class="bg-gradient-to-b from-brand-50 to-surface-50 pb-2">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-8">
        {{-- Search --}}
        <div class="max-w-2xl">
            <h1 class="text-2xl font-display font-bold mb-1">What are you craving?</h1>
            <p class="text-sm text-surface-800/50 mb-5">Explore restaurants and cuisines near you</p>
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" data-search=".restaurant-item" placeholder="Search restaurants, cuisines, or dishes..." class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white border border-surface-200 shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Category pills --}}
    <div class="flex gap-2 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-none mb-6">
        @php
        $categories = [
            ['name' => 'All', 'icon' => '🍽️', 'active' => true],
            ['name' => 'Burgers', 'icon' => '🍔', 'active' => false],
            ['name' => 'Pizza', 'icon' => '🍕', 'active' => false],
            ['name' => 'Sushi', 'icon' => '🍣', 'active' => false],
            ['name' => 'Shawarma', 'icon' => '🌯', 'active' => false],
            ['name' => 'Healthy', 'icon' => '🥗', 'active' => false],
            ['name' => 'Noodles', 'icon' => '🍜', 'active' => false],
            ['name' => 'Dessert', 'icon' => '🍰', 'active' => false],
            ['name' => 'Coffee', 'icon' => '☕', 'active' => false],
            ['name' => 'Breakfast', 'icon' => '🥐', 'active' => false],
        ];
        @endphp
        @foreach($categories as $cat)
        <button class="category-pill flex items-center gap-2 px-5 py-2.5 rounded-full border text-sm font-medium whitespace-nowrap {{ $cat['active'] ? 'active bg-brand-500 text-white border-brand-500' : 'bg-white text-surface-800/70 border-surface-200 hover:border-brand-300' }}" onclick="document.querySelectorAll('.category-pill').forEach(p => { p.classList.remove('active','bg-brand-500','text-white','border-brand-500'); p.classList.add('bg-white','text-surface-800/70','border-surface-200'); }); this.classList.add('active','bg-brand-500','text-white','border-brand-500'); this.classList.remove('bg-white','text-surface-800/70','border-surface-200');">
            <span class="text-base">{{ $cat['icon'] }}</span>
            {{ $cat['name'] }}
        </button>
        @endforeach
    </div>

    {{-- Promo banner --}}
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 p-6 lg:p-8 text-white relative overflow-hidden">
        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-8xl opacity-20">🛵</div>
        <div class="relative">
            <p class="text-sm font-medium text-brand-100 uppercase tracking-wider mb-1">Limited Offer</p>
            <h3 class="text-xl lg:text-2xl font-display font-bold mb-2">Free delivery on your first 3 orders</h3>
            <p class="text-sm text-brand-100 mb-4">Use code <span class="font-mono font-bold bg-white/20 px-2 py-0.5 rounded">WELCOME3</span> at checkout</p>
            <button class="px-6 py-2.5 bg-white text-brand-600 text-sm font-semibold rounded-xl hover:bg-brand-50 transition-colors">Order Now</button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <span class="text-sm font-medium text-surface-800/60">Sort by:</span>
        <button class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-surface-900 text-white">Recommended</button>
        <button class="px-3.5 py-1.5 text-xs font-medium rounded-lg bg-white border border-surface-200 text-surface-800/70 hover:border-brand-300 transition-colors">Fastest</button>
        <button class="px-3.5 py-1.5 text-xs font-medium rounded-lg bg-white border border-surface-200 text-surface-800/70 hover:border-brand-300 transition-colors">Top Rated</button>
        <button class="px-3.5 py-1.5 text-xs font-medium rounded-lg bg-white border border-surface-200 text-surface-800/70 hover:border-brand-300 transition-colors">Price ↑</button>
        <div class="ml-auto flex items-center gap-2">
            <span class="text-xs text-surface-300 hidden sm:inline">Surge pricing active in your area</span>
            <span class="surge-badge inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 text-[11px] font-bold rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                1.3× Surge
            </span>
        </div>
    </div>

    {{-- Restaurant grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 stagger-children">
        @php
        $allRestaurants = [
            ['name' => 'Shawarma Station', 'cuisine' => 'Middle Eastern · Grilled', 'rating' => '4.8', 'reviews' => '324', 'time' => '20-30', 'fee' => '2.99', 'gradient' => 'from-amber-400 to-orange-500', 'emoji' => '🌯', 'promo' => null, 'featured' => true],
            ['name' => 'Pizza Republic', 'cuisine' => 'Italian · Pizza · Pasta', 'rating' => '4.6', 'reviews' => '891', 'time' => '25-35', 'fee' => '1.99', 'gradient' => 'from-red-400 to-rose-500', 'emoji' => '🍕', 'promo' => '20% OFF', 'featured' => false],
            ['name' => 'Sushi Zen', 'cuisine' => 'Japanese · Sushi · Poke', 'rating' => '4.9', 'reviews' => '156', 'time' => '30-40', 'fee' => '3.99', 'gradient' => 'from-cyan-400 to-blue-500', 'emoji' => '🍣', 'promo' => null, 'featured' => false],
            ['name' => 'The Green Bowl', 'cuisine' => 'Healthy · Bowls · Salads', 'rating' => '4.7', 'reviews' => '203', 'time' => '15-25', 'fee' => '2.49', 'gradient' => 'from-emerald-400 to-green-500', 'emoji' => '🥗', 'promo' => 'Free Delivery', 'featured' => false],
            ['name' => 'Burger District', 'cuisine' => 'American · Burgers · Fries', 'rating' => '4.5', 'reviews' => '1.2K', 'time' => '20-30', 'fee' => '1.49', 'gradient' => 'from-yellow-400 to-amber-500', 'emoji' => '🍔', 'promo' => null, 'featured' => true],
            ['name' => 'Noodle House', 'cuisine' => 'Asian · Ramen · Thai', 'rating' => '4.7', 'reviews' => '445', 'time' => '25-35', 'fee' => '2.99', 'gradient' => 'from-violet-400 to-purple-500', 'emoji' => '🍜', 'promo' => null, 'featured' => false],
            ['name' => "Mama's Kitchen", 'cuisine' => 'Home-style · Egyptian', 'rating' => '4.8', 'reviews' => '567', 'time' => '30-45', 'fee' => '1.99', 'gradient' => 'from-pink-400 to-rose-500', 'emoji' => '🍲', 'promo' => null, 'featured' => false],
            ['name' => 'Bab El-Hara', 'cuisine' => 'Levantine · BBQ · Mezze', 'rating' => '4.6', 'reviews' => '289', 'time' => '25-40', 'fee' => '2.49', 'gradient' => 'from-orange-400 to-red-500', 'emoji' => '🥙', 'promo' => '15% OFF', 'featured' => false],
            ['name' => 'Sweet Cravings', 'cuisine' => 'Desserts · Bakery · Waffles', 'rating' => '4.4', 'reviews' => '178', 'time' => '15-20', 'fee' => '1.99', 'gradient' => 'from-fuchsia-400 to-pink-500', 'emoji' => '🧁', 'promo' => null, 'featured' => false],
        ];
        @endphp

        @foreach($allRestaurants as $r)
        <a href="{{ route('customer.restaurant', ['slug' => \Illuminate\Support\Str::slug($r['name'])]) }}" class="restaurant-card restaurant-item bg-white rounded-2xl overflow-hidden border border-surface-200/50 group">
            <div class="h-36 bg-gradient-to-br {{ $r['gradient'] }} relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center text-6xl opacity-25 group-hover:scale-110 transition-transform duration-500">{{ $r['emoji'] }}</div>
                @if($r['promo'])
                <div class="absolute top-3 right-3 px-2.5 py-1 bg-brand-500 text-white rounded-lg text-[11px] font-bold shadow-sm">{{ $r['promo'] }}</div>
                @endif
                @if($r['featured'])
                <div class="absolute bottom-3 left-3 px-2 py-0.5 bg-surface-900/80 backdrop-blur text-white rounded text-[10px] font-semibold uppercase tracking-wider">Featured</div>
                @endif
            </div>
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-sm font-display font-bold group-hover:text-brand-600 transition-colors">{{ $r['name'] }}</h3>
                        <p class="text-xs text-surface-300 mt-0.5">{{ $r['cuisine'] }}</p>
                    </div>
                    <div class="flex items-center gap-1 px-2 py-0.5 bg-emerald-50 rounded text-emerald-700">
                        <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-[11px] font-bold">{{ $r['rating'] }}</span>
                        <span class="text-[10px] text-surface-300">({{ $r['reviews'] }})</span>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-3 pt-3 border-t border-surface-100 text-xs text-surface-800/50">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $r['time'] }} min
                    </span>
                    <span>${{ $r['fee'] }} delivery</span>
                    <span class="ml-auto text-[10px] uppercase tracking-wider text-emerald-600 font-semibold">Open</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
