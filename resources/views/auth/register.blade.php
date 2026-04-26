@extends('layouts.guest')
@section('title', 'Create Account — DeliverEats')

@section('content')
<div>
    <h1 class="text-2xl font-display font-bold tracking-tight">Create your account</h1>
    <p class="text-sm text-surface-800/60 mt-2">Start ordering from 500+ restaurants near you.</p>

    <form class="mt-8 space-y-5" method="POST" action="#">
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="first_name" class="block text-sm font-medium text-surface-800 mb-1.5">First name</label>
                <input id="first_name" type="text" name="first_name" required placeholder="Ahmed"
                    class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-surface-800 mb-1.5">Last name</label>
                <input id="last_name" type="text" name="last_name" required placeholder="Hassan"
                    class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-surface-800 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" required placeholder="ahmed@example.com"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-surface-800 mb-1.5">Phone number</label>
            <input id="phone" type="tel" name="phone" required placeholder="+20 1XX XXX XXXX"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
        </div>

        <div>
            <label class="block text-sm font-medium text-surface-800 mb-2">I want to</label>
            <div class="grid grid-cols-3 gap-2">
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="customer" checked class="peer hidden">
                    <div class="p-3 rounded-xl border-2 border-surface-200 text-center peer-checked:border-brand-500 peer-checked:bg-brand-50 transition-all hover:border-surface-300">
                        <div class="text-2xl mb-1">🛒</div>
                        <span class="text-xs font-semibold text-surface-800">Order Food</span>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="restaurant" class="peer hidden">
                    <div class="p-3 rounded-xl border-2 border-surface-200 text-center peer-checked:border-brand-500 peer-checked:bg-brand-50 transition-all hover:border-surface-300">
                        <div class="text-2xl mb-1">🏪</div>
                        <span class="text-xs font-semibold text-surface-800">List My Place</span>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="rider" class="peer hidden">
                    <div class="p-3 rounded-xl border-2 border-surface-200 text-center peer-checked:border-brand-500 peer-checked:bg-brand-50 transition-all hover:border-surface-300">
                        <div class="text-2xl mb-1">🛵</div>
                        <span class="text-xs font-semibold text-surface-800">Deliver</span>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label for="reg_password" class="block text-sm font-medium text-surface-800 mb-1.5">Password</label>
            <input id="reg_password" type="password" name="password" required placeholder="Min 8 characters"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            <div class="mt-2 flex gap-1">
                <div class="h-1 flex-1 rounded-full bg-surface-200"></div>
                <div class="h-1 flex-1 rounded-full bg-surface-200"></div>
                <div class="h-1 flex-1 rounded-full bg-surface-200"></div>
                <div class="h-1 flex-1 rounded-full bg-surface-200"></div>
            </div>
        </div>

        <div class="flex items-start gap-2">
            <input id="terms" type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-surface-300 text-brand-500 focus:ring-brand-500/30">
            <label for="terms" class="text-xs text-surface-800/60 leading-relaxed">I agree to the <a href="#" class="text-brand-600 hover:underline">Terms of Service</a> and <a href="#" class="text-brand-600 hover:underline">Privacy Policy</a></label>
        </div>

        <a href="{{ route('customer.home') }}" class="w-full flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all">
            Create Account
        </a>
    </form>

    <p class="mt-6 text-center text-sm text-surface-800/60">
        Already have an account? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">Sign in</a>
    </p>
</div>
@endsection
