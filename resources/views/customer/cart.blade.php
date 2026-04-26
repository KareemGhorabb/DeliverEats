@extends('layouts.app')
@section('title', 'Your Cart — DeliverEats')
@section('hide-footer', true)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-display font-bold mb-1">Your cart</h1>
    <p class="text-sm text-surface-300 mb-8">Review your order before checkout</p>

    <div class="grid lg:grid-cols-5 gap-8">
        {{-- Cart items --}}
        <div class="lg:col-span-3 space-y-3" id="cart-items-container">
            {{-- Restaurant header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-lg">🌯</div>
                <div>
                    <p class="text-sm font-semibold">Shawarma Station</p>
                    <p class="text-xs text-surface-300">Estimated delivery: 25-35 min</p>
                </div>
            </div>

            {{-- Static demo items --}}
            <div class="cart-item flex items-center gap-4 bg-white rounded-xl border border-surface-200/50 p-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold">Classic Chicken Shawarma</h3>
                    <p class="text-xs text-surface-300 mt-0.5">Marinated chicken, garlic sauce, pickles</p>
                    <p class="text-sm font-bold text-brand-600 mt-1">$6.99</p>
                </div>
                <div class="qty-stepper flex items-center gap-2">
                    <button data-qty-minus class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <span class="qty-display w-8 text-center text-sm font-bold">2</span>
                    <button data-qty-plus class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                <button class="p-2 text-surface-300 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            <div class="cart-item flex items-center gap-4 bg-white rounded-xl border border-surface-200/50 p-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold">Garlic Fries</h3>
                    <p class="text-xs text-surface-300 mt-0.5">Crispy fries tossed in garlic butter</p>
                    <p class="text-sm font-bold text-brand-600 mt-1">$3.49</p>
                </div>
                <div class="qty-stepper flex items-center gap-2">
                    <button data-qty-minus class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <span class="qty-display w-8 text-center text-sm font-bold">1</span>
                    <button data-qty-plus class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                <button class="p-2 text-surface-300 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            <div class="cart-item flex items-center gap-4 bg-white rounded-xl border border-surface-200/50 p-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold">Fresh Lemonade w/ Mint</h3>
                    <p class="text-xs text-surface-300 mt-0.5">Squeezed lemon, mint leaves, ice</p>
                    <p class="text-sm font-bold text-brand-600 mt-1">$2.99</p>
                </div>
                <div class="qty-stepper flex items-center gap-2">
                    <button data-qty-minus class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <span class="qty-display w-8 text-center text-sm font-bold">2</span>
                    <button data-qty-plus class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                <button class="p-2 text-surface-300 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            {{-- Special instructions --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-surface-800 mb-1.5">Special Instructions</label>
                <textarea rows="2" placeholder="Allergies, preferences, delivery notes..." class="w-full px-4 py-3 rounded-xl bg-white border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all resize-none"></textarea>
            </div>

            {{-- Promo code --}}
            <div class="flex gap-2 mt-2">
                <input type="text" placeholder="Promo code" class="flex-1 px-4 py-2.5 rounded-xl bg-white border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300">
                <button class="px-5 py-2.5 text-sm font-semibold text-brand-600 border border-brand-300 bg-brand-50 rounded-xl hover:bg-brand-100 transition-colors">Apply</button>
            </div>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6 sticky top-20">
                <h3 class="text-base font-display font-bold mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-surface-800/70">
                        <span>Subtotal (5 items)</span>
                        <span class="font-medium">$23.45</span>
                    </div>
                    <div class="flex justify-between text-surface-800/70">
                        <span>Delivery Fee</span>
                        <span class="font-medium">$2.99</span>
                    </div>
                    <div class="flex justify-between text-surface-800/70">
                        <span class="flex items-center gap-1.5">
                            Surge (1.3×)
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </span>
                        <span class="font-medium text-amber-600">+$2.34</span>
                    </div>
                    <div class="flex justify-between text-surface-800/70">
                        <span>Service Fee</span>
                        <span class="font-medium">$1.50</span>
                    </div>
                    <div class="flex justify-between text-emerald-600">
                        <span>Promo Discount</span>
                        <span class="font-medium">-$0.00</span>
                    </div>
                    <div class="border-t border-surface-100 pt-3 flex justify-between">
                        <span class="font-semibold text-surface-900">Total</span>
                        <span class="text-lg font-display font-bold text-surface-900">$30.28</span>
                    </div>
                </div>

                <a href="{{ route('customer.checkout') }}" class="mt-6 w-full flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Proceed to Checkout
                </a>

                <p class="text-[11px] text-surface-300 text-center mt-3">Estimated delivery: 25-35 min</p>
            </div>
        </div>
    </div>
</div>
@endsection
