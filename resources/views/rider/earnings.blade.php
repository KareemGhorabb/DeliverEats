@extends('layouts.rider')
@section('title', 'Earnings — DeliverEats')

@section('content')
<div class="px-4 py-5">
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-center mb-6">
        <p class="text-sm text-emerald-100 mb-1">This Week's Earnings</p>
        <p class="text-4xl font-display font-extrabold text-white">EGP 542.50</p>
        <p class="text-sm text-emerald-200 mt-1">68 deliveries · 4.9 avg rating</p>
    </div>

    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-surface-800 rounded-xl p-4 border border-white/5">
            <p class="text-xs text-surface-300 mb-1">Base Pay</p>
            <p class="text-lg font-bold">EGP 408.00</p>
        </div>
        <div class="bg-surface-800 rounded-xl p-4 border border-white/5">
            <p class="text-xs text-surface-300 mb-1">Tips</p>
            <p class="text-lg font-bold text-emerald-400">EGP 134.50</p>
        </div>
    </div>

    {{-- Daily breakdown --}}
    <h3 class="text-xs font-semibold text-surface-300 uppercase tracking-wider mb-3">Daily Breakdown</h3>
    <div class="bg-surface-800 rounded-2xl border border-white/5 overflow-hidden mb-6">
        @php $days = [
            ['day' => 'Today', 'deliveries' => 12, 'earned' => '87.50', 'hours' => '5.2h'],
            ['day' => 'Saturday', 'deliveries' => 15, 'earned' => '112.00', 'hours' => '6.5h'],
            ['day' => 'Friday', 'deliveries' => 11, 'earned' => '78.75', 'hours' => '4.8h'],
            ['day' => 'Thursday', 'deliveries' => 8, 'earned' => '62.00', 'hours' => '3.5h'],
            ['day' => 'Wednesday', 'deliveries' => 10, 'earned' => '75.50', 'hours' => '4.2h'],
            ['day' => 'Tuesday', 'deliveries' => 7, 'earned' => '68.00', 'hours' => '3.8h'],
            ['day' => 'Monday', 'deliveries' => 5, 'earned' => '58.75', 'hours' => '3.0h'],
        ]; @endphp
        @foreach($days as $i => $d)
        <div class="flex items-center px-5 py-3.5 {{ $i > 0 ? 'border-t border-white/5' : '' }}">
            <div class="flex-1">
                <p class="text-sm font-medium {{ $i === 0 ? 'text-brand-400' : '' }}">{{ $d['day'] }}</p>
                <p class="text-[10px] text-surface-300">{{ $d['deliveries'] }} deliveries · {{ $d['hours'] }}</p>
            </div>
            <span class="text-sm font-bold text-emerald-400">EGP {{ $d['earned'] }}</span>
        </div>
        @endforeach
    </div>

    <div class="bg-surface-800 border border-white/10 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold">Payout Schedule</h3>
            <span class="text-xs text-emerald-400 font-medium">Weekly</span>
        </div>
        <p class="text-xs text-surface-300">Next payout: <span class="text-white font-medium">Tuesday, Apr 29</span> via Paymob</p>
        <div class="mt-3 h-2 bg-surface-900 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full" style="width: 72%"></div></div>
        <p class="text-[10px] text-surface-300 mt-1">EGP 390 / EGP 542.50 cleared</p>
    </div>
</div>
@endsection
