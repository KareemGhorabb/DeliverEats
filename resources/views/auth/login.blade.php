@extends('layouts.guest')
@section('title', 'Sign In — DeliverEats')

@section('content')
<div>
    <h1 class="text-2xl font-display font-bold tracking-tight">Welcome back</h1>
    <p class="text-sm text-surface-800/60 mt-2">Sign in to your account to continue ordering.</p>

    <form class="mt-8 space-y-5" method="POST" action="#">
        <div>
            <label for="email" class="block text-sm font-medium text-surface-800 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" required autocomplete="email" placeholder="you@example.com"
                class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-surface-800">Password</label>
                <a href="#" class="text-xs text-brand-600 hover:text-brand-700 font-medium transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
                <input id="password" type="password" name="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl bg-surface-50 border border-surface-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 placeholder:text-surface-300 transition-all pr-12">
                <button type="button" onclick="const i=document.getElementById('password'); i.type = i.type==='password'?'text':'password'; this.querySelector('.eye-open').classList.toggle('hidden'); this.querySelector('.eye-closed').classList.toggle('hidden')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-surface-300 hover:text-surface-800 transition-colors">
                    <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded border-surface-300 text-brand-500 focus:ring-brand-500/30">
            <label for="remember" class="text-sm text-surface-800/70">Keep me signed in</label>
        </div>

        <a href="{{ route('customer.home') }}" class="w-full flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all">
            Sign In
        </a>
    </form>

    <div class="mt-6 relative">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-surface-200"></div></div>
        <div class="relative flex justify-center"><span class="bg-white px-4 text-xs text-surface-300 uppercase tracking-wider">or continue with</span></div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-3">
        <button class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-surface-200 rounded-xl text-sm font-medium text-surface-800 hover:bg-surface-50 hover:border-surface-300 transition-all">
            <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Google
        </button>
        <button class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-surface-200 rounded-xl text-sm font-medium text-surface-800 hover:bg-surface-50 hover:border-surface-300 transition-all">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
            GitHub
        </button>
    </div>

    <p class="mt-8 text-center text-sm text-surface-800/60">
        Don't have an account? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">Sign up free</a>
    </p>
</div>
@endsection
