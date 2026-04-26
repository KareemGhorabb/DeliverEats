@extends('layouts.rider')
@section('title', 'Rider Dashboard — DeliverEats')

@section('content')
<div class="px-4 py-5">
    {{-- Earnings summary --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-surface-800 rounded-xl p-4 text-center border border-white/5">
            <p class="text-xs text-surface-300 mb-1">Today</p>
            <p class="text-xl font-display font-bold text-emerald-400">$87</p>
        </div>
        <div class="bg-surface-800 rounded-xl p-4 text-center border border-white/5">
            <p class="text-xs text-surface-300 mb-1">Deliveries</p>
            <p class="text-xl font-display font-bold">12</p>
        </div>
        <div class="bg-surface-800 rounded-xl p-4 text-center border border-white/5">
            <p class="text-xs text-surface-300 mb-1">Rating</p>
            <p class="text-xl font-display font-bold text-amber-400">4.9</p>
        </div>
    </div>

    {{-- Active delivery --}}
    <div class="mb-6">
        <h3 class="text-xs font-semibold text-surface-300 uppercase tracking-wider mb-3">Current Delivery</h3>
        <a href="{{ route('rider.delivery', ['id' => 'ORD-001']) }}" class="block bg-gradient-to-br from-brand-500/20 to-brand-600/10 border border-brand-500/30 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="px-2.5 py-1 bg-brand-500 text-white text-[10px] font-bold rounded-full">IN PROGRESS</span>
                <span class="text-xs text-surface-300">ORD-20260426-001</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-amber-500/20 flex items-center justify-center mt-0.5">
                        <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                    </div>
                    <div>
                        <p class="text-xs text-surface-300">Pickup from</p>
                        <p class="text-sm font-semibold">Shawarma Station</p>
                        <p class="text-xs text-surface-300">45 King Faisal St</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center mt-0.5">
                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    </div>
                    <div>
                        <p class="text-xs text-surface-300">Deliver to</p>
                        <p class="text-sm font-semibold">Ahmed Hassan</p>
                        <p class="text-xs text-surface-300">12 Tahrir Square, Apt 5</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm font-bold text-emerald-400">$7.50 earned</span>
                <span class="text-xs text-surface-300 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ETA 8 min
                </span>
            </div>
        </a>
    </div>

    {{-- Incoming request --}}
    <div class="mb-6">
        <h3 class="text-xs font-semibold text-surface-300 uppercase tracking-wider mb-3">New Request</h3>
        <div class="bg-surface-800 border border-white/10 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse-soft"></div>
                    <span class="text-xs font-semibold text-emerald-400">INCOMING</span>
                </div>
                <span class="text-sm font-bold text-emerald-400">$8.25</span>
            </div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center text-lg">🍕</div>
                <div>
                    <p class="text-sm font-semibold">Pizza Republic</p>
                    <p class="text-xs text-surface-300">2.3 km · ~12 min total</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button onclick="Toast.show('Accepted!', 'Navigate to restaurant', 'success')" class="py-3 text-sm font-bold text-white bg-emerald-500 rounded-xl hover:bg-emerald-600 transition-colors">Accept</button>
                <button onclick="this.closest('.bg-surface-800').style.display='none'" class="py-3 text-sm font-medium text-surface-300 border border-white/10 rounded-xl hover:bg-white/5 transition-colors">Decline</button>
            </div>
        </div>
    </div>

    {{-- Recent deliveries --}}
    <div>
        <h3 class="text-xs font-semibold text-surface-300 uppercase tracking-wider mb-3">Recent Deliveries</h3>
        <div class="space-y-2">
            @php $recent = [
                ['name' => 'Sushi Zen', 'emoji' => '🍣', 'earned' => '6.75', 'time' => '1h ago', 'rating' => '5'],
                ['name' => 'Burger District', 'emoji' => '🍔', 'earned' => '5.50', 'time' => '2h ago', 'rating' => '5'],
                ['name' => 'The Green Bowl', 'emoji' => '🥗', 'earned' => '7.00', 'time' => '3h ago', 'rating' => '4'],
            ]; @endphp
            @foreach($recent as $d)
            <div class="flex items-center gap-3 bg-surface-800 border border-white/5 rounded-xl p-3">
                <div class="text-xl">{{ $d['emoji'] }}</div>
                <div class="flex-1">
                    <p class="text-sm font-medium">{{ $d['name'] }}</p>
                    <p class="text-[10px] text-surface-300">{{ $d['time'] }} · ★ {{ $d['rating'] }}</p>
                </div>
                <span class="text-sm font-bold text-emerald-400">+${{ $d['earned'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
