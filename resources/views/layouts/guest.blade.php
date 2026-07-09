<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'INLIFE IMS') }} — Inventory Management</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased bg-[#F8FAFC] dark:bg-[#0B0F19] text-slate-900 dark:text-slate-100 min-h-screen flex flex-col justify-between selection:bg-[#24C4F4] selection:text-white relative overflow-x-hidden">
        
        <!-- Subtle Ambient Background Glow -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute -top-[20%] left-[20%] w-[500px] h-[500px] rounded-full bg-gradient-to-tr from-[#24C4F4]/10 via-[#7C3AED]/10 to-[#EC4899]/10 blur-[100px]"></div>
            <div class="absolute -bottom-[20%] right-[20%] w-[500px] h-[500px] rounded-full bg-gradient-to-tr from-[#D55BA9]/10 via-[#EA6F6B]/10 to-[#F1A84F]/10 blur-[100px]"></div>
            <!-- Subtle Grid Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        </div>

        <!-- Top Navigation / Header -->
        <header class="w-full max-w-6xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#24C4F4] via-[#7C3AED] to-[#EC4899] flex items-center justify-center text-white shadow-md shadow-[#24C4F4]/20 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white block leading-tight">INLIFE IMS</span>
                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block">Inventory System</span>
                </div>
            </a>

            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-700 shadow-sm transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Home</span>
            </a>
        </header>

        <!-- Main Content Area: Centered Sleek Auth Card -->
        <main class="flex-1 flex flex-col items-center justify-center px-4 py-8">
            <div class="w-full max-w-[440px]">
                <!-- Sleek Card Container -->
                <div class="bg-white dark:bg-slate-900/95 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-slate-800 p-8 sm:p-10 backdrop-blur-sm relative">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-6xl mx-auto px-6 py-6 text-center text-xs text-slate-400 dark:text-slate-500">
            <p>© {{ date('Y') }} Vivi's Inventory Management System.</p>
        </footer>
    </body>
</html>
