<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-neutral-100 antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900 flex flex-col">
        <div class="flex flex-col min-h-screen">
            <!-- Main Content -->
            <main class="flex-1 flex items-center justify-center p-6 md:p-10">
                <div class="w-full max-w-md">
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium mb-6" wire:navigate>
                        <span class="sr-only">PT. Kilang Pertamina - Refinery Unit VI Balongan</span>
                    </a>

                    <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs relative z-10">
                        <div class="p-10">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-3 text-center text-xs text-zinc-500 dark:text-white/60 w-full border-t border-zinc-200 dark:border-zinc-700">
                © {{ date('Y') }} PT Kilang Pertamina Internasional - Refinery Unit VI Balongan
            </footer>
        </div>

        @fluxScripts
    </body>
</html>
