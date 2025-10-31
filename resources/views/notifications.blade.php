<x-layouts.app :title="__('Notifications')">
    @push('styles')
    <style>
        .notification-card {
            transform-style: preserve-3d;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            perspective: 1000px;
            position: relative;
            overflow: hidden;
            border: 2px solid rgba(0, 0, 0, 0.15) !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            /* Ensure ALL content is visible in light mode */
            color: #111827 !important;
        }

        .notification-card h1,
        .notification-card h2,
        .notification-card h3,
        .notification-card h4,
        .notification-card h5,
        .notification-card h6,
        .notification-card p,
        .notification-card span,
        .notification-card div,
        .notification-card label {
            color: #111827 !important;
        }

        .dark .notification-card {
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            background: rgba(30, 30, 30, 0.98) !important;
            backdrop-filter: blur(10px);
            color: #f9fafb !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }

        .dark .notification-card h1,
        .dark .notification-card h2,
        .dark .notification-card h3,
        .dark .notification-card h4,
        .dark .notification-card h5,
        .dark .notification-card h6,
        .dark .notification-card p,
        .dark .notification-card span,
        .dark .notification-card div,
        .dark .notification-card label {
            color: #f9fafb !important;
        }

        /* System mode support */
        @media (prefers-color-scheme: dark) {
            .system-mode .notification-card {
                border: 2px solid rgba(255, 255, 255, 0.2) !important;
                background: rgba(30, 30, 30, 0.98) !important;
                backdrop-filter: blur(10px);
                color: #f9fafb !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            }

            .system-mode .notification-card h1,
            .system-mode .notification-card h2,
            .system-mode .notification-card h3,
            .system-mode .notification-card h4,
            .system-mode .notification-card h5,
            .system-mode .notification-card h6,
            .system-mode .notification-card p,
            .system-mode .notification-card span,
            .system-mode .notification-card div,
            .system-mode .notification-card label {
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

        /* Ensure all text in notification card is visible in light mode */
        .notification-card,
        .notification-card * {
            color: #111827 !important; /* dark gray for light mode */
        }

        /* Exception for buttons - they should maintain their colors */
        .notification-card button,
        .notification-card .action-button {
            color: white !important;
        }

        /* Exception for icons - they get inline color styles */
        .notification-card i.bx,
        .notification-card .notification-icon {
            color: inherit !important;
        }

        /* Dark mode text visibility */
        .dark .notification-card,
        .dark .notification-card * {
            color: #f9fafb !important; /* light gray for dark mode */
        }

        .dark .notification-card button,
        .dark .notification-card .action-button {
            color: white !important;
        }

        .dark .notification-card i.bx,
        .dark .notification-card .notification-icon {
            color: inherit !important;
        }

        /* System mode dark text visibility */
        @media (prefers-color-scheme: dark) {
            .system-mode .notification-card,
            .system-mode .notification-card * {
                color: #f9fafb !important;
            }

            .system-mode .notification-card button,
            .system-mode .notification-card .action-button {
                color: white !important;
            }

            .system-mode .notification-card i.bx,
            .system-mode .notification-card .notification-icon {
                color: inherit !important;
            }
        }

        /* Text colors for different modes - with higher contrast */
        .text-primary,
        .text-primary * {
            color: #111827 !important; /* gray-900 for light mode - high contrast */
        }

        .dark .text-primary,
        .dark .text-primary * {
            color: #f9fafb !important; /* gray-50 for dark mode - high contrast */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-primary,
            .system-mode .text-primary * {
                color: #f9fafb !important; /* gray-50 for system dark mode */
            }
        }

        .text-secondary,
        .text-secondary * {
            color: #111827 !important; /* gray-900 for light mode - better contrast */
        }

        .dark .text-secondary,
        .dark .text-secondary * {
            color: #f9fafb !important; /* gray-50 for dark mode - better contrast */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-secondary,
            .system-mode .text-secondary * {
                color: #f9fafb !important; /* gray-50 for system dark mode */
            }
        }

        .text-tertiary,
        .text-tertiary * {
            color: #111827 !important; /* gray-900 for light mode - better contrast */
        }

        .dark .text-tertiary,
        .dark .text-tertiary * {
            color: #f9fafb !important; /* gray-100 for dark mode - better contrast */
        }

        @media (prefers-color-scheme: dark) {
            .system-mode .text-tertiary,
            .system-mode .text-tertiary * {
                color: #f9fafb !important; /* gray-100 for system dark mode */
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
            background: #10b981 !important;
            color: white !important;
            border: 2px solid rgba(16, 185, 129, 0.7) !important;
            border-radius: 0.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
            padding: 0.5rem 1rem !important;
            transition: all 0.2s ease-in-out !important;
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

        /* Ensure modal is visible and not blurred/transparent */
        #notification-modal .notification-card {
            z-index: 1001;
            max-height: 90vh;
            overflow-y: auto;
            /* Override base card transparency/blur for modal */
            background: #000000 !important; /* solid black */
            color: #ffffff !important; /* force white text on black */
            backdrop-filter: none !important;
            opacity: 1 !important;
        }

        /* Ensure modal content is visible in all modes */
        #notification-modal .notification-card,
        #notification-modal .notification-card * {
            color: #ffffff !important; /* white text on black */
        }

        #notification-modal .notification-card button,
        #notification-modal .notification-card .action-button {
            color: white !important;
        }

        #notification-modal .notification-card i.bx,
        #notification-modal .notification-card .notification-icon {
            color: inherit !important;
        }

        .dark #notification-modal .notification-card,
        .dark #notification-modal .notification-card * {
            color: #ffffff !important; /* white for dark mode */
        }
        .dark #notification-modal .notification-card {
            background: #000000 !important; /* solid black */
            backdrop-filter: none !important;
            opacity: 1 !important;
        }

        .dark #notification-modal .notification-card button,
        .dark #notification-modal .notification-card .action-button {
            color: white !important;
        }

        .dark #notification-modal .notification-card i.bx,
        .dark #notification-modal .notification-card .notification-icon {
            color: inherit !important;
        }

        @media (prefers-color-scheme: dark) {
            .system-mode #notification-modal .notification-card,
            .system-mode #notification-modal .notification-card * {
                color: #ffffff !important;
            }
            .system-mode #notification-modal .notification-card {
                background: #000000 !important; /* solid black in system dark mode */
                backdrop-filter: none !important;
                opacity: 1 !important;
            }

            .system-mode #notification-modal .notification-card button,
            .system-mode #notification-modal .notification-card .action-button {
                color: white !important;
            }

            .system-mode #notification-modal .notification-card i.bx,
            .system-mode #notification-modal .notification-card .notification-icon {
                color: inherit !important;
            }
        }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .notification-header {
                padding: 1.5rem !important;
                margin-bottom: 1.5rem !important;
            }

            .notification-header .flex {
                flex-direction: column !important;
                gap: 1rem !important;
            }

            .notification-header h1 {
                font-size: 1.875rem !important;
                text-align: center !important;
            }

            .notification-header .text-xl {
                font-size: 1rem !important;
                text-align: center !important;
            }

            .notification-header .gap-4 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            .notification-item {
                flex-direction: column !important;
                gap: 1rem !important;
            }

            .notification-item .flex-shrink-0 {
                margin-bottom: 0.5rem !important;
            }

            .notification-actions {
                width: 100% !important;
                justify-content: flex-end !important;
            }
        }

        @media (max-width: 480px) {
            .notification-header {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }

            .notification-header h1 {
                font-size: 1.5rem !important;
            }

            .notification-header .text-xl {
                font-size: 0.875rem !important;
            }

            .notification-card {
                padding: 1rem !important;
            }
        }

        /* Notification channel badge */
        .channel-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .channel-system {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .dark .channel-system {
            background-color: #1e3a8a;
            color: #bfdbfe;
        }

        .channel-alert {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .dark .channel-alert {
            background-color: #7f1d1d;
            color: #fecaca;
        }

        .channel-info {
            background-color: #dcfce7;
            color: #15803d;
        }

        .dark .channel-info {
            background-color: #166534;
            color: #bbf7d0;
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

            <!-- Notifications List -->
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-primary text-visible">Recent Notifications</h2>
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/20 border border-green-500/40">
                        <div class="w-2 h-2 bg-green-400 rounded-full pulse-dot"></div>
                        <span class="text-sm font-medium text-success text-visible">Connected</span>
                    </div>
                </div>

                <div id="list" class="space-y-4">
                    <!-- Notifications will be loaded here by JavaScript -->
                    <div class="notification-card card-3d rounded-2xl p-6 fade-in text-center">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-500/20 rounded-2xl mx-auto mb-4">
                            <i class="bx bxs-bell text-3xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-primary mb-2 text-visible">Loading Notifications...</h3>
                        <p class="text-secondary text-sm text-visible">Please wait while we fetch your notifications.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Detail Modal -->
    <div id="notification-modal" class="fixed inset-0 z-50 hidden">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
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
                        <div class="flex items-center gap-2 mb-2">
                            <span id="modal-channel" class="channel-badge"></span>
                            <span id="modal-created-at" class="text-xs text-tertiary text-visible"></span>
                        </div>
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

    <script>
        // Set AppUserId immediately
        window.AppUserId = {{ auth()->check() ? auth()->id() : 'null' }};
        console.log('AppUserId from Blade:', window.AppUserId);

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
            if (!type) return {
                icon: 'bxs-bell',
                colorClass: 'text-blue-600 dark:text-blue-400',
                bgColorClass: 'bg-blue-100 dark:bg-blue-900/30',
                borderClass: 'border-blue-200 dark:border-blue-800'
            };

            const typeLower = type.toLowerCase();

            // Handle message notifications specifically
            if (typeLower.includes('message')) {
                return {
                    icon: 'bxs-message',
                    colorClass: 'text-green-600 dark:text-green-400',
                    bgColorClass: 'bg-green-100 dark:bg-green-900/30',
                    borderClass: 'border-green-200 dark:border-green-800'
                };
            } else if (typeLower.includes('error') || typeLower.includes('offline')) {
                return {
                    icon: 'bxs-error-circle',
                    colorClass: 'text-red-600 dark:text-red-400',
                    bgColorClass: 'bg-red-100 dark:bg-red-900/30',
                    borderClass: 'border-red-200 dark:border-red-800'
                };
            } else if (typeLower.includes('success') || typeLower.includes('online')) {
                return {
                    icon: 'bxs-check-circle',
                    colorClass: 'text-green-600 dark:text-green-400',
                    bgColorClass: 'bg-green-100 dark:bg-green-900/30',
                    borderClass: 'border-green-200 dark:border-green-800'
                };
            } else if (typeLower.includes('warning') || typeLower.includes('maintenance')) {
                return {
                    icon: 'bxs-error',
                    colorClass: 'text-yellow-600 dark:text-yellow-400',
                    bgColorClass: 'bg-yellow-100 dark:bg-yellow-900/30',
                    borderClass: 'border-yellow-200 dark:border-yellow-800'
                };
            } else if (typeLower.includes('info') || typeLower.includes('notification')) {
                return {
                    icon: 'bxs-info-circle',
                    colorClass: 'text-blue-600 dark:text-blue-400',
                    bgColorClass: 'bg-blue-100 dark:bg-blue-900/30',
                    borderClass: 'border-blue-200 dark:border-blue-800'
                };
            }

            return {
                icon: 'bxs-bell',
                colorClass: 'text-blue-600 dark:text-blue-400',
                bgColorClass: 'bg-blue-100 dark:bg-blue-900/30',
                borderClass: 'border-blue-200 dark:border-blue-800'
            };
        }

        // Function to get channel badge based on notification type
        function getChannelBadge(type) {
            if (!type) return { text: 'System', class: 'channel-system' };

            const typeLower = type.toLowerCase();

            // Handle message notifications specifically
            if (typeLower.includes('message')) {
                return { text: 'Message', class: 'channel-info' };
            } else if (typeLower.includes('error') || typeLower.includes('alert') || typeLower.includes('critical')) {
                return { text: 'Alert', class: 'channel-alert' };
            } else if (typeLower.includes('info') || typeLower.includes('notification')) {
                return { text: 'Info', class: 'channel-info' };
            } else {
                return { text: 'System', class: 'channel-system' };
            }
        }

        // Function to extract message from notification data
        function extractMessage(notification) {
            // Check if message is directly in the notification
            if (notification.message) {
                return notification.message;
            }

            // Check if message is in the data object
            if (notification.data && typeof notification.data === 'object') {
                if (notification.data.message) {
                    return notification.data.message;
                }

                // For message notifications, check for message_body
                if (notification.data.message_body) {
                    return notification.data.message_body;
                }

                // If data contains other fields, create a message from them
                const dataKeys = Object.keys(notification.data);
                if (dataKeys.length > 0) {
                    const message = `${dataKeys[0]}: ${notification.data[dataKeys[0]]}`;
                    return message;
                }
            }

            return 'No message available';
        }

        // Global variable to store the current notification
        let currentNotification = null;

        // Function to show notification modal or handle message notifications
        function showNotificationModal(notificationId) {
            // Find the notification element in the list
            const notificationCards = document.querySelectorAll('.notification-card');
            let notificationData = null;

            // Search through all notification cards to find the one with matching ID
            for (let card of notificationCards) {
                if (card.notificationData && card.notificationData.id == notificationId) {
                    notificationData = card.notificationData;
                    break;
                }
            }

            if (!notificationData) {
                console.error('Notification data not found for ID:', notificationId);
                return;
            }

            // Store current notification
            currentNotification = notificationData;

            // Check if this is a message notification
            const isMessageNotification = notificationData.type && notificationData.type.toLowerCase().includes('message');

            // Handle message notifications with enhanced options
            if (isMessageNotification) {
                // Extract sender ID from notification data
                let senderId = null;
                if (notificationData.data && notificationData.data.sender_id) {
                    senderId = notificationData.data.sender_id;
                } else if (notificationData.notifiable_id) {
                    senderId = notificationData.notifiable_id;
                }

                if (senderId) {
                    // Show enhanced modal for message notifications
                    showMessageNotificationModal(notificationData, senderId);
                    return;
                }
            }

            // For non-message notifications, show the standard modal
            showStandardNotificationModal(notificationData);
        }

        // Function to show enhanced modal for message notifications
        function showMessageNotificationModal(notificationData, senderId) {
            const modal = document.getElementById('notification-modal');
            const modalType = document.getElementById('modal-type');
            const modalStatus = document.getElementById('modal-status');
            const modalChannel = document.getElementById('modal-channel');
            const modalCreatedAt = document.getElementById('modal-created-at');
            const modalMessage = document.getElementById('modal-message');
            const modalData = document.getElementById('modal-data');
            const modalIcon = document.getElementById('modal-icon');
            const modalIconContainer = document.getElementById('modal-icon-container');
            const deleteBtn = document.getElementById('delete-notification-btn');
            const markAsReadBtn = document.getElementById('mark-as-read-btn');

            // Get notification style
            const style = getNotificationStyle(notificationData.type);
            const channel = getChannelBadge(notificationData.type);

            // Set modal content
            modalType.textContent = notificationData.type || 'Notification';
            modalStatus.className = 'status-indicator';
            modalStatus.title = notificationData.read_at ? 'Read' : 'Unread';
            if (notificationData.read_at) {
                modalStatus.classList.add('status-read');
            } else {
                modalStatus.classList.add('status-unread', 'pulse-dot');
            }
            modalChannel.textContent = channel.text;
            modalChannel.className = 'channel-badge ' + channel.class;
            modalCreatedAt.textContent = notificationData.created_at ? formatDateTime(notificationData.created_at) : 'Unknown time';
            modalMessage.textContent = extractMessage(notificationData);
            modalIcon.className = 'notification-icon text-2xl ' + style.colorClass;
            modalIcon.classList.add(style.icon);
            modalIconContainer.className = 'flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 ' + style.bgColorClass + ' ' + style.borderClass;

            // Set additional data
            if (notificationData.data && Object.keys(notificationData.data).length > 0) {
                let dataHtml = '<ul class="list-disc pl-5 space-y-1">';
                for (const [key, value] of Object.entries(notificationData.data)) {
                    if (key !== 'message' && key !== 'message_body') { // Skip message fields as they're already displayed
                        dataHtml += `<li><span class="font-medium">${key}:</span> ${value}</li>`;
                    }
                }
                dataHtml += '</ul>';
                modalData.innerHTML = dataHtml;
            } else {
                modalData.innerHTML = '<p class="text-secondary">No additional data available.</p>';
            }

            // Set button actions
            deleteBtn.onclick = () => deleteNotification(notificationData.id);
            markAsReadBtn.onclick = () => {
                if (notificationData.read_at) {
                    markAsUnread(notificationData.id);
                } else {
                    markAsRead(notificationData.id);
                }
            };
            markAsReadBtn.innerHTML = notificationData.read_at ?
                '<i class="bx bxs-checkbox mr-1"></i> Mark as Unread' :
                '<i class="bx bxs-check-circle mr-1"></i> Mark as Read';

            // Remove existing redirect button if it exists to prevent duplicates
            const existingRedirectBtn = document.getElementById('redirect-message-btn');
            if (existingRedirectBtn) {
                existingRedirectBtn.remove();
            }

            // Add redirect to message box button
            const redirectBtn = document.createElement('button');
            redirectBtn.id = 'redirect-message-btn';
            redirectBtn.className = 'px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 action-button';
            redirectBtn.innerHTML = '<i class="bx bxs-chat mr-1"></i> Go to Messages';
            redirectBtn.onclick = () => {
                window.location.href = `/messages/conversation/${senderId}`;
            };

            // Insert the redirect button before the delete button
            const buttonContainer = deleteBtn.parentNode;
            buttonContainer.insertBefore(redirectBtn, deleteBtn);

            // Show modal
            modal.classList.remove('hidden');
        }

        // Function to show standard modal for non-message notifications
        function showStandardNotificationModal(notificationData) {
            const modal = document.getElementById('notification-modal');
            const modalType = document.getElementById('modal-type');
            const modalStatus = document.getElementById('modal-status');
            const modalChannel = document.getElementById('modal-channel');
            const modalCreatedAt = document.getElementById('modal-created-at');
            const modalMessage = document.getElementById('modal-message');
            const modalData = document.getElementById('modal-data');
            const modalIcon = document.getElementById('modal-icon');
            const modalIconContainer = document.getElementById('modal-icon-container');
            const deleteBtn = document.getElementById('delete-notification-btn');
            const markAsReadBtn = document.getElementById('mark-as-read-btn');

            // Remove redirect button if it exists
            const existingRedirectBtn = document.getElementById('redirect-message-btn');
            if (existingRedirectBtn) {
                existingRedirectBtn.remove();
            }

            // Get notification style
            const style = getNotificationStyle(notificationData.type);
            const channel = getChannelBadge(notificationData.type);

            // Set modal content
            modalType.textContent = notificationData.type || 'Notification';
            modalStatus.className = 'status-indicator';
            modalStatus.title = notificationData.read_at ? 'Read' : 'Unread';
            if (notificationData.read_at) {
                modalStatus.classList.add('status-read');
            } else {
                modalStatus.classList.add('status-unread', 'pulse-dot');
            }
            modalChannel.textContent = channel.text;
            modalChannel.className = 'channel-badge ' + channel.class;
            modalCreatedAt.textContent = notificationData.created_at ? formatDateTime(notificationData.created_at) : 'Unknown time';
            modalMessage.textContent = extractMessage(notificationData);
            modalIcon.className = 'notification-icon text-2xl ' + style.colorClass;
            modalIcon.classList.add(style.icon);
            modalIconContainer.className = 'flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 ' + style.bgColorClass + ' ' + style.borderClass;

            // Set additional data
            if (notificationData.data && Object.keys(notificationData.data).length > 0) {
                let dataHtml = '<ul class="list-disc pl-5 space-y-1">';
                for (const [key, value] of Object.entries(notificationData.data)) {
                    if (key !== 'message' && key !== 'message_body') { // Skip message fields as they're already displayed
                        dataHtml += `<li><span class="font-medium">${key}:</span> ${value}</li>`;
                    }
                }
                dataHtml += '</ul>';
                modalData.innerHTML = dataHtml;
            } else {
                modalData.innerHTML = '<p class="text-secondary">No additional data available.</p>';
            }

            // Set button actions
            deleteBtn.onclick = () => deleteNotification(notificationData.id);
            markAsReadBtn.onclick = () => {
                if (notificationData.read_at) {
                    markAsUnread(notificationData.id);
                } else {
                    markAsRead(notificationData.id);
                }
            };
            markAsReadBtn.innerHTML = notificationData.read_at ?
                '<i class="bx bxs-checkbox mr-1"></i> Mark as Unread' :
                '<i class="bx bxs-check-circle mr-1"></i> Mark as Read';

            // Show modal
            modal.classList.remove('hidden');
        }

        async function load(){
            try {
                // Ensure we have a valid user
                if (!window.AppUserId || window.AppUserId === 'null') {
                    throw new Error('User not authenticated');
                }

                // Show loading state
                const wrap = document.getElementById('list');
                if (!wrap) {
                    throw new Error('Notification container not found');
                }

                wrap.innerHTML = `
                    <div class="notification-card card-3d rounded-2xl p-6 fade-in text-center">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-500/20 rounded-2xl mx-auto mb-4">
                            <i class="bx bxs-bell text-3xl text-blue-600 dark:text-blue-400 animate-pulse"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-primary mb-2 text-visible">Loading Notifications...</h3>
                        <p class="text-secondary text-sm text-visible">Please wait while we fetch your notifications.</p>
                    </div>
                `;

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                // Use the web route for notifications instead of API route
                const res = await fetch('/user-notifications', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                });

                // Handle authentication errors gracefully
                if (res.status === 401) {
                    wrap.innerHTML = `
                        <div class="notification-card card-3d rounded-2xl p-6 fade-in text-center">
                            <div class="flex items-center justify-center w-16 h-16 bg-red-500/20 rounded-2xl mx-auto mb-4">
                                <i class="bx bxs-error-circle text-3xl text-red-600 dark:text-red-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-primary mb-2 text-visible">Authentication Error</h3>
                            <p class="text-secondary text-sm text-visible">Please refresh the page or try again later.</p>
                            <button onclick="load()" class="mt-4 px-4 py-2 action-button transition-colors duration-200 text-visible">
                                Retry
                            </button>
                        </div>
                    `;
                    return;
                }

                if (!res.ok) {
                    const errorText = await res.text();
                    throw new Error(`HTTP error! status: ${res.status}, message: ${errorText}`);
                }

                const data = await res.json();

                // Handle case where data.success is false without throwing an error
                if (!data.success) {
                    // Show empty state instead of error
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

                if (!data.items || data.items.length === 0) {
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
                    const delay = index * 0.1;
                    const d = document.createElement('div');
                    d.className = 'notification-card card-3d rounded-2xl p-6 fade-in border-2';
                    d.style.animationDelay = `${delay}s`;
                    d.style.marginBottom = '1rem';

                    // Get notification style based on type
                    const style = getNotificationStyle(n.type);
                    const channel = getChannelBadge(n.type);

                    // Extract message
                    const message = extractMessage(n);

                    // Format the created_at date
                    const formattedDate = n.created_at ? formatDateTime(n.created_at) : 'Unknown time';

                    // Determine color values for inline styles
                    let bgColor, borderColor, iconColor;
                    const typeLower = (n.type || '').toLowerCase();

                    if (typeLower.includes('message')) {
                        bgColor = 'rgb(240 253 244)'; // green-50
                        borderColor = 'rgb(187 247 208)'; // green-300
                        iconColor = 'rgb(22 163 74)'; // green-600
                    } else if (typeLower.includes('error') || typeLower.includes('offline')) {
                        bgColor = 'rgb(254 242 242)'; // red-50
                        borderColor = 'rgb(254 202 202)'; // red-300
                        iconColor = 'rgb(220 38 38)'; // red-600
                    } else if (typeLower.includes('success') || typeLower.includes('online')) {
                        bgColor = 'rgb(240 253 244)'; // green-50
                        borderColor = 'rgb(187 247 208)'; // green-300
                        iconColor = 'rgb(22 163 74)'; // green-600
                    } else if (typeLower.includes('warning') || typeLower.includes('maintenance')) {
                        bgColor = 'rgb(254 252 232)'; // yellow-50
                        borderColor = 'rgb(254 240 138)'; // yellow-300
                        iconColor = 'rgb(202 138 4)'; // yellow-700
                    } else {
                        bgColor = 'rgb(239 246 255)'; // blue-50
                        borderColor = 'rgb(191 219 254)'; // blue-300
                        iconColor = 'rgb(37 99 235)'; // blue-600
                    }

                    // Escape HTML to prevent XSS
                    const escapedMessage = (message || '').toString().replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    const escapedType = (n.type || 'Notification').toString().replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    const escapedId = (n.id || '').toString().replace(/</g, '&lt;').replace(/>/g, '&gt;');

                    d.innerHTML = `
                        <div class="flex items-start gap-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded-lg transition-colors notification-item" onclick="showNotificationModal('${escapedId}')">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 border-2" style="background-color: ${bgColor}; border-color: ${borderColor};">
                                <i class="bx ${style.icon} notification-icon text-2xl" style="color: ${iconColor};"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-primary truncate text-visible">${escapedType}</span>
                                    <span class="channel-badge ${channel.class}">${channel.text}</span>
                                    ${n.read_at ?
                                        '<span class="status-indicator status-read" title="Read"></span>' :
                                        '<span class="status-indicator status-unread pulse-dot" title="Unread"></span>'
                                    }
                                </div>
                                <div class="text-sm text-secondary mb-2 break-words text-visible font-medium">${escapedMessage}</div>
                                <div class="text-xs text-tertiary text-visible">${formattedDate}</div>
                            </div>
                            <div class="flex items-center gap-2 notification-actions">
                                ${n.read_at ?
                                    '<span class="text-xs text-success font-medium text-visible">Read</span>' :
                                    `<button class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 action-button" onclick="markAsRead('${escapedId}'); event.stopPropagation();" title="Mark as read">
                                        <i class="bx bxs-check mr-1"></i> Mark as Read
                                    </button>`
                                }
                            </div>
                        </div>
                    `;

                    // Store the notification data in the element
                    d.notificationData = n;

                    wrap.appendChild(d);
                });
            } catch (error) {
                console.error('Error loading notifications:', error);
                const wrap = document.getElementById('list');
                if (wrap) {
                    let errorMessage = error.message || 'Unknown error';
                    let additionalAction = `
                        <button onclick="load()" class="mt-4 px-4 py-2 action-button transition-colors duration-200 text-visible">
                            Retry
                        </button>
                    `;

                    // Handle authentication errors gracefully
                    if(errorMessage.includes('401') || errorMessage.includes('Unauthenticated')) {
                        wrap.innerHTML = `
                            <div class="notification-card card-3d rounded-2xl p-6 fade-in text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-red-500/20 rounded-2xl mx-auto mb-4">
                                    <i class="bx bxs-error-circle text-3xl text-red-600 dark:text-red-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-primary mb-2 text-visible">Authentication Error</h3>
                                <p class="text-secondary text-sm text-visible">Please refresh the page or try again later.</p>
                                ${additionalAction}
                            </div>
                        `;
                        return;
                    }

                    wrap.innerHTML = `
                        <div class="notification-card card-3d rounded-2xl p-6 fade-in text-center">
                            <div class="flex items-center justify-center w-16 h-16 bg-red-500/20 rounded-2xl mx-auto mb-4">
                                <i class="bx bxs-error-circle text-3xl text-red-600 dark:text-red-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-primary mb-2 text-visible">Error Loading Notifications</h3>
                            <p class="text-secondary text-sm text-visible">Please refresh the page or try again later.</p>
                            <p class="text-error text-xs mt-2 text-visible">${errorMessage}</p>
                            ${additionalAction}
                        </div>
                    `;
                }
            }
        }

        // Function to close notification detail modal
        function closeNotificationModal() {
            const modal = document.getElementById('notification-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
            currentNotification = null;
        }

        // Function to mark notification as read
        async function markAsRead(notificationId) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                });

                // Handle authentication errors gracefully
                if (response.status === 401) {
                    alert('Authentication error. Please refresh the page.');
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    // Reload notifications
                    load();
                    // Close modal if it's for the current notification
                    if (currentNotification && currentNotification.id === notificationId) {
                        closeNotificationModal();
                    }
                } else {
                    alert('Failed to mark notification as read: ' + data.message);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
                alert('Error marking notification as read: ' + error.message);
            }
        }

        // Function to mark notification as unread
        async function markAsUnread(notificationId) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch(`/notifications/${notificationId}/unread`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                });

                // Handle authentication errors gracefully
                if (response.status === 401) {
                    alert('Authentication error. Please refresh the page.');
                    return;
                }

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
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                });

                // Handle authentication errors gracefully
                if (response.status === 401) {
                    alert('Authentication error. Please refresh the page.');
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    // Reload notifications
                    load();
                    // Close modal
                    closeNotificationModal();
                    // Show confirmation
                    alert('Notification deleted successfully!');
                } else {
                    alert('Failed to delete notification: ' + data.message);
                }
            } catch (error) {
                console.error('Error deleting notification:', error);
                alert('Failed to delete notification. Please try again.');
            }
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure AppUserId is set
            if (typeof window.AppUserId === 'undefined' || window.AppUserId === 'null') {
                window.AppUserId = {{ auth()->id() ?? 'null' }};
            }

            // Load notifications
            load();

            // Watch for color scheme changes
            if (window.matchMedia) {
                const colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
                colorSchemeQuery.addEventListener('change', load);
            }
        });

        // Auto-refresh every 30 seconds
        setInterval(load, 30000);

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('notification-modal').classList.contains('hidden')) {
                closeNotificationModal();
            }
        });

        // Listen for real-time updates (if using broadcasting)
        if (typeof Echo !== 'undefined' && window.AppUserId !== 'null') {
            Echo.private(`user.${window.AppUserId}`)
                .listen('.notification.created', (e) => {
                    console.log('Real-time notification received:', e);
                    load();
                });
        }

        // Listen for global realtime-notification events from app.js
        window.addEventListener('realtime-notification', function(e) {
            console.log('Global realtime notification received:', e.detail);
            // Reload notifications to show the new one
            load();
        });
    </script>
</x-layouts.app>
