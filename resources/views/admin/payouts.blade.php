@extends('layouts.admin')
@section('page-title', 'Financial Payouts')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <select id="status-select" class="px-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-500/50">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed</option>
        </select>
    </div>
</div>

<div class="grid lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <p class="text-xs text-neutral-400 mb-1">Total Pending</p>
        <p class="text-xl font-display font-bold text-amber-400" id="stat-pending">—</p>
    </div>
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <p class="text-xs text-neutral-400 mb-1">Total Paid</p>
        <p class="text-xl font-display font-bold text-emerald-400" id="stat-paid">—</p>
    </div>
</div>

<div id="loading-state" class="hidden flex justify-center py-12">
    <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="empty-state" class="hidden flex flex-col items-center justify-center py-16 bg-neutral-900 border border-neutral-800 rounded-2xl">
    <div class="w-16 h-16 bg-neutral-800 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/></svg>
    </div>
    <h3 class="text-lg font-bold text-white mb-1">No payouts found</h3>
    <p class="text-sm text-neutral-400">Try adjusting your filters.</p>
</div>

<div id="payouts-table-container" class="bg-neutral-900 rounded-2xl border border-neutral-800 overflow-hidden hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-neutral-400 uppercase tracking-wider border-b border-neutral-800 bg-neutral-950/50">
                    <th class="px-6 py-4 font-medium">ID</th>
                    <th class="px-6 py-4 font-medium">Recipient</th>
                    <th class="px-6 py-4 font-medium">Type</th>
                    <th class="px-6 py-4 font-medium">Amount</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="payouts-tbody" class="divide-y divide-neutral-800">
                {{-- Populated via JS --}}
            </tbody>
        </table>
    </div>
</div>

<div id="pagination-container" class="mt-6 flex justify-center hidden"></div>

@push('scripts')
<script>
    let currentPage = 1;
    let currentStatus = '';

    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadPayouts();
        
        // Also fetch general dashboard stats to populate the top cards
        fetch('/api/admin/dashboard', { headers })
            .then(res => res.json())
            .then(json => {
                if(json.success && json.data?.revenue) {
                    const rev = json.data.revenue;
                    document.getElementById('stat-pending').textContent = 'EGP ' + (rev.pending_payouts || 0).toFixed(2);
                    // Mock paid stat since API might only return pending. Let's calculate roughly if not provided
                    const paid = (rev.restaurant_payouts || 0) + (rev.rider_payouts || 0);
                    document.getElementById('stat-paid').textContent = 'EGP ' + paid.toFixed(2);
                }
            });

        const statusSelect = document.getElementById('status-select');
        if (statusSelect) {
            statusSelect.addEventListener('change', (e) => {
                currentStatus = e.target.value;
                currentPage = 1;
                loadPayouts();
            });
        }
    });

    function getStatusBadge(status) {
        const s = status.value || status;
        if (s === 'pending') return '<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-amber-400 bg-amber-500/10 uppercase">Pending</span>';
        if (s === 'paid') return '<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-emerald-400 bg-emerald-500/10 uppercase">Paid</span>';
        if (s === 'failed') return '<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-red-400 bg-red-500/10 uppercase">Failed</span>';
        return `<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-neutral-400 bg-neutral-500/10 uppercase">${s}</span>`;
    }

    async function loadPayouts() {
        const container = document.getElementById('payouts-table-container');
        const tbody = document.getElementById('payouts-tbody');
        const empty = document.getElementById('empty-state');
        const loading = document.getElementById('loading-state');
        const pagination = document.getElementById('pagination-container');

        container.classList.add('hidden');
        empty.classList.add('hidden');
        pagination.classList.add('hidden');
        loading.classList.remove('hidden');

        try {
            const url = new URL('/api/v1/payouts', window.location.origin);
            if (currentStatus) url.searchParams.append('status', currentStatus);

            const res = await fetch(url, { headers });
            const json = await res.json();
            
            loading.classList.add('hidden');

            const payouts = json.data;

            if (!payouts || payouts.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            tbody.innerHTML = payouts.map(p => {
                const date = new Date(p.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const type = p.restaurant ? 'Restaurant' : (p.rider ? 'Rider' : 'Unknown');
                const recipient = p.restaurant?.name || p.rider?.name || '—';
                const s = p.status.value || p.status;

                let actionBtn = '';
                if (s === 'pending') {
                    actionBtn = `<button onclick="markPaid(${p.id})" class="px-3 py-1.5 text-xs font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded hover:bg-emerald-500/20 transition-colors">Mark Paid</button>`;
                }

                return `
                <tr class="hover:bg-neutral-800/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-white">PAY-${p.id}</td>
                    <td class="px-6 py-4 text-white font-medium">${recipient}</td>
                    <td class="px-6 py-4 text-neutral-400">${type}</td>
                    <td class="px-6 py-4 font-bold text-white">EGP ${parseFloat(p.net_amount || 0).toFixed(2)}</td>
                    <td class="px-6 py-4">${getStatusBadge(p.status)}</td>
                    <td class="px-6 py-4 text-neutral-400">${date}</td>
                    <td class="px-6 py-4 text-right">${actionBtn}</td>
                </tr>`;
            }).join('');
            
            container.classList.remove('hidden');

            // Pagination logic if supported
            const meta = json.meta || null;
            if (meta && meta.last_page > 1) {
                renderPagination(meta);
                pagination.classList.remove('hidden');
            }

        } catch (e) {
            console.error(e);
            loading.classList.add('hidden');
            if (typeof Toast !== 'undefined') Toast.show('Error', 'Failed to load payouts', 'error');
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
        loadPayouts();
    };

    window.markPaid = async function(id) {
        if (!confirm('Mark this payout as paid?')) return;
        try {
            const res = await fetch(`/api/admin/payouts/${id}/mark-paid`, { method: 'POST', headers });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message || 'Action failed');
            if (typeof Toast !== 'undefined') Toast.show('Success', 'Payout marked as paid', 'success');
            loadPayouts();
        } catch (e) {
            if (typeof Toast !== 'undefined') Toast.show('Error', e.message, 'error');
        }
    };
</script>
@endpush
@endsection
