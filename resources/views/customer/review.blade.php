@extends('layouts.app')
@section('title', 'Leave a Review — DeliverEats')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-2 text-sm text-surface-300 hover:text-brand-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to orders
    </a>

    {{-- Loading State --}}
    <div id="review-loading" class="flex justify-center py-16">
        <svg class="w-8 h-8 text-brand-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    </div>

    {{-- Error State --}}
    <div id="review-error" class="hidden bg-white rounded-2xl border border-surface-200/50 p-8 text-center">
        <p class="text-red-500 font-semibold" id="error-message">Unable to load order.</p>
        <a href="{{ route('customer.orders.index') }}" class="mt-4 inline-block text-sm text-brand-600 hover:text-brand-700">← Back to orders</a>
    </div>

    {{-- Review Form --}}
    <div id="review-container" class="hidden bg-white rounded-2xl border border-surface-200/50 p-6 lg:p-8">
        <h1 class="text-xl font-display font-bold mb-1">How was your order?</h1>
        <p class="text-sm text-surface-300 mb-8" id="order-info">Loading...</p>

        <form id="review-form" class="space-y-8">
            {{-- Restaurant rating --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-lg">🍽️</div>
                    <div>
                        <p class="text-sm font-semibold" id="restaurant-name-label">Rate the Restaurant</p>
                        <p class="text-xs text-surface-300">Food quality, packaging, accuracy</p>
                    </div>
                </div>
                <div class="star-rating-input flex gap-1 mb-3" id="restaurant-stars" data-target="restaurant_rating">
                    <input type="hidden" name="restaurant_rating" value="0" id="restaurant_rating_input">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" class="star text-surface-200 text-3xl transition-transform hover:scale-125" data-value="{{ $i }}">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </button>
                    @endfor
                </div>
                <textarea rows="3" name="restaurant_comment" id="restaurant_comment" placeholder="Tell us about the food, packaging, and overall experience..." class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 resize-none"></textarea>
            </div>

            <div class="border-t border-surface-100"></div>

            {{-- Rider rating --}}
            <div id="rider-section">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold" id="rider-avatar-initial">R</div>
                    <div>
                        <p class="text-sm font-semibold" id="rider-name-label">Rate your rider</p>
                        <p class="text-xs text-surface-300">Speed, professionalism, care</p>
                    </div>
                </div>
                <div class="star-rating-input flex gap-1 mb-3" id="rider-stars" data-target="rider_rating">
                    <input type="hidden" name="rider_rating" value="0" id="rider_rating_input">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" class="star text-surface-200 text-3xl transition-transform hover:scale-125" data-value="{{ $i }}">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </button>
                    @endfor
                </div>
                <textarea rows="2" name="rider_comment" id="rider_comment" placeholder="How was the delivery experience?" class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 resize-none"></textarea>
            </div>

            <button type="submit" id="submit-review-btn" class="w-full px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] transition-all">
                Submit Review
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const orderId = "{{ $id }}";
    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadOrderForReview();
        initStarRatings();
    });

    async function loadOrderForReview() {
        try {
            const res = await fetch(`/api/v1/orders/${orderId}`, { headers });
            const json = await res.json();

            document.getElementById('review-loading').classList.add('hidden');

            if (!json.success || !json.data) {
                document.getElementById('error-message').textContent = json.message || 'Order not found.';
                document.getElementById('review-error').classList.remove('hidden');
                return;
            }

            const order = json.data;

            // Check if order is delivered
            const status = order.status?.value || order.status;
            if (status !== 'delivered') {
                document.getElementById('error-message').textContent = 'You can only review delivered orders.';
                document.getElementById('review-error').classList.remove('hidden');
                return;
            }

            // Populate order info
            document.getElementById('order-info').textContent = `Order #ORD-${order.id} from ${order.restaurant?.name || 'Restaurant'}`;
            document.getElementById('restaurant-name-label').textContent = `Rate ${order.restaurant?.name || 'the restaurant'}`;

            // Populate rider info
            if (order.rider) {
                document.getElementById('rider-name-label').textContent = `Rate your rider — ${order.rider.name}`;
                document.getElementById('rider-avatar-initial').textContent = order.rider.name.charAt(0).toUpperCase();
            } else {
                document.getElementById('rider-section').classList.add('hidden');
            }

            document.getElementById('review-container').classList.remove('hidden');
        } catch (e) {
            console.error('Failed to load order for review:', e);
            document.getElementById('review-loading').classList.add('hidden');
            document.getElementById('error-message').textContent = 'Failed to load order details.';
            document.getElementById('review-error').classList.remove('hidden');
        }
    }

    function initStarRatings() {
        document.querySelectorAll('.star-rating-input').forEach(container => {
            const stars = container.querySelectorAll('.star');
            const hiddenInput = container.querySelector('input[type="hidden"]');

            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const value = index + 1;
                    hiddenInput.value = value;

                    stars.forEach((s, i) => {
                        if (i < value) {
                            s.classList.remove('text-surface-200');
                            s.classList.add('text-amber-400');
                        } else {
                            s.classList.remove('text-amber-400');
                            s.classList.add('text-surface-200');
                        }
                    });
                });

                star.addEventListener('mouseenter', () => {
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.remove('text-surface-200');
                            s.classList.add('text-amber-300');
                        }
                    });
                });

                star.addEventListener('mouseleave', () => {
                    const current = parseInt(hiddenInput.value);
                    stars.forEach((s, i) => {
                        s.classList.remove('text-amber-300');
                        if (i < current) {
                            s.classList.remove('text-surface-200');
                            s.classList.add('text-amber-400');
                        } else {
                            s.classList.remove('text-amber-400');
                            s.classList.add('text-surface-200');
                        }
                    });
                });
            });
        });
    }

    // Form submission
    document.getElementById('review-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const restaurantRating = parseInt(document.getElementById('restaurant_rating_input').value);
        if (restaurantRating < 1) {
            Toast.show('Missing Rating', 'Please rate the restaurant (at least 1 star).', 'warning');
            return;
        }

        const btn = document.getElementById('submit-review-btn');
        btn.disabled = true;
        btn.textContent = 'Submitting...';

        const payload = {
            restaurant_rating: restaurantRating,
            restaurant_comment: document.getElementById('restaurant_comment').value || null,
            rider_rating: parseInt(document.getElementById('rider_rating_input').value) || null,
            rider_comment: document.getElementById('rider_comment').value || null,
        };

        try {
            const res = await fetch(`/api/v1/orders/${orderId}/review`, {
                method: 'POST',
                headers,
                body: JSON.stringify(payload)
            });
            const json = await res.json();

            if (json.success) {
                Toast.show('Review Submitted!', 'Thanks for your feedback! 🎉', 'success');
                setTimeout(() => window.location.href = '{{ route("customer.orders.index") }}', 1500);
            } else {
                Toast.show('Error', json.message || 'Failed to submit review.', 'error');
                btn.disabled = false;
                btn.textContent = 'Submit Review';
            }
        } catch (err) {
            console.error('Review submission failed:', err);
            Toast.show('Error', 'Something went wrong. Please try again.', 'error');
            btn.disabled = false;
            btn.textContent = 'Submit Review';
        }
    });
</script>
@endpush
@endsection
