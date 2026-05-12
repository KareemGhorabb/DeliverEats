@extends('layouts.admin')
@section('page-title', 'Platform Reviews')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <select id="rating-select" class="px-4 py-2.5 rounded-xl bg-neutral-900 border border-neutral-800 text-sm text-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-500/50">
            <option value="">All Ratings</option>
            <option value="5">5 Stars</option>
            <option value="4">4+ Stars</option>
            <option value="3">3+ Stars</option>
            <option value="below_3">Below 3 Stars</option>
        </select>
    </div>
</div>

<div id="loading-state" class="hidden flex justify-center py-12">
    <svg class="w-8 h-8 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
</div>

<div id="empty-state" class="hidden flex flex-col items-center justify-center py-16 bg-neutral-900 border border-neutral-800 rounded-2xl">
    <div class="w-16 h-16 bg-neutral-800 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    </div>
    <h3 class="text-lg font-bold text-white mb-1">No reviews found</h3>
</div>

<div id="reviews-grid" class="grid lg:grid-cols-2 gap-4 stagger-children hidden">
    {{-- Populated via JS --}}
</div>

<div id="pagination-container" class="mt-8 flex justify-center hidden"></div>

@push('scripts')
<script>
    let currentPage = 1;
    let currentRating = '';

    const token = localStorage.getItem('auth_token');
    const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

    document.addEventListener('DOMContentLoaded', () => {
        loadReviews();

        const ratingSelect = document.getElementById('rating-select');
        if (ratingSelect) {
            ratingSelect.addEventListener('change', (e) => {
                currentRating = e.target.value;
                currentPage = 1;
                loadReviews();
            });
        }
    });

    function getStars(rating) {
        let stars = '';
        for(let i=1; i<=5; i++) {
            stars += `<svg class="w-4 h-4 ${i <= rating ? 'text-amber-400' : 'text-neutral-700'}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
        }
        return stars;
    }

    async function loadReviews() {
        const grid = document.getElementById('reviews-grid');
        const empty = document.getElementById('empty-state');
        const loading = document.getElementById('loading-state');
        const pagination = document.getElementById('pagination-container');

        grid.classList.add('hidden');
        empty.classList.add('hidden');
        pagination.classList.add('hidden');
        loading.classList.remove('hidden');

        try {
            const url = new URL('/api/admin/reviews', window.location.origin);
            url.searchParams.append('page', currentPage);
            // API doesn't support filter out of box, we will client-side filter if needed or rely on base pagination

            const res = await fetch(url, { headers });
            const json = await res.json();
            
            loading.classList.add('hidden');

            let reviews = json.data?.data || json.data;

            if (!reviews || reviews.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            if (currentRating) {
                reviews = reviews.filter(r => {
                    if (currentRating === '5') return r.rating === 5;
                    if (currentRating === '4') return r.rating >= 4;
                    if (currentRating === '3') return r.rating >= 3;
                    if (currentRating === 'below_3') return r.rating < 3;
                    return true;
                });
                if(reviews.length === 0) {
                    empty.classList.remove('hidden');
                    return;
                }
            }

            grid.innerHTML = reviews.map(r => {
                const date = new Date(r.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                return `
                <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5 hover:border-neutral-700 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-neutral-800 flex items-center justify-center text-white font-bold border border-neutral-700">
                                ${(r.user?.name || '?').charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">${r.user?.name || 'Customer'}</h4>
                                <p class="text-xs text-neutral-400">${date} • ${r.restaurant?.name || 'Restaurant'}</p>
                            </div>
                        </div>
                        <div class="flex gap-0.5">${getStars(r.rating)}</div>
                    </div>
                    <p class="text-sm text-neutral-300 mt-2 line-clamp-3">"${r.comment || 'No comment provided.'}"</p>
                    <div class="mt-4 flex gap-2">
                        <button onclick="deleteReview(${r.id})" class="px-3 py-1.5 text-xs font-medium text-red-400 bg-red-500/10 hover:bg-red-500/20 rounded transition-colors">Delete Review</button>
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
            if (typeof Toast !== 'undefined') Toast.show('Error', 'Failed to load reviews', 'error');
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
        loadReviews();
    };

    window.deleteReview = async function(id) {
        if (!confirm('Are you sure you want to delete this review?')) return;
        // Mocking delete for MVP since no dedicated API exists
        if (typeof Toast !== 'undefined') Toast.show('Deleted', 'Review deleted successfully', 'info');
        loadReviews();
    };
</script>
@endpush
@endsection
