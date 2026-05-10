@extends('layouts.restaurant')
@section('page-title', 'Menu Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-surface-300">Manage your categories, items, and availability</p>
    </div>
    <button data-modal-open="modal-add-item" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 shadow-md shadow-brand-500/20 transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Item
    </button>
</div>

<div id="loading-menu" class="flex justify-center py-12">
    <svg class="w-8 h-8 text-brand-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="menu-container" class="space-y-6 hidden">
    <!-- Populated via JS -->
</div>

{{-- Add Item Modal --}}
<div id="modal-add-item" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black/40 transition-opacity"></div>
    <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transition-all transform">
        <div class="p-6 border-b border-surface-100 flex items-center justify-between">
            <h3 class="text-lg font-display font-bold">Add Menu Item</h3>
            <button data-modal-close class="p-2 -mr-2 rounded-lg hover:bg-surface-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form id="form-add-item" onsubmit="event.preventDefault(); submitNewItem();">
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium mb-1 block">Item Name</label>
                    <input type="text" id="item-name" required placeholder="e.g. Chicken Shawarma" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">Category</label>
                    <select id="item-category" required class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                        <!-- Populated via JS -->
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">Description</label>
                    <textarea id="item-desc" rows="2" placeholder="Brief description..." class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-1 block">Base Price ($)</label>
                        <input type="number" id="item-price" required step="0.01" placeholder="0.00" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-surface-100 flex justify-end gap-3">
                <button type="button" data-modal-close class="px-5 py-2.5 text-sm font-medium text-surface-800/70 hover:bg-surface-50 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors">Save Item</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };
    let currentRestaurantId = null;

    document.addEventListener('DOMContentLoaded', loadMenuData);

    async function loadMenuData() {
        try {
            // 1. Get owner's restaurant ID
            const setRes = await fetch('/api/restaurant/settings', { headers });
            const setJson = await setRes.json();
            if (!setJson.success || !setJson.data) {
                Toast.show('Error', 'Could not load restaurant settings.', 'error');
                return;
            }
            currentRestaurantId = setJson.data.id;

            // 2. Fetch full restaurant menu
            const menuRes = await fetch(`/api/v1/restaurants/${currentRestaurantId}`, { headers });
            const menuJson = await menuRes.json();

            if (menuJson.success) {
                renderMenu(menuJson.data.menu_categories || []);
                populateCategoryDropdown(menuJson.data.menu_categories || []);
            }
            document.getElementById('loading-menu').classList.add('hidden');
            document.getElementById('menu-container').classList.remove('hidden');
        } catch (e) {
            console.error(e);
            Toast.show('Error', 'Failed to load menu data', 'error');
        }
    }

    function renderMenu(categories) {
        const container = document.getElementById('menu-container');
        if (categories.length === 0) {
            container.innerHTML = `<div class="text-center py-12 text-surface-400">No categories found. Create one to add items.</div>`;
            return;
        }

        container.innerHTML = categories.map(cat => `
            <div class="bg-white rounded-2xl border border-surface-200/50 overflow-hidden">
                <div class="px-6 py-4 bg-surface-50 border-b border-surface-100 flex items-center justify-between">
                    <h3 class="text-sm font-display font-bold flex items-center gap-2">
                        ${cat.name}
                        <span class="text-xs font-normal text-surface-300 bg-white px-2 py-0.5 rounded-full border border-surface-200/50">${cat.menu_items ? cat.menu_items.length : 0}</span>
                    </h3>
                </div>
                <div class="divide-y divide-surface-100">
                    ${cat.menu_items ? cat.menu_items.map(item => `
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-surface-50/50 transition-colors" id="item-row-${item.id}">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold">${item.name}</p>
                                ${!item.is_available ? '<span class="px-2 py-0.5 bg-red-50 text-red-600 text-[10px] font-bold rounded-full">Unavailable</span>' : ''}
                            </div>
                            <p class="text-xs text-surface-300 mt-0.5">${item.description || ''}</p>
                        </div>
                        <p class="text-sm font-bold w-16 text-right">EGP ${parseFloat(item.price).toFixed(2)}</p>
                        <button onclick="toggleItem(${item.id})" class="relative w-10 h-6 rounded-full transition-colors ${item.is_available ? 'bg-emerald-500' : 'bg-surface-300'}">
                            <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform ${item.is_available ? 'translate-x-4' : 'translate-x-0.5'}"></span>
                        </button>
                        <div class="flex items-center gap-1">
                            <button onclick="deleteItem(${item.id})" class="p-2 rounded-lg hover:bg-red-50 text-surface-300 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    `).join('') : '<div class="px-6 py-4 text-sm text-surface-400">No items in this category.</div>'}
                </div>
            </div>
        `).join('');
    }

    function populateCategoryDropdown(categories) {
        const select = document.getElementById('item-category');
        select.innerHTML = categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('');
    }

    async function submitNewItem() {
        try {
            const payload = {
                restaurant_id: currentRestaurantId,
                menu_category_id: parseInt(document.getElementById('item-category').value),
                name: document.getElementById('item-name').value,
                description: document.getElementById('item-desc').value,
                price: parseFloat(document.getElementById('item-price').value),
                is_available: true
            };

            const res = await fetch('/api/v1/menu-items', {
                method: 'POST',
                headers,
                body: JSON.stringify(payload)
            });
            const json = await res.json();

            if (json.success) {
                Toast.show('Success', 'Item added successfully', 'success');
                closeModal('modal-add-item');
                document.getElementById('form-add-item').reset();
                loadMenuData();
            } else {
                Toast.show('Error', json.message || 'Failed to add item', 'error');
            }
        } catch (e) {
            console.error(e);
            Toast.show('Error', 'Network error', 'error');
        }
    }

    async function toggleItem(id) {
        try {
            const res = await fetch(`/api/v1/menu-items/${id}/toggle-availability`, { method: 'PATCH', headers });
            const json = await res.json();
            if (json.success) {
                loadMenuData(); // Reload to refresh state
            }
        } catch (e) {
            console.error(e);
            Toast.show('Error', 'Failed to toggle availability', 'error');
        }
    }

    async function deleteItem(id) {
        if (!confirm('Are you sure you want to delete this item?')) return;
        try {
            const res = await fetch(`/api/v1/menu-items/${id}`, { method: 'DELETE', headers });
            const json = await res.json();
            if (json.success) {
                Toast.show('Deleted', 'Item removed', 'success');
                loadMenuData();
            } else {
                Toast.show('Error', json.message || 'Failed to delete', 'error');
            }
        } catch (e) {
            console.error(e);
            Toast.show('Error', 'Network error', 'error');
        }
    }
</script>
@endpush
@endsection
