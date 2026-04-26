@extends('layouts.admin')
@section('page-title', 'Surge Pricing')
@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-surface-900 rounded-2xl border border-white/5 p-5">
        <p class="text-xs text-surface-300 mb-1">Active Strategy</p>
        <p class="text-lg font-display font-bold text-amber-400">Multiplier</p>
        <p class="text-xs text-surface-300 mt-1">Scales linearly with demand</p>
    </div>
    <div class="bg-surface-900 rounded-2xl border border-white/5 p-5">
        <p class="text-xs text-surface-300 mb-1">Current Avg Multiplier</p>
        <p class="text-lg font-display font-bold">1.35×</p>
        <p class="text-xs text-surface-300 mt-1">Across 3 active zones</p>
    </div>
    <div class="bg-surface-900 rounded-2xl border border-white/5 p-5">
        <p class="text-xs text-surface-300 mb-1">Max Cap</p>
        <p class="text-lg font-display font-bold text-red-400">3.0×</p>
        <p class="text-xs text-surface-300 mt-1">Maximum allowed surge</p>
    </div>
</div>

{{-- Strategy selector --}}
<div class="bg-surface-900 rounded-2xl border border-white/5 p-6 mb-6">
    <h3 class="text-sm font-display font-bold mb-4">Pricing Strategy (Strategy Pattern)</h3>
    <div class="grid md:grid-cols-3 gap-3 mb-6">
        <label class="cursor-pointer">
            <input type="radio" name="strategy" value="flat" class="peer hidden">
            <div class="p-4 rounded-xl border-2 border-white/10 peer-checked:border-brand-500 peer-checked:bg-brand-500/10 transition-all">
                <p class="text-sm font-bold">Flat</p>
                <p class="text-xs text-surface-300 mt-1">Fixed multiplier regardless of demand. Predictable for customers.</p>
            </div>
        </label>
        <label class="cursor-pointer">
            <input type="radio" name="strategy" value="multiplier" checked class="peer hidden">
            <div class="p-4 rounded-xl border-2 border-white/10 peer-checked:border-brand-500 peer-checked:bg-brand-500/10 transition-all">
                <p class="text-sm font-bold">Multiplier ✓</p>
                <p class="text-xs text-surface-300 mt-1">Linear scaling with demand score. Most common approach.</p>
            </div>
        </label>
        <label class="cursor-pointer">
            <input type="radio" name="strategy" value="time_based" class="peer hidden">
            <div class="p-4 rounded-xl border-2 border-white/10 peer-checked:border-brand-500 peer-checked:bg-brand-500/10 transition-all">
                <p class="text-sm font-bold">Time-Based</p>
                <p class="text-xs text-surface-300 mt-1">Peak hours get higher rates. Accounts for lunch/dinner rush.</p>
            </div>
        </label>
    </div>
    <div class="grid md:grid-cols-3 gap-4">
        <div><label class="block text-xs font-medium text-surface-300 mb-1">Base Multiplier</label><input type="number" step="0.1" value="1.0" class="w-full px-4 py-2.5 rounded-xl bg-surface-950 border border-white/10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
        <div><label class="block text-xs font-medium text-surface-300 mb-1">Max Multiplier</label><input type="number" step="0.1" value="3.0" class="w-full px-4 py-2.5 rounded-xl bg-surface-950 border border-white/10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
        <div><label class="block text-xs font-medium text-surface-300 mb-1">Demand Threshold</label><input type="number" value="20" class="w-full px-4 py-2.5 rounded-xl bg-surface-950 border border-white/10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand-500/30"><p class="text-[10px] text-surface-300 mt-0.5">Orders/hour to trigger</p></div>
    </div>
    <button onclick="Toast.show('Strategy Updated', 'Pricing engine recalculated', 'success')" class="mt-4 px-6 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600">Save Configuration</button>
</div>

{{-- Zone configs --}}
<div class="bg-surface-900 rounded-2xl border border-white/5 p-6 mb-6">
    <h3 class="text-sm font-display font-bold mb-4">Zone Configuration</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-surface-300 uppercase tracking-wider border-b border-white/5"><th class="px-4 py-3">Zone</th><th class="px-4 py-3">Current Demand</th><th class="px-4 py-3">Weather</th><th class="px-4 py-3">Time Factor</th><th class="px-4 py-3">Multiplier</th><th class="px-4 py-3">Status</th></tr></thead>
            <tbody class="divide-y divide-white/5">
                @php $zones = [
                    ['zone' => 'Downtown', 'demand' => '42 orders/hr', 'demand_level' => 'High', 'weather' => 'Clear', 'time' => 'Peak (Dinner)', 'multiplier' => '1.5×', 'active' => true],
                    ['zone' => 'Zamalek', 'demand' => '28 orders/hr', 'demand_level' => 'Medium', 'weather' => 'Clear', 'time' => 'Peak (Dinner)', 'multiplier' => '1.3×', 'active' => true],
                    ['zone' => 'Heliopolis', 'demand' => '22 orders/hr', 'demand_level' => 'Medium', 'weather' => 'Windy', 'time' => 'Peak (Dinner)', 'multiplier' => '1.2×', 'active' => true],
                    ['zone' => 'Maadi', 'demand' => '15 orders/hr', 'demand_level' => 'Normal', 'weather' => 'Clear', 'time' => 'Off-Peak', 'multiplier' => '1.0×', 'active' => false],
                    ['zone' => 'Nasr City', 'demand' => '18 orders/hr', 'demand_level' => 'Normal', 'weather' => 'Clear', 'time' => 'Off-Peak', 'multiplier' => '1.0×', 'active' => false],
                ]; @endphp
                @foreach($zones as $z)
                <tr class="hover:bg-white/[0.02]">
                    <td class="px-4 py-3 font-medium">{{ $z['zone'] }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs">{{ $z['demand'] }}</span>
                        @php $dColors = ['High' => 'text-red-400 bg-red-400/10', 'Medium' => 'text-amber-400 bg-amber-400/10', 'Normal' => 'text-emerald-400 bg-emerald-400/10']; @endphp
                        <span class="ml-1 px-1.5 py-0.5 text-[10px] font-bold rounded {{ $dColors[$z['demand_level']] }}">{{ $z['demand_level'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-surface-300">{{ $z['weather'] }}</td>
                    <td class="px-4 py-3 text-surface-300">{{ $z['time'] }}</td>
                    <td class="px-4 py-3"><span class="font-bold {{ $z['active'] ? 'text-amber-400' : '' }}">{{ $z['multiplier'] }}</span></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $z['active'] ? 'text-amber-400 bg-amber-400/10' : 'text-surface-300 bg-white/5' }}">{{ $z['active'] ? 'SURGE' : 'Normal' }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Surge log --}}
<div class="bg-surface-900 rounded-2xl border border-white/5 p-6">
    <h3 class="text-sm font-display font-bold mb-4">Surge Pricing Log (Event Sourcing)</h3>
    <div class="space-y-2 max-h-60 overflow-y-auto text-xs font-mono">
        @php $logs = [
            ['time' => '21:54:02', 'msg' => '[RECALC] Downtown: demand=42, weather=1.0, time=1.15 → multiplier=1.50 (cap: 3.0)', 'color' => 'text-amber-400'],
            ['time' => '21:54:02', 'msg' => '[RECALC] Zamalek: demand=28, weather=1.0, time=1.15 → multiplier=1.30 (cap: 3.0)', 'color' => 'text-amber-400'],
            ['time' => '21:54:02', 'msg' => '[RECALC] Heliopolis: demand=22, weather=1.1, time=1.15 → multiplier=1.20 (cap: 3.0)', 'color' => 'text-amber-400'],
            ['time' => '21:54:02', 'msg' => '[RECALC] Maadi: demand=15, weather=1.0, time=0.9 → multiplier=1.00 (below threshold)', 'color' => 'text-surface-300'],
            ['time' => '21:49:01', 'msg' => '[STRATEGY] Active strategy: MultiplierStrategy | Base: 1.0 | Cap: 3.0', 'color' => 'text-blue-400'],
            ['time' => '21:44:01', 'msg' => '[ROLLBACK] Nasr City: demand dropped to 18 — surge deactivated', 'color' => 'text-emerald-400'],
        ]; @endphp
        @foreach($logs as $l)
        <div class="flex gap-3 py-1.5">
            <span class="text-surface-300 flex-shrink-0">{{ $l['time'] }}</span>
            <span class="{{ $l['color'] }}">{{ $l['msg'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endsection
