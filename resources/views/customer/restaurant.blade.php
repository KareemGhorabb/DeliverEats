@extends('layouts.app')
@section('title', (($restaurant->name ?? 'Restaurant') . ' — DeliverEats'))
@section('hide-footer', true)

@section('content')
@php
$restaurantData = [
    'name' => $restaurant->name ?? 'Restaurant',
    'cuisine' => $restaurant->description ?: 'Restaurant menu',
    'rating' => number_format((float) ($restaurant->avg_rating ?? 0), 1),
    'reviews' => (string) ($restaurant->total_reviews ?? 0),
    'time' => '20-30',
    'fee' => number_format((float) ($restaurant->min_order_amount ?? 0), 2),
    'address' => $restaurant->address ?? '',
];

$menu = ($menuCategories ?? collect())->map(function ($category) {
    return [
        'cat' => $category->name,
        'items' => $category->menuItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'desc' => $item->description,
                'price' => (float) $item->price,
                'badge' => null,
            ];
        })->values()->all(),
    ];
})->values()->all();
@endphp

{{-- Restaurant header --}}
<div class="bg-gradient-to-br from-amber-400 to-orange-500 relative">
    <div class="absolute inset-0 flex items-center justify-center text-[12rem] opacity-10">🌯</div>
    @if(!empty($restaurant->cover_image))
        <img
            src="{{ $restaurant->cover_image }}"
            alt="{{ $restaurantData['name'] }} cover"
            class="absolute inset-0 w-full h-full object-cover object-center opacity-70"
            loading="lazy"
            onerror="this.style.display='none';"
        >
        <div class="absolute inset-0 bg-gradient-to-br from-surface-900/50 via-surface-900/30 to-surface-900/10"></div>
    @endif
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-20">
        <a href="{{ route('customer.home') }}" class="inline-flex items-center gap-2 text-white/80 text-sm hover:text-white transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to browse
        </a>
    </div>
</div>

{{-- Restaurant info card --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 mb-8">
    <div class="bg-white rounded-2xl shadow-xl border border-surface-200/50 p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    @if(!empty($restaurant->logo))
                        <img
                            src="{{ $restaurant->logo }}"
                            alt="{{ $restaurantData['name'] }} logo"
                            class="w-12 h-12 rounded-xl object-cover border border-surface-200"
                            loading="lazy"
                            onerror="this.style.display='none';"
                        >
                    @endif
                <h1 class="text-2xl font-display font-bold">{{ $restaurantData['name'] }}</h1>
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-full uppercase">Open</span>
                </div>
                <p class="text-sm text-surface-300">{{ $restaurantData['cuisine'] }}</p>
                <div class="flex items-center gap-5 mt-3 text-sm text-surface-800/60">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="font-semibold text-surface-900">{{ $restaurantData['rating'] }}</span>
                        <span>({{ $restaurantData['reviews'] }} reviews)</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $restaurantData['time'] }} min
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $restaurantData['address'] }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-surface-100 text-surface-800/70 rounded-lg text-xs font-medium">${{ $restaurantData['fee'] }} delivery</span>
                <span class="surge-badge px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold">1.3× Surge</span>
            </div>
        </div>
    </div>
</div>

{{-- Menu Content --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex gap-8">
        {{-- Category sidebar (desktop) --}}
        <nav class="hidden lg:block w-48 flex-shrink-0 sticky top-20 self-start">
            <p class="text-xs font-semibold text-surface-300 uppercase tracking-wider mb-3">Menu</p>
            @foreach($menu as $section)
            <a href="#menu-{{ Str::slug($section['cat']) }}" class="block px-3 py-2 text-sm font-medium text-surface-800/60 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all">{{ $section['cat'] }}</a>
            @endforeach
        </nav>

        {{-- Menu items --}}
        <div class="flex-1 space-y-10">
            @foreach($menu as $section)
            <div id="menu-{{ Str::slug($section['cat']) }}">
                <h2 class="text-lg font-display font-bold mb-4 flex items-center gap-3">
                    {{ $section['cat'] }}
                    <span class="text-xs font-normal text-surface-300 bg-surface-100 px-2 py-0.5 rounded-full">{{ count($section['items']) }}</span>
                </h2>
                <div class="space-y-3">
                    @foreach($section['items'] as $item)
                    <div class="group bg-white rounded-xl border border-surface-200/50 p-4 hover:shadow-lg hover:border-brand-200 transition-all cursor-pointer" onclick="Cart.add({id:{{ $item['id'] }}, name:'{{ $item['name'] }}', price:{{ $item['price'] }}, restaurant:'{{ $restaurantData['name'] }}', variantId: null, qty: 1})">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold group-hover:text-brand-600 transition-colors">{{ $item['name'] }}</h3>
                                    @if($item['badge'])
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $item['badge'] === 'Bestseller' ? 'bg-amber-100 text-amber-700' : 'bg-brand-100 text-brand-700' }}">{{ $item['badge'] }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-surface-300 mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                                <p class="text-sm font-bold text-surface-900 mt-2">${{ number_format($item['price'], 2) }}</p>
                            </div>
                            <button class="mt-1 w-9 h-9 rounded-xl bg-surface-100 group-hover:bg-brand-500 group-hover:text-white text-surface-800/40 flex items-center justify-center transition-all flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Floating cart bar --}}
<div class="fixed bottom-0 inset-x-0 z-40 p-4 lg:hidden" id="floating-cart">
    <a href="{{ route('customer.cart') }}" class="flex items-center justify-between w-full px-6 py-3.5 bg-brand-500 text-white rounded-2xl shadow-lg shadow-brand-500/30">
        <span class="flex items-center gap-2">
            <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold" data-cart-count>0</span>
            <span class="text-sm font-semibold">View Cart</span>
        </span>
        <span class="text-sm font-bold">$<span data-cart-subtotal>0.00</span></span>
    </a>
</div>
@endsection
