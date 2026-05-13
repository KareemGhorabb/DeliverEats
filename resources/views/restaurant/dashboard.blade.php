@extends('layouts.restaurant')
@section('page-title', 'Dashboard')

@section('content')
{{-- Greeting --}}
<div class="mb-8">
    <h2 class="text-xl font-display font-bold" id="greeting-title">Good evening 👋</h2>
    <p class="text-sm text-surface-300 mt-1">Here's what's happening with your restaurant today.</p>
</div>

{{-- Stats grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center"><svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
        </div>
        <p class="text-2xl font-display font-bold" id="stat-orders">0</p>
        <p class="text-xs text-surface-300 mt-0.5">Today's Orders</p>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
        <p class="text-2xl font-display font-bold">EGP <span id="stat-revenue">0.00</span></p>
        <p class="text-xs text-surface-300 mt-0.5">Today's Revenue</p>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
        </div>
        <p class="text-2xl font-display font-bold" id="stat-rating">4.8</p>
        <p class="text-xs text-surface-300 mt-0.5">Average Rating</p>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
        <p class="text-2xl font-display font-bold"><span id="stat-prep">18</span> min</p>
        <p class="text-xs text-surface-300 mt-0.5">Avg Prep Time</p>
    </div>
</div>

<div class="grid lg:grid-cols-5 gap-6">
    {{-- Pending orders --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6 h-full flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-display font-bold flex items-center gap-2">
                    Action Required
                </h3>
                <a href="{{ route('restaurant.orders') }}" class="text-xs text-brand-600 font-semibold hover:text-brand-700">View All</a>
            </div>
            
            <div id="loading-orders" class="flex justify-center py-8">
                <svg class="w-6 h-6 text-brand-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
            
            <div id="empty-orders" class="hidden text-center py-8 text-sm text-surface-400">
                No orders need attention right now.
            </div>

            <div id="orders-list" class="space-y-3 flex-1 overflow-y-auto pr-2 hidden">
                {{-- Populated via JS --}}
            </div>
        </div>
    </div>

    {{-- Revenue chart placeholder --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6 h-full">
            <h3 class="text-sm font-display font-bold mb-5">This Week's Revenue</h3>
            <div class="flex items-end gap-2 h-40">
                @php $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']; @endphp
                @foreach($days as $i => $day)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full rounded-lg bg-surface-200 transition-all" style="height: {{ rand(20, 90) }}%"></div>
                    <span class="text-[10px] text-surface-300 font-medium">{{ $day }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        const user = JSON.parse(localStorage.getItem('auth_user'));
        if (user) document.getElementById('greeting-title').textContent = `Good evening, ${user.name} 👋`;
        
        loadDashboard();
        setInterval(loadDashboard, 15000); // 15s refresh
    });

    async function loadDashboard() {
        try {
            // Fetch Orders
            const res = await fetch('/api/v1/orders', { headers });
            const json = await res.json();
            
            document.getElementById('loading-orders').classList.add('hidden');
            const list = document.getElementById('orders-list');
            const empty = document.getElementById('empty-orders');
            
            if (json.success && json.data) {
                // Handle case where data is wrapped in pagination data object
                const ordersArray = Array.isArray(json.data) ? json.data : (json.data.data || []);
                
                // Filter for action required (pending, accepted, preparing)
                const actionable = ordersArray.filter(o => {
                    const s = o.status.value || o.status;
                    return ['pending', 'accepted', 'preparing'].includes(s);
                });
                
                // Update stats
                document.getElementById('stat-orders').textContent = ordersArray.length;
                document.getElementById('stat-revenue').textContent = ordersArray.reduce((sum, o) => sum + parseFloat(o.total || 0), 0).toFixed(2);
                
                if (actionable.length === 0) {
                    list.classList.add('hidden');
                    empty.classList.remove('hidden');
                } else {
                    empty.classList.add('hidden');
                    list.innerHTML = actionable.map(o => {
                        const s = o.status.value || o.status;
                        const items = o.items ? o.items.map(i => `${i.quantity}× ${i.menu_item?.name || 'Item'}`).join(', ') : '';
                        
                        let buttons = '';
                        if (s === 'pending') {
                            buttons = `
                            <button onclick="updateStatus(${o.id}, 'accepted')" class="px-4 py-1.5 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 transition-colors">Accept</button>
                            <button onclick="updateStatus(${o.id}, 'cancelled')" class="px-4 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">Reject</button>`;
                        } else if (s === 'accepted') {
                            buttons = `<button onclick="updateStatus(${o.id}, 'preparing')" class="px-4 py-1.5 text-xs font-bold text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition-colors">Start Prep</button>`;
                        } else if (s === 'preparing') {
                            buttons = `<button onclick="updateStatus(${o.id}, 'ready_for_pickup')" class="px-4 py-1.5 text-xs font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">Ready</button>`;
                        }
                        
                        return `
                        <div class="flex items-start gap-4 p-4 rounded-xl border ${s === 'pending' ? 'border-brand-200 bg-brand-50/30' : 'border-surface-200/50'}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold">ORD-${o.id}</p>
                                    ${s === 'pending' ? '<span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse-soft"></span>' : ''}
                                </div>
                                <p class="text-xs text-surface-400 mt-0.5">${o.user?.name || 'Customer'}</p>
                                <p class="text-xs text-surface-500 mt-1 truncate">${items}</p>
                            </div>
                            <div class="flex flex-col gap-2">${buttons}</div>
                        </div>`;
                    }).join('');
                    list.classList.remove('hidden');
                }
            }
        } catch (e) {
            console.error(e);
        }
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
                loadDashboard();
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
