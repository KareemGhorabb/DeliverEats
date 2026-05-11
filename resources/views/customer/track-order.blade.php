@extends('layouts.app')
@section('title', 'Track Order — DeliverEats')
@section('hide-footer', true)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-display font-bold" id="order-title">Loading Order...</h1>
            <p class="text-sm text-surface-300 mt-0.5" id="order-subtitle">Fetching details...</p>
        </div>
        <a href="{{ route('customer.orders.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">All Orders →</a>
    </div>

    <div class="grid lg:grid-cols-5 gap-8">
        {{-- Map and status --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Live map --}}
            <div class="map-container h-80 lg:h-96 relative rounded-2xl overflow-hidden shadow-sm border border-surface-200" id="tracking-map">
                <div class="absolute inset-0 flex items-center justify-center bg-surface-100">
                    <div class="text-center">
                        <div class="dot-loading mb-3"><span></span><span></span><span></span></div>
                        <p class="text-sm text-surface-400 font-medium">Loading live map...</p>
                    </div>
                </div>
            </div>

            {{-- Rider info --}}
            <div id="rider-info-container" class="hidden bg-white rounded-2xl border border-surface-200/50 p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold" id="rider-avatar">R</div>
                <div class="flex-1">
                    <p class="text-sm font-semibold" id="rider-name">Rider Assigned</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-xs text-surface-300" id="rider-rating">4.9 Rider</span>
                    </div>
                </div>
                <button class="p-3 rounded-full bg-surface-50 text-surface-600 hover:bg-brand-50 hover:text-brand-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 011.94.86l-.85 3.85a1 1 0 01-.11.27l-.48.73A15.92 15.92 0 0115.97 19.12l.73-.48c.07-.05.17-.09.27-.11L19.88 19.4a1 1 0 01.86 1.94l-3.28 2a2 2 0 01-2.08 0 15.92 15.92 0 01-14.7-14.7 2 2 0 010-2.08z"/></svg>
                </button>
            </div>
        </div>

        {{-- Order progress --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6 shadow-sm">
                <h3 class="text-base font-display font-bold mb-6 flex items-center justify-between">
                    Order Progress
                    <button onclick="fetchOrderData()" class="p-1 text-brand-600 hover:bg-brand-50 rounded transition-all" id="btn-refresh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </button>
                </h3>

                {{-- Status timeline --}}
                <div class="order-timeline relative space-y-6 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-surface-100" id="status-timeline">
                    <!-- Populated via JS -->
                </div>

                {{-- Order items --}}
                <div class="mt-8 pt-6 border-t border-surface-100">
                    <h4 class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-4">Order Details</h4>
                    <div class="space-y-3 text-sm" id="order-items-list">
                        <!-- Populated via JS -->
                    </div>
                    <div class="mt-4 pt-4 border-t border-surface-100 flex justify-between font-display font-bold text-lg">
                        <span class="text-surface-900">Total</span>
                        <span id="order-total-price" class="text-brand-600">EGP 0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-dot {
        @apply absolute left-0 w-6 h-6 rounded-full border-4 border-white bg-surface-200 transition-all duration-500 z-10;
    }
    .timeline-dot.completed {
        @apply bg-emerald-500 border-emerald-100 shadow-[0_0_10px_rgba(16,185,129,0.3)];
    }
    .timeline-dot.current {
        @apply bg-brand-500 border-brand-100 scale-110 shadow-[0_0_15px_rgba(244,63,94,0.4)];
    }
    .timeline-dot.current::after {
        content: '';
        @apply absolute inset-0 rounded-full animate-ping bg-brand-400 opacity-40;
    }
    .order-timeline div:last-child {
        @apply mb-0;
    }
</style>

@push('scripts')
<script>
    const orderId = "{{ $id }}";
    let mapInstance = null;
    let riderMarker = null;

    document.addEventListener('DOMContentLoaded', () => {
        fetchOrderData();
        setInterval(fetchOrderData, 10000); // Polling
    });

    async function fetchOrderData() {
        const btn = document.getElementById('btn-refresh');
        if (btn) btn.classList.add('animate-spin');

        try {
            const res = await fetch(`/api/v1/orders/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                }
            });
            const json = await res.json();
            
            if (json.success && json.data) {
                renderOrder(json.data);
            }
        } catch (e) { console.error(e); }
        finally { if (btn) setTimeout(() => btn.classList.remove('animate-spin'), 500); }
    }

    function renderOrder(order) {
        document.getElementById('order-title').textContent = `Order #ORD-${order.id}`;
        document.getElementById('order-subtitle').textContent = `${order.restaurant?.name || 'Restaurant'} · ${new Date(order.created_at).toLocaleDateString()}`;

        // Render Items
        const itemsList = document.getElementById('order-items-list');
        itemsList.innerHTML = order.items.map(item => `
            <div class="flex justify-between items-start gap-4">
                <span class="text-surface-600 flex-1">${item.quantity}× ${item.menu_item?.name || 'Item'}</span>
                <span class="font-medium text-surface-900">EGP ${(item.unit_price * item.quantity).toFixed(2)}</span>
            </div>
        `).join('');
        document.getElementById('order-total-price').textContent = `EGP ${parseFloat(order.total).toFixed(2)}`;

        // Render Timeline
        renderTimeline(order);

        // Render Rider & Map
        if (order.rider) {
            document.getElementById('rider-info-container').classList.remove('hidden');
            document.getElementById('rider-name').textContent = order.rider.name;
            document.getElementById('rider-avatar').textContent = order.rider.name.charAt(0);
        }

        renderMap(order);
    }

    function renderTimeline(order) {
        const states = [
            { id: 'payment_pending', label: 'Awaiting Payment' },
            { id: 'pending', label: 'Order Placed', legacy: 'placed' },
            { id: 'accepted', label: 'Restaurant Confirmed', legacy: 'confirmed' },
            { id: 'preparing', label: 'Preparing' },
            { id: 'ready_for_pickup', label: 'Ready for Pickup' },
            { id: 'rider_assigned', label: 'Rider Assigned' },
            { id: 'picked_up', label: 'Rider Picked Up' },
            { id: 'delivered', label: 'Delivered' }
        ];

        let currentStatus = order.status.value || order.status;
        
        // Find current index, supporting legacy names
        let currentIndex = states.findIndex(s => s.id === currentStatus || (s.legacy && s.legacy === currentStatus));
        
        // Safety: If status not found (shouldn't happen), assume it's one step further if rider exists
        if (currentIndex === -1 && order.rider_id) currentIndex = 5;

        let isCancelled = currentStatus === 'cancelled';
        if (isCancelled) {
            document.getElementById('status-timeline').innerHTML = `
                <div class="relative pl-10 py-1">
                    <div class="timeline-dot bg-red-500 border-red-100 current"></div>
                    <p class="text-sm font-bold text-red-600">Order Cancelled</p>
                    <p class="text-xs text-surface-400 mt-0.5">This order has been cancelled.</p>
                </div>
            `;
            return;
        }

        const timelineHtml = states.map((state, index) => {
            const isCompleted = index < currentIndex || (currentStatus === 'delivered' && state.id === 'delivered');
            const isCurrent = index === currentIndex && currentStatus !== 'delivered';
            
            let dotClass = '';
            if (isCompleted) dotClass = 'completed';
            else if (isCurrent) dotClass = 'current';

            let dotContent = '';
            if (isCompleted) {
                dotContent = `<svg class="w-3 h-3 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>`;
            }

            let textClass = isCurrent ? 'text-brand-600 font-bold' : (isCompleted ? 'text-surface-900 font-medium' : 'text-surface-300');
            
            return `
                <div class="relative pl-10">
                    <div class="timeline-dot ${dotClass}">${dotContent}</div>
                    <p class="text-sm ${textClass}">${state.label}</p>
                    ${isCurrent ? `<p class="text-[10px] text-brand-400 mt-0.5 font-medium">Actual step</p>` : ''}
                    ${(currentStatus === 'delivered' && state.id === 'delivered') ? `<p class="text-[10px] text-emerald-500 mt-0.5 font-bold">Successfully Delivered</p>` : ''}
                </div>
            `;
        }).join('');

        document.getElementById('status-timeline').innerHTML = timelineHtml;
    }

    function renderMap(order) {
        if (typeof L === 'undefined') return;
        
        const restLat = parseFloat(order.restaurant?.latitude) || 30.0444;
        const restLng = parseFloat(order.restaurant?.longitude) || 31.2357;

        if (!mapInstance) {
            const mapEl = document.getElementById('tracking-map');
            if (!mapEl) return;
            mapEl.innerHTML = '';
            mapInstance = L.map('tracking-map', { zoomControl: false }).setView([restLat, restLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapInstance);
            L.control.zoom({ position: 'topright' }).addTo(mapInstance);

            // Restaurant Marker
            const restIcon = L.divIcon({ className: '', html: '<div style="background:#F26522;color:white;width:32px;height:32px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:16px;box-shadow:0 2px 8px rgba(0,0,0,0.2)">🏪</div>', iconSize: [32, 32] });
            L.marker([restLat, restLng], { icon: restIcon }).addTo(mapInstance).bindPopup(`<b>${order.restaurant?.name || 'Restaurant'}</b>`);
        }

        // Live Rider Marker
        if (order.rider && order.rider.latitude && order.rider.longitude) {
            const riderLat = parseFloat(order.rider.latitude);
            const riderLng = parseFloat(order.rider.longitude);

            if (!riderMarker) {
                const riderIcon = L.divIcon({ className: '', html: '<div style="background:#10B981;color:white;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 12px rgba(16,185,129,0.4);border:3px solid white">🛵</div>', iconSize: [36, 36] });
                riderMarker = L.marker([riderLat, riderLng], { icon: riderIcon }).addTo(mapInstance);
            } else {
                riderMarker.setLatLng([riderLat, riderLng]);
            }
            
            const bounds = L.latLngBounds([[restLat, restLng], [riderLat, riderLng]]);
            mapInstance.fitBounds(bounds, { padding: [50, 50] });
        }
    }
</script>
@endpush
@endsection
