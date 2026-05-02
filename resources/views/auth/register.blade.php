@extends('layouts.guest')
@section('title', 'Create Account — DeliverEats')

@section('content')
<div>
    <h1 class="text-2xl font-display font-bold tracking-tight">Create your account</h1>
    <p class="text-sm text-surface-800/60 mt-2">Start ordering from 500+ restaurants near you.</p>

    <form id="registerForm" class="mt-8 space-y-5" method="POST" action="/api/register">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-surface-800 mb-1.5">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Ahmed Hassan"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border @error('name') border-red-500 @else border-surface-200 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-surface-800 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="ahmed@example.com"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border @error('email') border-red-500 @else border-surface-200 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-surface-800 mb-1.5">Phone number</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+20 1XX XXX XXXX"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border @error('phone') border-red-500 @else border-surface-200 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            @error('phone')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
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
                    <input type="radio" name="role" value="restaurant_owner" class="peer hidden">
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
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border @error('password') border-red-500 @else border-surface-200 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
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

        <button type="submit" class="w-full flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all">
            Create Account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-surface-800/60">
        Already have an account? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">Sign in</a>
    </p>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const payload = Object.fromEntries(formData.entries());
    
    const errorContainer = document.getElementById('error-message') || document.createElement('p');
    errorContainer.id = 'error-message';
    errorContainer.className = 'mt-4 text-sm text-center text-red-500';
    this.appendChild(errorContainer);
    errorContainer.innerText = 'Creating account...';

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            errorContainer.innerText = data.message || Object.values(data.errors || {})[0]?.[0] || 'Registration failed.';
            return;
        }

        localStorage.setItem('auth_token', data.token);
        
        if (data.user.role === 'admin') {
            window.location.href = '/admin/dashboard';
        } else if (data.user.role === 'restaurant_owner') {
            window.location.href = '/restaurant/dashboard';
        } else if (data.user.role === 'rider') {
            window.location.href = '/rider/dashboard';
        } else {
            window.location.href = '/browse';
        }
        
    } catch (error) {
        errorContainer.innerText = 'An error occurred connecting to the server.';
    }
});
</script>
@endsection
