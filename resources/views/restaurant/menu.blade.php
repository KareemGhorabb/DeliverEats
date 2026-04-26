@extends('layouts.restaurant')
@section('page-title', 'Menu Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-surface-300">Manage your categories, items, and availability</p>
    </div>
    <button data-modal-open="modal-add-item" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 shadow-md shadow-brand-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Item
    </button>
</div>

@php
$categories = [
    ['name' => 'Popular', 'items' => [
        ['name' => 'Classic Chicken Shawarma', 'price' => '6.99', 'available' => true, 'orders' => 142],
        ['name' => 'Mixed Grill Platter', 'price' => '14.99', 'available' => true, 'orders' => 89],
        ['name' => 'Chicken Fattoush Bowl', 'price' => '9.49', 'available' => true, 'orders' => 67],
    ]],
    ['name' => 'Wraps & Sandwiches', 'items' => [
        ['name' => 'Beef Shawarma Wrap', 'price' => '7.99', 'available' => true, 'orders' => 95],
        ['name' => 'Falafel Wrap', 'price' => '5.49', 'available' => true, 'orders' => 73],
        ['name' => 'Halloumi & Zaatar Wrap', 'price' => '6.49', 'available' => false, 'orders' => 34],
    ]],
    ['name' => 'Platters', 'items' => [
        ['name' => 'Shawarma Platter', 'price' => '11.99', 'available' => true, 'orders' => 56],
        ['name' => 'Kebab Platter', 'price' => '13.99', 'available' => true, 'orders' => 41],
    ]],
    ['name' => 'Sides & Extras', 'items' => [
        ['name' => 'Hummus', 'price' => '3.99', 'available' => true, 'orders' => 120],
        ['name' => 'Garlic Fries', 'price' => '3.49', 'available' => true, 'orders' => 98],
        ['name' => 'Fattoush Salad', 'price' => '4.99', 'available' => true, 'orders' => 45],
    ]],
    ['name' => 'Drinks', 'items' => [
        ['name' => 'Fresh Lemonade w/ Mint', 'price' => '2.99', 'available' => true, 'orders' => 88],
        ['name' => 'Ayran', 'price' => '1.99', 'available' => true, 'orders' => 52],
    ]],
];
@endphp

<div class="space-y-6">
    @foreach($categories as $cat)
    <div class="bg-white rounded-2xl border border-surface-200/50 overflow-hidden">
        <div class="px-6 py-4 bg-surface-50 border-b border-surface-100 flex items-center justify-between">
            <h3 class="text-sm font-display font-bold flex items-center gap-2">
                {{ $cat['name'] }}
                <span class="text-xs font-normal text-surface-300 bg-white px-2 py-0.5 rounded-full border border-surface-200/50">{{ count($cat['items']) }}</span>
            </h3>
            <div class="flex items-center gap-2">
                <button class="text-xs text-brand-600 font-semibold hover:text-brand-700">+ Add Item</button>
                <button class="text-xs text-surface-300 hover:text-surface-800">Edit Category</button>
            </div>
        </div>
        <div class="divide-y divide-surface-100">
            @foreach($cat['items'] as $item)
            <div class="px-6 py-4 flex items-center gap-4 hover:bg-surface-50/50 transition-colors">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold">{{ $item['name'] }}</p>
                        @unless($item['available'])
                        <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[10px] font-bold rounded-full">Unavailable</span>
                        @endunless
                    </div>
                    <p class="text-xs text-surface-300 mt-0.5">{{ $item['orders'] }} orders this month</p>
                </div>
                <p class="text-sm font-bold w-16 text-right">${{ $item['price'] }}</p>
                {{-- Availability toggle --}}
                <button class="relative w-10 h-6 rounded-full transition-colors {{ $item['available'] ? 'bg-emerald-500' : 'bg-surface-300' }}" onclick="this.classList.toggle('bg-emerald-500'); this.classList.toggle('bg-surface-300'); this.querySelector('span').classList.toggle('translate-x-4'); this.querySelector('span').classList.toggle('translate-x-0.5')">
                    <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform {{ $item['available'] ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                </button>
                <div class="flex items-center gap-1">
                    <button class="p-2 rounded-lg hover:bg-surface-100 text-surface-300 hover:text-surface-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button class="p-2 rounded-lg hover:bg-red-50 text-surface-300 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

{{-- Add Item Modal --}}
<div id="modal-add-item" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/40 transition-opacity"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transition-all transform">
        <div class="p-6 border-b border-surface-100 flex items-center justify-between">
            <h3 class="text-lg font-display font-bold">Add Menu Item</h3>
            <button data-modal-close class="p-2 -mr-2 rounded-lg hover:bg-surface-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-6 space-y-4">
            <div><label class="text-sm font-medium mb-1 block">Item Name</label><input type="text" placeholder="e.g. Chicken Shawarma" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
            <div><label class="text-sm font-medium mb-1 block">Category</label><select class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"><option>Popular</option><option>Wraps & Sandwiches</option><option>Platters</option><option>Sides & Extras</option><option>Drinks</option></select></div>
            <div><label class="text-sm font-medium mb-1 block">Description</label><textarea rows="2" placeholder="Brief description..." class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 resize-none"></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-sm font-medium mb-1 block">Base Price ($)</label><input type="number" step="0.01" placeholder="0.00" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
                <div><label class="text-sm font-medium mb-1 block">Prep Time (min)</label><input type="number" placeholder="15" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
            </div>
            <div><label class="text-sm font-medium mb-1 block">Image</label><div class="border-2 border-dashed border-surface-200 rounded-xl p-6 text-center hover:border-brand-300 transition-colors cursor-pointer"><svg class="w-8 h-8 mx-auto text-surface-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-xs text-surface-300">Click to upload or drag & drop</p></div></div>
            <h4 class="text-sm font-semibold pt-2">Variants <span class="text-xs text-surface-300 font-normal">(optional)</span></h4>
            <div class="flex gap-2"><input type="text" placeholder="Variant name (e.g. Large)" class="flex-1 px-3 py-2 rounded-lg bg-surface-50 border border-surface-200 text-sm"><input type="number" step="0.01" placeholder="+$" class="w-24 px-3 py-2 rounded-lg bg-surface-50 border border-surface-200 text-sm"><button class="px-3 py-2 text-sm font-medium text-brand-600 border border-brand-300 rounded-lg hover:bg-brand-50">Add</button></div>
        </div>
        <div class="p-6 border-t border-surface-100 flex justify-end gap-3">
            <button data-modal-close class="px-5 py-2.5 text-sm font-medium text-surface-800/70 hover:bg-surface-50 rounded-xl transition-colors">Cancel</button>
            <button onclick="Toast.show('Item Added!', 'Menu updated successfully', 'success'); closeModal('modal-add-item')" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors">Save Item</button>
        </div>
    </div>
</div>
@endsection
