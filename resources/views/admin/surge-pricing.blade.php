@extends('layouts.admin')
@section('page-title', 'Surge Pricing Control')
@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <p class="text-xs text-neutral-400 mb-1">Surge Engine</p>
        <p class="text-lg font-display font-bold text-amber-400">Active</p>
        <p class="text-xs text-neutral-500 mt-1">Multi-strategy engine running</p>
    </div>
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <p class="text-xs text-neutral-400 mb-1">Max System Cap</p>
        <p class="text-lg font-display font-bold text-red-400">3.0×</p>
        <p class="text-xs text-neutral-500 mt-1">Hard limit for customer protection</p>
    </div>
    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
        <p class="text-xs text-neutral-400 mb-1">Manual Overrides</p>
        <p class="text-lg font-display font-bold text-white" id="override-count">0</p>
        <p class="text-xs text-neutral-500 mt-1">Currently active admin overrides</p>
    </div>
</div>

<div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-6 mb-6">
    <h3 class="text-sm font-bold text-white mb-4">Restaurant Surge Status & Overrides</h3>
    
    <div id="loading-surge" class="flex justify-center py-8">
        <svg class="w-6 h-6 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    </div>

    <div id="surge-table-container" class="overflow-x-auto hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-neutral-400 uppercase tracking-wider border-b border-neutral-800">
                    <th class="px-4 py-3 font-medium">Restaurant</th>
                    <th class="px-4 py-3 font-medium">Demand Multiplier</th>
                    <th class="px-4 py-3 font-medium">Time Multiplier</th>
                    <th class="px-4 py-3 font-medium text-amber-400">Final Multiplier</th>
                    <th class="px-4 py-3 font-medium text-right">Manual Override</th>
                </tr>
            </thead>
            <tbody id="surge-tbody" class="divide-y divide-neutral-800 text-white">
                {{-- Populated via JS --}}
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadSurgeData();
        setInterval(loadSurgeData, 30000); // refresh every 30s
    });

    async function loadSurgeData() {
        try {
            const res = await fetch('/api/admin/surge-pricing', { headers });
            const json = await res.json();
            
            document.getElementById('loading-surge').classList.add('hidden');
            const tbody = document.getElementById('surge-tbody');
            
            if (json.success && json.data) {
                let overrideCount = 0;
                
                tbody.innerHTML = json.data.map(item => {
                    const bd = item.breakdown || {};
                    const demand = bd.DemandBasedStrategy?.multiplier || 1.0;
                    const time = bd.TimeBasedStrategy?.multiplier || 1.0;
                    const manual = bd.MultiplierStrategy?.multiplier || 1.0;
                    const final = bd.final?.multiplier || 1.0;
                    
                    if (manual > 1.0) overrideCount++;
                    
                    return `
                    <tr class="hover:bg-neutral-800/50 transition-colors">
                        <td class="px-4 py-4 font-medium">${item.restaurant_name}</td>
                        <td class="px-4 py-4 text-neutral-400">${demand.toFixed(1)}×</td>
                        <td class="px-4 py-4 text-neutral-400">${time.toFixed(1)}×</td>
                        <td class="px-4 py-4 font-bold text-amber-400">${final.toFixed(1)}×</td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <input type="number" step="0.1" min="1.0" max="5.0" id="override-${item.restaurant_id}" value="${manual.toFixed(1)}" class="w-20 px-2 py-1.5 bg-neutral-950 border border-neutral-700 rounded text-center text-white focus:outline-none focus:border-orange-500">
                                <button onclick="setOverride(${item.restaurant_id})" class="px-3 py-1.5 bg-neutral-800 hover:bg-neutral-700 border border-neutral-700 rounded text-xs font-semibold text-white transition-colors">Apply</button>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
                
                document.getElementById('override-count').textContent = overrideCount;
                document.getElementById('surge-table-container').classList.remove('hidden');
            }
        } catch (e) {
            console.error('Failed to load surge data', e);
        }
    }

    window.setOverride = async function(restaurantId) {
        const val = document.getElementById(`override-${restaurantId}`).value;
        const multiplier = parseFloat(val);
        
        if (isNaN(multiplier) || multiplier < 1.0) {
            if (typeof Toast !== 'undefined') Toast.show('Invalid', 'Multiplier must be >= 1.0', 'error');
            return;
        }
        
        try {
            const res = await fetch('/api/admin/surge-pricing/override', {
                method: 'POST',
                headers,
                body: JSON.stringify({ restaurant_id: restaurantId, multiplier })
            });
            
            const json = await res.json();
            
            if (res.ok && json.success) {
                if (typeof Toast !== 'undefined') Toast.show('Success', json.message, 'success');
                loadSurgeData();
            } else {
                throw new Error(json.message || 'Failed to set override');
            }
        } catch (e) {
            if (typeof Toast !== 'undefined') Toast.show('Error', e.message, 'error');
        }
    };
</script>
@endpush
@endsection
