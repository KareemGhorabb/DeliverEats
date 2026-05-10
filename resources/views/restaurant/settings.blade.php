@extends('layouts.restaurant')
@section('page-title', 'Settings')
@section('content')
<div id="loading-settings" class="flex justify-center py-12">
    <svg class="w-8 h-8 text-brand-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="settings-container" class="max-w-2xl space-y-6 hidden">
    <form id="settings-form" onsubmit="event.preventDefault(); saveSettings();">
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
            <h3 class="text-sm font-display font-bold mb-4">Restaurant Profile</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-medium text-surface-300 mb-1 block">Restaurant Name</label>
                    <input type="text" id="rest-name" required placeholder="e.g. Shawarma Station" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                </div>
                <div>
                    <label class="text-xs font-medium text-surface-300 mb-1 block">Category</label>
                    <input type="text" id="rest-category" placeholder="e.g. Middle Eastern" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                </div>
                <div>
                    <label class="text-xs font-medium text-surface-300 mb-1 block">Address</label>
                    <input type="text" id="rest-address" required placeholder="Full Address" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-surface-300 mb-1 block">Phone</label>
                        <input type="text" id="rest-phone" required placeholder="+1234567890" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-surface-300 mb-1 block">Delivery Fee / Min Order ($)</label>
                        <input type="number" id="rest-delivery-fee" required step="0.01" placeholder="5.00" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-surface-300 mb-1 block">Description</label>
                    <textarea id="rest-desc" rows="3" placeholder="Describe your restaurant..." class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-brand-500/30"></textarea>
                </div>
            </div>
            <button type="submit" id="btn-save" class="mt-4 px-6 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors flex items-center gap-2">
                Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const user = JSON.parse(localStorage.getItem('auth_user'));
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };
    let currentRestaurantId = null;

    document.addEventListener('DOMContentLoaded', loadSettings);

    async function loadSettings() {
        try {
            const res = await fetch('/api/restaurant/settings', { headers });
            const json = await res.json();
            
            document.getElementById('loading-settings').classList.add('hidden');
            document.getElementById('settings-container').classList.remove('hidden');

            if (json.success && json.data) {
                const r = json.data;
                currentRestaurantId = r.id;
                document.getElementById('rest-name').value = r.name || '';
                document.getElementById('rest-category').value = r.category || '';
                document.getElementById('rest-address').value = r.address || '';
                document.getElementById('rest-phone').value = r.phone || '';
                document.getElementById('rest-delivery-fee').value = r.delivery_radius_km || r.min_order_amount || 0; // mapped to min_order_amount/delivery_fee in store
                document.getElementById('rest-desc').value = r.description || '';
            } else {
                Toast.show('Info', 'Please create your restaurant profile to start receiving orders.', 'info');
            }
        } catch (e) {
            console.error(e);
            Toast.show('Error', 'Failed to load settings', 'error');
        }
    }

    async function saveSettings() {
        const btn = document.getElementById('btn-save');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        try {
            const name = document.getElementById('rest-name').value;
            const payload = {
                user_id: user.id,
                name: name,
                slug: name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''),
                category: document.getElementById('rest-category').value,
                address: document.getElementById('rest-address').value,
                phone: document.getElementById('rest-phone').value,
                delivery_fee: parseFloat(document.getElementById('rest-delivery-fee').value) || 0,
                description: document.getElementById('rest-desc').value
            };

            let url = '/api/v1/restaurants';
            let method = 'POST';

            if (currentRestaurantId) {
                url = `/api/v1/restaurants/${currentRestaurantId}`;
                method = 'PUT';
            }

            const res = await fetch(url, {
                method,
                headers,
                body: JSON.stringify(payload)
            });
            const json = await res.json();

            if (json.success) {
                currentRestaurantId = json.data.id;
                Toast.show('Success', 'Restaurant settings saved!', 'success');
                
                // Update local storage so sidebar updates instantly
                const updatedUser = { ...user, restaurant_name: json.data.name, restaurant_id: json.data.id };
                Auth.setUser(updatedUser);
            } else {
                Toast.show('Error', json.message || 'Validation failed', 'error');
            }
        } catch (e) {
            console.error(e);
            Toast.show('Error', 'Failed to save settings', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Save Changes';
        }
    }
</script>
@endpush
@endsection
