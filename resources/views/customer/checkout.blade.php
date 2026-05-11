@extends('layouts.app')
@section('title', 'Checkout — DeliverEats')
@section('hide-footer', true)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.cart') }}" class="p-2 rounded-lg hover:bg-surface-100 dark:hover:bg-white/5 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
        <h1 class="text-2xl font-display font-bold">Checkout</h1>
    </div>

    <div class="grid lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-6">
            {{-- Delivery address --}}
            <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-surface-200/50 dark:border-white/10 p-6 shadow-sm text-surface-900 dark:text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-display font-bold flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-brand-500 text-white text-xs flex items-center justify-center font-bold">1</span>
                        Delivery Address
                    </h3>
                </div>
                
                <div class="space-y-4">
                    <div class="relative">
                        <input type="text" id="address-autocomplete" placeholder="Search for your delivery address..." class="w-full px-4 py-3 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
                    </div>
                    
                    {{-- Map Selector --}}
                    <div id="checkout-map" class="w-full h-64 rounded-xl border border-surface-200 dark:border-white/10 overflow-hidden"></div>
                    
                    <div class="flex items-start gap-3 p-3 bg-surface-50 dark:bg-white/5 rounded-xl">
                        <svg class="w-5 h-5 text-brand-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div class="w-full">
                            <input type="text" id="delivery-address" placeholder="Confirmed address will appear here" readonly class="w-full px-1 py-1 bg-transparent border-none text-xs text-surface-500 dark:text-gray-400 focus:outline-none">
                            <input type="hidden" id="delivery-lat">
                            <input type="hidden" id="delivery-lng">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment method --}}
            <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-surface-200/50 dark:border-white/10 p-6 shadow-sm text-surface-900 dark:text-white">
                <h3 class="text-sm font-display font-bold flex items-center gap-2 mb-4">
                    <span class="w-6 h-6 rounded-full bg-brand-500 text-white text-xs flex items-center justify-center font-bold">2</span>
                    Payment Method
                </h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-brand-500 bg-brand-50 dark:bg-brand-500/10 cursor-pointer">
                        <input type="radio" name="payment" value="card" checked class="text-brand-500 focus:ring-brand-500">
                        <span class="text-sm font-medium flex-1 text-surface-900 dark:text-white">Pay Online (Paymob)</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-surface-200 dark:border-white/10 hover:border-surface-300 dark:hover:border-white/20 cursor-pointer transition-colors">
                        <input type="radio" name="payment" value="cash" class="text-brand-500 focus:ring-brand-500">
                        <div class="w-8 h-5 rounded bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center"><span class="text-xs">💵</span></div>
                        <span class="text-sm font-medium flex-1 text-surface-900 dark:text-white">Cash on Delivery</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-surface-200/50 dark:border-white/10 p-6 sticky top-20 shadow-sm dark:text-gray-600">
                <h3 class="text-base font-display font-bold mb-4 dark:text-white">Order Summary</h3>
                
                <div id="checkout-items" class="space-y-2 mb-4"></div>
                <div class="border-t border-surface-100 dark:border-white/5 pt-3 space-y-2 text-sm">
                    <div class="flex justify-between text-surface-600 dark:text-gray-400"><span>Subtotal</span><span>EGP <span id="checkout-subtotal">0.00</span></span></div>
                    <div class="flex justify-between text-surface-600 dark:text-gray-400"><span>Delivery</span><span>EGP <span id="checkout-delivery">25.00</span></span></div>
                    <div class="flex justify-between text-surface-600 dark:text-gray-400"><span>Service Fee</span><span>EGP <span id="checkout-fee">1.50</span></span></div>
                </div>
                <div class="border-t border-surface-200 dark:border-white/10 mt-3 pt-3 flex justify-between">
                    <span class="font-semibold dark:text-white">Total</span>
                    <span class="text-xl font-display font-bold text-brand-600">EGP <span id="checkout-total">0.00</span></span>
                </div>

                <button id="btn-place-order" class="mt-6 w-full flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] transition-all">
                    Place Order
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let map, marker;

    function initMap() {
        // Fix Leaflet's default icon path issues
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

        const cairo = [30.0444, 31.2357];
    function initMap() {
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            window.addEventListener('google-maps-loaded', initMap);
            return;
        }

        const cairo = { lat: 30.0444, lng: 31.2357 };
        
        map = new google.maps.Map(document.getElementById('checkout-map'), {
            center: cairo,
            zoom: 14,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        marker = new google.maps.Marker({
            position: cairo,
            map: map,
            draggable: true
        });

        const geocoder = new google.maps.Geocoder();

        marker.addListener('dragend', function() {
            const pos = marker.getPosition();
            geocoder.geocode({ location: pos }, (results, status) => {
                if (status === "OK" && results[0]) {
                    updateAddressFields(results[0].formatted_address, pos.lat(), pos.lng());
                    document.getElementById('address-autocomplete').value = results[0].formatted_address;
                } else {
                    updateAddressFields(`${pos.lat().toFixed(4)}, ${pos.lng().toFixed(4)}`, pos.lat(), pos.lng());
                }
            });
        });

        const input = document.getElementById('address-autocomplete');
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            map.setCenter(place.geometry.location);
            map.setZoom(15);
            marker.setPosition(place.geometry.location);

            updateAddressFields(place.formatted_address || place.name, place.geometry.location.lat(), place.geometry.location.lng());
        });
    }

    function updateAddressFields(addr, lat, lng) {
        document.getElementById('delivery-address').value = addr;
        document.getElementById('delivery-lat').value = lat;
        document.getElementById('delivery-lng').value = lng;
    }

    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        
        const items = Cart.getAll();
        if (items.length === 0) { window.location.href = '/browse'; return; }

        const itemsContainer = document.getElementById('checkout-items');
        let subtotal = 0;
        itemsContainer.innerHTML = items.map(item => {
            subtotal += item.price * item.qty;
            return `<div class="flex justify-between text-sm"><span class="text-surface-600 dark:text-gray-400">${item.qty}× ${item.name}</span><span class="font-medium">EGP ${(item.price * item.qty).toFixed(2)}</span></div>`;
        }).join('');

        const delivery = 25.00;
        const fee = 1.50;
        const total = subtotal + delivery + fee;

        document.getElementById('checkout-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('checkout-total').textContent = total.toFixed(2);

        document.getElementById('btn-place-order').addEventListener('click', async () => {
            const addr = document.getElementById('delivery-address').value;
            if (!addr) { Toast.show('Missing Info', 'Please select a delivery address on the map.', 'error'); return; }

            const btn = document.getElementById('btn-place-order');
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-spin mr-2">⏳</span>Processing...';

            try {
                const res = await fetch('/api/v1/orders', {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json', 
                        'Content-Type': 'application/json', 
                        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        restaurant_id: items[0].restaurant_id,
                        delivery_address: addr,
                        delivery_lat: document.getElementById('delivery-lat').value,
                        delivery_lng: document.getElementById('delivery-lng').value,
                        payment_method: document.querySelector('input[name="payment"]:checked').value,
                        items: items.map(i => ({ menu_item_id: i.id, quantity: i.qty }))
                    })
                });

                const json = await res.json();
                if (json.success) {
                    Cart.clear();
                    if (json.data.payment_token) {
                        const iframeId = '{{ config('services.paymob.iframe_id') }}';
                        window.location.href = `https://accept.paymob.com/api/acceptance/iframes/${iframeId}?payment_token=${json.data.payment_token}`;
                    } else {
                        window.location.href = `/orders/${json.data.id}/track`;
                    }
                } else {
                    Toast.show('Error', json.message, 'error');
                    btn.disabled = false; btn.innerHTML = 'Place Order';
                }
            } catch (e) {
                console.error("Checkout Error:", e);
                Toast.show('Error', 'A network error occurred. See console.', 'error');
                btn.disabled = false; btn.innerHTML = 'Place Order';
            }
        });
    });
</script>
@endpush
@endsection
