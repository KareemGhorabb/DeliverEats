@extends('layouts.app')
@section('title', 'Track Order — DeliverEats')
@section('hide-footer', true)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-display font-bold">Order #ORD-20260426-001</h1>
            <p class="text-sm text-surface-300 mt-0.5">Placed 12 minutes ago · Shawarma Station</p>
        </div>
        <a href="{{ route('customer.orders.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">All Orders →</a>
    </div>

    <div class="grid lg:grid-cols-5 gap-8">
        {{-- Map and status --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Live map --}}
            <div class="map-container h-80 lg:h-96 relative" id="tracking-map">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="dot-loading mb-3"><span></span><span></span><span></span></div>
                        <p class="text-sm text-surface-300">Loading live map...</p>
                    </div>
                </div>
            </div>

            {{-- Rider info --}}
            <div class="bg-white rounded-2xl border border-surface-200/50 p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold">M</div>
                <div class="flex-1">
                    <p class="text-sm font-semibold">Mohamed Ali</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-xs text-surface-300">4.9 · 1,240 deliveries</span>
                    </div>
                    <p class="text-xs text-emerald-600 font-medium mt-1">🛵 On the way — ETA 8 min</p>
                </div>
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center hover:bg-brand-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-surface-100 text-surface-800/60 flex items-center justify-center hover:bg-surface-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Order progress --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
                <h3 class="text-base font-display font-bold mb-6">Order Progress</h3>

                {{-- Status timeline --}}
                <div class="order-timeline space-y-6">
                    <div class="relative pl-8">
                        <div class="timeline-dot completed top-1">
                            <svg class="w-3 h-3 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-surface-900">Order Placed</p>
                        <p class="text-xs text-surface-300 mt-0.5">9:42 PM — Payment confirmed</p>
                    </div>
                    <div class="relative pl-8">
                        <div class="timeline-dot completed top-1">
                            <svg class="w-3 h-3 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-surface-900">Restaurant Confirmed</p>
                        <p class="text-xs text-surface-300 mt-0.5">9:43 PM — Shawarma Station accepted</p>
                    </div>
                    <div class="relative pl-8">
                        <div class="timeline-dot completed top-1">
                            <svg class="w-3 h-3 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-surface-900">Preparing</p>
                        <p class="text-xs text-surface-300 mt-0.5">9:44 PM — Kitchen is on it</p>
                    </div>
                    <div class="relative pl-8">
                        <div class="timeline-dot current top-1"></div>
                        <p class="text-sm font-semibold text-brand-600">On the Way</p>
                        <p class="text-xs text-surface-300 mt-0.5">9:54 PM — Mohamed picked up your order</p>
                        <div class="mt-2 flex items-center gap-2 px-3 py-2 bg-brand-50 rounded-lg">
                            <div class="animate-pulse-soft w-2 h-2 rounded-full bg-brand-500"></div>
                            <span class="text-xs font-medium text-brand-700">Live tracking active</span>
                        </div>
                    </div>
                    <div class="relative pl-8">
                        <div class="timeline-dot top-1"></div>
                        <p class="text-sm font-medium text-surface-300">Delivered</p>
                        <p class="text-xs text-surface-300 mt-0.5">Estimated ~10:02 PM</p>
                    </div>
                </div>

                {{-- Order items --}}
                <div class="mt-8 pt-6 border-t border-surface-100">
                    <h4 class="text-xs font-semibold text-surface-300 uppercase tracking-wider mb-3">Items</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-surface-800/70">2× Classic Chicken Shawarma</span><span>$13.98</span></div>
                        <div class="flex justify-between"><span class="text-surface-800/70">1× Garlic Fries</span><span>$3.49</span></div>
                        <div class="flex justify-between"><span class="text-surface-800/70">2× Fresh Lemonade</span><span>$5.98</span></div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-surface-100 flex justify-between font-semibold">
                        <span>Total</span><span>$32.28</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapEl = document.getElementById('tracking-map');
        if (typeof L !== 'undefined' && mapEl) {
            mapEl.innerHTML = '';
            const map = L.map('tracking-map', { zoomControl: false }).setView([30.0444, 31.2357], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            // Restaurant marker
            const restaurantIcon = L.divIcon({ className: '', html: '<div style="background:#F26522;color:white;width:32px;height:32px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:16px;box-shadow:0 2px 8px rgba(0,0,0,0.2)">🌯</div>', iconSize: [32, 32] });
            L.marker([30.0480, 31.2400], { icon: restaurantIcon }).addTo(map).bindPopup('<b>Shawarma Station</b><br>Pickup point');

            // Customer marker
            const customerIcon = L.divIcon({ className: '', html: '<div style="background:#3B82F6;color:white;width:32px;height:32px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:16px;box-shadow:0 2px 8px rgba(0,0,0,0.2)">📍</div>', iconSize: [32, 32] });
            L.marker([30.0395, 31.2330], { icon: customerIcon }).addTo(map).bindPopup('<b>Your Location</b><br>12 Tahrir Square');

            // Rider marker (animated)
            const riderIcon = L.divIcon({ className: '', html: '<div style="background:#10B981;color:white;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 12px rgba(16,185,129,0.4);border:3px solid white">🛵</div>', iconSize: [36, 36] });
            const riderMarker = L.marker([30.0450, 31.2370], { icon: riderIcon }).addTo(map).bindPopup('<b>Mohamed Ali</b><br>Your rider');

            // Simulate rider movement
            const positions = [[30.0450, 31.2370],[30.0445, 31.2365],[30.0440, 31.2360],[30.0435, 31.2355],[30.0430, 31.2350],[30.0425, 31.2345],[30.0420, 31.2340],[30.0415, 31.2338],[30.0410, 31.2335],[30.0405, 31.2333],[30.0400, 31.2331],[30.0395, 31.2330]];
            let step = 0;
            setInterval(() => {
                if (step < positions.length) {
                    riderMarker.setLatLng(positions[step]);
                    step++;
                }
            }, 3000);

            L.control.zoom({ position: 'topright' }).addTo(map);
        }
    });
</script>
@endpush
@endsection
