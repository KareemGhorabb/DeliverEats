@extends('layouts.restaurant')
@section('page-title', 'Orders')

@section('content')
<div class="flex items-center gap-3 mb-6" data-tabs>
    <button data-tab="active" class="active px-4 py-2 text-sm font-semibold border-b-2 border-brand-500 text-brand-600">Active <span id="badge-active" class="ml-1 px-1.5 py-0.5 bg-brand-100 text-brand-700 text-[10px] font-bold rounded-full">0</span></button>
    <button data-tab="completed" class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-surface-800">Completed <span id="badge-completed" class="ml-1 px-1.5 py-0.5 bg-surface-100 text-surface-600 text-[10px] font-bold rounded-full">0</span></button>
    <button data-tab="cancelled" class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-surface-800">Cancelled <span id="badge-cancelled" class="ml-1 px-1.5 py-0.5 bg-surface-100 text-surface-600 text-[10px] font-bold rounded-full">0</span></button>
</div>

<div id="loading-orders" class="flex justify-center py-12">
    <svg class="w-8 h-8 text-brand-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div data-tab-panel="active" class="hidden">
    <div id="grid-active" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    <p id="empty-active" class="hidden text-sm text-surface-300 py-12 text-center">No active orders right now.</p>
</div>

<div data-tab-panel="completed" class="hidden">
    <div id="grid-completed" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    <p id="empty-completed" class="hidden text-sm text-surface-300 py-12 text-center">No completed orders found.</p>
</div>

<div data-tab-panel="cancelled" class="hidden">
    <div id="grid-cancelled" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    <p id="empty-cancelled" class="hidden text-sm text-surface-300 py-12 text-center">No cancelled orders found.</p>
</div>

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadOrders();
        setInterval(loadOrders, 15000); // Poll every 15s
    });

    async function loadOrders() {
        try {
            const res = await fetch('/api/v1/orders', { headers });
            const json = await res.json();
            
            document.getElementById('loading-orders').classList.add('hidden');
            
            // Un-hide panels
            document.querySelectorAll('[data-tab-panel]').forEach(el => {
                // keep logic for tabs, only unhide the active tab container
                if (document.querySelector(`[data-tab="${el.dataset.tabPanel}"]`).classList.contains('active')) {
                    el.classList.remove('hidden');
                }
            });

            if (json.success && json.data) {
                const ordersArray = Array.isArray(json.data) ? json.data : (json.data.data || []);
                
                const active = ordersArray.filter(o => {
                    const s = o.status.value || o.status;
                    return ['pending', 'accepted', 'preparing', 'ready_for_pickup', 'picked_up'].includes(s);
                });
                const completed = ordersArray.filter(o => {
                    const s = o.status.value || o.status;
                    return ['delivered'].includes(s);
                });
                const cancelled = ordersArray.filter(o => {
                    const s = o.status.value || o.status;
                    return ['cancelled'].includes(s);
                });

                document.getElementById('badge-active').textContent = active.length;
                document.getElementById('badge-completed').textContent = completed.length;
                document.getElementById('badge-cancelled').textContent = cancelled.length;

                renderGrid('active', active);
                renderGrid('completed', completed);
                renderGrid('cancelled', cancelled);
            }
        } catch (e) {
            console.error(e);
        }
    }

    function renderGrid(tab, orders) {
        const grid = document.getElementById(`grid-${tab}`);
        const empty = document.getElementById(`empty-${tab}`);
        
        if (orders.length === 0) {
            grid.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        
        empty.classList.add('hidden');
        
        grid.innerHTML = orders.map(order => {
            const s = order.status.value || order.status;
            
            let label = 'Unknown';
            let color = 'bg-surface-300';
            let buttons = '';
            
            if (s === 'pending') {
                label = 'New Order'; color = 'bg-brand-500';
                buttons = `
                    <div class="flex gap-2">
                        <button onclick="updateStatus(${order.id}, 'accepted')" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 transition-colors">Accept</button>
                        <button onclick="updateStatus(${order.id}, 'cancelled')" class="flex-1 py-2 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">Reject</button>
                    </div>`;
            } else if (s === 'accepted') {
                label = 'Accepted'; color = 'bg-blue-500';
                buttons = `<button onclick="updateStatus(${order.id}, 'preparing')" class="w-full py-2 text-xs font-bold text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition-colors">Start Preparing</button>`;
            } else if (s === 'preparing') {
                label = 'Preparing'; color = 'bg-amber-500';
                buttons = `<button onclick="updateStatus(${order.id}, 'ready_for_pickup')" class="w-full py-2 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 transition-colors">Mark as Ready</button>`;
            } else if (s === 'ready_for_pickup') {
                label = 'Ready for Pickup'; color = 'bg-emerald-500';
                buttons = `<div class="flex items-center gap-2 text-xs text-emerald-600 font-medium"><div class="animate-pulse-soft w-2 h-2 rounded-full bg-emerald-500"></div>Waiting for rider pickup</div>`;
            } else if (s === 'picked_up') {
                label = 'Out for Delivery'; color = 'bg-violet-500';
                buttons = `<div class="flex items-center gap-2 text-xs text-violet-600 font-medium"><div class="animate-pulse-soft w-2 h-2 rounded-full bg-violet-500"></div>Rider is delivering...</div>`;
            } else if (s === 'delivered') {
                label = 'Delivered'; color = 'bg-emerald-500';
            } else if (s === 'cancelled') {
                label = 'Cancelled'; color = 'bg-red-500';
                buttons = `<div class="text-xs text-red-600 font-medium mt-1">Reason: ${order.cancellation_reason || 'Unknown'}</div>`;
            }

            const itemsStr = order.items ? order.items.map(i => `<p class="text-xs text-surface-800/60">• ${i.quantity}× ${i.menu_item?.name || 'Item'}</p>`).join('') : '';

            return `
            <div class="bg-white rounded-2xl border border-surface-200/50 overflow-hidden flex flex-col h-full">
                <div class="px-5 py-3 ${color} text-white flex items-center justify-between">
                    <span class="text-xs font-bold">${label}</span>
                    <span class="text-[10px] opacity-80">${new Date(order.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-bold">ORD-${order.id}</p>
                        <p class="text-sm font-bold text-brand-600">EGP ${parseFloat(order.total || 0).toFixed(2)}</p>
                    </div>
                    <p class="text-xs text-surface-300 mb-2">${order.user?.name || 'Customer'}</p>
                    <div class="space-y-1 mb-4 flex-1">
                        ${itemsStr}
                    </div>
                    <div class="mt-auto pt-4">
                        ${buttons}
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    window.updateStatus = async function(id, status) {
        try {
            const res = await fetch(`/api/v1/orders/${id}/status`, {
                method: 'PATCH',
                headers,
                body: JSON.stringify({ status })
            });
            const json = await res.json();
            if (json.success) {
                if (typeof Toast !== 'undefined') Toast.show('Updated', `Order marked as ${status}`, 'success');
                loadOrders();
            } else {
                if (typeof Toast !== 'undefined') Toast.show('Error', json.message, 'error');
            }
        } catch (e) {
            if (typeof Toast !== 'undefined') Toast.show('Error', 'Failed to update', 'error');
        }
    };
</script>
@endpush
@endsection
