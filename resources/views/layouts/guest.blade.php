<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sign In — DeliverEats')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-surface-50 dark:bg-neutral-950 font-body antialiased transition-colors duration-300">
    <div class="min-h-screen flex">
        {{-- Left decorative panel --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-surface-900 via-surface-800 to-brand-900">
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative z-10 flex flex-col justify-center px-12 xl:px-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-12">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-2xl font-display font-bold text-white">Deliver<span class="text-brand-400">Eats</span></span>
                </a>
                <h2 class="text-3xl xl:text-4xl font-display font-bold text-white leading-tight mb-4">
                    Your favourite food,<br>
                    <span class="text-brand-400">delivered in minutes.</span>
                </h2>
                <p class="text-surface-200/80 text-lg leading-relaxed max-w-md">Join thousands of users who order from the best restaurants in their city. Track every step of your delivery in real-time.</p>
                <div class="mt-12 flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-3xl font-display font-bold text-white">500+</p>
                        <p class="text-sm text-surface-300 mt-1">Restaurants</p>
                    </div>
                    <div class="w-px h-12 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-3xl font-display font-bold text-white">50K+</p>
                        <p class="text-sm text-surface-300 mt-1">Happy Customers</p>
                    </div>
                    <div class="w-px h-12 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-3xl font-display font-bold text-white">15 min</p>
                        <p class="text-sm text-surface-300 mt-1">Avg Delivery</p>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full bg-brand-500/10 blur-3xl"></div>
            <div class="absolute top-20 -right-10 w-40 h-40 rounded-full bg-brand-400/5 blur-2xl"></div>
        </div>

        {{-- Right form panel --}}
        <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 py-12 dark:bg-neutral-900">
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-lg font-display font-bold dark:text-white">Deliver<span class="text-brand-500">Eats</span></span>
                </a>
            </div>
            <div class="w-full max-w-md mx-auto lg:mx-0">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Toast System
        window.Toast = {
            show(title, message, icon = 'info') {
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon,
                    title,
                    text: message
                });
            }
        };

        @if(session('success')) window.Toast.show('Success', "{{ session('success') }}", 'success'); @endif
        @if(session('error')) window.Toast.show('Error', "{{ session('error') }}", 'error'); @endif
    </script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
