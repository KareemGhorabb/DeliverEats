@extends('layouts.app')
@section('title', 'My Profile — DeliverEats')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-display font-bold mb-8">My Profile</h1>

    <div class="space-y-6">
        {{-- Avatar & basic info --}}
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-brand-500/20">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <h2 class="text-lg font-display font-bold">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-surface-300">Member since {{ auth()->user()->created_at->format('F Y') }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[11px] font-bold rounded-full">Gold Member</span>
                        <span class="text-xs text-surface-300">{{ auth()->user()->orders()->count() }} orders placed</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Personal info --}}
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
            <h3 class="text-sm font-display font-bold mb-4">Personal Information</h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-surface-300 mb-1">Full Name</label>
                    <input type="text" value="{{ auth()->user()->name }}" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-300 mb-1">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-300 mb-1">Phone</label>
                    <input type="tel" value="{{ auth()->user()->phone ?? '' }}" placeholder="+20 100 234 5678" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-300 mb-1">Date of Birth</label>
                    <input type="date" value="1998-03-15" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400">
                </div>
            </div>
            <button onclick="Toast.show('Saved!', 'Profile updated successfully', 'success')" class="mt-4 px-6 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors">Save Changes</button>
        </div>

        {{-- Saved addresses --}}
        <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-display font-bold">Saved Addresses</h3>
                <button class="text-xs text-brand-600 font-semibold hover:text-brand-700">+ Add New</button>
            </div>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 bg-surface-50 rounded-xl border border-surface-200/50">
                    <svg class="w-5 h-5 text-brand-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">Home</p>
                        <p class="text-xs text-surface-300" id="profile-address-display">Fetching address...</p>
                    </div>
                    <span class="px-2 py-0.5 bg-brand-50 text-brand-600 text-[10px] font-bold rounded">Default</span>
                </div>
                <div class="flex items-start gap-3 p-3 bg-surface-50 rounded-xl border border-surface-200/50">
                    <svg class="w-5 h-5 text-surface-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">Office</p>
                        <p class="text-xs text-surface-300">Smart Village, Building B4, Giza</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
