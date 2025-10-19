<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-900 dark:to-zinc-800 flex flex-col">
        <div class="flex flex-1">
            <!-- Desktop Sidebar -->
            <x-layouts.app.sidebar />

            <div class="flex flex-col flex-1 w-full">
                <div class="sticky top-0 z-10">
                    <x-layouts.app.header />
                </div>

                <main class="flex-1 px-4 lg:px-6 py-6 overflow-y-auto">
                    {{ $slot }}
                </main>

                <footer class="py-4 text-center text-sm text-zinc-600 dark:text-zinc-400 w-full border-t border-zinc-300 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm">
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
