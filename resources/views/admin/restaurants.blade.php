@extends('layouts.admin')
@section('page-title', 'Restaurant Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="relative">
        <input type="text" id="search-input" placeholder="Search restaurants..." class="pl-10 pr-4 py-2.5 rounded-xl bg-white dark:bg-white/5 border border-surface-200 dark:border-white/10 text-sm text-surface-900 dark:text-white placeholder:text-surface-400 focus:outline-none focus:ring-2 focus:ring-brand-500/50 w-64 transition-all">
        <svg class="w-4 h-4 text-surface-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </div>
    <button onclick="openAddModal()" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50 flex items-center gap-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add Restaurant
    </button>
</div>

<div id="loading-state" class="hidden flex justify-center py-12">
    <svg class="w-8 h-8 text-brand-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="empty-state" class="hidden flex flex-col items-center justify-center py-16 bg-white dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-2xl">
    <div class="w-16 h-16 bg-surface-50 dark:bg-white/5 rounded-full flex items-center justify-center mb-4 text-2xl">🏪</div>
    <h3 class="text-lg font-bold text-surface-900 dark:text-white mb-1">No restaurants found</h3>
    <p class="text-sm text-surface-500 dark:text-gray-400">Try adjusting your search or add a new restaurant.</p>
</div>

<div id="restaurants-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 stagger-children">
    {{-- Populated via JS --}}
</div>

<div id="pagination-container" class="mt-8 flex justify-center hidden"></div>

{{-- Restaurant Modal --}}
<div id="restaurant-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-surface-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('restaurant-modal')"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-neutral-900 border border-surface-200 dark:border-white/10 rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-in zoom-in duration-300">
        <div class="flex items-center justify-between p-6 border-b border-surface-100 dark:border-white/5">
            <h3 class="text-xl font-display font-bold text-surface-900 dark:text-white" id="modal-title">Add Restaurant</h3>
            <button onclick="closeModal('restaurant-modal')" class="p-2 rounded-xl hover:bg-surface-100 dark:hover:bg-white/5 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="restaurant-form" class="space-y-4">
                <input type="hidden" name="id" id="edit-id">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Slug</label>
                        <input type="text" name="slug" required class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 outline-none transition-all dark:text-white">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Owner</label>
                    <select name="user_id" id="owner-select" required class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 outline-none transition-all dark:text-white">
                        <option value="">Select an owner...</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Address</label>
                    <input type="text" name="address" required id="modal-address" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 outline-none transition-all dark:text-white">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Latitude</label>
                        <input type="number" step="any" name="latitude" id="modal-lat" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Longitude</label>
                        <input type="number" step="any" name="longitude" id="modal-lng" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Delivery Fee (EGP)</label>
                        <input type="number" step="0.01" name="delivery_fee" id="modal-fee" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-surface-400 dark:text-gray-500 uppercase mb-1.5">Status</label>
                        <select name="is_active" id="modal-status" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm dark:text-white">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-surface-100 dark:border-white/5 flex justify-end gap-3 bg-surface-50 dark:bg-white/5">
            <button onclick="closeModal('restaurant-modal')" class="px-5 py-2.5 text-sm font-semibold text-surface-500 dark:text-gray-400 hover:text-surface-700 transition-colors">Cancel</button>
            <button onclick="submitRestaurant()" id="submit-btn" class="px-6 py-2.5 bg-brand-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-brand-500/20 hover:bg-brand-600 transition-all">Save Changes</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let restaurants = [];
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadRestaurants();
        loadOwners();
        initAutocompleteFallback();
    });

    function initAutocompleteFallback() {
        const input = document.getElementById('modal-address');
        input.addEventListener('change', async (e) => {
            const query = e.target.value;
            if (!query) return;
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
                const data = await res.json();
                if (data && data.length > 0) {
                    const place = data[0];
                    document.getElementById('modal-lat').value = place.lat;
                    document.getElementById('modal-lng').value = place.lon;
                    input.value = place.display_name;
                }
            } catch (e) {
                console.error("Geocoding failed", e);
            }
        });
    }

    async function loadRestaurants() {
        const grid = document.getElementById('restaurants-grid');
        document.getElementById('loading-state').classList.remove('hidden');
        grid.classList.add('hidden');

        try {
            const res = await fetch('/api/v1/restaurants', { headers });
            const json = await res.json();
            restaurants = json.data?.data || json.data || [];
            
            document.getElementById('loading-state').classList.add('hidden');
            if (restaurants.length === 0) {
                document.getElementById('empty-state').classList.remove('hidden');
                return;
            }

            grid.innerHTML = restaurants.map(r => `
                <div class="bg-white dark:bg-white/5 rounded-2xl border border-surface-200 dark:border-white/10 overflow-hidden shadow-sm hover:shadow-md transition-all group">
                    <div class="h-24 bg-surface-50 dark:bg-white/5 relative flex items-center justify-center text-4xl opacity-20 group-hover:opacity-40 transition-opacity">🏪</div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="min-w-0 pr-2">
                                <h3 class="text-sm font-bold text-surface-900 dark:text-white truncate">${r.name}</h3>
                                <p class="text-[10px] text-surface-500 dark:text-gray-400 font-medium uppercase tracking-wider">${r.user?.name || 'No Owner'}</p>
                            </div>
                            <div class="flex items-center gap-1 bg-brand-50 dark:bg-brand-500/10 px-2 py-0.5 rounded-full text-[10px] font-black text-brand-600 dark:text-brand-400">
                                <span>★</span> ${r.rating || 'NEW'}
                            </div>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <button onclick="deleteRestaurant(${r.id})" class="flex-1 py-2 text-[11px] font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors border border-red-100 dark:border-red-500/20">DELETE</button>
                            <button onclick="openEditModal(${r.id})" class="flex-1 py-2 text-[11px] font-bold text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 rounded-lg transition-colors border border-brand-100 dark:border-brand-500/20">EDIT</button>
                        </div>
                    </div>
                </div>
            `).join('');
            grid.classList.remove('hidden');
        } catch (e) {
            document.getElementById('loading-state').classList.add('hidden');
            Toast.show('Error', 'Failed to sync restaurants', 'error');
        }
    }

    function openAddModal() {
        document.getElementById('restaurant-form').reset();
        document.getElementById('edit-id').value = '';
        document.getElementById('modal-title').textContent = 'Add Restaurant';
        document.getElementById('restaurant-modal').classList.remove('hidden');
    }

    function openEditModal(id) {
        const r = restaurants.find(x => x.id === id);
        if (!r) return;
        
        const f = document.getElementById('restaurant-form');
        document.getElementById('edit-id').value = r.id;
        f.name.value = r.name;
        f.slug.value = r.slug;
        f.user_id.value = r.user_id;
        f.address.value = r.address;
        f.latitude.value = r.latitude;
        f.longitude.value = r.longitude;
        document.getElementById('modal-fee').value = r.delivery_fee || 25;
        f.is_active.value = r.is_active ? '1' : '0';
        
        document.getElementById('modal-title').textContent = 'Edit Restaurant';
        document.getElementById('restaurant-modal').classList.remove('hidden');
    }

    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    async function submitRestaurant() {
        const id = document.getElementById('edit-id').value;
        const form = document.getElementById('restaurant-form');
        const data = Object.fromEntries(new FormData(form).entries());
        
        const method = id ? 'PATCH' : 'POST';
        const url = id ? `/api/v1/restaurants/${id}` : '/api/v1/restaurants';
        
        try {
            const res = await fetch(url, { method, headers, body: JSON.stringify(data) });
            const json = await res.json();
            if (json.success) {
                Toast.show('Success', id ? 'Restaurant updated' : 'Restaurant added', 'success');
                closeModal('restaurant-modal');
                loadRestaurants();
            } else { Toast.show('Validation Error', json.message, 'error'); }
        } catch (e) { Toast.show('Error', 'Action failed', 'error'); }
    }

    async function deleteRestaurant(id) {
        const result = await Swal.fire({
            title: 'Delete Restaurant?',
            text: "This action cannot be undone. All menu items and data will be removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F26522',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete it!',
            background: document.documentElement.classList.contains('dark') ? '#171717' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
        });

        if (result.isConfirmed) {
            try {
                const res = await fetch(`/api/v1/restaurants/${id}`, { method: 'DELETE', headers });
                if (res.ok) { 
                    Toast.show('Deleted', 'Restaurant removed successfully', 'success'); 
                    loadRestaurants(); 
                } else {
                    const data = await res.json();
                    Toast.show('Error', data.message || 'Failed to delete', 'error');
                }
            } catch (e) { Toast.show('Error', 'Deletion failed', 'error'); }
        }
    }

    async function loadOwners() {
        const res = await fetch('/api/admin/users?role=restaurant_owner', { headers });
        const json = await res.json();
        if (json.success) {
            const sel = document.getElementById('owner-select');
            (json.data.data || json.data).forEach(u => sel.innerHTML += `<option value="${u.id}">${u.name}</option>`);
        }
    }
</script>
@endpush
@endsection