@extends('layouts.app')
@section('title', 'Your Cart — DeliverEats')
@section('hide-footer', true)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-display font-bold mb-1">Your cart</h1>
    <p class="text-sm text-surface-300 mb-8">Review your order before checkout</p>

    <div class="grid lg:grid-cols-5 gap-8">
        {{-- Cart items --}}
        <div class="lg:col-span-3 space-y-3" id="cart-container">
            <div id="cart-empty" class="hidden bg-white rounded-2xl border border-surface-200/50 p-10 text-center">
                <div class="w-16 h-16 bg-brand-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                </div>
                <h3 class="text-lg font-bold">Your cart is empty</h3>
                <p class="text-sm text-surface-300 mt-1 mb-6">Looks like you haven't added anything yet.</p>
                <a href="{{ route('customer.home') }}" class="inline-flex px-6 py-2.5 bg-brand-500 text-white font-bold rounded-xl hover:bg-brand-600 transition-colors">Browse Restaurants</a>
            </div>

            <div id="cart-items" class="space-y-3">
                <!-- Javascript populated -->
            </div>

            {{-- Special instructions --}}
            <div id="cart-instructions" class="mt-4 hidden">
                <label class="block text-sm font-medium text-surface-800 mb-1.5">Special Instructions</label>
                <textarea rows="2" placeholder="Allergies, preferences, delivery notes..." class="w-full px-4 py-3 rounded-xl bg-white border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all resize-none"></textarea>
            </div>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6 sticky top-20">
                <h3 class="text-base font-display font-bold mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-surface-800/70">
                        <span>Subtotal</span>
                        <span class="font-medium">EGP <span id="summary-subtotal">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-surface-800/70">
                        <span>Delivery Fee</span>
                        <span class="font-medium">EGP <span id="summary-delivery">2.99</span></span>
                    </div>
                    <div class="flex justify-between text-surface-800/70">
                        <span>Service Fee</span>
                        <span class="font-medium">EGP <span id="summary-fee">1.50</span></span>
                    </div>
                    <div class="border-t border-surface-100 pt-3 flex justify-between">
                        <span class="font-semibold text-surface-900">Total</span>
                        <span class="text-lg font-display font-bold text-surface-900">EGP <span id="summary-total">0.00</span></span>
                    </div>
                </div>

                <a href="{{ route('customer.checkout') }}" id="btn-checkout" class="mt-6 w-full flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
        window.addEventListener('cart:updated', renderCart);
    });

    window.updateItemQty = function(id, variantId, qty) {
        Cart.updateQty(id, variantId, qty);
    };

    window.removeItem = function(id, variantId) {
        Cart.remove(id, variantId);
    };

    function renderCart() {
        const items = Cart.getAll();
        const container = document.getElementById('cart-items');
        const empty = document.getElementById('cart-empty');
        const instructions = document.getElementById('cart-instructions');
        const btnCheckout = document.getElementById('btn-checkout');

        if (items.length === 0) {
            container.innerHTML = '';
            empty.classList.remove('hidden');
            instructions.classList.add('hidden');
            btnCheckout.classList.add('opacity-50', 'pointer-events-none');
            document.getElementById('summary-subtotal').textContent = '0.00';
            document.getElementById('summary-total').textContent = '0.00';
            return;
        }

        empty.classList.add('hidden');
        instructions.classList.remove('hidden');
        btnCheckout.classList.remove('opacity-50', 'pointer-events-none');

        let subtotal = 0;
        container.innerHTML = items.map(i => {
            subtotal += (i.price * i.qty);
            const varIdArg = i.variantId ? `'${i.variantId}'` : 'null';
            return `
            <div class="cart-item flex items-center gap-4 bg-white rounded-xl border border-surface-200/50 p-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold">${i.name}</h3>
                    <p class="text-xs text-surface-300 mt-0.5">${i.restaurant}</p>
                    <p class="text-sm font-bold text-brand-600 mt-1">EGP ${(i.price * i.qty).toFixed(2)}</p>
                </div>
                <div class="qty-stepper flex items-center gap-2">
                    <button onclick="updateItemQty(${i.id}, ${varIdArg}, ${i.qty - 1})" class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <span class="w-8 text-center text-sm font-bold">${i.qty}</span>
                    <button onclick="updateItemQty(${i.id}, ${varIdArg}, ${i.qty + 1})" class="w-8 h-8 rounded-lg border border-surface-200 flex items-center justify-center text-surface-800/60 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                <button onclick="removeItem(${i.id}, ${varIdArg})" class="p-2 text-surface-300 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            `;
        }).join('');

        const total = subtotal + 2.99 + 1.50;
        document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('summary-total').textContent = total.toFixed(2);
    }
</script>
@endpush
@endsection
