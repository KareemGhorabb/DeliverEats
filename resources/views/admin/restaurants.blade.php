@extends('layouts.admin')
@section('page-title', 'Restaurant Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <input type="text" placeholder="Search restaurants..." class="px-4 py-2.5 rounded-xl bg-surface-900 border border-white/10 text-sm text-white placeholder:text-surface-300 focus:outline-none focus:ring-2 focus:ring-brand-500/30 w-64">
    <button class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Restaurant
    </button>
</div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 stagger-children">
    @php $restaurants = [
        ['name' => 'Shawarma Station', 'cuisine' => 'Middle Eastern', 'emoji' => '🌯', 'gradient' => 'from-amber-400 to-orange-500', 'rating' => '4.8', 'orders' => '2,847', 'revenue' => '12,847', 'commission' => '15%', 'status' => 'active'],
        ['name' => 'Pizza Republic', 'cuisine' => 'Italian', 'emoji' => '🍕', 'gradient' => 'from-red-400 to-rose-500', 'rating' => '4.6', 'orders' => '5,120', 'revenue' => '28,900', 'commission' => '12%', 'status' => 'active'],
        ['name' => 'Sushi Zen', 'cuisine' => 'Japanese', 'emoji' => '🍣', 'gradient' => 'from-cyan-400 to-blue-500', 'rating' => '4.9', 'orders' => '1,230', 'revenue' => '9,450', 'commission' => '18%', 'status' => 'active'],
        ['name' => 'The Green Bowl', 'cuisine' => 'Healthy', 'emoji' => '🥗', 'gradient' => 'from-emerald-400 to-green-500', 'rating' => '4.7', 'orders' => '3,200', 'revenue' => '14,200', 'commission' => '15%', 'status' => 'active'],
        ['name' => 'Burger District', 'cuisine' => 'American', 'emoji' => '🍔', 'gradient' => 'from-yellow-400 to-amber-500', 'rating' => '4.5', 'orders' => '7,800', 'revenue' => '32,500', 'commission' => '10%', 'status' => 'active'],
        ['name' => 'Noodle House', 'cuisine' => 'Asian Fusion', 'emoji' => '🍜', 'gradient' => 'from-violet-400 to-purple-500', 'rating' => '4.7', 'orders' => '2,900', 'revenue' => '11,800', 'commission' => '15%', 'status' => 'pending'],
    ]; @endphp
    @foreach($restaurants as $r)
    <div class="bg-surface-900 rounded-2xl border border-white/5 overflow-hidden hover:border-white/10 transition-colors">
        <div class="h-20 bg-gradient-to-br {{ $r['gradient'] }} relative"><div class="absolute inset-0 flex items-center justify-center text-5xl opacity-20">{{ $r['emoji'] }}</div>
            @if($r['status'] === 'pending')
            <div class="absolute top-2 right-2 px-2 py-0.5 bg-amber-500 text-white text-[10px] font-bold rounded">PENDING REVIEW</div>
            @endif
        </div>
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div><h3 class="text-sm font-bold">{{ $r['name'] }}</h3><p class="text-xs text-surface-300">{{ $r['cuisine'] }}</p></div>
                <div class="flex items-center gap-1"><svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><span class="text-xs font-bold">{{ $r['rating'] }}</span></div>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center text-xs mb-4">
                <div class="bg-white/[0.03] rounded-lg py-2"><p class="text-surface-300">Orders</p><p class="font-bold mt-0.5">{{ $r['orders'] }}</p></div>
                <div class="bg-white/[0.03] rounded-lg py-2"><p class="text-surface-300">Revenue</p><p class="font-bold mt-0.5 text-emerald-400">${{ $r['revenue'] }}</p></div>
                <div class="bg-white/[0.03] rounded-lg py-2"><p class="text-surface-300">Commission</p><p class="font-bold mt-0.5">{{ $r['commission'] }}</p></div>
            </div>
            <div class="flex gap-2">
                <button class="flex-1 py-2 text-xs font-medium text-surface-200 border border-white/10 rounded-lg hover:bg-white/5">View Details</button>
                @if($r['status'] === 'pending')
                <button class="flex-1 py-2 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600">Approve</button>
                @else
                <button class="flex-1 py-2 text-xs font-medium text-brand-400 border border-brand-500/30 rounded-lg hover:bg-brand-500/10">Edit</button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
