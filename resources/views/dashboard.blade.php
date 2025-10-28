<x-layouts.app :title="__('Dashboard')">
    <!-- Dashboard Container -->
    <div class="page-wrapper">
        <div class="max-w-screen-xl mx-auto px-4 py-6 page-content-area">

            <!-- Enhanced Header -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 text-white card-3d fade-in mb-8 shadow-xl">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold mb-3 text-white">Dashboard</h1>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30">
                                        <x-bxs-user class="w-5 h-5" />
                                        <span class="font-medium text-white">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30">
                                        <x-bxs-shield class="w-5 h-5" />
                                        <span class="font-medium text-white">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Header Responsive -->
            <style>
                @media (max-width: 768px) {
                    .dashboard-header {
                        padding: 1.5rem !important;
                        margin-bottom: 1.5rem !important;
                    }
                    .dashboard-header h1 {
                        font-size: 1.875rem !important;
                        margin-bottom: 0.75rem !important;
                    }
                    .dashboard-header .flex-wrap {
                        flex-direction: column;
                        gap: 0.5rem !important;
                    }
                    .dashboard-header .flex-wrap > div {
                        width: 100%;
                        justify-content: center;
                    }
                }
                @media (max-width: 480px) {
                    .dashboard-header {
                        padding: 1rem !important;
                        margin-bottom: 1rem !important;
                    }
                    .dashboard-header h1 {
                        font-size: 1.5rem !important;
                    }
                }
            </style>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Buildings Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl shadow">
                            <x-bxs-building class="text-3xl text-blue-600 dark:text-blue-400 w-8 h-8" />
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">Total Buildings</div>
                    <div class="text-3xl md:text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ \App\Models\Building::count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Buildings that are registered</div>
                </div>

                <!-- Mobile Stats Responsive -->
                <style>
                    @media (max-width: 768px) {
                        .stats-grid {
                            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                            gap: 1rem !important;
                        }
                        .stats-card {
                            padding: 1rem !important;
                            border-radius: 1rem !important;
                        }
                        .stats-card .text-3xl {
                            font-size: 1.875rem !important;
                        }
                        .stats-card .text-xs {
                            font-size: 0.625rem !important;
                        }
                    }
                    @media (max-width: 480px) {
                        .stats-grid {
                            grid-template-columns: 1fr !important;
                            gap: 0.75rem !important;
                        }
                        .stats-card {
                            padding: 0.75rem !important;
                            border-radius: 0.75rem !important;
                        }
                        .stats-card .text-3xl {
                            font-size: 1.5rem !important;
                        }
                    }
                </style>

                <!-- Total Rooms Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl shadow">
                            <x-bxs-door-open class="text-3xl text-green-600 dark:text-green-400 w-8 h-8" />
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">Total Rooms</div>
                    <div class="text-3xl md:text-4xl font-bold text-green-600 dark:text-green-400 mb-2">{{ \App\Models\Room::count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Rooms that are monitored</div>
                </div>

                <!-- CCTV Online Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl shadow">
                            <x-bxs-video class="text-3xl text-emerald-600 dark:text-emerald-400 w-8 h-8" />
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">CCTV Online</div>
                    <div class="text-3xl md:text-4xl font-bold text-emerald-500 dark:text-emerald-400 mb-2" id="stat-online">{{ \App\Models\Cctv::where('status','online')->count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ round((\App\Models\Cctv::where('status','online')->count() / max(\App\Models\Cctv::count(), 1)) * 100, 1) }}% Active</div>
                </div>

                <!-- CCTV Offline Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-rose-100 dark:bg-rose-900/30 rounded-2xl shadow">
                            <x-bxs-error-circle class="text-3xl text-rose-600 dark:text-rose-400 w-8 h-8" />
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">CCTV Offline</div>
                    <div class="text-3xl md:text-4xl font-bold text-rose-500 dark:text-rose-400 mb-2" id="stat-offline">{{ \App\Models\Cctv::where('status','offline')->count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Need attention</div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- System Performance Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 card-3d fade-in border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">System Performance</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Real-time system metrics</p>
                        </div>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="system-performance-chart"></canvas>
                        <div id="system-chart-error" class="hidden absolute inset-0 flex items-center justify-center text-red-500">
                            <p>Failed to load chart. Please refresh the page.</p>
                        </div>
                    </div>
                </div>

                <!-- CCTV Status Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 card-3d fade-in border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">CCTV Status Distribution</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Current camera status overview</p>
                        </div>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="cctv-status-chart"></canvas>
                        <div id="cctv-chart-error" class="hidden absolute inset-0 flex items-center justify-center text-red-500">
                            <p>Failed to load chart. Please refresh the page.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <div class="grid gap-4 grid-cols-2 sm:grid-cols-3">
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="window.location.href='{{ route('maps') }}'">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-map class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">View Maps</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="window.location.href='{{ route('locations') }}'">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-location-plus class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">Manage Playlist Locations</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="window.location.href='{{ route('contact') }}'">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-message-square class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">Contact Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .gradient-text-outline {
            background: linear-gradient(135deg, #FFFFFF, #FFF2CC);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(255,255,255,0.3);
        }

        .card-3d {
            transform-style: preserve-3d;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            perspective: 1000px;
            border-radius: 1rem;
            overflow: hidden;
        }

        .card-3d:hover {
            transform: translateY(-8px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15), 0 0 0 1px rgba(255,255,255,0.1), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00a884, #25d366);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #008f73, #1ebc5d);
        }

        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #2d3748;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00a884, #25d366);
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #008f73, #1ebc5d);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize charts
        function initializeCharts() {
            try {
                // Hide any previous error messages
                document.getElementById('system-chart-error')?.classList.add('hidden');
                document.getElementById('cctv-chart-error')?.classList.add('hidden');

                // Destroy existing charts if they exist
                if (window.systemPerformanceChart) {
                    window.systemPerformanceChart.destroy();
                }
                if (window.cctvStatusChart) {
                    window.cctvStatusChart.destroy();
                }

                // Fetch real-time system performance data
                fetch('/api/analytics/system-performance')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // System Performance Chart
                            const systemCtx = document.getElementById('system-performance-chart');
                            if (systemCtx) {
                                window.systemPerformanceChart = new Chart(systemCtx, {
                                    type: 'line',
                                    data: data.data,
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                labels: {
                                                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                                                    font: {
                                                        size: 12
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                ticks: {
                                                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280',
                                                    callback: function(value) {
                                                        return value + '%';
                                                    }
                                                },
                                                grid: {
                                                    color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                                }
                                            },
                                            x: {
                                                ticks: {
                                                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                                                },
                                                grid: {
                                                    color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                                }
                                            }
                                        }
                                    },
                                    plugins: [{
                                        id: 'chartErrorHandler',
                                        afterDraw: function(chart) {
                                            // Chart rendered successfully
                                        }
                                    }]
                                });
                            }
                        } else {
                            throw new Error(data.message || 'Failed to load system performance data');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching system performance data:', error);
                        document.getElementById('system-chart-error')?.classList.remove('hidden');
                    });

                // CCTV Status Chart
                const cctvCtx = document.getElementById('cctv-status-chart');
                if (cctvCtx) {
                    window.cctvStatusChart = new Chart(cctvCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Online', 'Offline', 'Maintenance'],
                            datasets: [{
                                data: [
                                    {{ \App\Models\Cctv::where('status','online')->count() }},
                                    {{ \App\Models\Cctv::where('status','offline')->count() }},
                                    {{ \App\Models\Cctv::where('status','maintenance')->count() }}
                                ],
                                backgroundColor: ['#10B981', '#EF4444', '#F59E0B'],
                                borderWidth: 0,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                                        padding: 20,
                                        usePointStyle: true,
                                        font: {
                                            size: 12
                                        }
                                    }
                                }
                            },
                            cutout: '65%'
                        },
                        plugins: [{
                            id: 'chartErrorHandler',
                            afterDraw: function(chart) {
                                // Chart rendered successfully
                            }
                        }]
                    });
                }
            } catch (error) {
                console.error('Error initializing charts:', error);
                // Show error messages
                document.getElementById('system-chart-error')?.classList.remove('hidden');
                document.getElementById('cctv-chart-error')?.classList.remove('hidden');
            }
        }

        // Function to update system performance chart with new data
        function updateSystemPerformanceChart() {
            // Check if the chart container is visible
            const chartContainer = document.getElementById('system-performance-chart');
            if (!chartContainer || chartContainer.offsetParent === null) {
                return; // Chart is not visible, skip update
            }

            fetch('/api/analytics/system-performance')
                .then(response => response.json())
                .then(data => {
                    if (data.success && window.systemPerformanceChart) {
                        // Smoothly update the chart data with animation
                        window.systemPerformanceChart.data.labels = data.data.labels;
                        window.systemPerformanceChart.data.datasets[0].data = data.data.datasets[0].data;
                        window.systemPerformanceChart.data.datasets[1].data = data.data.datasets[1].data;
                        window.systemPerformanceChart.data.datasets[2].data = data.data.datasets[2].data;
                        window.systemPerformanceChart.update('easeOutQuart');
                    }
                })
                .catch(error => {
                    console.error('Error updating system performance data:', error);
                });
        }

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add a small delay to ensure DOM is fully ready
            setTimeout(function() {
                initializeCharts();
            }, 100);

            // Retry chart initialization if it fails
            setTimeout(function() {
                const systemChartError = document.getElementById('system-chart-error');
                const cctvChartError = document.getElementById('cctv-chart-error');

                if (systemChartError && !systemChartError.classList.contains('hidden')) {
                    initializeCharts();
                }

                if (cctvChartError && !cctvChartError.classList.contains('hidden')) {
                    initializeCharts();
                }
            }, 2000);

            // Update system performance chart every 30 seconds
            setInterval(updateSystemPerformanceChart, 30000);

            // Listen for live metrics updates from Echo (wired in app.js)
            window.addEventListener('dashboard-metrics', (ev) => {
                const data = ev.detail?.metrics || ev.detail;
                if (!data) return;
                // Update counters when payload provides counters via Redis hash
                try {
                    const counters = data.counters || {};
                    if (document.getElementById('stat-online') && (counters.online ?? null) !== null) {
                        document.getElementById('stat-online').textContent = counters.online;
                    }
                    if (document.getElementById('stat-offline') && (counters.offline ?? null) !== null) {
                        document.getElementById('stat-offline').textContent = counters.offline;
                    }
                } catch (_) {}
            });
        });

        // Redraw charts when window is resized
        window.addEventListener('resize', function() {
            if (window.resizeTimeout) {
                clearTimeout(window.resizeTimeout);
            }
            window.resizeTimeout = setTimeout(function() {
                initializeCharts();
            }, 100);
        });
    </script>
    @endpush
</x-layouts.app>
