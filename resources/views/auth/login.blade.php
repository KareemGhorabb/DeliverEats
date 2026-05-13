@extends('layouts.guest')
@section('title', 'Sign In — DeliverEats')

@section('content')
<div>
    <h1 class="text-2xl font-display font-bold tracking-tight dark:text-white">Welcome back</h1>
    <p class="text-sm text-surface-800/60 dark:text-gray-400 mt-2">Sign in to your account to continue ordering.</p>

    <form id="loginForm" class="mt-8 space-y-5" method="POST" action="/api/login">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-surface-800 dark:text-gray-200 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 dark:text-white dark:placeholder:text-gray-500 transition-all">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-surface-800 dark:text-gray-200">Password</label>
                <a href="#" class="text-xs text-brand-600 hover:text-brand-700 font-medium transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
                <input id="password" type="password" name="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl bg-surface-50 dark:bg-white/5 border border-surface-200 dark:border-white/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 dark:text-white dark:placeholder:text-gray-500 transition-all pr-12">
                <button type="button" onclick="const i=document.getElementById('password'); i.type = i.type==='password'?'text':'password'; this.querySelector('.eye-open').classList.toggle('hidden'); this.querySelector('.eye-closed').classList.toggle('hidden')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-surface-300 hover:text-surface-800 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all">
            Sign In
        </button>
    </form>

    <div class="mt-6 relative">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-surface-200 dark:border-white/10"></div></div>
        <div class="relative flex justify-center"><span class="bg-white dark:bg-neutral-900 px-4 text-xs text-surface-300 dark:text-gray-500 uppercase tracking-wider">or continue with</span></div>
    </div>

    <div class="mt-6">
        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-white/5 border border-surface-200 dark:border-white/10 rounded-xl text-sm font-medium text-surface-800 dark:text-gray-300 hover:bg-surface-50 dark:hover:bg-white/10 transition-all">
            <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Continue with Google
        </a>
    </div>

    <p class="mt-8 text-center text-sm text-surface-800/60 dark:text-gray-400">
        Don't have an account? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">Sign up free</a>
    </p>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    // Clear old state
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-spin mr-2">⚙️</span>Logging in...';

    try {
        const response = await fetch('/api/login', {
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
            if (window.Toast) window.Toast.show('Login Failed', data.message || 'Check your credentials', 'error');
            else alert(data.message || 'Login failed');
            return; 
        }

        // Save for API calls
        localStorage.setItem('auth_token', data.data.token);
        localStorage.setItem('auth_user', JSON.stringify(data.data.user));
        
        if (window.Toast) window.Toast.show('Success', 'Redirecting to your dashboard...', 'success');

        // Redirect after a short delay for the Toast
        setTimeout(() => {
            const role = data.data.user.role;
            if (role === 'admin') window.location.href = '/admin/dashboard';
            else if (role === 'restaurant_owner') window.location.href = '/restaurant/dashboard';
            else if (role === 'rider') window.location.href = '/rider/dashboard';
            else window.location.href = '/browse';
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
