@extends('layouts.app')
@section('title', 'Leave a Review — DeliverEats')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-2 text-sm text-surface-300 hover:text-brand-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to orders
    </a>

    <div class="bg-white rounded-2xl border border-surface-200/50 p-6 lg:p-8">
        <h1 class="text-xl font-display font-bold mb-1">How was your order?</h1>
        <p class="text-sm text-surface-300 mb-8">Order #ORD-20260425-014 from Pizza Republic</p>

        <form class="space-y-8">
            {{-- Restaurant rating --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center text-lg">🍕</div>
                    <div>
                        <p class="text-sm font-semibold">Rate Pizza Republic</p>
                        <p class="text-xs text-surface-300">Food quality, packaging, accuracy</p>
                    </div>
                </div>
                <div class="star-rating-input flex gap-1 mb-3">
                    <input type="hidden" name="restaurant_rating" value="0">
                    @for($i = 0; $i < 5; $i++)
                    <button type="button" class="star empty text-3xl transition-transform hover:scale-125" data-selected="0">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </button>
                    @endfor
                </div>
                <textarea rows="3" name="restaurant_comment" placeholder="Tell us about the food, packaging, and overall experience..." class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 resize-none"></textarea>
            </div>

            <div class="border-t border-surface-100"></div>

            {{-- Rider rating --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold">M</div>
                    <div>
                        <p class="text-sm font-semibold">Rate your rider — Mohamed</p>
                        <p class="text-xs text-surface-300">Speed, professionalism, care</p>
                    </div>
                </div>
                <div class="star-rating-input flex gap-1 mb-3">
                    <input type="hidden" name="rider_rating" value="0">
                    @for($i = 0; $i < 5; $i++)
                    <button type="button" class="star empty text-3xl transition-transform hover:scale-125" data-selected="0">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </button>
                    @endfor
                </div>
                <textarea rows="2" name="rider_comment" placeholder="How was the delivery experience?" class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 resize-none"></textarea>
            </div>

            <button type="submit" onclick="event.preventDefault(); Toast.show('Review Submitted!', 'Thanks for your feedback', 'success'); setTimeout(() => window.location.href='{{ route('customer.orders.index') }}', 1500)" class="w-full px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] transition-all">
                Submit Review
            </button>
        </form>
    </div>
</div>
@endsection
