<x-layouts.app :title="'Dashboard'">

    <div class="space-y-8">

        {{-- Welcome Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-[#24C4F4]/10 via-[#7C3AED]/10 to-[#EC4899]/10 dark:from-[#24C4F4]/20 dark:via-[#7C3AED]/20 dark:to-[#EC4899]/20 p-6 rounded-lg border border-[#24C4F4]/20 dark:border-[#7C3AED]/30">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    Welcome back, {{ auth()->user()->name }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                    Here is what is happening across your inventory warehouse today.
                </p>
            </div>
            @can('create', App\Models\Borrowing::class)
            <a href="{{ route('borrowings.create') }}" class="btn-primary flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>New Borrowing</span>
            </a>
            @endcan
        </div>

        {{-- Stat Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Total Products --}}
            <div class="stat-card">
                <div class="stat-icon bg-[#24C4F4]/15 text-[#24C4F4] border border-[#24C4F4]/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Products</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($totalProducts) }}</p>
                </div>
            </div>

            {{-- Available Products --}}
            <div class="stat-card">
                <div class="stat-icon bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Available Stock</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($availableProducts) }}</p>
                </div>
            </div>

            {{-- Items Currently Borrowed --}}
            <div class="stat-card">
                <div class="stat-icon bg-[#7C3AED]/15 text-[#7C3AED] border border-[#7C3AED]/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Items Borrowed</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($borrowedCount) }}</p>
                </div>
            </div>

            {{-- Active Borrowings --}}
            <div class="stat-card">
                <div class="stat-icon bg-[#EC4899]/15 text-[#EC4899] border border-[#EC4899]/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Active Borrowings</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($activeBorrowings) }}</p>
                </div>
            </div>
        </div>

        {{-- Monthly Borrowing Chart --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Monthly Borrowing Activity</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ now()->year }} — Number of borrowing transactions per month</p>
                </div>
                <span class="badge-info">Live Analytics</span>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" class="w-full" style="max-height: 340px;"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        
        // Brand gradient for chart bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 340);
        gradient.addColorStop(0, 'rgba(36, 196, 244, 0.9)');   // #24C4F4
        gradient.addColorStop(0.5, 'rgba(124, 58, 237, 0.85)'); // #7C3AED
        gradient.addColorStop(1, 'rgba(236, 72, 153, 0.8)');   // #EC4899

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
        const textColor = isDark ? '#94A3B8' : '#64748B';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Borrowings',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: gradient,
                    borderColor: '#24C4F4',
                    borderWidth: 0,
                    borderRadius: 8,
                    barThickness: 'flex',
                    maxBarThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1E293B' : '#0F172A',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '600' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} borrowing${ctx.parsed.y !== 1 ? 's' : ''}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0, color: textColor, font: { family: 'Plus Jakarta Sans', size: 11 } },
                        grid: { color: gridColor, drawBorder: false }
                    },
                    x: { 
                        ticks: { color: textColor, font: { family: 'Plus Jakarta Sans', size: 11 } },
                        grid: { display: false } 
                    }
                }
            }
        });
    </script>

</x-layouts.app>
