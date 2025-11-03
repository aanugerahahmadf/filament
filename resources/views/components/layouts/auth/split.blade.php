<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900 flex flex-col">
        <div class="flex flex-col min-h-screen">
            <!-- Main Content -->
            <main class="flex-1">
                <div class="relative grid h-dvh items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
                    <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
                        <div class="absolute inset-0 bg-neutral-900"></div>
                        <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                            PT. Kilang Pertamina - Refinery Unit VI Balongan
                        </a>

                        @php
                            [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                        @endphp

                        <div class="relative z-20 mt-auto">
                            <blockquote class="space-y-2">
                                <flux:heading size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                                <footer><flux:heading>{{ trim($author) }}</flux:heading></footer>
                            </blockquote>
                        </div>
                    </div>
                    <div class="w-full lg:p-8">
                        <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                            <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                                <span class="sr-only">PT. Kilang Pertamina - Refinery Unit VI Balongan</span>
                            </a>
                            <div class="relative z-10">
                                {{ $slot }}
                            </div>
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
