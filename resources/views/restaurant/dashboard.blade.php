@extends('layouts.restaurant')
@section('page-title', 'Dashboard')

@section('content')
{{-- Greeting --}}
<div class="mb-8">
    <h2 class="text-xl font-display font-bold">Good evening, Shawarma Station 👋</h2>
    <p class="text-sm text-surface-300 mt-1">Here's what's happening with your restaurant today.</p>
</div>

{{-- Stats grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center"><svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+12%</span>
        </div>
        <p class="text-2xl font-display font-bold stat-number" data-count-to="34">0</p>
        <p class="text-xs text-surface-300 mt-0.5">Today's Orders</p>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+8%</span>
        </div>
        <p class="text-2xl font-display font-bold">$<span class="stat-number" data-count-to="847" data-duration="1500">0</span></p>
        <p class="text-xs text-surface-300 mt-0.5">Today's Revenue</p>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
        </div>
        <p class="text-2xl font-display font-bold">4.8</p>
        <p class="text-xs text-surface-300 mt-0.5">Average Rating</p>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
        <p class="text-2xl font-display font-bold"><span class="stat-number" data-count-to="18">0</span> min</p>
        <p class="text-xs text-surface-300 mt-0.5">Avg Prep Time</p>
    </div>
</div>

<div class="grid lg:grid-cols-5 gap-6">
    {{-- Pending orders --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-display font-bold flex items-center gap-2">
                    Pending Orders
                    <span class="px-2 py-0.5 bg-brand-100 text-brand-700 text-[10px] font-bold rounded-full">3 new</span>
                </h3>
                <a href="{{ route('restaurant.orders') }}" class="text-xs text-brand-600 font-semibold hover:text-brand-700">View All</a>
            </div>
            <div class="space-y-3">
                @php
                $pending = [
                    ['id' => 'ORD-001', 'customer' => 'Ahmed H.', 'items' => '2× Chicken Shawarma, 1× Garlic Fries', 'total' => '17.47', 'time' => '2 min ago', 'status' => 'new'],
                    ['id' => 'ORD-002', 'customer' => 'Sara M.', 'items' => '1× Mixed Grill Platter, 1× Ayran', 'total' => '16.98', 'time' => '5 min ago', 'status' => 'new'],
                    ['id' => 'ORD-003', 'customer' => 'Omar K.', 'items' => '3× Falafel Wrap, 2× Lemonade', 'total' => '22.45', 'time' => '8 min ago', 'status' => 'preparing'],
                ];
                @endphp
                @foreach($pending as $order)
                <div class="flex items-start gap-4 p-4 rounded-xl border {{ $order['status'] === 'new' ? 'border-brand-200 bg-brand-50/30' : 'border-surface-200/50' }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold">{{ $order['id'] }}</p>
                            @if($order['status'] === 'new')
                            <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse-soft"></span>
                            @endif
                        </div>
                        <p class="text-xs text-surface-300 mt-0.5">{{ $order['customer'] }} · {{ $order['time'] }}</p>
                        <p class="text-xs text-surface-800/60 mt-1 truncate">{{ $order['items'] }}</p>
                        <p class="text-sm font-bold mt-1">${{ $order['total'] }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        @if($order['status'] === 'new')
                        <button onclick="Toast.show('Order Accepted!', '{{ $order['id'] }} confirmed', 'success')" class="px-4 py-1.5 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 transition-colors">Accept</button>
                        <button class="px-4 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">Reject</button>
                        @else
                        <button onclick="Toast.show('Marked Ready!', 'Rider will be notified', 'success')" class="px-4 py-1.5 text-xs font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">Ready</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Revenue chart --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
            <h3 class="text-sm font-display font-bold mb-5">This Week's Revenue</h3>
            <div class="flex items-end gap-2 h-40">
                @php $bars = [40,65,55,80,70,90,60]; $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']; @endphp
                @foreach($bars as $i => $h)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full rounded-lg {{ $i === 5 ? 'bg-gradient-to-t from-brand-500 to-brand-400' : 'bg-surface-200' }} transition-all" style="height: {{ $h }}%"></div>
                    <span class="text-[10px] text-surface-300 font-medium">{{ $days[$i] }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-surface-100 flex items-center justify-between text-sm">
                <span class="text-surface-300">This week total</span>
                <span class="font-display font-bold">$4,832</span>
            </div>
        </div>

        {{-- Popular items --}}
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6 mt-4">
            <h3 class="text-sm font-display font-bold mb-4">Top Items Today</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-surface-300 w-5">1</span>
                    <div class="flex-1"><p class="text-sm font-medium">Chicken Shawarma</p></div>
                    <span class="text-xs text-surface-300">42 sold</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-surface-300 w-5">2</span>
                    <div class="flex-1"><p class="text-sm font-medium">Mixed Grill Platter</p></div>
                    <span class="text-xs text-surface-300">28 sold</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-surface-300 w-5">3</span>
                    <div class="flex-1"><p class="text-sm font-medium">Falafel Wrap</p></div>
                    <span class="text-xs text-surface-300">19 sold</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
