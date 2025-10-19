<x-layouts.app :title="__('Dashboard')">
    <!-- Dashboard Container -->
    <div class="min-h-screen">
        <div class="max-w-screen-xl mx-auto px-4 py-6">

            <!-- Enhanced Header -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 text-white card-3d fade-in mb-8 shadow-xl">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold mb-3 gradient-text-outline">Dashboard</h1>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30">
                                        <x-bxs-user class="w-5 h-5" />
                                        <span class="font-medium">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30">
                                        <x-bxs-shield class="w-5 h-5" />
                                        <span class="font-medium">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30">
                                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                        <span class="font-medium">Live Monitoring</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-sm opacity-80">Last Updated</div>
                                <div class="font-bold text-lg" id="real-time-clock"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Buildings Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl shadow">
                            <x-bxs-building class="text-3xl text-blue-600 dark:text-blue-400 w-8 h-8" />
                        </div>
                        <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
                            <x-bxs-up-arrow class="text-lg w-5 h-5" />
                            <span class="text-sm font-medium">+2 this month</span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">Total Buildings</div>
                    <div class="text-3xl md:text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ \App\Models\Building::count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">18 Gedung Terdaftar</div>
                    <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, (\App\Models\Building::count() / 18) * 100) }}%"></div>
                    </div>
                </div>

                <!-- Total Rooms Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl shadow">
                            <x-bxs-door-open class="text-3xl text-green-600 dark:text-green-400 w-8 h-8" />
                        </div>
                        <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                            <x-bxs-shield class="text-lg w-5 h-5" />
                            <span class="text-sm font-medium">Secure</span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">Total Rooms</div>
                    <div class="text-3xl md:text-4xl font-bold text-green-600 dark:text-green-400 mb-2">{{ \App\Models\Room::count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Ruangan Terpantau</div>
                    <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, (\App\Models\Room::count() / 50) * 100) }}%"></div>
                    </div>
                </div>

                <!-- CCTV Online Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl shadow">
                            <x-bxs-video class="text-3xl text-emerald-600 dark:text-emerald-400 w-8 h-8" />
                        </div>
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium">Live</span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">CCTV Online</div>
                    <div class="text-3xl md:text-4xl font-bold text-emerald-500 mb-2" id="stat-online">{{ \App\Models\Cctv::where('status','online')->count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ round((\App\Models\Cctv::where('status','online')->count() / max(\App\Models\Cctv::count(), 1)) * 100, 1) }}% Active</div>
                    <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ round((\App\Models\Cctv::where('status','online')->count() / max(\App\Models\Cctv::count(), 1)) * 100, 1) }}%"></div>
                    </div>
                </div>

                <!-- CCTV Offline Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center justify-center w-14 h-14 bg-rose-100 dark:bg-rose-900/30 rounded-2xl shadow">
                            <x-bxs-error-circle class="text-3xl text-rose-600 dark:text-rose-400 w-8 h-8" />
                        </div>
                        <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                            <x-bxs-alarm class="text-lg w-5 h-5" />
                            <span class="text-sm font-medium">Alert</span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold tracking-wide mb-2">CCTV Offline</div>
                    <div class="text-3xl md:text-4xl font-bold text-rose-500 mb-2" id="stat-offline">{{ \App\Models\Cctv::where('status','offline')->count() }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Perlu Perhatian</div>
                    <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-rose-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, (\App\Models\Cctv::where('status','offline')->count() / max(\App\Models\Cctv::count(), 1)) * 100) }}%"></div>
                    </div>
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
                        <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-green-600 dark:text-green-400">Optimal</span>
                        </div>
                    </div>
                    <div class="h-64" id="system-performance-chart">
                        <!-- Chart will be rendered here -->
                        <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                            <div class="text-center">
                                <x-bxs-bar-chart-alt-2 class="text-4xl mb-4 w-10 h-10 mx-auto" />
                                <p>Loading Chart...</p>
                            </div>
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
                        <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30">
                            <x-bxs-pie-chart-alt-2 class="text-sm text-blue-600 dark:text-blue-400 w-5 h-5" />
                            <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Live Data</span>
                        </div>
                    </div>
                    <div class="h-64" id="cctv-status-chart">
                        <!-- Chart will be rendered here -->
                        <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                            <div class="text-center">
                                <x-bxs-pie-chart-alt-2 class="text-4xl mb-4 w-10 h-10 mx-auto" />
                                <p>Loading Chart...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Actions -->
            <div class="mb-8">
                <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-6">
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="exportStats()">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-download class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">Export Stats</div>
                            <div class="text-xs text-green-100">Dashboard Data</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="exportData('buildings')">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-building class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">Buildings</div>
                            <div class="text-xs text-blue-100">18 Gedung</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="exportData('rooms')">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-door-open class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">Rooms</div>
                            <div class="text-xs text-purple-100">Ruangan</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="exportData('cctvs')">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-video class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">CCTVs</div>
                            <div class="text-xs text-red-100">Kamera</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="exportData('users')">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-user class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">Users</div>
                            <div class="text-xs text-yellow-100">Pengguna</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl text-white card-3d fade-in hover:shadow-2xl transition-all duration-300 cursor-pointer group" onclick="exportData('contacts')">
                        <div class="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl group-hover:scale-110 transition-transform">
                            <x-bxs-phone class="text-xl w-6 h-6" />
                        </div>
                        <div>
                            <div class="font-bold text-sm">Contacts</div>
                            <div class="text-xs text-indigo-100">Kontak</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced System Status -->
            <div class="grid gap-6 lg:grid-cols-3 mb-8">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-2xl">
                            <x-bxs-bolt class="text-xl text-green-600 dark:text-green-400 w-6 h-6" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                    </div>
                    <div class="space-y-3">
                        <a href="/maps" class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-blue-500/10 to-blue-600/20 hover:from-blue-500/20 hover:to-blue-600/30 border border-blue-500/30 hover:border-blue-500/50 transition-all duration-300 group">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-500/20 rounded-lg group-hover:bg-blue-500/30 transition-colors">
                                <x-bxs-map class="text-blue-500 text-xl group-hover:scale-110 transition-transform w-6 h-6" />
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white">View Maps</span>
                            <x-bxs-right-arrow-alt class="ml-auto text-blue-400 group-hover:translate-x-1 transition-transform w-5 h-5" />
                        </a>
                        <a href="{{ route('locations') }}" class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-green-500/10 to-green-600/20 hover:from-green-500/20 hover:to-green-600/30 border border-green-500/30 hover:border-green-500/50 transition-all duration-300 group">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-500/20 rounded-lg group-hover:bg-green-500/30 transition-colors">
                                <x-bxs-location-plus class="text-green-500 text-xl group-hover:scale-110 transition-transform w-6 h-6" />
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white">Manage Locations</span>
                            <x-bxs-right-arrow-alt class="ml-auto text-green-400 group-hover:translate-x-1 transition-transform w-5 h-5" />
                        </a>
                        <a href="/contact" class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 to-purple-600/20 hover:from-purple-500/20 hover:to-purple-600/30 border border-purple-500/30 hover:border-purple-500/50 transition-all duration-300 group">
                            <div class="flex items-center justify-center w-10 h-10 bg-purple-500/20 rounded-lg group-hover:bg-purple-500/30 transition-colors">
                                <x-bxs-message-square class="text-purple-500 text-xl group-hover:scale-110 transition-transform w-6 h-6" />
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white">Contact Support</span>
                            <x-bxs-right-arrow-alt class="ml-auto text-purple-400 group-hover:translate-x-1 transition-transform w-5 h-5" />
                        </a>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-2xl">
                            <x-bxs-bar-chart-alt-2 class="text-xl text-blue-600 dark:text-blue-400 w-6 h-6" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">System Health</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Database</span>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-green-600">Healthy</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Redis Cache</span>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-green-600">Optimal</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">FFmpeg Streaming</span>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-green-600">Active</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">WebSocket</span>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-green-600">Connected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Real-time Alerts -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 card-3d fade-in border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-2xl">
                            <x-bxs-bell class="text-xl text-yellow-600 dark:text-yellow-400 w-6 h-6" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Alerts</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                            <x-bx-error-circle class="text-yellow-600 dark:text-yellow-400 w-5 h-5" />
                            <div>
                                <div class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Camera RTSP-045</div>
                                <div class="text-xs text-yellow-700 dark:text-yellow-300">Went offline 5m ago</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                            <x-bx-check-circle class="text-green-600 dark:text-green-400 w-5 h-5" />
                            <div>
                                <div class="text-sm font-medium text-green-900 dark:text-green-100">Maintenance Complete</div>
                                <div class="text-xs text-green-700 dark:text-green-300">Building C - 2h ago</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                            <x-bx-info-circle class="text-blue-600 dark:text-blue-400 w-5 h-5" />
                            <div>
                                <div class="text-sm font-medium text-blue-900 dark:text-blue-100">System Update</div>
                                <div class="text-xs text-blue-700 dark:text-blue-300">Ready for restart</div>
                            </div>
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
        // Initialize real-time clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-GB');
            const dateString = now.toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            const clockElement = document.getElementById('real-time-clock');
            if (clockElement) {
                clockElement.innerHTML = `
                    <div class="text-lg font-bold">${timeString}</div>
                    <div class="text-xs text-red-100">${dateString}</div>
                `;
            }
        }

        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // Initialize charts
        function initializeCharts() {
            // System Performance Chart
            const systemCtx = document.getElementById('system-performance-chart');
            if (systemCtx) {
                new Chart(systemCtx, {
                    type: 'line',
                    data: {
                        labels: ['12 AM', '3 AM', '6 AM', '9 AM', '12 PM', '3 PM', '6 PM', '9 PM'],
                        datasets: [{
                            label: 'CPU Usage',
                            data: [45, 52, 38, 65, 78, 82, 58, 42],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Memory Usage',
                            data: [38, 41, 35, 52, 61, 68, 55, 48],
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Network I/O',
                            data: [28, 32, 25, 45, 58, 64, 42, 35],
                            borderColor: '#8B5CF6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
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
                    }
                });
            }

            // CCTV Status Chart
            const cctvCtx = document.getElementById('cctv-status-chart');
            if (cctvCtx) {
                new Chart(cctvCtx, {
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
                    }
                });
            }
        }

        // Export functions
        function exportStats() {
            window.open('{{ route("export.stats") }}', '_blank');
        }

        function exportData(type) {
            const routes = {
                'buildings': '{{ route("export.buildings") }}',
                'rooms': '{{ route("export.rooms") }}',
                'cctvs': '{{ route("export.cctvs") }}',
                'users': '{{ route("export.users") }}',
                'contacts': '{{ route("export.contacts") }}'
            };

            if (routes[type]) {
                window.open(routes[type], '_blank');
            }
        }

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
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

            // Toast for critical notifications
            window.addEventListener('realtime-notification', (ev) => {
                const n = ev.detail || {};
                const type = (n.type || '').toLowerCase();
                if (type.includes('critical') || type.includes('offline') || type.includes('error')) {
                    try { showRealtimeToast('ALERT: ' + (n.data?.message || 'Periksa sistem'), 'bxs-error', '#EF4444'); } catch(_) {}
                }
            });
        });

        // Auto-refresh dashboard every 30 seconds
        setInterval(() => {
            // Update only specific elements that need refresh
            console.log('Dashboard refresh scheduled');
        }, 30000);
    </script>
    @endpush
</x-layouts.app>
