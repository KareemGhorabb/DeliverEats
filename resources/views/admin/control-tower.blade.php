@extends('layouts.admin')
@section('page-title', 'Control Tower')
@section('content')
<div class="grid lg:grid-cols-4 gap-6">
    {{-- Map --}}
    <div class="lg:col-span-3">
        <div class="map-container h-[500px] lg:h-[600px] relative" id="control-tower-map">
            <div class="absolute top-4 left-4 z-[1000] glass-dark rounded-xl px-4 py-3">
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-brand-500"></span><span class="text-surface-200">Restaurants (12)</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span><span class="text-surface-200">Riders (8)</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500"></span><span class="text-surface-200">Active Orders (14)</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        <div class="bg-surface-900 rounded-2xl border border-white/5 p-5">
            <h3 class="text-sm font-display font-bold mb-3 flex items-center gap-2">Active Orders <span class="px-1.5 py-0.5 bg-brand-500/20 text-brand-400 text-[10px] font-bold rounded-full">14</span></h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @php $mapOrders = [
                    ['id' => 'ORD-284', 'from' => 'Pizza Republic', 'to' => 'Youssef A.', 'rider' => 'Pending', 'status' => 'placed'],
                    ['id' => 'ORD-283', 'from' => 'Sushi Zen', 'to' => 'Nour M.', 'rider' => 'Ali S.', 'status' => 'confirmed'],
                    ['id' => 'ORD-282', 'from' => 'Shawarma Station', 'to' => 'Ahmed H.', 'rider' => 'Mohamed A.', 'status' => 'on_the_way'],
                    ['id' => 'ORD-281', 'from' => 'Burger District', 'to' => 'Sara K.', 'rider' => 'Hassan M.', 'status' => 'on_the_way'],
                    ['id' => 'ORD-280', 'from' => 'Green Bowl', 'to' => 'Omar N.', 'rider' => 'Karim R.', 'status' => 'preparing'],
                ]; @endphp
                @foreach($mapOrders as $o)
                <div class="p-3 rounded-xl bg-white/[0.03] hover:bg-white/[0.06] cursor-pointer transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold">{{ $o['id'] }}</span>
                        @php $colors = ['placed' => 'text-brand-400', 'confirmed' => 'text-blue-400', 'preparing' => 'text-amber-400', 'on_the_way' => 'text-violet-400']; @endphp
                        <span class="text-[10px] {{ $colors[$o['status']] ?? 'text-surface-300' }} font-semibold uppercase">{{ str_replace('_', ' ', $o['status']) }}</span>
                    </div>
                    <p class="text-[11px] text-surface-300 mt-1">{{ $o['from'] }} → {{ $o['to'] }}</p>
                    <p class="text-[10px] text-surface-300 mt-0.5">🛵 {{ $o['rider'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-surface-900 rounded-2xl border border-white/5 p-5">
            <h3 class="text-sm font-display font-bold mb-3">Rider Fleet</h3>
            <div class="space-y-2">
                @php $riders = [
                    ['name' => 'Mohamed Ali', 'status' => 'delivering', 'color' => 'bg-violet-400'],
                    ['name' => 'Hassan Magdy', 'status' => 'delivering', 'color' => 'bg-violet-400'],
                    ['name' => 'Ali Saeed', 'status' => 'available', 'color' => 'bg-emerald-400'],
                    ['name' => 'Karim Rashad', 'status' => 'delivering', 'color' => 'bg-violet-400'],
                    ['name' => 'Tarek Ibrahim', 'status' => 'available', 'color' => 'bg-emerald-400'],
                    ['name' => 'Amr Fawzy', 'status' => 'offline', 'color' => 'bg-surface-300/50'],
                ]; @endphp
                @foreach($riders as $r)
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/[0.03] transition-colors">
                    <div class="w-2 h-2 rounded-full {{ $r['color'] }}"></div>
                    <span class="text-xs flex-1">{{ $r['name'] }}</span>
                    <span class="text-[10px] text-surface-300 capitalize">{{ $r['status'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-amber-400 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Surge Active
            </h3>
            <p class="text-xs text-surface-300 mb-2">3 zones with elevated demand</p>
            <div class="space-y-1 text-xs">
                <div class="flex justify-between"><span class="text-surface-200">Downtown</span><span class="text-amber-400 font-bold">1.5×</span></div>
                <div class="flex justify-between"><span class="text-surface-200">Zamalek</span><span class="text-amber-400 font-bold">1.3×</span></div>
                <div class="flex justify-between"><span class="text-surface-200">Heliopolis</span><span class="text-amber-400 font-bold">1.2×</span></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapEl = document.getElementById('control-tower-map');
    if (typeof L !== 'undefined' && mapEl) {
        mapEl.innerHTML = '';
        const legend = mapEl.querySelector('.glass-dark');
        const map = L.map('control-tower-map', { zoomControl: false }).setView([30.0444, 31.2357], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '' }).addTo(map);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Restaurants
        const restaurants = [[30.0480,31.2400],[30.0520,31.2300],[30.0400,31.2250],[30.0350,31.2450],[30.0560,31.2380],[30.0420,31.2500],[30.0300,31.2350],[30.0470,31.2200]];
        restaurants.forEach(pos => {
            const icon = L.divIcon({ className: '', html: '<div style="background:#F26522;width:16px;height:16px;border-radius:6px;border:2px solid rgba(242,101,34,0.3);box-shadow:0 0 8px rgba(242,101,34,0.4)"></div>', iconSize: [16,16] });
            L.marker(pos, { icon }).addTo(map);
        });

        // Riders
        const riderPositions = [[30.0450,31.2370],[30.0430,31.2290],[30.0500,31.2420],[30.0380,31.2340],[30.0540,31.2350],[30.0460,31.2440],[30.0410,31.2380],[30.0350,31.2410]];
        riderPositions.forEach(pos => {
            const icon = L.divIcon({ className: '', html: '<div style="background:#10B981;width:14px;height:14px;border-radius:50%;border:2px solid rgba(16,185,129,0.3);box-shadow:0 0 10px rgba(16,185,129,0.5)"></div>', iconSize: [14,14] });
            L.marker(pos, { icon }).addTo(map);
        });

        // Surge zones
        L.circle([30.0444, 31.2357], { radius: 1200, color: '#F59E0B', fillColor: '#F59E0B', fillOpacity: 0.08, weight: 1 }).addTo(map);
        L.circle([30.0600, 31.2200], { radius: 800, color: '#F59E0B', fillColor: '#F59E0B', fillOpacity: 0.06, weight: 1 }).addTo(map);
    }
});
</script>
@endpush
@endsection
