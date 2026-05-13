@extends('layouts.admin')
@section('page-title', 'Admin Settings')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-surface-200 overflow-hidden">
        <div class="p-6 border-b border-surface-100">
            <h3 class="text-lg font-bold text-surface-900">Profile Settings</h3>
            <p class="text-sm text-surface-500">Manage your administrative account details.</p>
        </div>
        <div class="p-6 space-y-6">
            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-2">Full Name</label>
                    <input type="text" id="admin-fullname" class="w-full px-4 py-2.5 rounded-xl border border-surface-200 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none" value="Administrator">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-surface-700 mb-2">Email Address</label>
                    <input type="email" id="admin-email" class="w-full px-4 py-2.5 rounded-xl border border-surface-200 bg-surface-50 text-surface-500 outline-none" value="admin@delivereats.com" readonly>
                </div>
            </div>
            <button onclick="Toast.show('Saved', 'Settings updated successfully', 'success')" class="px-6 py-2.5 bg-brand-500 text-white rounded-xl font-bold text-sm hover:bg-brand-600 transition-all">Save Changes</button>
        </div>
    </div>
</div>
@endsection
