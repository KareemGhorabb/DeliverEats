@extends('layouts.restaurant')
@section('page-title', 'Settings')
@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
        <h3 class="text-sm font-display font-bold mb-4">Restaurant Profile</h3>
        <div class="space-y-4">
            <div><label class="text-xs font-medium text-surface-300 mb-1 block">Restaurant Name</label><input value="Shawarma Station" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
            <div><label class="text-xs font-medium text-surface-300 mb-1 block">Cuisine Type</label><input value="Middle Eastern · Grilled · Wraps" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
            <div><label class="text-xs font-medium text-surface-300 mb-1 block">Address</label><input value="45 King Faisal St, Downtown" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-xs font-medium text-surface-300 mb-1 block">Phone</label><input value="+20 100 555 1234" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
                <div><label class="text-xs font-medium text-surface-300 mb-1 block">Avg Prep Time (min)</label><input type="number" value="20" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30"></div>
            </div>
            <div><label class="text-xs font-medium text-surface-300 mb-1 block">Description</label><textarea rows="3" class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-brand-500/30">Authentic shawarma and Middle Eastern grills since 2018. Everything is prepared fresh daily using premium ingredients.</textarea></div>
        </div>
        <button onclick="Toast.show('Saved!', 'Settings updated', 'success')" class="mt-4 px-6 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 transition-colors">Save Changes</button>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-6">
        <h3 class="text-sm font-display font-bold mb-4">Operating Hours</h3>
        <div class="space-y-3">
            @php $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']; @endphp
            @foreach($days as $day)
            <div class="flex items-center gap-4"><span class="text-sm w-28">{{ $day }}</span><input type="time" value="10:00" class="px-3 py-2 rounded-lg bg-surface-50 border border-surface-200 text-sm"><span class="text-surface-300">to</span><input type="time" value="23:00" class="px-3 py-2 rounded-lg bg-surface-50 border border-surface-200 text-sm"></div>
            @endforeach
        </div>
    </div>
</div>
@endsection
