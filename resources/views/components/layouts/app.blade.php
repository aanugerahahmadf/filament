<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            /* Sidebar visibility - hide when explicitly collapsed */
            [data-flux-sidebar-collapsed-desktop] {
                display: none !important;
                width: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                border: 0 !important;
                overflow: hidden !important;
            }

            /* Ensure main content expands when sidebar is hidden */
            [data-flux-sidebar-collapsed-desktop] + div {
                width: 100% !important;
                flex: 1 !important;
            }

            /* Override Flux sidebar completely */
            .sidebar-hidden {
                display: none !important;
                width: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                border: 0 !important;
                overflow: hidden !important;
            }

            .main-content-expanded {
                width: 100% !important;
                flex: 1 !important;
            }

            /* Always show sidebar on desktop by default */
            @media (min-width: 769px) {
                [data-flux-sidebar]:not([data-flux-sidebar-collapsed-desktop]) {
                    display: flex !important;
                    width: 256px !important;
                }
            }

            /* Sidebar no scroll - fixed height */
            [data-flux-sidebar] {
                overflow-y: hidden !important;
                position: relative !important;
                height: 100vh !important;
                max-height: 100vh !important;
            }

            [data-flux-sidebar] .flux-navlist {
                overflow-y: hidden !important;
                max-height: calc(100vh - 200px) !important;
            }

            /* Allow sidebar to scroll - removed conflicting overrides */
            /* Note: Sidebar now uses overflow-y: auto to show all menu items */

            /* Ensure consistent height across all layout elements */
            html, body {
                height: 100%;
                overflow: hidden;
            }

            .flex.flex-1.overflow-hidden {
                height: 100vh;
                max-height: 100vh;
            }

            .flex.flex-col.flex-1.overflow-hidden {
                height: 100vh;
                max-height: 100vh;
            }

            /* Mobile First Responsive Design */
            @media (max-width: 480px) {
                /* Extra small mobile */
                .flux-header {
                    padding: 0.5rem !important;
                    gap: 0.25rem !important;
                }

                .flux-navbar {
                    gap: 0.125rem !important;
                }

                .flux-navbar-item {
                    padding: 0.25rem !important;
                    min-width: 2rem !important;
                }

                .flux-navbar-item svg {
                    width: 1rem !important;
                    height: 1rem !important;
                }

                #sidebar-toggle-btn {
                    padding: 0.375rem !important;
                    min-width: 2rem !important;
                }

                #sidebar-toggle-btn svg {
                    width: 1rem !important;
                    height: 1rem !important;
                }
            }

            @media (min-width: 481px) and (max-width: 640px) {
                /* Small mobile */
                .flux-header {
                    padding: 0.75rem !important;
                    gap: 0.5rem !important;
                }

                .flux-navbar {
                    gap: 0.25rem !important;
                }

                .flux-navbar-item {
                    padding: 0.5rem !important;
                    min-width: 2.5rem !important;
                }

                .flux-navbar-item svg {
                    width: 1.125rem !important;
                    height: 1.125rem !important;
                }

                #sidebar-toggle-btn {
                    padding: 0.5rem !important;
                    min-width: 2.5rem !important;
                }

                #sidebar-toggle-btn svg {
                    width: 1.125rem !important;
                    height: 1.125rem !important;
                }
            }

            @media (min-width: 641px) and (max-width: 768px) {
                /* Tablet */
                .flux-header {
                    padding: 1rem !important;
                    gap: 0.75rem !important;
                }

                .flux-navbar {
                    gap: 0.5rem !important;
                }

                .flux-navbar-item {
                    padding: 0.75rem !important;
                    min-width: 3rem !important;
                }

                .flux-navbar-item svg {
                    width: 1.25rem !important;
                    height: 1.25rem !important;
                }

                #sidebar-toggle-btn {
                    padding: 0.75rem !important;
                    min-width: 3rem !important;
                }

                #sidebar-toggle-btn svg {
                    width: 1.25rem !important;
                    height: 1.25rem !important;
                }
            }

            @media (min-width: 769px) {
                /* Desktop */
                .flux-header {
                    padding: 1rem !important;
                    gap: 1rem !important;
                }

                .flux-navbar {
                    gap: 0.75rem !important;
                }

                .flux-navbar-item {
                    padding: 0.75rem !important;
                    min-width: 3rem !important;
                }

                .flux-navbar-item svg {
                    width: 1.25rem !important;
                    height: 1.25rem !important;
                }

                #sidebar-toggle-btn {
                    padding: 0.75rem !important;
                    min-width: 3rem !important;
                }

                #sidebar-toggle-btn svg {
                    width: 1.25rem !important;
                    height: 1.25rem !important;
                }
            }

            /* Prevent overlapping and ensure proper spacing */
            .flux-header {
                display: flex !important;
                align-items: center !important;
                width: 100% !important;
                overflow: hidden !important;
            }

            .flux-header > * {
                flex-shrink: 0 !important;
                white-space: nowrap !important;
            }

            .flux-spacer {
                flex: 1 !important;
                min-width: 0 !important;
                overflow: hidden !important;
            }

            .flux-navbar {
                display: flex !important;
                align-items: center !important;
                flex-shrink: 0 !important;
            }

            .flux-navbar-item {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                flex-shrink: 0 !important;
            }

            /* Hide mobile sidebar on desktop - only show on mobile */
            @media (min-width: 769px) {
                /* Hide mobile sidebar on desktop */
                #mobile-sidebar {
                    display: none !important;
                }
            }

            /* Ensure sidebar responsive behavior */
            @media (max-width: 768px) {
                [data-flux-sidebar] {
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    height: 100vh !important;
                    width: 256px !important;
                    z-index: 50 !important;
                    transform: translateX(-100%) !important;
                    transition: transform 0.3s ease !important;
                    overflow-y: auto !important;
                }

                [data-flux-sidebar].sidebar-open {
                    transform: translateX(0) !important;
                }

                [data-flux-sidebar].sidebar-hidden {
                    transform: translateX(-100%) !important;
                }

                /* No scroll on mobile - fixed height */
                [data-flux-sidebar] .flux-navlist {
                    overflow-y: hidden !important;
                    max-height: calc(100vh - 150px) !important;
                }

                [data-flux-sidebar] .flux-navlist-item {
                    display: flex !important;
                    align-items: center !important;
                    white-space: nowrap !important;
                    min-height: 44px !important;
                }

                /* Add backdrop for mobile */
                .sidebar-backdrop {
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100vw !important;
                    height: 100vh !important;
                    background: rgba(0, 0, 0, 0.5) !important;
                    z-index: 40 !important;
                    display: none !important;
                }

                .sidebar-backdrop.show {
                    display: block !important;
                }
            }

            @media (min-width: 769px) {
                [data-flux-sidebar] {
                    position: relative !important;
                    transform: none !important;
                    transition: width 0.3s ease, padding 0.3s ease !important;
                    overflow-y: hidden !important;
                }
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let sidebarVisible = true;

                function updateToggleIcon() {
                    const openIcon = document.getElementById('sidebar-open-icon');
                    const closeIcon = document.getElementById('sidebar-close-icon');
                    
                    if (openIcon && closeIcon) {
                        if (sidebarVisible) {
                            openIcon.classList.add('hidden');
                            closeIcon.classList.remove('hidden');
                        } else {
                            openIcon.classList.remove('hidden');
                            closeIcon.classList.add('hidden');
                        }
                    }
                }
                
                // Initialize icon state
                updateToggleIcon();

                function toggleSidebar() {
                    const sidebar = document.querySelector('[data-flux-sidebar]');
                    const mainContent = document.querySelector('.flex.flex-col.flex-1');
                    const backdrop = document.querySelector('.sidebar-backdrop');

                    console.log('Toggle clicked! Sidebar visible:', sidebarVisible);
                    console.log('Window width:', window.innerWidth);

                    if (sidebar && mainContent) {
                        if (window.innerWidth <= 768) {
                            // Mobile behavior - slide in/out
                            if (sidebarVisible) {
                                sidebar.classList.remove('sidebar-open');
                                sidebar.classList.add('sidebar-hidden');
                                if (backdrop) backdrop.classList.remove('show');
                                sidebarVisible = false;
                                console.log('✅ Mobile Sidebar HIDDEN');
                            } else {
                                sidebar.classList.remove('sidebar-hidden');
                                sidebar.classList.add('sidebar-open');
                                if (backdrop) backdrop.classList.add('show');
                                sidebarVisible = true;
                                console.log('✅ Mobile Sidebar VISIBLE');
                            }
                        } else {
                            // Desktop behavior - collapse/expand
                            if (sidebarVisible) {
                                sidebar.style.cssText = 'display: none !important; width: 0 !important; padding: 0 !important; margin: 0 !important; border: 0 !important; overflow: hidden !important;';
                                mainContent.style.cssText = 'width: 100% !important; flex: 1 !important;';
                                sidebarVisible = false;
                                console.log('✅ Desktop Sidebar HIDDEN');
                            } else {
                                sidebar.style.cssText = 'display: flex !important; width: 16rem !important; padding: 1rem !important; margin: 0 !important; border-right: 1px solid rgb(161 161 170) !important; overflow-y: hidden !important;';
                                mainContent.style.cssText = 'width: auto !important; flex: 1 !important;';
                                sidebarVisible = true;
                                console.log('✅ Desktop Sidebar VISIBLE');
                            }
                        }
                        
                        // Update icon based on sidebar state
                        updateToggleIcon();
                    } else {
                        console.error('❌ Sidebar or main content not found!');
                    }
                }

                // Add click listener to our custom button
                const toggleBtn = document.getElementById('sidebar-toggle-btn');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('🎯 Custom toggle button clicked!');
                        toggleSidebar();
                    });
                } else {
                    console.error('❌ Toggle button not found!');
                }

                // Handle responsive behavior
                function handleResize() {
                    const sidebar = document.querySelector('[data-flux-sidebar]');
                    const mainContent = document.querySelector('.flex.flex-col.flex-1');
                    const backdrop = document.querySelector('.sidebar-backdrop');

                    if (window.innerWidth <= 768) {
                        // Mobile: Reset to hidden state
                        if (sidebar && mainContent) {
                            sidebar.classList.remove('sidebar-open');
                            sidebar.classList.add('sidebar-hidden');
                            if (backdrop) backdrop.classList.remove('show');
                            sidebarVisible = false;
                        }
                    } else {
                        // Desktop: Reset to visible state
                        if (sidebar && mainContent && sidebarVisible) {
                            sidebar.style.cssText = 'display: flex !important; width: 16rem !important; padding: 1rem !important; margin: 0 !important; border-right: 1px solid rgb(161 161 170) !important; overflow-y: hidden !important;';
                            mainContent.style.cssText = 'width: auto !important; flex: 1 !important;';
                        }
                    }
                    
                    // Update icon state
                    updateToggleIcon();
                }

                // Initial responsive check
                handleResize();

                // Listen for window resize
                window.addEventListener('resize', handleResize);

                // Add backdrop click handler for mobile
                const backdrop = document.querySelector('.sidebar-backdrop');
                if (backdrop) {
                    backdrop.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            toggleSidebar();
                        }
                    });
                }
            });
        </script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-900 dark:to-zinc-800 flex flex-col h-screen">
        <!-- Mobile Sidebar Backdrop -->
        <div class="sidebar-backdrop"></div>

        <div class="flex flex-1 overflow-hidden">
            <!-- Desktop Sidebar -->
            <x-layouts.app.sidebar />

            <div class="flex flex-col flex-1 w-full data-flux-sidebar-collapsed-desktop:w-full overflow-hidden">
                <div class="sticky top-0 z-10 flex-shrink-0">
                    <x-layouts.app.header />
                </div>

                <main class="flex-1 px-4 lg:px-6 py-6 overflow-y-auto">
                    {{ $slot }}
                </main>

                <footer class="py-4 text-center text-sm text-zinc-600 dark:text-zinc-400 w-full border-t border-zinc-300 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm flex-shrink-0">
                    <div class="container mx-auto px-4">
                        &copy; {{ date('Y') }} PT. Kilang Pertamina Internasional - Refinery Unit VI Balongan
                    </div>
                </footer>
            </div>
        </div>

        @fluxScripts
        @livewireScriptConfig
    </body>
</html>
