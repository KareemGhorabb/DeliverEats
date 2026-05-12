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
        // Define restaurant categories (not menu categories)
        $restaurantCategories = collect([
            ['name' => 'All', 'slug' => 'all', 'active' => true],
            ['name' => 'Middle Eastern', 'slug' => 'middle-eastern', 'active' => false],
            ['name' => 'Japanese', 'slug' => 'japanese', 'active' => false],
            ['name' => 'Italian', 'slug' => 'italian', 'active' => false],
            ['name' => 'Healthy', 'slug' => 'healthy', 'active' => false],
            ['name' => 'Egyptian', 'slug' => 'egyptian', 'active' => false],
            ['name' => 'French', 'slug' => 'french', 'active' => false],
        ]);
        @endphp
        @foreach($restaurantCategories as $pill)
        <button
            class="category-pill flex items-center gap-2 px-5 py-2.5 rounded-full border text-sm font-medium whitespace-nowrap {{ $pill['active'] ? 'active bg-brand-500 text-white border-brand-500' : 'bg-white text-surface-800/70 border-surface-200 hover:border-brand-300' }}"
            data-category="{{ $pill['slug'] }}"
            data-restaurant-category="{{ $pill['slug'] }}"
            onclick="window.filterRestaurantsByCategory('{{ $pill['slug'] }}', this)"
        >
            <span class="text-base">🍽️</span>
            {{ $pill['name'] }}
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
        <button
            data-sort="recommended"
            class="sort-btn px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-surface-900 text-white"
        >Recommended</button>
        <button
            data-sort="fastest"
            class="sort-btn px-3.5 py-1.5 text-xs font-medium rounded-lg bg-white border border-surface-200 text-surface-800/70 hover:border-brand-300 transition-colors"
        >Fastest</button>
        <button
            data-sort="top-rated"
            class="sort-btn px-3.5 py-1.5 text-xs font-medium rounded-lg bg-white border border-surface-200 text-surface-800/70 hover:border-brand-300 transition-colors"
        >Top Rated</button>
        <button
            data-sort="price-asc"
            class="sort-btn px-3.5 py-1.5 text-xs font-medium rounded-lg bg-white border border-surface-200 text-surface-800/70 hover:border-brand-300 transition-colors"
            data-label-asc="Price ↑"
            data-label-desc="Price ↓"
        >Price ↑</button>
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
        $allRestaurants = ($restaurants ?? collect())->map(function ($restaurant, $index) {
            $gradients = [
                'from-amber-400 to-orange-500',
                'from-red-400 to-rose-500',
                'from-cyan-400 to-blue-500',
                'from-emerald-400 to-green-500',
                'from-yellow-400 to-amber-500',
                'from-violet-400 to-purple-500',
            ];

            // Calculate average menu item price
            $menuItems = $restaurant->menuCategories->flatMap(function ($category) {
                return $category->menuItems ?? collect();
            });
            
            $avgMenuPrice = 0;
            if ($menuItems->isNotEmpty()) {
                $totalPrice = $menuItems->sum(function ($item) {
                    // Get the lowest variant price or use item price
                    if ($item->itemVariants && $item->itemVariants->isNotEmpty()) {
                        return $item->itemVariants->min('price') ?? $item->price ?? 0;
                    }
                    return $item->price ?? 0;
                });
                $avgMenuPrice = $totalPrice / $menuItems->count();
            }

            return [
                'slug' => $restaurant->slug,
                'name' => $restaurant->name,
                'category' => $restaurant->category ?? 'Restaurant',
                'rating' => number_format((float) ($restaurant->avg_rating ?? 0), 1),
                'reviews' => (string) ($restaurant->total_reviews ?? 0),
                'time' => [20, 15, 30, 25, 18, 22, 12, 28][$index % 8],
                'fee' => number_format((float) ($restaurant->min_order_amount ?? 0), 2),
                'avg_menu_price' => number_format($avgMenuPrice, 2),
                'logo' => $restaurant->logo,
                'gradient' => $gradients[$index % count($gradients)],
                'emoji' => '🍽️',
                'promo' => null,
                'featured' => (bool) ($restaurant->is_featured ?? false),
            ];
        });
        @endphp

        @foreach($allRestaurants as $r)
        <a
            href="{{ route('customer.restaurant', ['slug' => $r['slug']]) }}"
            class="restaurant-card restaurant-item bg-white rounded-2xl overflow-hidden border border-surface-200/50 group"
            data-restaurant-category="{{ \Illuminate\Support\Str::slug($r['category']) }}"
            data-rating="{{ $r['rating'] }}"
            data-fee="{{ $r['fee'] }}"
            data-menu-price="{{ $r['avg_menu_price'] }}"
            data-time="{{ $r['time'] }}"
        >
            <div class="h-36 bg-gradient-to-br {{ $r['gradient'] }} relative overflow-hidden">
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
                    <div class="absolute inset-0 flex items-center justify-center text-6xl opacity-25 group-hover:scale-110 transition-transform duration-500">{{ $r['emoji'] }}</div>
                @endif
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
                        <p class="text-xs text-surface-300 mt-0.5">{{ $r['category'] }}</p>
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
                        {{ is_numeric($r['time']) ? $r['time'] : $r['time'] }} min
                    </span>
                    <span>${{ $r['fee'] }} delivery</span>
                    <span class="ml-auto text-[10px] uppercase tracking-wider text-emerald-600 font-semibold">Open</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    window.filterRestaurantsByCategory = function (categorySlug, button) {
        document.querySelectorAll('.category-pill').forEach((pill) => {
            pill.classList.remove('active', 'bg-brand-500', 'text-white', 'border-brand-500');
            pill.classList.add('bg-white', 'text-surface-800/70', 'border-surface-200');
        });

        button.classList.add('active', 'bg-brand-500', 'text-white', 'border-brand-500');
        button.classList.remove('bg-white', 'text-surface-800/70', 'border-surface-200');

        document.querySelectorAll('.restaurant-item').forEach((card) => {
            // Check if restaurant matches the selected category
            const restaurantCategory = card.dataset.restaurantCategory || '';
            const matches = categorySlug === 'all' || restaurantCategory === categorySlug;
            card.style.display = matches ? '' : 'none';
        });
    };

    // Initialize sorting functionality
    (function () {
        // Track original DOM order so Recommended can restore it
        const grid = document.querySelector('.grid.sm\\:grid-cols-2.lg\\:grid-cols-3');
        if (!grid) {
            console.error('Grid not found');
            return;
        }

        // Capture original order once on load
        const originalOrder = Array.from(grid.querySelectorAll('.restaurant-item'));
        console.log('Found', originalOrder.length, 'restaurant items');

        // Active button state classes
        const ACTIVE_CLASSES   = ['bg-surface-900', 'text-white', 'font-semibold'];
        const INACTIVE_CLASSES = ['bg-white', 'border', 'border-surface-200', 'text-surface-800/70', 'font-medium'];

        function setActiveButton(activeBtn) {
            document.querySelectorAll('.sort-btn').forEach((btn) => {
                btn.classList.remove(...ACTIVE_CLASSES);
                btn.classList.add(...INACTIVE_CLASSES);
            });
            activeBtn.classList.remove(...INACTIVE_CLASSES);
            activeBtn.classList.add(...ACTIVE_CLASSES);
        }

        function sortCards(compareFn) {
            // Only sort currently visible cards; hidden ones (from category filter) stay hidden
            const allCards = Array.from(grid.querySelectorAll('.restaurant-item'));
            const visible  = allCards.filter((c) => c.style.display !== 'none');
            const hidden   = allCards.filter((c) => c.style.display === 'none');

            visible.sort(compareFn);

            // Re-append in new order (hidden ones go to end, preserving their hidden state)
            [...visible, ...hidden].forEach((card) => grid.appendChild(card));
        }

        // Price button toggles between asc and desc
        let priceAsc = true;

        // Add event listeners to sort buttons
        const sortButtons = document.querySelectorAll('.sort-btn');
        console.log('Found', sortButtons.length, 'sort buttons');
        
        sortButtons.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Sort button clicked:', btn.dataset.sort);
                
                const sort = btn.dataset.sort;
                setActiveButton(btn);

                if (sort === 'recommended') {
                    // Restore original DOM order
                    originalOrder.forEach((card) => grid.appendChild(card));
                    console.log('Restored original order');

                } else if (sort === 'fastest') {
                    sortCards((a, b) => {
                        const tA = parseFloat(a.dataset.time) || 999;
                        const tB = parseFloat(b.dataset.time) || 999;
                        return tA - tB; // ascending: lowest time first
                    });
                    console.log('Sorted by fastest');

                } else if (sort === 'top-rated') {
                    sortCards((a, b) => {
                        const rA = parseFloat(a.dataset.rating) || 0;
                        const rB = parseFloat(b.dataset.rating) || 0;
                        return rB - rA; // descending: highest rating first
                    });
                    console.log('Sorted by top rated');

                } else if (sort === 'price-asc' || sort === 'price-desc') {
                    // Sort by average menu item price (lowest first)
                    sortCards((a, b) => {
                        const priceA = parseFloat(a.dataset.menuPrice) || 0;
                        const priceB = parseFloat(b.dataset.menuPrice) || 0;
                        return priceA - priceB; // ascending: lowest menu price first
                    });
                    
                    // Update button to show Price ↓ (indicating it can be toggled)
                    btn.textContent = 'Price ↓';
                    btn.dataset.sort = 'price-desc';
                    console.log('Sorted by lowest menu price first');
                }
            });
        });
    })();
});
</script>
@endsection
