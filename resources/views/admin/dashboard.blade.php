@extends('layouts.admin')
@section('page-title', 'Platform Dashboard')
@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php $stats = [
        ['label' => 'Total Orders Today', 'value' => '284', 'change' => '+14%', 'up' => true, 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'color' => 'from-brand-500 to-brand-600'],
        ['label' => 'Active Riders', 'value' => '42', 'change' => '', 'up' => true, 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'color' => 'from-emerald-500 to-emerald-600'],
        ['label' => 'Platform Revenue', 'value' => '$4,230', 'change' => '+22%', 'up' => true, 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1', 'color' => 'from-violet-500 to-violet-600'],
        ['label' => 'Avg Delivery Time', 'value' => '24 min', 'change' => '-3 min', 'up' => true, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'from-blue-500 to-blue-600'],
    ]; @endphp
    @foreach($stats as $s)
    <div class="bg-surface-900 rounded-2xl border border-white/5 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $s['color'] }} flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $s['icon'] }}"/></svg></div>
            @if($s['change'])<span class="text-xs font-semibold text-emerald-400">{{ $s['change'] }}</span>@endif
        </div>
        <p class="text-2xl font-display font-bold">{{ $s['value'] }}</p>
        <p class="text-xs text-surface-300 mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Live feed --}}
    <div class="lg:col-span-2 bg-surface-900 rounded-2xl border border-white/5 p-6">
        <h3 class="text-sm font-display font-bold mb-4 flex items-center gap-2">
            Live Order Feed
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse-soft"></span><span class="text-[10px] text-emerald-400">LIVE</span></span>
        </h3>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            @php $liveOrders = [
                ['id' => 'ORD-284', 'restaurant' => 'Pizza Republic', 'customer' => 'Youssef A.', 'status' => 'placed', 'color' => 'text-brand-400', 'time' => 'Just now', 'total' => '24.99'],
                ['id' => 'ORD-283', 'restaurant' => 'Sushi Zen', 'customer' => 'Nour M.', 'status' => 'confirmed', 'color' => 'text-blue-400', 'time' => '1m ago', 'total' => '32.50'],
                ['id' => 'ORD-282', 'restaurant' => 'Shawarma Station', 'customer' => 'Ahmed H.', 'status' => 'preparing', 'color' => 'text-amber-400', 'time' => '4m ago', 'total' => '17.47'],
                ['id' => 'ORD-281', 'restaurant' => 'Burger District', 'customer' => 'Sara K.', 'status' => 'on_the_way', 'color' => 'text-violet-400', 'time' => '8m ago', 'total' => '19.98'],
                ['id' => 'ORD-280', 'restaurant' => 'The Green Bowl', 'customer' => 'Omar N.', 'status' => 'delivered', 'color' => 'text-emerald-400', 'time' => '12m ago', 'total' => '14.99'],
                ['id' => 'ORD-279', 'restaurant' => 'Noodle House', 'customer' => 'Layla F.', 'status' => 'delivered', 'color' => 'text-emerald-400', 'time' => '15m ago', 'total' => '22.49'],
            ]; @endphp
            @foreach($liveOrders as $o)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.03] hover:bg-white/[0.06] transition-colors">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-surface-200">{{ $o['id'] }}</span>
                        <span class="text-[10px] {{ $o['color'] }} font-semibold uppercase">{{ str_replace('_', ' ', $o['status']) }}</span>
                    </div>
                    <p class="text-xs text-surface-300 mt-0.5 truncate">{{ $o['restaurant'] }} → {{ $o['customer'] }}</p>
                </div>
                <span class="text-xs text-surface-300">{{ $o['time'] }}</span>
                <span class="text-sm font-bold">${{ $o['total'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="space-y-4">
        <div class="bg-surface-900 rounded-2xl border border-white/5 p-6">
            <h3 class="text-sm font-display font-bold mb-4">Platform Health</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between"><span class="text-xs text-surface-300">Orders/hour</span><span class="text-sm font-bold text-emerald-400">38</span></div>
                <div class="flex items-center justify-between"><span class="text-xs text-surface-300">Rider utilization</span><span class="text-sm font-bold">86%</span></div>
                <div class="flex items-center justify-between"><span class="text-xs text-surface-300">Avg wait time</span><span class="text-sm font-bold">4.2 min</span></div>
                <div class="flex items-center justify-between"><span class="text-xs text-surface-300">Cancel rate</span><span class="text-sm font-bold text-emerald-400">2.1%</span></div>
                <div class="flex items-center justify-between"><span class="text-xs text-surface-300">Surge zones active</span><span class="text-sm font-bold text-amber-400">3</span></div>
            </div>
        </div>
        <a href="{{ route('admin.control-tower') }}" class="block bg-gradient-to-br from-brand-500/20 to-brand-600/10 border border-brand-500/30 rounded-2xl p-5 hover:border-brand-500/50 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg></div>
                <div>
                    <p class="text-sm font-semibold group-hover:text-brand-400 transition-colors">Control Tower</p>
                    <p class="text-[10px] text-surface-300">Live order map & dispatch</p>
                </div>
                <svg class="w-4 h-4 text-surface-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>
</div>
@endsection
