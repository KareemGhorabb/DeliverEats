@extends('layouts.admin')
@section('page-title', 'Rider Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="relative">
        <input type="text" id="search-input" placeholder="Search riders..." class="pl-10 pr-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50 w-64 transition-all">
        <svg class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </div>
</div>

<div id="loading-state" class="hidden flex justify-center py-12">
    <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="empty-state" class="hidden flex flex-col items-center justify-center py-16 bg-neutral-900 border border-neutral-800 rounded-2xl">
    <div class="w-16 h-16 bg-neutral-800 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/></svg>
    </div>
    <h3 class="text-lg font-bold text-white mb-1">No riders found</h3>
    <p class="text-sm text-neutral-400">Try adjusting your filters.</p>
</div>

<div id="riders-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 stagger-children hidden">
    {{-- Populated via JS --}}
</div>

<div id="pagination-container" class="mt-8 flex justify-center hidden"></div>

@push('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';
    let debounceTimer;

    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadRiders();

        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                currentSearch = e.target.value;
                debounceTimer = setTimeout(() => { currentPage = 1; loadRiders(); }, 300);
            });
        }
    });

    async function loadRiders() {
        const grid = document.getElementById('riders-grid');
        const empty = document.getElementById('empty-state');
        const loading = document.getElementById('loading-state');
        const pagination = document.getElementById('pagination-container');

        grid.classList.add('hidden');
        empty.classList.add('hidden');
        pagination.classList.add('hidden');
        loading.classList.remove('hidden');

        try {
            const url = new URL('/api/v1/users', window.location.origin);
            url.searchParams.append('role', 'rider');
            url.searchParams.append('page', currentPage);
            if (currentSearch) url.searchParams.append('search', currentSearch);

            const res = await fetch(url, { headers });
            const json = await res.json();
            
            loading.classList.add('hidden');

            const riders = json.data?.data || json.data;

            if (!riders || riders.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            grid.innerHTML = riders.map(r => {
                const initial = r.name.charAt(0).toUpperCase();
                // Randomize simulated status since rider app isn't active
                const statuses = ['Active', 'Active', 'Busy', 'Offline'];
                const status = statuses[Math.floor(Math.random() * statuses.length)];
                let statusBadge = '';
                if (status === 'Active') statusBadge = '<span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 text-[10px] font-bold rounded-full">Available</span>';
                if (status === 'Busy') statusBadge = '<span class="px-2 py-0.5 bg-violet-500/20 text-violet-400 text-[10px] font-bold rounded-full">On Delivery</span>';
                if (status === 'Offline') statusBadge = '<span class="px-2 py-0.5 bg-neutral-500/20 text-neutral-400 text-[10px] font-bold rounded-full">Offline</span>';

                return `
                <div class="bg-neutral-900 rounded-2xl border border-neutral-800 overflow-hidden hover:border-neutral-700 transition-colors p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-neutral-800 to-neutral-700 border border-neutral-600 flex items-center justify-center text-white text-lg font-bold">
                            ${initial}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-white truncate">${r.name}</h3>
                            <p class="text-xs text-neutral-400 truncate">${r.email}</p>
                        </div>
                        <div>${statusBadge}</div>
                    </div>
                    <div class="pt-4 border-t border-neutral-800 flex gap-2">
                        <button onclick="suspendRider(${r.id})" class="flex-1 py-2 text-xs font-medium text-red-400 border border-red-500/20 rounded-lg hover:bg-red-500/10 transition-colors">Suspend</button>
                    </div>
                </div>`;
            }).join('');
            
            grid.classList.remove('hidden');

            const meta = json.meta || json.data;
            if (meta && meta.last_page > 1) {
                renderPagination(meta);
                pagination.classList.remove('hidden');
            }

        } catch (e) {
            console.error(e);
            loading.classList.add('hidden');
            if (typeof Toast !== 'undefined') Toast.show('Error', 'Failed to load riders', 'error');
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
        loadRiders();
    };

    window.suspendRider = async function(id) {
        if (!confirm('Suspend this rider? (Will be deleted in MVP)')) return;
        try {
            const res = await fetch(`/api/v1/users/${id}`, { method: 'DELETE', headers });
            if (!res.ok) throw new Error('Failed to suspend');
            if (typeof Toast !== 'undefined') Toast.show('Suspended', 'Rider suspended successfully', 'info');
            loadRiders();
        } catch (e) {
            if (typeof Toast !== 'undefined') Toast.show('Error', e.message, 'error');
        }
    };
</script>
@endpush
@endsection
