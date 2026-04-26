@extends('layouts.app')
@section('title', 'My Orders — DeliverEats')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-display font-bold mb-6">My Orders</h1>

    @php
    $orders = [
        ['id' => 'ORD-20260426-001', 'restaurant' => 'Shawarma Station', 'emoji' => '🌯', 'gradient' => 'from-amber-400 to-orange-500', 'items' => '2× Chicken Shawarma, 1× Garlic Fries, 2× Lemonade', 'total' => '32.28', 'status' => 'on_the_way', 'status_label' => 'On the Way', 'status_color' => 'text-blue-600 bg-blue-50', 'date' => 'Today, 9:42 PM', 'can_track' => true],
        ['id' => 'ORD-20260425-014', 'restaurant' => 'Pizza Republic', 'emoji' => '🍕', 'gradient' => 'from-red-400 to-rose-500', 'items' => '1× Margherita Pizza (L), 1× Garlic Bread', 'total' => '18.47', 'status' => 'delivered', 'status_label' => 'Delivered', 'status_color' => 'text-emerald-600 bg-emerald-50', 'date' => 'Yesterday, 8:15 PM', 'can_track' => false],
        ['id' => 'ORD-20260424-009', 'restaurant' => 'Sushi Zen', 'emoji' => '🍣', 'gradient' => 'from-cyan-400 to-blue-500', 'items' => '1× Dragon Roll, 1× Salmon Nigiri Set, 1× Miso Soup', 'total' => '27.95', 'status' => 'delivered', 'status_label' => 'Delivered', 'status_color' => 'text-emerald-600 bg-emerald-50', 'date' => 'Apr 24, 7:30 PM', 'can_track' => false],
        ['id' => 'ORD-20260422-003', 'restaurant' => 'Burger District', 'emoji' => '🍔', 'gradient' => 'from-yellow-400 to-amber-500', 'items' => '2× Smash Burger, 1× Loaded Fries, 2× Milkshake', 'total' => '24.96', 'status' => 'delivered', 'status_label' => 'Delivered', 'status_color' => 'text-emerald-600 bg-emerald-50', 'date' => 'Apr 22, 6:45 PM', 'can_track' => false],
        ['id' => 'ORD-20260420-011', 'restaurant' => 'The Green Bowl', 'emoji' => '🥗', 'gradient' => 'from-emerald-400 to-green-500', 'items' => '1× Açaí Bowl, 1× Green Smoothie', 'total' => '14.98', 'status' => 'cancelled', 'status_label' => 'Cancelled', 'status_color' => 'text-red-600 bg-red-50', 'date' => 'Apr 20, 1:20 PM', 'can_track' => false],
    ];
    @endphp

    <div class="space-y-4 stagger-children">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl border border-surface-200/50 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $order['gradient'] }} flex items-center justify-center text-xl flex-shrink-0">{{ $order['emoji'] }}</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-semibold">{{ $order['restaurant'] }}</h3>
                            <p class="text-xs text-surface-300 mt-0.5">{{ $order['id'] }} · {{ $order['date'] }}</p>
                        </div>
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full {{ $order['status_color'] }}">{{ $order['status_label'] }}</span>
                    </div>
                    <p class="text-xs text-surface-800/60 mt-2 truncate">{{ $order['items'] }}</p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-surface-100">
                        <span class="text-sm font-bold">${{ $order['total'] }}</span>
                        <div class="flex items-center gap-2">
                            @if($order['can_track'])
                            <a href="{{ route('customer.orders.track', ['id' => $order['id']]) }}" class="px-4 py-1.5 text-xs font-semibold text-white bg-brand-500 rounded-lg hover:bg-brand-600 transition-colors">Track Order</a>
                            @elseif($order['status'] === 'delivered')
                            <a href="{{ route('customer.orders.review', ['id' => $order['id']]) }}" class="px-4 py-1.5 text-xs font-semibold text-brand-600 border border-brand-300 bg-brand-50 rounded-lg hover:bg-brand-100 transition-colors">Leave Review</a>
                            <button class="px-4 py-1.5 text-xs font-medium text-surface-800/60 border border-surface-200 rounded-lg hover:bg-surface-50 transition-colors">Reorder</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
