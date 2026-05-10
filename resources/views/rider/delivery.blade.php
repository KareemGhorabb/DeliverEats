@extends('layouts.rider')
@section('title', 'Active Delivery — DeliverEats')

@section('content')
<div id="delivery-loading" class="flex flex-col items-center justify-center py-20">
    <div class="dot-loading mb-4"><span></span><span></span><span></span></div>
    <p class="text-sm text-surface-400">Loading delivery details...</p>
</div>

<div id="delivery-content" class="hidden">
    {{-- Google Map with Routing --}}
    <div class="h-64 lg:h-80 relative rounded-none" id="rider-delivery-map"></div>

    <div class="px-4 py-5 space-y-4">
        {{-- Status steps --}}
        <div class="status-track relative flex justify-between px-4 py-2">
            <div class="progress-fill transition-all duration-700 bg-brand-500 h-1 absolute top-1/2 left-0 -translate-y-1/2 z-0" id="delivery-progress-bar"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div id="step-pickup" class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs mb-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg></div>
                <span class="text-[10px] text-surface-400 dark:text-gray-400">Pickup</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div id="step-way" class="w-8 h-8 rounded-full bg-surface-100 dark:bg-neutral-800 border-2 border-surface-200 dark:border-white/10 flex items-center justify-center text-surface-400 text-xs mb-1 transition-colors">🛵</div>
                <span id="text-way" class="text-[10px] text-surface-400 dark:text-gray-400">On the way</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div id="step-deliver" class="w-8 h-8 rounded-full bg-surface-100 dark:bg-neutral-800 border-2 border-surface-200 dark:border-white/10 flex items-center justify-center text-surface-400 text-xs mb-1 transition-colors">📍</div>
                <span id="text-deliver" class="text-[10px] text-surface-400 dark:text-gray-400">Deliver</span>
            </div>
        </div>

        {{-- Delivery details --}}
        <div class="bg-white dark:bg-neutral-800 border border-surface-200 dark:border-white/10 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-bold text-surface-900 dark:text-white" id="del-order-id">ORD-...</span>
                <span class="text-xs text-surface-500 dark:text-gray-400" id="del-order-summary">...</span>
            </div>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-brand-500 font-bold uppercase tracking-widest mb-0.5">Drop-off Address</p>
                        <p class="text-sm font-bold text-surface-900 dark:text-white" id="del-customer-name">Loading...</p>
                        <p class="text-xs text-surface-600 dark:text-gray-400 mt-1" id="del-customer-address">Loading...</p>
                        <p class="text-xs text-surface-500 dark:text-gray-500 italic mt-2 border-l-2 border-surface-200 dark:border-neutral-700 pl-2 hidden" id="del-instructions"></p>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex gap-2">
                <a href="#" id="btn-call" class="flex-1 flex items-center justify-center gap-2 py-3 bg-brand-500/10 text-brand-600 dark:text-brand-500 rounded-xl text-sm font-semibold hover:bg-brand-500/20 transition-colors uppercase tracking-tight">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Call Customer
                </a>
                <button id="btn-navigate" class="flex-1 flex items-center justify-center gap-2 py-3 bg-surface-100 dark:bg-white/5 text-surface-700 dark:text-gray-300 rounded-xl text-sm font-medium border border-surface-200 dark:border-white/10 hover:bg-surface-200 dark:hover:bg-white/10 transition-colors uppercase tracking-tight">
                    Navigate
                </button>
            </div>
        </div>

        {{-- Action button --}}
        <button id="btn-action" onclick="performAction()" class="w-full py-4 text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-2xl shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 active:scale-[0.98] transition-all uppercase tracking-widest">
            Processing...
        </button>
    </div>
</div>

<template id="empty-state">
    <div class="py-20 text-center px-4">
        <div class="w-16 h-16 bg-surface-100 dark:bg-neutral-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-surface-200 dark:border-white/5 text-2xl">🛵</div>
        <p class="text-sm font-semibold text-surface-900 dark:text-white">No active delivery</p>
        <p class="text-xs text-surface-500 dark:text-gray-400 mt-1 mb-6">You don't have any active orders right now.</p>
        <a href="{{ route('rider.dashboard') }}" class="px-6 py-2.5 bg-brand-500 text-white rounded-full font-bold text-xs shadow-lg hover:bg-brand-600 transition-colors uppercase">GO TO DASHBOARD</a>
    </div>
</template>

@push('scripts')
<script>
    const orderId = "{{ $id }}";
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };
    
    let currentOrder = null;
    let map, directionsService, directionsRenderer;

    function initMap() {
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: { strokeColor: "#F26522", strokeOpacity: 0.8, strokeWeight: 5 }
        });
        
        map = new google.maps.Map(document.getElementById("rider-delivery-map"), {
            zoom: 14,
            center: { lat: 30.0444, lng: 31.2357 },
            disableDefaultUI: true,
            styles: document.documentElement.classList.contains('dark') ? [
                { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                { featureType: "water", elementType: "geometry", stylers: [{ color: "#17263c" }] },
            ] : []
        });
        directionsRenderer.setMap(map);
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof google !== 'undefined') initMap();
        if (orderId === 'active') fetchActiveOrder();
        else fetchOrder(orderId);
    });

    async function fetchActiveOrder() {
        try {
            const res = await fetch('/api/rider/dashboard', { headers });
            const json = await res.json();
            if (json.success && json.data.active_orders.length > 0) {
                currentOrder = json.data.active_orders[0];
                renderDelivery();
            } else { showEmpty(); }
        } catch (e) { showEmpty(); }
    }

    async function fetchOrder(id) {
        try {
            const res = await fetch(`/api/rider/delivery/${id}`, { headers });
            const json = await res.json();
            if (json.success) { currentOrder = json.data; renderDelivery(); }
            else { showEmpty(); }
        } catch (e) { showEmpty(); }
    }

    function showEmpty() {
        document.getElementById('delivery-loading').classList.add('hidden');
        document.getElementById('delivery-content').innerHTML = document.getElementById('empty-state').innerHTML;
        document.getElementById('delivery-content').classList.remove('hidden');
    }

    function renderDelivery() {
        document.getElementById('delivery-loading').classList.add('hidden');
        document.getElementById('delivery-content').classList.remove('hidden');
        
        const o = currentOrder;
        const s = o.status.value || o.status;

        document.getElementById('del-order-id').textContent = `ORD-${o.id}`;
        document.getElementById('del-order-summary').textContent = `EGP ${parseFloat(o.total || 0).toFixed(2)} · ${o.items_count || 0} items`;
        document.getElementById('del-customer-name').textContent = o.customer?.name || 'Customer';
        document.getElementById('del-customer-address').textContent = o.delivery_address || 'Cairo';
        document.getElementById('btn-call').href = `tel:${o.customer?.phone || '+201000000000'}`;

        const restLoc = { lat: parseFloat(o.restaurant.latitude), lng: parseFloat(o.restaurant.longitude) };
        const delLoc = { lat: parseFloat(o.delivery_lat || 30.0444), lng: parseFloat(o.delivery_lng || 31.2357) };

        // Route Plotting
        directionsService.route({
            origin: restLoc,
            destination: delLoc,
            travelMode: google.maps.TravelMode.DRIVING
        }, (result, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
                new google.maps.Marker({ position: restLoc, map, icon: 'https://maps.google.com/mapfiles/kml/pal2/icon10.png' });
                new google.maps.Marker({ position: delLoc, map, icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png' });
            }
        });

        // UI Logic
        const btn = document.getElementById('btn-action');
        const pBar = document.getElementById('delivery-progress-bar');
        const sWay = document.getElementById('step-way');
        const sDel = document.getElementById('step-deliver');

        if (s === 'rider_assigned') {
            btn.textContent = 'Confirm Restaurant Arrival & Pickup';
            pBar.style.width = '33%';
        } else if (s === 'picked_up') {
            btn.textContent = 'Confirm Successful Delivery';
            btn.className = "w-full py-4 text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl shadow-lg uppercase tracking-widest transition-all";
            pBar.style.width = '66%';
            sWay.classList.add('bg-brand-500', 'text-white');
            sWay.innerHTML = '🛵';
        } else if (s === 'delivered') {
            btn.textContent = 'Return to Dashboard';
            pBar.style.width = '100%';
            sDel.classList.add('bg-emerald-500', 'text-white');
            sDel.innerHTML = '✅';
        }
        
        document.getElementById('btn-navigate').onclick = () => {
            const target = s === 'picked_up' ? delLoc : restLoc;
            window.open(`https://www.google.com/maps/dir/?api=1&destination=${target.lat},${target.lng}`, '_blank');
        };
    }

    async function performAction() {
        const o = currentOrder;
        const s = o.status.value || o.status;
        if (s === 'delivered') { window.location.href = "{{ route('rider.dashboard') }}"; return; }

        const action = s === 'rider_assigned' ? 'pickup' : 'deliver';
        try {
            const res = await fetch(`/api/rider/orders/${o.id}/${action}`, { method: 'PATCH', headers });
            const json = await res.json();
            if (json.success) {
                Toast.show('Status Updated', `Order ${action} successfully!`, 'success');
                if (action === 'deliver') setTimeout(() => window.location.href = "{{ route('rider.dashboard') }}", 1500);
                else { currentOrder = json.data; renderDelivery(); }
            } else { Toast.show('Error', json.message, 'error'); }
        } catch (e) { Toast.show('Error', 'Network error', 'error'); }
    }
</script>
@endpush
@endsection