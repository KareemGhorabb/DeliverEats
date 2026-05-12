@extends('layouts.admin')
@section('page-title', 'User Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <div class="relative">
            <input type="text" id="search-input" placeholder="Search users..." class="pl-10 pr-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50 w-64 transition-all">
            <svg class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <select id="role-select" class="px-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-500/50">
            <option value="">All Roles</option>
            <option value="customer">Customer</option>
            <option value="restaurant_owner">Restaurant Owner</option>
            <option value="rider">Rider</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <button onclick="openModal('add-user-modal')" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-xl hover:bg-orange-600 focus:ring-2 focus:ring-orange-500/50 flex items-center gap-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add User
    </button>
</div>

<div id="loading-state" class="hidden flex justify-center py-12">
    <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="empty-state" class="hidden flex flex-col items-center justify-center py-16 bg-neutral-900 border border-neutral-800 rounded-2xl">
    <div class="w-16 h-16 bg-neutral-800 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </div>
    <h3 class="text-lg font-bold text-white mb-1">No users found</h3>
    <p class="text-sm text-neutral-400">Try adjusting your filters or add a new user.</p>
</div>

<div id="users-table-container" class="bg-neutral-900 rounded-2xl border border-neutral-800 overflow-hidden hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-neutral-400 uppercase tracking-wider border-b border-neutral-800 bg-neutral-950/50">
                <th class="px-6 py-4 font-medium">User</th>
                <th class="px-6 py-4 font-medium">Role</th>
                <th class="px-6 py-4 font-medium">Email</th>
                <th class="px-6 py-4 font-medium">Joined</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody id="users-tbody" class="divide-y divide-neutral-800">
            {{-- Populated via JS --}}
        </tbody>
    </table>
</div>

<div id="pagination-container" class="mt-6 flex justify-center hidden"></div>

{{-- Add User Modal --}}
<div id="add-user-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-backdrop transition-opacity opacity-0" onclick="closeModal('add-user-modal')"></div>
    <div class="relative w-full max-w-md bg-neutral-900 border border-neutral-800 rounded-2xl shadow-2xl modal-content transition-all transform scale-95 opacity-0 flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-neutral-800">
            <h3 class="text-lg font-bold text-white">Add New User</h3>
            <button onclick="closeModal('add-user-modal')" class="text-neutral-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="add-user-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-1">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required class="w-full px-4 py-2 bg-neutral-950 border border-neutral-800 rounded-xl text-white focus:ring-2 focus:ring-orange-500/50 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-1">Email Address <span class="text-red-400">*</span></label>
                    <input type="email" name="email" required class="w-full px-4 py-2 bg-neutral-950 border border-neutral-800 rounded-xl text-white focus:ring-2 focus:ring-orange-500/50 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-1">Password <span class="text-red-400">*</span></label>
                    <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 bg-neutral-950 border border-neutral-800 rounded-xl text-white focus:ring-2 focus:ring-orange-500/50 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-1">Role <span class="text-red-400">*</span></label>
                    <select name="role" required class="w-full px-4 py-2 bg-neutral-950 border border-neutral-800 rounded-xl text-white focus:ring-2 focus:ring-orange-500/50 focus:outline-none">
                        <option value="customer">Customer</option>
                        <option value="restaurant_owner">Restaurant Owner</option>
                        <option value="rider">Rider</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-neutral-800 flex justify-end gap-3 bg-neutral-900 rounded-b-2xl">
            <button onclick="closeModal('add-user-modal')" class="px-5 py-2 text-sm font-medium text-neutral-300 hover:text-white transition-colors">Cancel</button>
            <button onclick="submitAddUser()" id="submit-user-btn" class="px-5 py-2 text-sm font-bold text-white bg-orange-500 rounded-xl hover:bg-orange-600 transition-colors flex items-center gap-2">
                Save User
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';
    let currentRole = '';
    let debounceTimer;

    const token = localStorage.getItem('auth_token');
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    document.addEventListener('DOMContentLoaded', () => {
        loadUsers();

        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                currentSearch = e.target.value;
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    loadUsers();
                }, 300);
            });
        }

        const roleSelect = document.getElementById('role-select');
        if (roleSelect) {
            roleSelect.addEventListener('change', (e) => {
                currentRole = e.target.value;
                currentPage = 1;
                loadUsers();
            });
        }
    });

    function getRoleBadge(role) {
        const badges = {
            'customer': '<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-blue-400 bg-blue-500/10">Customer</span>',
            'restaurant_owner': '<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-amber-400 bg-amber-500/10">Owner</span>',
            'rider': '<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-emerald-400 bg-emerald-500/10">Rider</span>',
            'admin': '<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-violet-400 bg-violet-500/10">Admin</span>'
        };
        return badges[role] || `<span class="px-2.5 py-1 text-[11px] font-bold rounded-full text-neutral-400 bg-neutral-500/10">${role}</span>`;
    }

    async function loadUsers() {
        const container = document.getElementById('users-table-container');
        const tbody = document.getElementById('users-tbody');
        const empty = document.getElementById('empty-state');
        const loading = document.getElementById('loading-state');
        const pagination = document.getElementById('pagination-container');

        container.classList.add('hidden');
        empty.classList.add('hidden');
        pagination.classList.add('hidden');
        loading.classList.remove('hidden');

        try {
            const url = new URL('/api/v1/users', window.location.origin);
            url.searchParams.append('page', currentPage);
            if (currentSearch) url.searchParams.append('search', currentSearch);
            if (currentRole) url.searchParams.append('role', currentRole);

            const res = await fetch(url, { headers });
            const json = await res.json();
            
            loading.classList.add('hidden');

            if (!json.data || json.data.data.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            tbody.innerHTML = json.data.data.map(u => {
                const initial = u.name.charAt(0).toUpperCase();
                const joined = new Date(u.created_at).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                return `
                <tr class="hover:bg-neutral-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-neutral-700 to-neutral-800 flex items-center justify-center text-white text-xs font-bold border border-neutral-700">${initial}</div>
                            <span class="font-medium text-white">${u.name}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">${getRoleBadge(u.role)}</td>
                    <td class="px-6 py-4 text-neutral-400">${u.email}</td>
                    <td class="px-6 py-4 text-neutral-400">${joined}</td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="deleteUser(${u.id})" class="p-2 rounded-lg hover:bg-red-500/10 text-neutral-500 hover:text-red-400 transition-colors" title="Delete User">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>`;
            }).join('');
            
            container.classList.remove('hidden');

            if (json.data.last_page > 1) {
                renderPagination(json.data);
                pagination.classList.remove('hidden');
            }

        } catch (e) {
            console.error(e);
            loading.classList.add('hidden');
            if (typeof Toast !== 'undefined') Toast.show('Error', 'Failed to load users', 'error');
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
        loadUsers();
    };

    window.submitAddUser = async function() {
        const form = document.getElementById('add-user-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const btn = document.getElementById('submit-user-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
        btn.disabled = true;

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('/api/v1/users', {
                method: 'POST',
                headers,
                body: JSON.stringify(data)
            });
            const json = await res.json();
            
            if (!res.ok || !json.success) {
                // Check if validation errors exist
                if (json.errors) {
                    const errorMsg = Object.values(json.errors).flat().join('\n');
                    throw new Error(errorMsg);
                }
                throw new Error(json.message || 'Failed to create user');
            }

            if (typeof Toast !== 'undefined') Toast.show('Success', 'User created successfully', 'success');
            closeModal('add-user-modal');
            form.reset();
            loadUsers();
        } catch (e) {
            if (typeof Toast !== 'undefined') Toast.show('Error', e.message, 'error');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    };

    window.deleteUser = async function(id) {
        if (!confirm('Are you sure you want to delete this user?')) return;
        
        try {
            const res = await fetch(`/api/v1/users/${id}`, {
                method: 'DELETE',
                headers
            });
            
            if (!res.ok) throw new Error('Delete failed');
            
            if (typeof Toast !== 'undefined') Toast.show('Deleted', 'User deleted successfully', 'info');
            loadUsers();
        } catch (e) {
            if (typeof Toast !== 'undefined') Toast.show('Error', 'Failed to delete user', 'error');
        }
    };

    // Modal utility if not globally defined
    if (typeof window.openModal === 'undefined') {
        window.openModal = function(id) {
            const m = document.getElementById(id);
            if(m) {
                m.classList.remove('hidden');
                setTimeout(() => {
                    m.querySelector('.modal-backdrop')?.classList.remove('opacity-0');
                    m.querySelector('.modal-content')?.classList.remove('scale-95', 'opacity-0');
                }, 10);
            }
        };
        window.closeModal = function(id) {
            const m = document.getElementById(id);
            if(m) {
                m.querySelector('.modal-backdrop')?.classList.add('opacity-0');
                m.querySelector('.modal-content')?.classList.add('scale-95', 'opacity-0');
                setTimeout(() => { m.classList.add('hidden'); }, 300);
            }
        };
    }
</script>
@endpush
@endsection
