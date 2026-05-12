@extends('layouts.admin')
@section('page-title', 'Control Tower — Live Dispatch')
@section('content')

<div class="grid lg:grid-cols-4 gap-6 h-[calc(100vh-160px)]">
    {{-- Sidebar: Active Stats & List --}}
    <div class="lg:col-span-1 space-y-6 flex flex-col overflow-hidden">
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white dark:bg-white/5 border border-surface-200 dark:border-white/10 p-4 rounded-2xl shadow-sm">
                <p class="text-[10px] text-surface-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Active Orders</p>
                <p class="text-2xl font-display font-bold text-surface-900 dark:text-white" id="stat-active-count">0</p>
            </div>
            <div class="bg-white dark:bg-white/5 border border-surface-200 dark:border-white/10 p-4 rounded-2xl shadow-sm">
                <p class="text-[10px] text-surface-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Riders Online</p>
                <p class="text-2xl font-display font-bold text-emerald-500" id="stat-riders-count">0</p>
            </div>
        </div>

        <div class="bg-white dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-2xl shadow-sm flex-1 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-surface-100 dark:border-white/5 flex items-center justify-between">
                <h3 class="text-sm font-bold text-surface-900 dark:text-white">Active Dispatch</h3>
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
            </div>
            <div id="active-orders-list" class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                {{-- Populated via JS --}}
                <div class="animate-pulse space-y-3">
                    <div class="h-20 bg-surface-50 dark:bg-white/5 rounded-xl"></div>
                    <div class="h-20 bg-surface-50 dark:bg-white/5 rounded-xl"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main: Live Map --}}
    <div class="lg:col-span-3 relative bg-white dark:bg-neutral-900 border border-surface-200 dark:border-white/10 rounded-3xl overflow-hidden shadow-xl flex flex-col" style="min-height: 500px;">
        <livewire:admin.control-tower-map />
        
        <div class="absolute bottom-6 left-6 z-10 bg-white/90 dark:bg-neutral-800/90 backdrop-blur-md border border-surface-200 dark:border-white/10 p-3 rounded-2xl shadow-2xl flex gap-6 text-[10px] font-bold uppercase tracking-widest text-surface-500 dark:text-gray-400">
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-brand-500 shadow-lg shadow-brand-500/50"></span> Active Order</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50"></span> Online Rider</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500 shadow-lg shadow-blue-500/50"></span> Restaurant</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Keep sidebar updating via the same API for the active dispatch list
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

    async function loadData() {
        try {
            const res = await fetch('/api/admin/control-tower', { headers });
            const json = await res.json();
            if (json.success) {
                renderList(json.data.active_orders);
                document.getElementById('stat-active-count').textContent = json.data.active_orders.length;
                const uniqueRiders = [...new Set(json.data.active_orders.filter(o => o.rider).map(o => o.rider.id))];
                document.getElementById('stat-riders-count').textContent = uniqueRiders.length;
            }
        } catch (e) { console.error('Tower sync failed', e); }
    }

    function renderList(orders) {
        const list = document.getElementById('active-orders-list');
        if (orders.length === 0) {
            list.innerHTML = '<p class="text-center py-10 text-xs text-surface-400">No active dispatch</p>';
            return;
        }
        list.innerHTML = orders.map(o => `
            <div class="p-3 rounded-xl border border-surface-100 dark:border-white/5 bg-surface-50/50 dark:bg-white/5 hover:border-brand-200 dark:hover:border-brand-500/30 transition-all group cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold text-brand-600 dark:text-brand-400">#ORD-${o.id}</span>
                    <span class="px-1.5 py-0.5 bg-white dark:bg-neutral-800 text-[9px] font-black rounded border border-surface-200 dark:border-white/10 uppercase">${o.status.replace(/_/g, ' ')}</span>
                </div>
                <p class="text-xs font-bold text-surface-900 dark:text-white truncate">${o.restaurant?.name || 'Restaurant'}</p>
                <div class="flex items-center gap-2 mt-2">
                    <div class="w-1 h-1 rounded-full bg-surface-300"></div>
                    <p class="text-[10px] text-surface-500 dark:text-gray-400 truncate">Rider: ${o.rider?.name || 'Unassigned'}</p>
                </div>
            </div>
        `).join('');
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadData();
        setInterval(loadData, 10000);
    });
</script>
@endpush
@endsection