@extends('layouts.rider')
@section('title', 'Rider Dashboard — DeliverEats')

@section('content')
<div class="px-4 py-5">
    {{-- Header with Status Toggle --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-display font-bold text-white">Dashboard</h2>
            <p class="text-xs text-gray-400 mt-0.5" id="rider-status-text">Detecting location...</p>
        </div>
        <button id="btn-toggle-online" onclick="toggleOnline()" class="px-4 py-2 rounded-full text-xs font-bold transition-all bg-white/5 text-white border border-white/10 uppercase tracking-wider">
            GO ONLINE
        </button>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-surface-800 rounded-2xl p-4 border border-white/5 shadow-lg">
            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Today</p>
            <p class="text-lg font-display font-bold text-emerald-400" id="stat-earnings">EGP 0.00</p>
        </div>
        <div class="bg-surface-800 rounded-2xl p-4 border border-white/5 shadow-lg">
            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Trips</p>
            <p class="text-lg font-display font-bold text-white" id="stat-count">0</p>
        </div>
        <div class="bg-surface-800 rounded-2xl p-4 border border-white/5 shadow-lg">
            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Rating</p>
            <div class="flex items-center gap-1">
                <p class="text-lg font-display font-bold text-amber-400" id="stat-rating">5.0</p>
                <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex items-center bg-surface-800 p-1 rounded-2xl border border-white/5 mb-6 shadow-inner">
        <button onclick="switchTab('available')" id="tab-available" class="flex-1 py-2.5 text-xs font-bold rounded-xl transition-all tab-active">Available</button>
        <button onclick="switchTab('active')" id="tab-active" class="flex-1 py-2.5 text-xs font-bold rounded-xl transition-all text-gray-400">Accepted</button>
        <button onclick="switchTab('history')" id="tab-history" class="flex-1 py-2.5 text-xs font-bold rounded-xl transition-all text-gray-400">History</button>
    </div>

    {{-- Sections --}}
    <div id="section-available" class="space-y-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Orders Near You</h3>
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
        </div>
        <div id="available-list" class="space-y-4">
            <div class="animate-pulse space-y-4">
                <div class="h-32 bg-surface-800 rounded-2xl"></div>
                <div class="h-32 bg-surface-800 rounded-2xl"></div>
            </div>
        </div>
    </div>

    <div id="section-active" class="hidden space-y-4">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Your Active Deliveries</h3>
        <div id="active-list" class="space-y-4"></div>
    </div>

    <div id="section-history" class="hidden space-y-4">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Past Deliveries</h3>
        <div id="history-list" class="space-y-4"></div>
    </div>

    {{-- Empty State Template --}}
    <template id="empty-state">
        <div class="py-12 text-center">
            <div class="w-16 h-16 bg-surface-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/5">
                <svg class="w-8 h-8 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <p class="text-sm font-semibold text-surface-300">Nothing here yet</p>
            <p class="text-xs text-surface-500 mt-1">Check back in a few moments.</p>
        </div>
    </template>
</div>

@push('scripts')
<style>
    .tab-active { @apply bg-brand-500 text-white shadow-lg shadow-brand-500/20; }
    .glass-card { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
    .status-online { @apply bg-emerald-500 text-white border-emerald-400 shadow-lg shadow-emerald-500/30; }
</style>

<script>
    let activeTab = 'available';
    let isOnline = false;
    let riderLat = null;
    let riderLng = null;

    const token = localStorage.getItem('auth_token');
    const headers = { 
        'Accept': 'application/json', 
        'Content-Type': 'application/json', 
        'Authorization': `Bearer ${token}` 
    };

    document.addEventListener('DOMContentLoaded', () => {
        initLocation();
        refreshAll();
        setInterval(refreshAll, 10000); // UI Refresh
        setInterval(updateLocation, 30000); // GPS Ping
    });

    async function initLocation() {
        const statusText = document.getElementById('rider-status-text');
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(async (pos) => {
                riderLat = pos.coords.latitude;
                riderLng = pos.coords.longitude;
                statusText.textContent = 'GPS Active.';
                await updateLocation();
            }, async (err) => {
                console.warn('Geolocation blocked', err);
                statusText.textContent = 'GPS Blocked. Using Default.';
                riderLat = 30.0444; riderLng = 31.2357; // Cairo
                await updateLocation();
            });
        } else {
            statusText.textContent = 'GPS Unsupported.';
            riderLat = 30.0444; riderLng = 31.2357;
            await updateLocation();
        }
    }

    async function toggleOnline() {
        const btn = document.getElementById('btn-toggle-online');
        if (!riderLat) { riderLat = 30.0444; riderLng = 31.2357; }
        
        isOnline = !isOnline;
        if (isOnline) {
            btn.textContent = 'ONLINE';
            btn.classList.add('status-online');
            btn.classList.remove('bg-white/5', 'text-white');
            await updateLocation('online');
        } else {
            btn.textContent = 'GO ONLINE';
            btn.classList.remove('status-online');
            btn.classList.add('bg-white/5', 'text-white');
            await updateLocation('offline');
        }
        refreshAll();
    }

    async function updateLocation(statusOverride = null) {
        if (!riderLat || !riderLng) return;
        try {
            const res = await fetch('/api/rider/location', {
                method: 'POST',
                headers,
                body: JSON.stringify({
                    latitude: riderLat,
                    longitude: riderLng,
                    availability: statusOverride || (isOnline ? 'online' : 'offline')
                })
            });
            const json = await res.json();
            if (json.success) {
                if (statusOverride === 'online') isOnline = true;
                if (statusOverride === 'offline') isOnline = false;
            }
        } catch (e) { console.error('Sync failed', e); }
    }

    async function refreshAll() {
        updateStats();
        if (activeTab === 'available') loadAvailable();
        if (activeTab === 'active') loadActive();
        if (activeTab === 'history') loadHistory();
    }

    function switchTab(tab) {
        activeTab = tab;
        ['available', 'active', 'history'].forEach(t => {
            const btn = document.getElementById(`tab-${t}`);
            const section = document.getElementById(`section-${t}`);
            if (t === tab) {
                btn.classList.add('tab-active');
                btn.classList.remove('text-gray-400');
                section.classList.remove('hidden');
            } else {
                btn.classList.remove('tab-active');
                btn.classList.add('text-gray-400');
                section.classList.add('hidden');
            }
        });
        refreshAll();
    }

    async function updateStats() {
        try {
            const res = await fetch('/api/rider/dashboard', { headers });
            const json = await res.json();
            if (json.success) {
                const d = json.data;
                document.getElementById('stat-earnings').textContent = `EGP ${parseFloat(d.earnings?.total_net || 0).toFixed(2)}`;
                document.getElementById('stat-count').textContent = d.today_deliveries;
                document.getElementById('stat-rating').textContent = d.rating?.average_rating || '5.0';
                
                if (d.location && d.location.availability === 'online' && !isOnline) {
                    isOnline = true;
                    const btn = document.getElementById('btn-toggle-online');
                    btn.textContent = 'ONLINE';
                    btn.classList.add('status-online');
                }
            }
        } catch (e) { }
    }

    async function loadAvailable() {
        const list = document.getElementById('available-list');
        if (!isOnline) {
            list.innerHTML = `<div class="py-12 text-center"><p class="text-sm font-semibold text-gray-500">You are offline</p><button onclick="toggleOnline()" class="mt-4 text-emerald-500 font-bold text-xs">GO ONLINE TO SEE ORDERS</button></div>`;
            return;
        }

        try {
            const res = await fetch('/api/rider/orders/available?radius=100', { headers });
            const json = await res.json();
            
            if (json.success) {
                if (json.data.length === 0) {
                    list.innerHTML = document.getElementById('empty-state').innerHTML;
                    return;
                }
                list.innerHTML = json.data.map(order => `
                    <div class="glass-card rounded-2xl p-5 border border-white/5 shadow-xl animate-in fade-in slide-in-from-bottom-4">
                        <div class="flex justify-between items-start mb-4">
                            <div><p class="text-[10px] text-brand-400 font-black uppercase tracking-widest mb-1">Available</p><h4 class="font-display font-bold text-white text-base">${order.restaurant.name}</h4></div>
                            <div class="text-right"><p class="text-lg font-bold text-white">EGP ${order.delivery_fee.toFixed(2)}</p></div>
                        </div>
                        <div class="space-y-3 mb-5">
                            <div class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-brand-500"></div><p class="text-xs text-gray-300 truncate">${order.restaurant.address}</p></div>
                            <div class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div><p class="text-xs text-gray-300 truncate">${order.delivery_address}</p></div>
                        </div>
                        <button onclick="acceptOrder(${order.id})" class="w-full py-3 bg-brand-500 hover:bg-brand-600 text-white rounded-xl text-xs font-black tracking-widest transition-all">ACCEPT ORDER</button>
                    </div>
                `).join('');
            } else {
                list.innerHTML = `<div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-center"><p class="text-xs text-red-400">${json.message || 'Error'}</p></div>`;
            }
        } catch (e) { list.innerHTML = `<p class="text-center text-xs text-red-400">Network Error</p>`; }
    }

    async function loadActive() {
        const list = document.getElementById('active-list');
        try {
            const res = await fetch('/api/rider/dashboard', { headers });
            const json = await res.json();
            if (json.success) {
                const active = json.data.active_orders;
                if (!active || active.length === 0) { list.innerHTML = document.getElementById('empty-state').innerHTML; return; }
                list.innerHTML = active.map(order => {
                    let btnText = 'PICK UP ORDER'; let btnClass = 'bg-brand-500 hover:bg-brand-600'; let action = `updateStatus(${order.id}, 'pickup')`;
                    if (order.status === 'picked_up') { btnText = 'MARK DELIVERED'; btnClass = 'bg-emerald-500 hover:bg-emerald-600'; action = `updateStatus(${order.id}, 'deliver')`; }
                    return `
                    <div class="bg-surface-800 rounded-2xl p-5 border border-white/10 shadow-xl relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-surface-700 flex items-center justify-center border border-white/5 text-lg">📦</div>
                                <div><p class="text-[10px] text-gray-500 font-bold uppercase">ORD-${order.id}</p><h4 class="font-bold text-white text-sm">${order.customer?.name || 'Customer'}</h4></div>
                            </div>
                            <span class="px-2 py-1 bg-surface-700 text-[9px] font-black text-white rounded-lg uppercase tracking-tighter">${order.status.replace(/_/g, ' ')}</span>
                        </div>
                        <div class="space-y-4 mb-6 border-l-2 border-dashed border-surface-700 ml-5 pl-6">
                            <div><p class="text-[9px] text-gray-600 uppercase font-black tracking-widest">Restaurant</p><p class="text-xs text-white font-semibold">${order.restaurant.name}</p></div>
                            <div><p class="text-[9px] text-gray-600 uppercase font-black tracking-widest">Delivery To</p><p class="text-xs text-white font-semibold">${order.delivery_address}</p></div>
                        </div>
                        <a href="/rider/delivery/${order.id}" class="w-full block text-center py-4 ${btnClass} text-white rounded-xl text-xs font-black tracking-widest transition-all shadow-lg">${btnText}</a>
                    </div>`;
                }).join('');
            }
        } catch (e) { }
    }

    async function loadHistory() {
        const list = document.getElementById('history-list');
        try {
            const res = await fetch('/api/rider/orders/history', { headers });
            const json = await res.json();
            if (json.success) {
                const orders = json.data?.data || json.data;
                if (!orders || orders.length === 0) { list.innerHTML = document.getElementById('empty-state').innerHTML; return; }
                list.innerHTML = orders.map(order => `
                    <div class="bg-surface-800/50 rounded-2xl p-4 border border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface-700 flex items-center justify-center text-sm">${order.status === 'delivered' ? '✅' : '❌'}</div>
                            <div><p class="text-xs font-bold text-white">#${order.id} • ${order.restaurant.name}</p><p class="text-[10px] text-gray-500">${new Date(order.delivered_at || order.updated_at).toLocaleDateString()}</p></div>
                        </div>
                        <p class="text-xs font-bold text-emerald-400">EGP ${order.delivery_fee.toFixed(2)}</p>
                    </div>
                `).join('');
            }
        } catch (e) { }
    }

    async function acceptOrder(id) {
        try {
            const res = await fetch(`/api/rider/orders/${id}/accept`, { method: 'POST', headers });
            const json = await res.json();
            if (json.success) { Toast.show('Success', 'Order accepted!', 'success'); switchTab('active'); }
            else { Toast.show('Error', json.message, 'error'); }
        } catch (e) { Toast.show('Error', 'Network error', 'error'); }
    }

    async function updateStatus(id, action) {
        try {
            const res = await fetch(`/api/rider/orders/${id}/${action}`, { method: 'PATCH', headers });
            const json = await res.json();
            if (json.success) {
                Toast.show('Success', `Order ${action === 'pickup' ? 'picked up' : 'delivered'}!`, 'success');
                if (action === 'deliver') { switchTab('history'); updateStats(); } else { loadActive(); }
            } else { Toast.show('Error', json.message, 'error'); }
        } catch (e) { Toast.show('Error', 'Update failed', 'error'); }
    }
</script>
@endpush
@endsection
