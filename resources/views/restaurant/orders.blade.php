@extends('layouts.restaurant')
@section('page-title', 'Orders')

@section('content')
<div class="flex items-center gap-3 mb-6" data-tabs>
    <button data-tab="active" class="active px-4 py-2 text-sm font-semibold border-b-2 border-brand-500 text-brand-600">Active <span class="ml-1 px-1.5 py-0.5 bg-brand-100 text-brand-700 text-[10px] font-bold rounded-full">5</span></button>
    <button data-tab="completed" class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-surface-800">Completed</button>
    <button data-tab="cancelled" class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-surface-800">Cancelled</button>
</div>

<div data-tab-panel="active">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @php
        $activeOrders = [
            ['id' => 'ORD-001', 'customer' => 'Ahmed Hassan', 'items' => ['2× Chicken Shawarma', '1× Garlic Fries'], 'total' => '17.47', 'status' => 'placed', 'label' => 'New Order', 'color' => 'bg-brand-500', 'time' => '2m ago'],
            ['id' => 'ORD-002', 'customer' => 'Sara Mohamed', 'items' => ['1× Mixed Grill', '1× Ayran'], 'total' => '16.98', 'status' => 'confirmed', 'label' => 'Confirmed', 'color' => 'bg-blue-500', 'time' => '5m ago'],
            ['id' => 'ORD-003', 'customer' => 'Omar Khalil', 'items' => ['3× Falafel Wrap', '2× Lemonade'], 'total' => '22.45', 'status' => 'preparing', 'label' => 'Preparing', 'color' => 'bg-amber-500', 'time' => '12m ago'],
            ['id' => 'ORD-004', 'customer' => 'Nour Ali', 'items' => ['1× Shawarma Platter', '1× Hummus'], 'total' => '15.98', 'status' => 'ready', 'label' => 'Ready for Pickup', 'color' => 'bg-emerald-500', 'time' => '18m ago'],
            ['id' => 'ORD-005', 'customer' => 'Youssef Adel', 'items' => ['2× Kebab Platter'], 'total' => '27.98', 'status' => 'on_the_way', 'label' => 'Out for Delivery', 'color' => 'bg-violet-500', 'time' => '25m ago'],
        ];
        @endphp
        @foreach($activeOrders as $order)
        <div class="bg-white rounded-2xl border border-surface-200/50 overflow-hidden">
            <div class="px-5 py-3 {{ $order['color'] }} text-white flex items-center justify-between">
                <span class="text-xs font-bold">{{ $order['label'] }}</span>
                <span class="text-[10px] opacity-80">{{ $order['time'] }}</span>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-bold">{{ $order['id'] }}</p>
                    <p class="text-sm font-bold text-brand-600">${{ $order['total'] }}</p>
                </div>
                <p class="text-xs text-surface-300 mb-2">{{ $order['customer'] }}</p>
                <div class="space-y-1 mb-4">
                    @foreach($order['items'] as $item)
                    <p class="text-xs text-surface-800/60">• {{ $item }}</p>
                    @endforeach
                </div>
                @if($order['status'] === 'placed')
                <div class="flex gap-2">
                    <button onclick="Toast.show('Accepted!','',  'success')" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600">Accept</button>
                    <button class="flex-1 py-2 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50">Reject</button>
                </div>
                @elseif($order['status'] === 'confirmed')
                <button onclick="Toast.show('Preparing now!','', 'info')" class="w-full py-2 text-xs font-bold text-white bg-amber-500 rounded-lg hover:bg-amber-600">Start Preparing</button>
                @elseif($order['status'] === 'preparing')
                <button onclick="Toast.show('Marked ready!','Rider notified', 'success')" class="w-full py-2 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600">Mark as Ready</button>
                @elseif($order['status'] === 'ready')
                <div class="flex items-center gap-2 text-xs text-emerald-600 font-medium"><div class="animate-pulse-soft w-2 h-2 rounded-full bg-emerald-500"></div>Waiting for rider pickup</div>
                @else
                <div class="flex items-center gap-2 text-xs text-violet-600 font-medium"><div class="animate-pulse-soft w-2 h-2 rounded-full bg-violet-500"></div>Rider: Mohamed A. · ETA 8 min</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<div data-tab-panel="completed" class="hidden">
    <p class="text-sm text-surface-300 py-12 text-center">Completed orders will appear here.</p>
</div>
<div data-tab-panel="cancelled" class="hidden">
    <p class="text-sm text-surface-300 py-12 text-center">Cancelled orders will appear here.</p>
</div>
@endsection
