<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name') }}</title>
    <meta name="description" content="Inventory Management System — enterprise SaaS dashboard to manage products, categories, and borrowings.">

    {{-- Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] dark:bg-[#0F172A] font-sans antialiased text-slate-900 dark:text-slate-100 h-full selection:bg-[#24C4F4]/20 selection:text-[#0F172A] dark:selection:text-white">

    {{-- Sidebar + Main layout --}}
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar (260px width, Dark theme #111827 / Light theme #FFFFFF) --}}
        <aside id="sidebar" class="w-[260px] bg-white dark:bg-[#111827] text-slate-800 dark:text-slate-200 flex flex-col flex-shrink-0 border-r border-slate-200 dark:border-slate-800 shadow-sm z-20">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 h-[72px] border-b border-slate-200 dark:border-slate-800">
                <div class="w-9 h-9 bg-gradient-to-tr from-[#24C4F4] via-[#7C3AED] to-[#EC4899] rounded-md flex items-center justify-center shadow-md shadow-[#24C4F4]/25">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white leading-tight">Inventory MS</span>
                    <span class="text-[11px] font-medium text-[#7C3AED] dark:text-[#24C4F4] uppercase tracking-wider">Enterprise SaaS</span>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3.5 py-5 space-y-1.5 overflow-y-auto">
                <div class="px-2.5 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Main Menu
                </div>

                @can('view-dashboard')
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-md text-sm transition-all duration-150
                          {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#24C4F4] via-[#7C3AED] to-[#EC4899] text-white font-semibold shadow-sm shadow-[#7C3AED]/20' : 'font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                @endcan

                @can('viewAny', App\Models\Product::class)
                <a href="{{ route('products.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-md text-sm transition-all duration-150
                          {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-[#24C4F4] via-[#7C3AED] to-[#EC4899] text-white font-semibold shadow-sm shadow-[#7C3AED]/20' : 'font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Products</span>
                </a>
                @endcan

                @can('viewAny', App\Models\Category::class)
                <a href="{{ route('categories.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-md text-sm transition-all duration-150
                          {{ request()->routeIs('categories.*') ? 'bg-gradient-to-r from-[#24C4F4] via-[#7C3AED] to-[#EC4899] text-white font-semibold shadow-sm shadow-[#7C3AED]/20' : 'font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>Categories</span>
                </a>
                @endcan

                @can('viewAny', App\Models\Borrowing::class)
                <a href="{{ route('borrowings.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-md text-sm transition-all duration-150
                          {{ request()->routeIs('borrowings.*') ? 'bg-gradient-to-r from-[#24C4F4] via-[#7C3AED] to-[#EC4899] text-white font-semibold shadow-sm shadow-[#7C3AED]/20' : 'font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Borrowings</span>
                </a>
                @endcan

            </nav>

            {{-- User info + logout --}}
            <div class="border-t border-slate-200 dark:border-slate-800 px-4 py-4 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-md bg-gradient-to-tr from-[#7C3AED] to-[#EC4899] flex items-center justify-center text-sm font-bold text-white shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 capitalize">{{ auth()->user()->role?->name ?? 'Staff' }}</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3.5">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 rounded-md text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center justify-between group">
                        <span>Sign Out</span>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content area --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Navbar (72px height, surface color) --}}
            <header class="h-[72px] bg-white dark:bg-[#1E293B] border-b border-slate-200 dark:border-slate-800 px-8 flex items-center justify-between flex-shrink-0 z-10 shadow-xs">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                        {{ $title ?? config('app.name') }}
                    </h1>
                </div>
                <div class="flex items-center gap-6">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-md border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-[#24C4F4] animate-pulse"></span>
                        <span>System Online</span>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/80 px-3 py-1.5 rounded-md border border-slate-200 dark:border-slate-700">
                        {{ now()->format('l, d M Y') }}
                    </div>
                </div>
            </header>

            {{-- Flash messages --}}
            <div class="px-8 pt-6 space-y-3">
                @if (session('success'))
                    <div id="flash-success" class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300 rounded-md px-4 py-3 text-sm font-medium shadow-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="document.getElementById('flash-success').remove()" class="text-emerald-600 hover:text-emerald-800 dark:hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div id="flash-error" class="flex items-center justify-between bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-800 dark:text-red-300 rounded-md px-4 py-3 text-sm font-medium shadow-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="document.getElementById('flash-error').remove()" class="text-red-600 hover:text-red-800 dark:hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
            </div>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto px-8 py-6">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    {{-- Auto-dismiss flash messages after 5s --}}
    <script>
        setTimeout(() => {
            ['flash-success', 'flash-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.style.opacity = '0'; el.style.transition = 'opacity 0.5s ease-out'; setTimeout(() => el.remove(), 500); }
            });
        }, 5000);
    </script>

</body>
</html>
