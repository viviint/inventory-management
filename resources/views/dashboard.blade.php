<x-layouts.app :title="'Dashboard'">

    <div class="space-y-6">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            {{-- Total Products --}}
            <div class="stat-card">
                <div class="stat-icon bg-indigo-100">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($totalProducts) }}</p>
                </div>
            </div>

            {{-- Available Products --}}
            <div class="stat-card">
                <div class="stat-icon bg-emerald-100">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Available Products</p>
                    <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($availableProducts) }}</p>
                </div>
            </div>

            {{-- Items Currently Borrowed --}}
            <div class="stat-card">
                <div class="stat-icon bg-amber-100">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Items Borrowed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($borrowedCount) }}</p>
                </div>
            </div>

            {{-- Active Borrowings --}}
            <div class="stat-card">
                <div class="stat-icon bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Active Borrowings</p>
                    <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($activeBorrowings) }}</p>
                </div>
            </div>
        </div>

        {{-- Monthly Borrowing Chart --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Monthly Borrowing Activity</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ now()->year }} — Number of borrowing transactions per month</p>
                </div>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" class="w-full" style="max-height: 320px;"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Borrowings',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} borrowing${ctx.parsed.y !== 1 ? 's' : ''}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>

</x-layouts.app>
