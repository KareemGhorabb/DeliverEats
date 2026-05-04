@extends('layouts.restaurant')
@section('page-title', 'Revenue & Payouts')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <p class="text-xs text-surface-300 mb-1">Total Revenue (Apr)</p>
        <p class="text-2xl font-display font-bold text-emerald-600">$12,847</p>
        <span class="text-xs text-emerald-600 font-medium">↑ 18% vs Mar</span>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <p class="text-xs text-surface-300 mb-1">Platform Commission</p>
        <p class="text-2xl font-display font-bold text-surface-900">$1,927</p>
        <span class="text-xs text-surface-300">15% rate</span>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <p class="text-xs text-surface-300 mb-1">Net Earnings</p>
        <p class="text-2xl font-display font-bold text-surface-900">$10,920</p>
        <span class="text-xs text-surface-300">After fees</span>
    </div>
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <p class="text-xs text-surface-300 mb-1">Pending Payout</p>
        <p class="text-2xl font-display font-bold text-brand-600">$2,340</p>
        <span class="text-xs text-brand-600 font-medium">Processing Tue</span>
    </div>
</div>

{{-- Payout history --}}
<div class="bg-white rounded-2xl border border-surface-200/50 overflow-hidden">
    <div class="px-6 py-4 border-b border-surface-100"><h3 class="text-sm font-display font-bold">Payout History</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-surface-300 uppercase tracking-wider border-b border-surface-100"><th class="px-6 py-3">Date</th><th class="px-6 py-3">Period</th><th class="px-6 py-3">Orders</th><th class="px-6 py-3">Gross</th><th class="px-6 py-3">Commission</th><th class="px-6 py-3">Net Payout</th><th class="px-6 py-3">Status</th></tr></thead>
            <tbody class="divide-y divide-surface-100">
                @php
                $mappedPayouts = collect($payoutOrders)->map(fn($o) => [
                    'date'       => \Carbon\Carbon::parse($o['delivered_at'] ?? $o['created_at'])->format('M d'),
                    'period'     => \Carbon\Carbon::parse($o['created_at'])->format('M d'),
                    'orders'     => 1,
                    'gross'      => number_format($o['subtotal'], 2),
                    'commission' => number_format($o['subtotal'] * 0.15, 2),
                    'net'        => number_format($o['subtotal'] * 0.85, 2),
                    'status'     => 'Paid',
                    'color'      => 'text-emerald-600 bg-emerald-50',
                ])->values()->all();
                @endphp
                @foreach($mappedPayouts as $p)
                <tr class="hover:bg-surface-50"><td class="px-6 py-4 font-medium">{{ $p['date'] }}</td><td class="px-6 py-4 text-surface-300">{{ $p['period'] }}</td><td class="px-6 py-4">{{ $p['orders'] }}</td><td class="px-6 py-4">${{ $p['gross'] }}</td><td class="px-6 py-4 text-red-500">-${{ $p['commission'] }}</td><td class="px-6 py-4 font-bold">${{ $p['net'] }}</td><td class="px-6 py-4"><span class="px-2.5 py-1 text-[11px] font-bold rounded-full {{ $p['color'] }}">{{ $p['status'] }}</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-3">
    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div><p class="text-sm font-semibold text-blue-800">Stripe Connect</p><p class="text-xs text-blue-700/70 mt-0.5">Payouts are processed every Tuesday via Stripe Connect. Funds are deposited directly to your bank account within 2-3 business days.</p></div>
</div>
@endsection
