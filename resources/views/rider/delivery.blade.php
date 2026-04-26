@extends('layouts.rider')
@section('title', 'Active Delivery — DeliverEats')

@section('content')
{{-- Map --}}
<div class="map-container h-64 relative rounded-none" id="rider-delivery-map"></div>

<div class="px-4 py-5 space-y-4">
    {{-- Status steps --}}
    <div class="status-track relative flex justify-between px-4 py-2">
        <div class="progress-fill" style="width: 66%"></div>
        <div class="relative z-10 flex flex-col items-center">
            <div class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs mb-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg></div>
            <span class="text-[10px] text-surface-300">Pickup</span>
        </div>
        <div class="relative z-10 flex flex-col items-center">
            <div class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs mb-1 ring-4 ring-brand-500/20">🛵</div>
            <span class="text-[10px] text-brand-400 font-semibold">On the way</span>
        </div>
        <div class="relative z-10 flex flex-col items-center">
            <div class="w-8 h-8 rounded-full bg-surface-800 border-2 border-surface-300/30 flex items-center justify-center text-surface-300 text-xs mb-1">📍</div>
            <span class="text-[10px] text-surface-300">Deliver</span>
        </div>
    </div>

    {{-- Delivery details --}}
    <div class="bg-surface-800 border border-white/10 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold">ORD-20260426-001</span>
            <span class="text-xs text-surface-300">$32.28 · 5 items</span>
        </div>
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-2 h-2 rounded-full bg-emerald-400 mt-2"></div>
                <div>
                    <p class="text-xs text-surface-300">Deliver to</p>
                    <p class="text-sm font-semibold">Ahmed Hassan</p>
                    <p class="text-xs text-surface-300 mt-0.5">12 Tahrir Square, Apt 5, Cairo</p>
                    <p class="text-xs text-surface-300 italic mt-1">"Ring the doorbell, 3rd floor"</p>
                </div>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <a href="tel:+201001234567" class="flex-1 flex items-center justify-center gap-2 py-3 bg-brand-500/20 text-brand-400 rounded-xl text-sm font-semibold hover:bg-brand-500/30 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Call
            </a>
            <button class="flex-1 flex items-center justify-center gap-2 py-3 bg-white/5 text-surface-200 rounded-xl text-sm font-medium border border-white/10 hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                Navigate
            </button>
        </div>
    </div>

    {{-- Action button --}}
    <button onclick="Toast.show('Delivered!', 'Earnings credited: $7.50', 'success'); setTimeout(() => window.location.href='{{ route('rider.dashboard') }}', 2000)" class="w-full py-4 text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 active:scale-[0.98] transition-all">
        Confirm Delivery ✓
    </button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapEl = document.getElementById('rider-delivery-map');
    if (typeof L !== 'undefined' && mapEl) {
        mapEl.innerHTML = '';
        const map = L.map('rider-delivery-map', { zoomControl: false }).setView([30.0430, 31.2355], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '' }).addTo(map);
        const riderIcon = L.divIcon({ className: '', html: '<div style="background:#10B981;color:white;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;box-shadow:0 2px 12px rgba(16,185,129,0.4);border:2px solid white">🛵</div>', iconSize: [32, 32] });
        const destIcon = L.divIcon({ className: '', html: '<div style="background:#3B82F6;color:white;width:28px;height:28px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;box-shadow:0 2px 8px rgba(0,0,0,0.2)">📍</div>', iconSize: [28, 28] });
        L.marker([30.0440, 31.2370], { icon: riderIcon }).addTo(map);
        L.marker([30.0395, 31.2330], { icon: destIcon }).addTo(map);
    }
});
</script>
@endpush
@endsection
