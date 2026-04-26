@extends('layouts.admin')
@section('page-title', 'User Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        <input type="text" placeholder="Search users..." class="px-4 py-2.5 rounded-xl bg-surface-900 border border-white/10 text-sm text-white placeholder:text-surface-300 focus:outline-none focus:ring-2 focus:ring-brand-500/30 w-64">
        <select class="px-4 py-2.5 rounded-xl bg-surface-900 border border-white/10 text-sm text-surface-200 focus:outline-none">
            <option>All Roles</option><option>Customer</option><option>Restaurant</option><option>Rider</option><option>Admin</option>
        </select>
    </div>
    <button class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-500 rounded-xl hover:bg-brand-600 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Add User</button>
</div>

<div class="bg-surface-900 rounded-2xl border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-surface-300 uppercase tracking-wider border-b border-white/5"><th class="px-6 py-3">User</th><th class="px-6 py-3">Role</th><th class="px-6 py-3">Email</th><th class="px-6 py-3">Joined</th><th class="px-6 py-3">Status</th><th class="px-6 py-3"></th></tr></thead>
        <tbody class="divide-y divide-white/5">
            @php $users = [
                ['name' => 'Ahmed Hassan', 'avatar' => 'A', 'color' => 'from-brand-400 to-brand-600', 'role' => 'Customer', 'email' => 'ahmed@example.com', 'joined' => 'Apr 2025', 'status' => 'Active', 'scolor' => 'text-emerald-400 bg-emerald-400/10'],
                ['name' => 'Shawarma Station', 'avatar' => 'S', 'color' => 'from-amber-400 to-orange-500', 'role' => 'Restaurant', 'email' => 'info@shawarma.com', 'joined' => 'Jan 2025', 'status' => 'Active', 'scolor' => 'text-emerald-400 bg-emerald-400/10'],
                ['name' => 'Mohamed Ali', 'avatar' => 'M', 'color' => 'from-blue-400 to-blue-600', 'role' => 'Rider', 'email' => 'mohamed@rider.com', 'joined' => 'Feb 2025', 'status' => 'Active', 'scolor' => 'text-emerald-400 bg-emerald-400/10'],
                ['name' => 'Sara Mohamed', 'avatar' => 'S', 'color' => 'from-pink-400 to-rose-500', 'role' => 'Customer', 'email' => 'sara@example.com', 'joined' => 'Mar 2025', 'status' => 'Active', 'scolor' => 'text-emerald-400 bg-emerald-400/10'],
                ['name' => 'Pizza Republic', 'avatar' => 'P', 'color' => 'from-red-400 to-rose-500', 'role' => 'Restaurant', 'email' => 'pizza@republic.com', 'joined' => 'Nov 2024', 'status' => 'Active', 'scolor' => 'text-emerald-400 bg-emerald-400/10'],
                ['name' => 'Hassan Magdy', 'avatar' => 'H', 'color' => 'from-emerald-400 to-green-500', 'role' => 'Rider', 'email' => 'hassan@rider.com', 'joined' => 'Mar 2025', 'status' => 'Active', 'scolor' => 'text-emerald-400 bg-emerald-400/10'],
                ['name' => 'Omar Khalil', 'avatar' => 'O', 'color' => 'from-cyan-400 to-blue-500', 'role' => 'Customer', 'email' => 'omar@example.com', 'joined' => 'Apr 2026', 'status' => 'Suspended', 'scolor' => 'text-red-400 bg-red-400/10'],
                ['name' => 'Kareem Admin', 'avatar' => 'K', 'color' => 'from-violet-400 to-violet-600', 'role' => 'Admin', 'email' => 'kareem@delivereats.com', 'joined' => 'Sep 2024', 'status' => 'Active', 'scolor' => 'text-emerald-400 bg-emerald-400/10'],
            ]; @endphp
            @foreach($users as $u)
            <tr class="hover:bg-white/[0.02]">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $u['color'] }} flex items-center justify-center text-white text-xs font-bold">{{ $u['avatar'] }}</div>
                        <span class="font-medium">{{ $u['name'] }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @php $roleColors = ['Customer' => 'text-blue-400 bg-blue-400/10', 'Restaurant' => 'text-amber-400 bg-amber-400/10', 'Rider' => 'text-emerald-400 bg-emerald-400/10', 'Admin' => 'text-violet-400 bg-violet-400/10']; @endphp
                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full {{ $roleColors[$u['role']] }}">{{ $u['role'] }}</span>
                </td>
                <td class="px-6 py-4 text-surface-300">{{ $u['email'] }}</td>
                <td class="px-6 py-4 text-surface-300">{{ $u['joined'] }}</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-[11px] font-bold rounded-full {{ $u['scolor'] }}">{{ $u['status'] }}</span></td>
                <td class="px-6 py-4"><button class="p-2 rounded-lg hover:bg-white/10 text-surface-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
