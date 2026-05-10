@extends('layouts.admin')
@section('page-title', 'Platform Dashboard')
@section('content')
{{-- Stats Cards (populated via API) --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
        <p id="stat-total-orders" class="text-2xl font-display font-bold text-white">—</p>
        <p class="text-xs text-neutral-400 mt-0.5">Total Orders Today</p>
    </div>
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            </div>
        </div>
        <p id="stat-active-riders" class="text-2xl font-display font-bold text-white">—</p>
        <p class="text-xs text-neutral-400 mt-0.5">Active Riders</p>
    </div>
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/></svg>
            </div>
        </div>
        <p id="stat-revenue" class="text-2xl font-display font-bold text-white">—</p>
        <p class="text-xs text-neutral-400 mt-0.5">Platform Revenue (EGP)</p>
    </div>
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
        <p id="stat-restaurants" class="text-2xl font-display font-bold text-white">—</p>
        <p class="text-xs text-neutral-400 mt-0.5">Total Restaurants</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Live Order Feed (populated via API) --}}
    <div class="lg:col-span-2 bg-neutral-900 rounded-2xl border border-neutral-800 p-6">
        <h3 class="text-sm font-display font-bold mb-4 flex items-center gap-2 text-white">
            Live Order Feed
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span><span class="text-[10px] text-emerald-400">LIVE</span></span>
        </h3>
        <div id="live-order-feed" class="space-y-3 max-h-80 overflow-y-auto">
            <div class="flex items-center justify-center py-8 text-neutral-500 text-sm">
                <svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Loading orders…
            </div>
        </div>
    </div>

    {{-- Quick actions + Platform Health --}}
    <div class="space-y-4">
        <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-6">
            <h3 class="text-sm font-display font-bold mb-4 text-white">Platform Health</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between"><span class="text-xs text-neutral-400">Active Orders</span><span id="health-active-orders" class="text-sm font-bold text-emerald-400">—</span></div>
                <div class="flex items-center justify-between"><span class="text-xs text-neutral-400">Delivered Today</span><span id="health-delivered-today" class="text-sm font-bold text-white">—</span></div>
                <div class="flex items-center justify-between"><span class="text-xs text-neutral-400">Total Users</span><span id="health-total-users" class="text-sm font-bold text-white">—</span></div>
                <div class="flex items-center justify-between"><span class="text-xs text-neutral-400">Pending Payouts</span><span id="health-pending-payouts" class="text-sm font-bold text-amber-400">—</span></div>
            </div>
        </div>
        <a href="{{ route('admin.control-tower') }}" class="block bg-gradient-to-br from-orange-500/20 to-orange-600/10 border border-orange-500/30 rounded-2xl p-5 hover:border-orange-500/50 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg></div>
                <div>
                    <p class="text-sm font-semibold text-white group-hover:text-orange-400 transition-colors">Control Tower</p>
                    <p class="text-[10px] text-neutral-400">Live order monitoring & dispatch</p>
                </div>
                <svg class="w-4 h-4 text-neutral-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json' };
    if (token) headers['Authorization'] = `Bearer ${token}`;

    async function loadDashboard() {
        try {
            const res = await fetch('/api/admin/dashboard', { headers });
            const json = await res.json();
            if (!json.success) return;
            const d = json.data;

            // Stats cards
            document.getElementById('stat-total-orders').textContent = d.delivered_today ?? 0;
            document.getElementById('stat-restaurants').textContent = d.total_restaurants ?? 0;
            document.getElementById('stat-revenue').textContent = 'EGP ' + (d.revenue?.total_commissions ?? 0).toLocaleString('en-US', {minimumFractionDigits: 2});

            // Health
            document.getElementById('health-active-orders').textContent = d.active_orders ?? 0;
            document.getElementById('health-delivered-today').textContent = d.delivered_today ?? 0;
            document.getElementById('health-total-users').textContent = d.total_users ?? 0;
            document.getElementById('health-pending-payouts').textContent = 'EGP ' + (d.revenue?.pending_payouts ?? 0).toLocaleString('en-US', {minimumFractionDigits: 2});
        } catch (e) {
            console.error('Dashboard load failed:', e);
        }
    }

    async function loadActiveRiders() {
        try {
            const res = await fetch('/api/admin/users?role=rider', { headers });
            const json = await res.json();
            if (json.success) {
                document.getElementById('stat-active-riders').textContent = json.data?.total ?? json.data?.data?.length ?? 0;
            }
        } catch (e) {}
    }

    async function loadLiveOrders() {
        try {
            const res = await fetch('/api/admin/control-tower', { headers });
            const json = await res.json();
            if (!json.success) return;

            const feed = document.getElementById('live-order-feed');
            const orders = json.data.active_orders || [];

            if (orders.length === 0) {
                feed.innerHTML = '<p class="text-center text-neutral-500 py-8 text-sm">No active orders right now</p>';
                return;
            }

            const statusColors = {
                placed: 'text-orange-400',
                confirmed: 'text-blue-400',
                preparing: 'text-amber-400',
                ready_for_pickup: 'text-violet-400',
                picked_up: 'text-cyan-400',
                delivered: 'text-emerald-400',
                cancelled: 'text-red-400',
            };

            feed.innerHTML = orders.slice(0, 10).map(o => {
                const status = o.status?.value || o.status || 'unknown';
                const color = statusColors[status] || 'text-neutral-400';
                const time = o.created_at ? new Date(o.created_at).toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'}) : '';
                return `
                <div class="flex items-center gap-3 p-3 rounded-xl bg-neutral-800/50 hover:bg-neutral-800 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-neutral-200">ORD-${o.id}</span>
                            <span class="text-[10px] ${color} font-semibold uppercase">${status.replace(/_/g, ' ')}</span>
                        </div>
                        <p class="text-xs text-neutral-400 mt-0.5 truncate">${o.restaurant?.name || '—'} → ${o.user?.name || '—'}</p>
                    </div>
                    <span class="text-xs text-neutral-500">${time}</span>
                    <span class="text-sm font-bold text-white">EGP ${parseFloat(o.total || 0).toFixed(2)}</span>
                </div>`;
            }).join('');
        } catch (e) {
            document.getElementById('live-order-feed').innerHTML = '<p class="text-center text-red-400 py-8 text-sm">Failed to load orders</p>';
        }
    }

    loadDashboard();
    loadActiveRiders();
    loadLiveOrders();

    // Auto-refresh every 15 seconds
    setInterval(() => { loadDashboard(); loadLiveOrders(); }, 15000);
})();
</script>
@endpush
@endsection
