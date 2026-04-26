@extends('layouts.app')
@section('title', 'Checkout — DeliverEats')
@section('hide-footer', true)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.cart') }}" class="p-2 rounded-lg hover:bg-surface-100 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
        <h1 class="text-2xl font-display font-bold">Checkout</h1>
    </div>

    <div class="grid lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-6">
            {{-- Delivery address --}}
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-display font-bold flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-brand-500 text-white text-xs flex items-center justify-center font-bold">1</span>
                        Delivery Address
                    </h3>
                    <button class="text-xs text-brand-600 font-semibold hover:text-brand-700">Change</button>
                </div>
                <div class="flex items-start gap-3 p-3 bg-surface-50 rounded-xl">
                    <svg class="w-5 h-5 text-brand-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div>
                        <p class="text-sm font-semibold">Home</p>
                        <p class="text-xs text-surface-300 mt-0.5">12 Tahrir Square, Apt 5, Cairo, Egypt</p>
                    </div>
                </div>
                <textarea rows="2" placeholder="Delivery instructions (e.g., ring doorbell, leave at door)" class="mt-3 w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 resize-none"></textarea>
            </div>

            {{-- Payment method --}}
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
                <h3 class="text-sm font-display font-bold flex items-center gap-2 mb-4">
                    <span class="w-6 h-6 rounded-full bg-brand-500 text-white text-xs flex items-center justify-center font-bold">2</span>
                    Payment Method
                </h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-brand-500 bg-brand-50 cursor-pointer">
                        <input type="radio" name="payment" value="card" checked class="text-brand-500 focus:ring-brand-500">
                        <svg class="w-8 h-5" viewBox="0 0 32 20"><rect width="32" height="20" rx="3" fill="#1A1F71"/><circle cx="12" cy="10" r="6" fill="#EB001B"/><circle cx="20" cy="10" r="6" fill="#F79E1B" opacity="0.8"/></svg>
                        <span class="text-sm font-medium flex-1">•••• •••• •••• 4242</span>
                        <span class="text-xs text-surface-300">Visa</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-surface-200 hover:border-surface-300 cursor-pointer transition-colors">
                        <input type="radio" name="payment" value="cash" class="text-brand-500 focus:ring-brand-500">
                        <div class="w-8 h-5 rounded bg-emerald-100 flex items-center justify-center"><span class="text-xs">💵</span></div>
                        <span class="text-sm font-medium flex-1">Cash on Delivery</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-surface-200 hover:border-surface-300 cursor-pointer transition-colors">
                        <input type="radio" name="payment" value="new" class="text-brand-500 focus:ring-brand-500">
                        <div class="w-8 h-5 rounded bg-blue-100 flex items-center justify-center"><svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                        <span class="text-sm font-medium flex-1">Add New Card</span>
                    </label>
                </div>
            </div>

            {{-- Tip --}}
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
                <h3 class="text-sm font-display font-bold flex items-center gap-2 mb-4">
                    <span class="w-6 h-6 rounded-full bg-brand-500 text-white text-xs flex items-center justify-center font-bold">3</span>
                    Tip Your Rider
                </h3>
                <div class="flex gap-2">
                    <button class="flex-1 py-2.5 rounded-xl border border-surface-200 text-sm font-medium hover:border-brand-300 hover:bg-brand-50 transition-all">$0</button>
                    <button class="flex-1 py-2.5 rounded-xl border-2 border-brand-500 bg-brand-50 text-brand-600 text-sm font-bold">$2</button>
                    <button class="flex-1 py-2.5 rounded-xl border border-surface-200 text-sm font-medium hover:border-brand-300 hover:bg-brand-50 transition-all">$5</button>
                    <button class="flex-1 py-2.5 rounded-xl border border-surface-200 text-sm font-medium hover:border-brand-300 hover:bg-brand-50 transition-all">$10</button>
                    <button class="flex-1 py-2.5 rounded-xl border border-surface-200 text-sm font-medium hover:border-brand-300 hover:bg-brand-50 transition-all">Other</button>
                </div>
                <p class="text-xs text-surface-300 mt-2">100% of the tip goes to your delivery rider.</p>
            </div>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6 sticky top-20">
                <h3 class="text-base font-display font-bold mb-4">Order Summary</h3>
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm"><span class="text-surface-800/70">2× Classic Chicken Shawarma</span><span class="font-medium">$13.98</span></div>
                    <div class="flex justify-between text-sm"><span class="text-surface-800/70">1× Garlic Fries</span><span class="font-medium">$3.49</span></div>
                    <div class="flex justify-between text-sm"><span class="text-surface-800/70">2× Fresh Lemonade</span><span class="font-medium">$5.98</span></div>
                </div>
                <div class="border-t border-surface-100 pt-3 space-y-2 text-sm">
                    <div class="flex justify-between text-surface-800/70"><span>Subtotal</span><span>$23.45</span></div>
                    <div class="flex justify-between text-surface-800/70"><span>Delivery</span><span>$2.99</span></div>
                    <div class="flex justify-between text-amber-600"><span>Surge (1.3×)</span><span>+$2.34</span></div>
                    <div class="flex justify-between text-surface-800/70"><span>Service Fee</span><span>$1.50</span></div>
                    <div class="flex justify-between text-surface-800/70"><span>Rider Tip</span><span>$2.00</span></div>
                </div>
                <div class="border-t border-surface-200 mt-3 pt-3 flex justify-between">
                    <span class="font-semibold">Total</span>
                    <span class="text-xl font-display font-bold">$32.28</span>
                </div>

                <a href="{{ route('customer.orders.track', ['id' => 'ORD-20260426-001']) }}" class="mt-6 w-full flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Place Order — $32.28
                </a>
                <div class="flex items-center justify-center gap-2 mt-3">
                    <svg class="w-3.5 h-3.5 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="text-[11px] text-surface-300">Secured by Stripe</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
