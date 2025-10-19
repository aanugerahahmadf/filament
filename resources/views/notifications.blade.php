<x-layouts.app :title="__('Notifications')">
    @push('styles')
    <style>
        .notification-card {
            transform-style: preserve-3d;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            perspective: 1000px;
            position: relative;
            overflow: hidden;
        }

        .notification-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .notification-card:hover::before {
            left: 100%;
        }

        .notification-card:hover {
            transform: translateY(-4px) rotateX(2deg);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.1);
        }

        .notification-icon {
            transition: all 0.3s ease;
        }

        .notification-card:hover .notification-icon {
            transform: scale(1.1) rotate(5deg);
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

        .pulse-dot {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }

        /* Ensure text is visible in both light and dark modes */
        .text-visible {
            color: #1f2937; /* gray-900 for light mode */
        }

        .dark .text-visible {
            color: #f9fafb; /* gray-50 for dark mode */
        }
    </style>
    @endpush

    <!-- Remove the min-h-screen and background classes that conflict with main layout -->
    <div class="w-full">
        <div class="max-w-screen-xl mx-auto px-6 py-6">
            <!-- Enhanced Header -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-500 p-8 text-white card-3d fade-in mb-8">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mr-4">
                            <i class="bx bxs-bell text-3xl text-white floating"></i>
                        </div>
                        <div class="text-left">
                            <h1 class="text-4xl font-bold mb-2 gradient-text-outline text-white">Notification</h1>
                            <p class="text-xl text-blue-100 font-medium">Real-time System Alerts</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-4">
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm">
                            <div class="w-3 h-3 bg-green-400 rounded-full pulse-dot"></div>
                            <span class="font-medium text-white">Live Updates</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm">
                            <i class="bx bxs-time text-lg text-white"></i>
                            <span class="font-medium text-white">Real-time</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Recent Notifications</h2>
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/20 border border-green-500/40">
                        <div class="w-2 h-2 bg-green-400 rounded-full pulse-dot"></div>
                        <span class="text-sm text-green-700 dark:text-green-300 font-medium">Connected</span>
                    </div>
                </div>

                <div id="list" class="grid gap-4">
                    <!-- Loading placeholder -->
                    <div class="notification-card card-3d bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-6 fade-in">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-500/20 rounded-xl">
                                <i class="bx bxs-loader-alt notification-icon text-2xl text-blue-600 dark:text-blue-400 animate-spin"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-700 dark:text-white/80">Loading notifications...</div>
                                <div class="text-xs text-gray-500 dark:text-white/50 mt-1">Please wait</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.AppUserId = {{ auth()->id() ?? 'null' }};

        async function load(){
            try {
                console.log('Fetching notifications from /api/notifications/');

                // Fix the API endpoint to match the defined route
                const res = await fetch('/api/notifications/', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Response status:', res.status);

                if (!res.ok) {
                    const errorText = await res.text();
                    console.error('HTTP error! status:', res.status, 'response:', errorText);
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const data = await res.json();
                console.log('Received data:', data);

                const wrap = document.getElementById('list');
                wrap.innerHTML = '';

                if (!data.success) {
                    throw new Error(data.message || 'API returned failure');
                }

                if (!data.items || data.items.length === 0) {
                    wrap.innerHTML = `
                        <div class="notification-card card-3d bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-6 fade-in text-center">
                            <div class="flex items-center justify-center w-16 h-16 bg-gray-500/20 rounded-2xl mx-auto mb-4">
                                <i class="bx bxs-bell-off text-3xl text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Notifications</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">You're all caught up! Check back later for updates.</p>
                        </div>
                    `;
                    return;
                }

                data.items.forEach((n, index) => {
                    const delay = index * 0.1;
                    const d = document.createElement('div');
                    d.className = 'notification-card card-3d bg-white dark:bg-white/5 backdrop-blur-sm border border-gray-200 dark:border-white/10 rounded-2xl p-6 fade-in';
                    d.style.animationDelay = `${delay}s`;

                    // Determine icon and color based on type
                    let icon = 'bxs-bell';
                    let color = 'blue';
                    let bgColor = 'blue-500/20';
                    let textColor = 'text-gray-900 dark:text-white';
                    let subTextColor = 'text-gray-700 dark:text-white/80';
                    let dateColor = 'text-gray-500 dark:text-white/50';

                    if (n.type?.toLowerCase().includes('error') || n.type?.toLowerCase().includes('offline')) {
                        icon = 'bxs-error-circle';
                        color = 'red';
                        bgColor = 'red-500/20';
                    } else if (n.type?.toLowerCase().includes('success') || n.type?.toLowerCase().includes('online')) {
                        icon = 'bxs-check-circle';
                        color = 'green';
                        bgColor = 'green-500/20';
                    } else if (n.type?.toLowerCase().includes('warning') || n.type?.toLowerCase().includes('maintenance')) {
                        icon = 'bxs-error';
                        color = 'yellow';
                        bgColor = 'yellow-500/20';
                    }

                    // Format the created_at date
                    let formattedDate = 'Just now';
                    if (n.created_at) {
                        try {
                            const date = new Date(n.created_at);
                            formattedDate = date.toLocaleString();
                        } catch (e) {
                            formattedDate = n.created_at;
                        }
                    }

                    // Extract message from data if it exists
                    let message = n.message || 'No message available';
                    if (n.data && typeof n.data === 'object' && n.data.message) {
                        message = n.data.message;
                    }

                    d.innerHTML = `
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center w-12 h-12 bg-${bgColor} rounded-xl">
                                <i class="bx ${icon} notification-icon text-2xl text-${color}-600 dark:text-${color}-400"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold ${textColor}">${n.type || 'Notification'}</span>
                                    <div class="w-2 h-2 bg-${color}-400 rounded-full pulse-dot"></div>
                                </div>
                                <div class="text-sm ${subTextColor} mb-2">${message}</div>
                                <div class="text-xs ${dateColor}">${formattedDate}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-green-400/50" onclick="markAsRead('${n.id}')">
                                    <i class="bx bxs-check text-sm"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    wrap.appendChild(d);
                });
            } catch (error) {
                console.error('Error loading notifications:', error);
                const wrap = document.getElementById('list');
                wrap.innerHTML = `
                    <div class="notification-card card-3d bg-red-500/10 backdrop-blur-sm border border-red-500/30 rounded-2xl p-6 fade-in text-center">
                        <div class="flex items-center justify-center w-16 h-16 bg-red-500/20 rounded-2xl mx-auto mb-4">
                                <i class="bx bxs-error-circle text-3xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Error Loading Notifications</h3>
                        <p class="text-red-700 dark:text-red-300 text-sm">Please refresh the page or try again later.</p>
                        <p class="text-red-600 dark:text-red-400 text-xs mt-2">Error: ${error.message}</p>
                </div>
                `;
            }
        }

        async function markAsRead(id) {
            try {
                console.log('Marking notification as read:', id);

                const res = await fetch(`/api/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const data = await res.json();
                console.log('Mark as read response:', data);

                // Reload notifications to reflect the change
                load();
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Load notifications on page load
        load();

        // Auto-refresh every 30 seconds
        setInterval(load, 30000);

        // Listen for real-time updates
        window.addEventListener('cctv-status', load);
        window.addEventListener('dashboard-monitoring', load);
        window.addEventListener('realtime-notification', (ev) => {
            const d = ev.detail;
            if (!d) return;
            load();
        });
    </script>
    @endpush
</x-layouts.app>
