@extends('layouts.admin')
@section('page-title', 'Order Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <div class="relative">
            <input type="text" id="search-input" placeholder="Search orders..." class="pl-10 pr-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50 w-64 transition-all">
            <svg class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <select id="status-select" class="px-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-500/50">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="preparing">Preparing</option>
            <option value="ready_for_pickup">Ready for Pickup</option>
            <option value="rider_assigned">Rider Assigned</option>
            <option value="picked_up">Picked Up</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
</div>

<div id="loading-state" class="hidden flex justify-center py-12">
    <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="empty-state" class="hidden flex flex-col items-center justify-center py-16 bg-neutral-900 border border-neutral-800 rounded-2xl">
    <div class="w-16 h-16 bg-neutral-800 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
    </div>
    <h3 class="text-lg font-bold text-white mb-1">No orders found</h3>
    <p class="text-sm text-neutral-400">Try adjusting your filters.</p>
</div>

<div id="orders-table-container" class="bg-neutral-900 rounded-2xl border border-neutral-800 overflow-hidden hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-neutral-400 uppercase tracking-wider border-b border-neutral-800 bg-neutral-950/50">
                <th class="px-6 py-4 font-medium">Order ID</th>
                <th class="px-6 py-4 font-medium">Customer</th>
                <th class="px-6 py-4 font-medium">Restaurant</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium">Total</th>
                <th class="px-6 py-4 font-medium">Date</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody id="orders-tbody" class="divide-y divide-neutral-800">
            {{-- Populated via JS --}}
        </tbody>
    </table>
</div>

<div id="pagination-container" class="mt-6 flex justify-center hidden"></div>

@push('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';
    let currentStatus = '';
    let debounceTimer;

    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadOrders();

        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                currentSearch = e.target.value;
                debounceTimer = setTimeout(() => { currentPage = 1; loadOrders(); }, 300);
            });
        }

        const statusSelect = document.getElementById('status-select');
        if (statusSelect) {
            statusSelect.addEventListener('change', (e) => {
                currentStatus = e.target.value;
                currentPage = 1;
                loadOrders();
            });
        }
    });

    function getStatusBadge(status) {
        const s = status.value || status;
        const colors = {
            'payment_pending': 'text-neutral-400 bg-neutral-500/10',
            'pending': 'text-orange-400 bg-orange-500/10',
            'accepted': 'text-blue-400 bg-blue-500/10',
            'preparing': 'text-amber-400 bg-amber-500/10',
            'ready_for_pickup': 'text-violet-400 bg-violet-500/10',
            'rider_assigned': 'text-indigo-400 bg-indigo-500/10',
            'picked_up': 'text-cyan-400 bg-cyan-500/10',
            'delivered': 'text-emerald-400 bg-emerald-500/10',
            'cancelled': 'text-red-400 bg-red-500/10'
        };
        const color = colors[s] || 'text-neutral-400 bg-neutral-500/10';
        return `<span class="px-2.5 py-1 text-[11px] font-bold rounded-full ${color} uppercase">${s.replace(/_/g, ' ')}</span>`;
    }

    async function loadOrders() {
        const container = document.getElementById('orders-table-container');
        const tbody = document.getElementById('orders-tbody');
        const empty = document.getElementById('empty-state');
        const loading = document.getElementById('loading-state');
        const pagination = document.getElementById('pagination-container');

        container.classList.add('hidden');
        empty.classList.add('hidden');
        pagination.classList.add('hidden');
        loading.classList.remove('hidden');

        try {
            const url = new URL('/api/v1/orders', window.location.origin);
            url.searchParams.append('page', currentPage);
            if (currentSearch) url.searchParams.append('search', currentSearch);
            if (currentStatus) url.searchParams.append('status', currentStatus);

            const res = await fetch(url, { headers });
            const json = await res.json();
            
            loading.classList.add('hidden');

            const orders = json.data?.data || json.data;

            if (!orders || orders.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            tbody.innerHTML = orders.map(o => {
                const date = new Date(o.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                return `
                <tr class="hover:bg-neutral-800/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-white">ORD-${o.id}</td>
                    <td class="px-6 py-4 text-neutral-300">${o.user?.name || '—'}</td>
                    <td class="px-6 py-4 text-neutral-300">${o.restaurant?.name || '—'}</td>
                    <td class="px-6 py-4">${getStatusBadge(o.status)}</td>
                    <td class="px-6 py-4 font-bold text-emerald-400">EGP ${parseFloat(o.total || 0).toFixed(2)}</td>
                    <td class="px-6 py-4 text-neutral-400">${date}</td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="cancelOrder(${o.id})" class="px-3 py-1.5 text-xs font-medium text-red-400 border border-red-500/20 rounded hover:bg-red-500/10 transition-colors">Cancel</button>
                    </td>
                </tr>`;
            }).join('');
            
            container.classList.remove('hidden');

            const meta = json.meta || json.data;
            if (meta && meta.last_page > 1) {
                renderPagination(meta);
                pagination.classList.remove('hidden');
            }

        } catch (e) {
            console.error(e);
            loading.classList.add('hidden');
            if (typeof Toast !== 'undefined') Toast.show('Error', 'Failed to load orders', 'error');
        }
    }

    function renderPagination(meta) {
        const container = document.getElementById('pagination-container');
        let html = '<div class="flex items-center gap-1">';
        html += `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1.5 rounded-lg text-sm font-medium ${currentPage === 1 ? 'text-neutral-600 cursor-not-allowed' : 'text-neutral-300 hover:bg-neutral-800'}">&laquo;</button>`;
        for (let i = 1; i <= meta.last_page; i++) {
            if (i === 1 || i === meta.last_page || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `<button onclick="changePage(${i})" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium ${currentPage === i ? 'bg-orange-500 text-white' : 'text-neutral-300 hover:bg-neutral-800'}">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<span class="text-neutral-600 px-1">...</span>`;
            }
        }
        html += `<button onclick="changePage(${currentPage + 1})" ${currentPage === meta.last_page ? 'disabled' : ''} class="px-3 py-1.5 rounded-lg text-sm font-medium ${currentPage === meta.last_page ? 'text-neutral-600 cursor-not-allowed' : 'text-neutral-300 hover:bg-neutral-800'}">&raquo;</button>`;
        html += '</div>';
        container.innerHTML = html;
    }

    window.changePage = function(page) {
        currentPage = page;
        loadOrders();
    };

    window.cancelOrder = async function(id) {
        if (!confirm('Are you sure you want to cancel this order?')) return;
        try {
            const res = await fetch(`/api/v1/orders/${id}/cancel`, { method: 'POST', headers });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message || 'Cancel failed');
            if (typeof Toast !== 'undefined') Toast.show('Cancelled', 'Order cancelled successfully', 'info');
            loadOrders();
        } catch (e) {
            if (typeof Toast !== 'undefined') Toast.show('Error', e.message, 'error');
        }
    };
</script>
@endpush
@endsection
