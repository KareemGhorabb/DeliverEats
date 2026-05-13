@extends('layouts.app')
@section('title', 'My Orders — DeliverEats')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-display font-bold mb-6">My Orders</h1>

    <div id="orders-list">
        {{-- Skeleton Loading --}}
        <div class="animate-pulse space-y-4">
            <div class="h-32 bg-white rounded-2xl border border-surface-100"></div>
            <div class="h-32 bg-white rounded-2xl border border-surface-100"></div>
        </div>
    </div>

    {{-- Review Modal --}}
    <div id="review-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm transition-opacity" onclick="closeReviewModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
            <div class="p-6 border-b border-surface-100 flex items-center justify-between">
                <h3 class="text-xl font-display font-bold">Rate your experience</h3>
                <button onclick="closeReviewModal()" class="p-2 rounded-xl hover:bg-surface-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="review-form" class="p-6 space-y-6" onsubmit="submitReview(event)">
                <input type="hidden" id="review-order-id">
                
                {{-- Restaurant Rating --}}
                <div class="space-y-3">
                    <p class="text-sm font-bold text-surface-900">How was the food?</p>
                    <div class="flex gap-2" id="restaurant-rating-stars">
                        @for($i=1; $i<=5; $i++)
                            <button type="button" onclick="setRating('restaurant', {{ $i }})" class="star-btn p-1 text-surface-200 hover:text-amber-400 transition-colors" data-value="{{ $i }}">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        @endfor
                    </div>
                </div>

                {{-- Rider Rating --}}
                <div class="space-y-3">
                    <p class="text-sm font-bold text-surface-900">How was the delivery?</p>
                    <div class="flex gap-2" id="rider-rating-stars">
                        @for($i=1; $i<=5; $i++)
                            <button type="button" onclick="setRating('rider', {{ $i }})" class="star-btn p-1 text-surface-200 hover:text-amber-400 transition-colors" data-value="{{ $i }}">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        @endfor
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-surface-700">Optional Comment</label>
                    <textarea id="review-comment" rows="3" class="w-full px-4 py-3 rounded-2xl bg-surface-50 border border-surface-200 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-sm resize-none" placeholder="What did you like or dislike?"></textarea>
                </div>

                <button type="submit" id="btn-submit-review" class="w-full py-4 bg-brand-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-brand-500/20 hover:bg-brand-600 transition-all">Submit Review</button>
            </form>
        </div>
    </div>
</div>

<style>
    .star-active { color: #F59E0B !important; }
</style>

@push('scripts')
<script>
    let currentRatings = { restaurant: 5, rider: 5 };

    document.addEventListener('DOMContentLoaded', () => {
        loadOrders();
    });

    async function loadOrders() {
        const container = document.getElementById('orders-list');
        try {
            const res = await fetch('/api/v1/orders', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                }
            });
            const json = await res.json();
            
            if (json.success) {
                if (json.data.length === 0) {
                    container.innerHTML = `<div class="bg-white rounded-2xl border border-surface-200/50 p-10 text-center">
                        <p class="text-surface-300">You haven't placed any orders yet.</p>
                        <a href="{{ route('customer.home') }}" class="inline-block mt-4 text-brand-500 font-bold">Start Browsing</a>
                    </div>`;
                    return;
                }
                renderOrders(json.data);
            }
        } catch (e) { console.error(e); }
    }

    function renderOrders(orders) {
        const container = document.getElementById('orders-list');
        if (!orders || orders.length === 0) {
            container.innerHTML = `<div class="bg-white dark:bg-white/5 rounded-3xl border border-surface-200 dark:border-white/10 p-12 text-center">
                <div class="w-16 h-16 bg-surface-50 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">📦</div>
                <h3 class="text-lg font-bold text-surface-900 dark:text-white mb-1">No orders yet</h3>
                <p class="text-sm text-surface-500 dark:text-gray-400 mb-6">Looks like you haven't placed any orders.</p>
                <a href="/browse" class="inline-flex px-6 py-2.5 bg-brand-500 text-white rounded-xl font-bold text-sm hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20">Start Browsing</a>
            </div>`;
            return;
        }

        container.innerHTML = orders.map(order => {
            const items = order.items.map(i => `<span class="inline-block px-2 py-0.5 bg-surface-50 dark:bg-white/5 rounded text-[11px] text-surface-600 dark:text-gray-400 mr-1 mb-1">${i.quantity}× ${i.menu_item?.name || 'Item'}</span>`).join('');
            const status = order.status.value || order.status;
            
            let statusClasses = 'bg-surface-100 text-surface-600 dark:bg-white/5 dark:text-gray-400';
            if (status === 'delivered') statusClasses = 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400';
            if (status === 'cancelled') statusClasses = 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400';
            if (['pending', 'accepted', 'preparing', 'ready_for_pickup'].includes(status)) statusClasses = 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400';

            const canReview = status === 'delivered' && !order.is_reviewed;
            
            const actionButtons = `
                <div class="flex items-center gap-2">
                    ${canReview ? `<button onclick="openReviewModal(${order.id})" class="px-4 py-1.5 text-xs font-bold bg-brand-500 text-white hover:bg-brand-600 rounded-lg transition-colors shadow-sm">Review</button>` : ''}
                    ${(status !== 'delivered' && status !== 'cancelled') ? `<a href="/orders/${order.id}/track" class="px-4 py-1.5 text-xs font-bold text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-500/30 hover:bg-brand-50 dark:hover:bg-brand-500/10 rounded-lg transition-all">Track</a>` : ''}
                </div>
            `;

            return `
            <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-surface-200 dark:border-white/10 overflow-hidden mb-4 hover:shadow-md transition-all duration-300">
                <div class="p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-surface-50 dark:bg-white/5 flex items-center justify-center text-2xl border border-surface-100 dark:border-white/5">
                                ${order.restaurant?.logo ? `<img src="${order.restaurant.logo}" class="w-full h-full object-cover rounded-2xl">` : '🏪'}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-surface-900 dark:text-white truncate">${order.restaurant?.name || 'Restaurant'}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] text-surface-400 dark:text-gray-500 uppercase font-black tracking-tighter">#ORD-${order.id}</span>
                                    <span class="text-[10px] text-surface-300 dark:text-gray-600">•</span>
                                    <span class="text-[10px] text-surface-400 dark:text-gray-500 font-medium">${new Date(order.created_at).toLocaleDateString()}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-black text-surface-900 dark:text-white">EGP ${parseFloat(order.total).toFixed(2)}</p>
                                <p class="text-[9px] text-surface-400 dark:text-gray-500 uppercase font-bold tracking-widest">${order.payment?.method || order.payment_method || 'Cash'}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${statusClasses}">${status.replace(/_/g, ' ')}</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-surface-100 dark:border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex-1 min-w-0 flex flex-wrap">${items}</div>
                        ${actionButtons}
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    window.openReviewModal = function(orderId) {
        document.getElementById('review-order-id').value = orderId;
        document.getElementById('review-modal').classList.remove('hidden');
        setRating('restaurant', 5);
        setRating('rider', 5);
    };

    window.closeReviewModal = function() {
        document.getElementById('review-modal').classList.add('hidden');
    };

    window.setRating = function(type, val) {
        currentRatings[type] = val;
        const container = document.getElementById(`${type}-rating-stars`);
        const stars = container.querySelectorAll('.star-btn');
        stars.forEach((s, idx) => {
            if (idx < val) s.classList.add('star-active');
            else s.classList.remove('star-active');
        });
    };

    window.submitReview = async function(e) {
        e.preventDefault();
        const orderId = document.getElementById('review-order-id').value;
        const comment = document.getElementById('review-comment').value;
        const btn = document.getElementById('btn-submit-review');
        
        btn.disabled = true;
        btn.textContent = 'Submitting...';

        try {
            const res = await fetch(`/api/v1/orders/${orderId}/review`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${Auth.getToken()}`
                },
                body: JSON.stringify({
                    restaurant_rating: currentRatings.restaurant,
                    rider_rating: currentRatings.rider,
                    restaurant_comment: comment,
                })
            });
            const json = await res.json();
            if (json.success) {
                if (window.Toast) window.Toast.show('Success', 'Thank you for your feedback!', 'success');
                closeReviewModal();
                loadOrders(); // Refresh list
            } else {
                if (window.Toast) window.Toast.show('Error', json.message, 'error');
            }
        } catch (e) {
            if (window.Toast) window.Toast.show('Error', 'Failed to submit review', 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Submit Review';
        }
    };
</script>
@endpush
@endsection
