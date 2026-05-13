@extends('layouts.rider')
@section('title', 'Rider Settings — DeliverEats')
@section('content')
<div class="px-4 py-8 max-w-lg mx-auto">
    <h2 class="text-2xl font-display font-bold text-white mb-6">Settings</h2>
    
    <div class="space-y-4">
        <div class="bg-surface-800 rounded-2xl p-5 border border-white/10">
            <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Account Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-surface-400 uppercase mb-1.5">Full Name</label>
                    <input type="text" class="w-full bg-surface-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-brand-500 outline-none transition-all" value="Rider Name">
                </div>
                <div>
                    <label class="block text-xs font-bold text-surface-400 uppercase mb-1.5">Phone Number</label>
                    <input type="text" class="w-full bg-surface-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-brand-500 outline-none transition-all" value="+201000000000">
                </div>
            </div>
        </div>

        <div class="bg-surface-800 rounded-2xl p-5 border border-white/10">
            <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Vehicle Details</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-surface-400 uppercase mb-1.5">Vehicle Type</label>
                    <select class="w-full bg-surface-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-brand-500 outline-none transition-all">
                        <option>Motorcycle</option>
                        <option>Bicycle</option>
                        <option>Car</option>
                    </select>
                </div>
            </div>
        </div>

        <button onclick="Toast.show('Saved', 'Profile updated', 'success')" class="w-full py-4 bg-brand-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-brand-500/20 hover:bg-brand-600 transition-all">
            Save All Changes
        </button>

        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 w-full mt-4">
            @csrf
            <button type="submit" class="w-full py-4 bg-red-500/10 text-red-500 border border-red-500/20 rounded-2xl font-bold text-sm hover:bg-red-500/20 transition-all">
                Sign Out
            </button>
        </form>
    </div>
</div>
@endsection
