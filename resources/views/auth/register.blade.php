@extends('layouts.guest')
@section('title', 'Create Account — DeliverEats')

@section('content')
<div>
    <h1 class="text-2xl font-display font-bold tracking-tight dark:text-white">Create your account</h1>
    <p class="text-sm text-surface-800/60 dark:text-gray-400 mt-2">Start ordering from 500+ restaurants near you.</p>

    <form id="registerForm" class="mt-8 space-y-5" method="POST" action="/api/register">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-surface-800 dark:text-gray-200 mb-1.5">Full name</label>
            <input id="name" type="text" name="name" required placeholder="Ahmed Hassan"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 dark:text-white dark:placeholder:text-gray-500 transition-all">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-surface-800 dark:text-gray-200 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" required placeholder="ahmed@example.com"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 dark:text-white dark:placeholder:text-gray-500 transition-all">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-surface-800 dark:text-gray-200 mb-1.5">Phone number</label>
            <input id="phone" type="tel" name="phone" required placeholder="+20 1XX XXX XXXX"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 dark:text-white dark:placeholder:text-gray-500 transition-all">
        </div>

        <div>
            <label class="block text-sm font-medium text-surface-800 dark:text-gray-200 mb-2">I want to</label>
            <div class="grid grid-cols-3 gap-2">
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="customer" checked class="peer hidden">
                    <div class="p-3 rounded-xl border-2 border-surface-200 dark:border-white/10 text-center peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-500/10 transition-all hover:border-surface-300 dark:hover:border-white/20">
                        <div class="text-2xl mb-1">🛒</div>
                        <span class="text-[10px] font-bold text-surface-800 dark:text-white uppercase">Order</span>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="restaurant_owner" class="peer hidden">
                    <div class="p-3 rounded-xl border-2 border-surface-200 dark:border-white/10 text-center peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-500/10 transition-all hover:border-surface-300 dark:hover:border-white/20">
                        <div class="text-2xl mb-1">🏪</div>
                        <span class="text-[10px] font-bold text-surface-800 dark:text-white uppercase">Sell</span>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="rider" class="peer hidden">
                    <div class="p-3 rounded-xl border-2 border-surface-200 dark:border-white/10 text-center peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-500/10 transition-all hover:border-surface-300 dark:hover:border-white/20">
                        <div class="text-2xl mb-1">🛵</div>
                        <span class="text-[10px] font-bold text-surface-800 dark:text-white uppercase">Deliver</span>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label for="reg_password" class="block text-sm font-medium text-surface-800 dark:text-gray-200 mb-1.5">Password</label>
            <input id="reg_password" type="password" name="password" required placeholder="Min 12 characters"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 dark:text-white dark:placeholder:text-gray-500 transition-all pr-12">
        </div>

        <button type="submit" class="w-full flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md hover:scale-[1.01] active:scale-[0.99] transition-all">
            Create Account
        </button>
    </form>

    <div class="mt-6 relative">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-surface-200 dark:border-white/10"></div></div>
        <div class="relative flex justify-center"><span class="bg-white dark:bg-neutral-900 px-4 text-xs text-surface-300 dark:text-gray-500 uppercase tracking-wider">or join with</span></div>
    </div>

    <div class="mt-6">
        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm font-medium text-surface-800 dark:text-gray-300 hover:bg-surface-50 dark:hover:bg-white/10 transition-all">
            <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Continue with Google
        </a>
    </div>

    <p class="mt-6 text-center text-sm text-surface-800/60 dark:text-gray-400">
        Already have an account? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">Sign in</a>
    </p>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-spin mr-2">⏳</span>Creating account...';

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        const data = await response.json();
        
        if (!response.ok) { 
            if (window.Toast) window.Toast.show('Registration Failed', data.message || 'Check your details', 'error');
            return; 
        }

        localStorage.setItem('auth_token', data.data.token);
        localStorage.setItem('auth_user', JSON.stringify(data.data.user));
        
        if (window.Toast) window.Toast.show('Success', 'Welcome to DeliverEats!', 'success');
        
        setTimeout(() => {
            window.location.href = '/browse';
        }, 800);
    } catch (error) { 
        if (window.Toast) window.Toast.show('Error', 'Connection to server failed', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>
@endsection
