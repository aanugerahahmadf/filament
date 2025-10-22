<x-layouts.app :title="__('Notifications')">
    @push('styles')
    <style>
        .notification-card {
            transform-style: preserve-3d;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            perspective: 1000px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            margin-bottom: 1rem;
            /* Ensure content is visible */
            color: #111827 !important;
        }

        .dark .notification-card {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            color: #f9fafb !important;
        }

        /* System mode support */
        @media (prefers-color-scheme: dark) {
            .system-mode .notification-card {
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(30, 30, 30, 0.95);
                backdrop-filter: blur(10px);
                color: #f9fafb !important;
            }
        }

        .notification-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .dark .notification-card::before {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .notification-card::before {
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            }
        }

        .notification-card:hover::before {
            left: 100%;
        }

        .notification-card:hover {
            transform: translateY(-4px) rotateX(2deg);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.1);
        }

        .dark .notification-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.2);
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .notification-card:hover {
                box-shadow: 0 15px 30px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.2);
            }
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

        /* Ensure text is visible in all modes - more specific selectors */
        .notification-card .text-visible,
        .notification-card .text-primary,
        .notification-card .text-secondary,
        .notification-card .text-tertiary,
        .notification-card .text-error,
        .notification-card .text-success {
            color: #111827 !important; /* gray-900 for light mode - high contrast */
        }

        .dark .notification-card .text-visible,
        .dark .notification-card .text-primary,
        .dark .notification-card .text-secondary,
        .dark .notification-card .text-tertiary,
        .dark .notification-card .text-error,
        .dark .notification-card .text-success {
            color: #f9fafb !important; /* gray-50 for dark mode - high contrast */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .notification-card .text-visible,
            .system-mode .notification-card .text-primary,
            .system-mode .notification-card .text-secondary,
            .system-mode .notification-card .text-tertiary,
            .system-mode .notification-card .text-error,
            .system-mode .notification-card .text-success {
                color: #f9fafb !important; /* gray-50 for system dark mode */
            }
        }

        /* Text colors for different modes - with higher contrast */
        .text-primary {
            color: #111827 !important; /* gray-900 for light mode - high contrast */
        }

        .dark .text-primary {
            color: #f9fafb !important; /* gray-50 for dark mode - high contrast */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-primary {
                color: #f9fafb !important; /* gray-50 for system dark mode */
            }
        }

        .text-secondary {
            color: #111827 !important; /* gray-900 for light mode - better contrast */
        }

        .dark .text-secondary {
            color: #f9fafb !important; /* gray-50 for dark mode - better contrast */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-secondary {
                color: #f9fafb !important; /* gray-50 for system dark mode */
            }
        }

        .text-tertiary {
            color: #111827 !important; /* gray-900 for light mode - better contrast */
        }

        .dark .text-tertiary {
            color: #f3f4f6 !important; /* gray-100 for dark mode - better contrast */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-tertiary {
                color: #f3f4f6 !important; /* gray-100 for system dark mode */
            }
        }

        /* Error text colors */
        .text-error {
            color: #dc2626 !important; /* red-600 for light mode */
        }

        .dark .text-error {
            color: #fecaca !important; /* red-200 for dark mode */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-error {
                color: #fecaca !important; /* red-200 for system dark mode */
            }
        }

        /* Success text colors */
        .text-success {
            color: #16a34a !important; /* green-600 for light mode */
        }

        .dark .text-success {
            color: #bbf7d0 !important; /* green-200 for dark mode */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-success {
                color: #bbf7d0 !important; /* green-200 for system dark mode */
            }
        }

        /* Loading skeleton styles */
        .skeleton {
            background: #f0f0f0;
            animation: loading 1.5s infinite;
            border-radius: 0.5rem;
        }

        .dark .skeleton {
            background: #2d3748;
            animation: loading 1.5s infinite;
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .skeleton {
                background: #2d3748;
                animation: loading 1.5s infinite;
            }
        }

        @keyframes loading {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Button styles for all modes */
        .action-button {
            background: #10b981;
            color: white !important;
            border: 1px solid rgba(16, 185, 129, 0.7) !important;
            border-radius: 0.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }

        .dark .action-button {
            background: #059669;
            border: 1px solid rgba(16, 185, 129, 0.5) !important;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .action-button {
                background: #059669;
                border: 1px solid rgba(16, 185, 129, 0.5) !important;
                box-shadow: 0 4px 6px rgba(16, 185, 129, 0.1);
            }
        }

        .action-button:hover {
            background: #059669;
            box-shadow: 0 6px 8px rgba(16, 185, 129, 0.3);
            transform: scale(1.05);
        }

        .dark .action-button:hover {
            background: #047857;
            box-shadow: 0 6px 8px rgba(16, 185, 129, 0.2);
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .action-button:hover {
                background: #047857;
                box-shadow: 0 6px 8px rgba(16, 185, 129, 0.2);
            }
        }

        /* Header styles */
        .header-title {
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header-subtitle {
            color: #e0f2fe !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        /* Header text visibility */
        .header-title, .header-subtitle {
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* Ensure header text is visible in all modes */
        .notification-header .text-visible,
        .notification-header .text-primary {
            color: white !important;
        }

        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-unread {
            background-color: #3b82f6; /* blue-500 */
        }

        .status-read {
            background-color: #10b981; /* green-500 */
        }

        /* Modal styles */
        #notification-modal {
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 1000;
        }

        #notification-modal.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        #notification-modal:not(.hidden) {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Ensure modal is visible */
        #notification-modal .notification-card {
            z-index: 1001;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Ensure modal content is visible */
        #notification-modal .text-visible {
            color: #111827 !important;
        }

        .dark #notification-modal .text-visible {
            color: #f9fafb !important;
        }
    </style>
    @endpush

    <!-- Remove the min-h-screen and background classes that conflict with main layout -->
    <div class="page-wrapper">
        <div class="max-w-screen-xl mx-auto px-6 py-6 page-content-area">
            <!-- Enhanced Header -->
            <div class="relative overflow-hidden rounded-2xl bg-blue-600 p-8 text-white card-3d fade-in mb-8">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mr-8">
                            <i class="bx bxs-bell text-3xl text-white floating"></i>
                        </div>
                        <div class="text-left">
                            <h1 class="text-4xl font-bold mb-2 header-title text-visible">Notification</h1>
                            <p class="text-xl header-subtitle font-medium text-visible">Real-time System Alerts</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-4">
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm">
                            <div class="w-3 h-3 bg-green-400 rounded-full pulse-dot"></div>
                            <span class="font-medium text-white text-visible">Live Updates</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm">
                            <i class="bx bxs-time text-lg text-white"></i>
                            <span class="font-medium text-white text-visible">Real-time</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Notifications Responsive -->
            <style>
                @media (max-width: 768px) {
                    .notifications-container {
                        padding: 1rem !important;
                    }
                    .notifications-header {
                        padding: 1.5rem !important;
                        margin-bottom: 1.5rem !important;
                    }
                    .notifications-header .flex {
                        flex-direction: column !important;
                        gap: 1rem !important;
                    }
                    .notifications-header h1 {
                        font-size: 1.875rem !important;
                        text-align: center !important;
                    }
                    .notifications-header .text-xl {
                        font-size: 1rem !important;
                        text-align: center !important;
                    }
                    .notifications-header .gap-4 {
                        flex-direction: column !important;
                        gap: 0.5rem !important;
                    }
                    .notifications-grid {
                        grid-template-columns: 1fr !important;
                        gap: 1rem !important;
                    }
                }
                @media (max-width: 480px) {
                    .notifications-container {
                        padding: 0.75rem !important;
                    }
                    .notifications-header {
                        padding: 1rem !important;
                        margin-bottom: 1rem !important;
                    }
                    .notifications-header h1 {
                        font-size: 1.5rem !important;
                    }
                    .notifications-header .text-xl {
                        font-size: 0.875rem !important;
                    }
                }
            </style>

            <!-- Notifications List -->
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-primary text-visible">Recent Notifications</h2>
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/20 border border-green-500/40">
                        <div class="w-2 h-2 bg-green-400 rounded-full pulse-dot"></div>
                        <span class="text-sm font-medium text-success text-visible">Connected</span>
                    </div>
                </div>

                <div id="list" class="grid gap-4">
                    <!-- Loading placeholders will be dynamically inserted by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Detail Modal -->
    <div id="notification-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeNotificationModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="notification-card card-3d rounded-2xl p-6 w-full max-w-2xl fade-in relative">
                <button onclick="closeNotificationModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                    <i class="bx bx-x text-2xl text-gray-500 dark:text-gray-400"></i>
                </button>

                <div class="flex items-start gap-4 mb-6">
                    <div id="modal-icon-container" class="flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0">
                        <i id="modal-icon" class="notification-icon text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span id="modal-type" class="text-sm font-semibold text-primary truncate text-visible"></span>
                            <span id="modal-status" class="status-indicator" title="Unread"></span>
                        </div>
                        <div id="modal-created-at" class="text-xs text-tertiary text-visible"></div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-primary mb-2 text-visible">Notification Details</h3>
                    <div id="modal-message" class="text-secondary break-words text-visible"></div>
                </div>

                <div class="mb-6">
                    <h4 class="text-md font-semibold text-primary mb-2 text-visible">Additional Data</h4>
                    <div id="modal-data" class="text-secondary text-sm break-words text-visible"></div>
                </div>

                <div class="flex justify-end gap-2">
                    <button id="delete-notification-btn" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 action-button">
                        <i class="bx bxs-trash mr-1"></i> Delete
                    </button>
                    <button id="mark-as-read-btn" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 action-button">
                        <i class="bx bxs-check-circle mr-1"></i> Mark as Read
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.AppUserId = {{ auth()->id() ?? 'null' }};

        // Function to format date
        function formatDateTime(dateString) {
            try {
                const date = new Date(dateString);
                return date.toLocaleString();
            } catch (e) {
                return dateString;
            }
        }

        // Function to get icon and color based on notification type
        function getNotificationStyle(type) {
            if (!type) return { icon: 'bxs-bell', color: 'blue', bgColor: 'blue-500/20' };

            const typeLower = type.toLowerCase();

            if (typeLower.includes('error') || typeLower.includes('offline')) {
                return { icon: 'bxs-error-circle', color: 'red', bgColor: 'red-500/20' };
            } else if (typeLower.includes('success') || typeLower.includes('online')) {
                return { icon: 'bxs-check-circle', color: 'green', bgColor: 'green-500/20' };
            } else if (typeLower.includes('warning') || typeLower.includes('maintenance')) {
                return { icon: 'bxs-error', color: 'yellow', bgColor: 'yellow-500/20' };
            } else if (typeLower.includes('info') || typeLower.includes('message')) {
                return { icon: 'bxs-info-circle', color: 'blue', bgColor: 'blue-500/20' };
            }

            return { icon: 'bxs-bell', color: 'blue', bgColor: 'blue-500/20' };
        }

        // Function to extract message from notification data
        function extractMessage(notification) {
            console.log('Processing notification:', notification);
            // Check if message is directly in the notification
            if (notification.message) {
                console.log('Found direct message:', notification.message);
                return notification.message;
            }

            // Check if message is in the data object
            if (notification.data && typeof notification.data === 'object') {
                if (notification.data.message) {
                    console.log('Found message in data:', notification.data.message);
                    return notification.data.message;
                }

                // If data contains other fields, create a message from them
                const dataKeys = Object.keys(notification.data);
                if (dataKeys.length > 0) {
                    const message = `${dataKeys[0]}: ${notification.data[dataKeys[0]]}`;
                    console.log('Created message from data keys:', message);
                    return message;
                }
            }

            console.log('No message found, returning default');
            return 'No message available';
        }

        // Function to detect color mode
        function getColorMode() {
            // Check for explicit dark/light mode
            if (document.documentElement.classList.contains('dark')) {
                return 'dark';
            } else if (!document.documentElement.classList.contains('dark')) {
                return 'light';
            }

            // Check for system mode
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            return systemPrefersDark ? 'dark' : 'light';
        }

        async function load(){
            try {
                console.log('Fetching notifications from /api/notifications/');

                // Ensure we have a valid user
                if (!window.AppUserId || window.AppUserId === 'null') {
                    console.error('No valid user ID found');
                    throw new Error('User not authenticated');
                }

                // Show loading state
                const wrap = document.getElementById('list');
                if (!wrap) {
                    console.error('Notification container not found');
                    throw new Error('Notification container not found');
                }

                wrap.innerHTML = `
                    <div class="notification-card card-3d rounded-2xl p-6 fade-in">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 skeleton"></div>
                            <div class="flex-1">
                                <div class="h-4 skeleton mb-2"></div>
                                <div class="h-3 skeleton w-3/4"></div>
                            </div>
                        </div>
                    </div>
                    <div class="notification-card card-3d rounded-2xl p-6 fade-in">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 skeleton"></div>
                            <div class="flex-1">
                                <div class="h-4 skeleton mb-2"></div>
                                <div class="h-3 skeleton w-1/2"></div>
                            </div>
                        </div>
                    </div>
                `;

                // Fix the API endpoint to match the defined route
                const res = await fetch('/api/notifications/', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                console.log('Response status:', res.status);

                if (!res.ok) {
                    const errorText = await res.text();
                    console.error('HTTP error! status:', res.status, 'response:', errorText);
                    throw new Error(`HTTP error! status: ${res.status}, message: ${errorText}`);
                }

                const data = await res.json();
                console.log('Received data:', data);

                if (!data.success) {
                    throw new Error(data.message || 'API returned failure');
                }

                console.log('Items received:', data.items);
                if (!data.items || data.items.length === 0) {
                    console.log('No items to display');
                    wrap.innerHTML = `
                        <div class="notification-card card-3d rounded-2xl p-6 fade-in text-center">
                            <div class="flex items-center justify-center w-16 h-16 bg-gray-500/20 rounded-2xl mx-auto mb-4">
                                <i class="bx bxs-bell-off text-3xl text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-primary mb-2 text-visible">No Notifications</h3>
                            <p class="text-secondary text-sm text-visible">You're all caught up! Check back later for updates.</p>
                        </div>
                    `;
                    return;
                }

                // Clear the container
                wrap.innerHTML = '';

                data.items.forEach((n, index) => {
                    console.log('Processing notification item:', n);
                    const delay = index * 0.1;
                    const d = document.createElement('div');
                    d.className = 'notification-card card-3d rounded-2xl p-6 fade-in';
                    d.style.animationDelay = `${delay}s`;

                    // Get notification style based on type
                    const style = getNotificationStyle(n.type);
                    console.log('Notification style:', style);

                    // Extract message
                    const message = extractMessage(n);
                    console.log('Extracted message:', message);

                    // Format the created_at date
                    const formattedDate = n.created_at ? formatDateTime(n.created_at) : 'Unknown time';
                    console.log('Formatted date:', formattedDate);

                    d.innerHTML = `
                        <div class="flex items-start gap-4 cursor-pointer" onclick="showNotificationModal(${JSON.stringify(n).replace(/"/g, '&quot;')})">
                            <div class="flex items-center justify-center w-12 h-12 bg-${style.bgColor} rounded-xl flex-shrink-0">
                                <i class="bx ${style.icon} notification-icon text-2xl text-${style.color}-600 dark:text-${style.color}-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-primary truncate text-visible">${n.type || 'Notification'}</span>
                                    ${n.read_at ?
                                        '<span class="status-indicator status-read" title="Read"></span>' :
                                        '<span class="status-indicator status-unread pulse-dot" title="Unread"></span>'
                                    }
                                </div>
                                <div class="text-sm text-secondary mb-2 break-words text-visible">${message}</div>
                                <div class="text-xs text-tertiary text-visible">${formattedDate}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                ${n.read_at ?
                                    '<span class="text-xs text-success font-medium text-visible">Read</span>' :
                                    `<button class="p-2 action-button shadow-lg transform transition-all duration-300 cursor-pointer" onclick="markAsRead('${n.id}'); event.stopPropagation();" title="Mark as read">
                                        <i class="bx bxs-check text-sm"></i>
                                    </button>`
                                }
                            </div>
                        </div>
                    `;
                    wrap.appendChild(d);
                });
            } catch (error) {
                console.error('Error loading notifications:', error);
                const wrap = document.getElementById('list');
                if (wrap) {
                    wrap.innerHTML = `
                        <div class="notification-card card-3d rounded-2xl p-6 fade-in text-center">
                            <div class="flex items-center justify-center w-16 h-16 bg-red-500/20 rounded-2xl mx-auto mb-4">
                                    <i class="bx bxs-error-circle text-3xl text-red-600 dark:text-red-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-primary mb-2 text-visible">Error Loading Notifications</h3>
                            <p class="text-secondary text-sm text-visible">Please refresh the page or try again later.</p>
                            <p class="text-error text-xs mt-2 text-visible">Error: ${error.message}</p>
                            <button onclick="load()" class="mt-4 px-4 py-2 action-button transition-colors duration-200 text-visible">
                                Retry
                            </button>
                        </div>
                    `;
                }
            }
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, attempting to load notifications');
            // Ensure AppUserId is set
            if (typeof window.AppUserId === 'undefined' || window.AppUserId === 'null') {
                window.AppUserId = {{ auth()->id() ?? 'null' }};
            }
            console.log('AppUserId:', window.AppUserId);

            load();

            // Watch for color scheme changes
            if (window.matchMedia) {
                const colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
                colorSchemeQuery.addEventListener('change', load);
            }
        });

        // Auto-refresh every 30 seconds
        setInterval(load, 30000);

        // Listen for real-time updates (if using broadcasting)
        if (typeof Echo !== 'undefined') {
            Echo.private(`user.${window.AppUserId}`)
                .listen('.notification.created', (e) => {
                    console.log('New notification received:', e);
                    load();
                });
        }
    </script>

    <script>
        // Global variable to store the current notification
        let currentNotification = null;

        // Function to show notification detail modal
        function showNotificationModal(notification) {
            console.log('Showing notification modal for:', notification);
            currentNotification = notification;

            // Set modal content
            const style = getNotificationStyle(notification.type);
            console.log('Notification style:', style);

            // Set icon and background
            document.getElementById('modal-icon-container').className = `flex items-center justify-center w-12 h-12 bg-${style.bgColor} rounded-xl flex-shrink-0`;
            document.getElementById('modal-icon').className = `bx ${style.icon} notification-icon text-2xl text-${style.color}-600 dark:text-${style.color}-400`;

            // Set type and status
            document.getElementById('modal-type').textContent = notification.type || 'Notification';
            document.getElementById('modal-status').className = notification.read_at ?
                'status-indicator status-read' :
                'status-indicator status-unread pulse-dot';
            document.getElementById('modal-status').title = notification.read_at ? 'Read' : 'Unread';

            // Set created at
            const formattedDate = notification.created_at ? formatDateTime(notification.created_at) : 'Unknown time';
            document.getElementById('modal-created-at').textContent = formattedDate;

            // Set message
            const message = extractMessage(notification);
            document.getElementById('modal-message').textContent = message;

            // Set additional data
            const dataContainer = document.getElementById('modal-data');
            if (notification.data && typeof notification.data === 'object' && Object.keys(notification.data).length > 0) {
                let dataHtml = '<ul class="space-y-1">';
                for (const [key, value] of Object.entries(notification.data)) {
                    if (key !== 'message') { // Skip message as it's already displayed
                        dataHtml += `<li><strong>${key}:</strong> ${value}</li>`;
                    }
                }
                dataHtml += '</ul>';
                dataContainer.innerHTML = dataHtml;
            } else {
                dataContainer.textContent = 'No additional data available';
            }

            // Set button states
            const deleteBtn = document.getElementById('delete-notification-btn');
            const markAsReadBtn = document.getElementById('mark-as-read-btn');

            if (notification.read_at) {
                markAsReadBtn.innerHTML = '<i class="bx bxs-check-circle mr-1"></i> Mark as Unread';
                markAsReadBtn.onclick = () => markAsUnread(notification.id);
            } else {
                markAsReadBtn.innerHTML = '<i class="bx bxs-check-circle mr-1"></i> Mark as Read';
                markAsReadBtn.onclick = () => markAsRead(notification.id);
            }

            deleteBtn.onclick = () => deleteNotification(notification.id);

            // Show modal
            console.log('Showing modal');
            document.getElementById('notification-modal').classList.remove('hidden');
            console.log('Modal should be visible now');
        }

        // Function to close notification detail modal
        function closeNotificationModal() {
            console.log('Closing notification modal');
            document.getElementById('notification-modal').classList.add('hidden');
            currentNotification = null;
            console.log('Modal closed');
        }

        // Function to mark notification as read
        async function markAsRead(notificationId) {
            try {
                const response = await fetch(`/api/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    // Reload notifications
                    load();
                    // Close modal if it's for the current notification
                    if (currentNotification && currentNotification.id === notificationId) {
                        closeNotificationModal();
                    }
                } else {
                    console.error('Failed to mark notification as read:', data.message);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Function to mark notification as unread
        async function markAsUnread(notificationId) {
            try {
                const response = await fetch(`/api/notifications/${notificationId}/unread`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    // Reload notifications
                    load();
                    // Close modal if it's for the current notification
                    if (currentNotification && currentNotification.id === notificationId) {
                        closeNotificationModal();
                    }
                } else {
                    console.error('Failed to mark notification as unread:', data.message);
                }
            } catch (error) {
                console.error('Error marking notification as unread:', error);
            }
        }

        // Function to delete notification
        async function deleteNotification(notificationId) {
            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }

            try {
                const response = await fetch(`/api/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    // Reload notifications
                    load();
                    // Close modal
                    closeNotificationModal();
                    // Show confirmation
                    alert('Notification deleted successfully!');
                } else {
                    console.error('Failed to delete notification:', data.message);
                    alert('Failed to delete notification: ' + data.message);
                }
            } catch (error) {
                console.error('Error deleting notification:', error);
                alert('Failed to delete notification. Please try again.');
            }
        }

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('notification-modal').classList.contains('hidden')) {
                closeNotificationModal();
            }
        });
    </script>
    @endpush
</x-layouts.app>
