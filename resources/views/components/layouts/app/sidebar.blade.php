<flux:sidebar sticky stashable class="border-e border-zinc-300 dark:border-zinc-600 bg-gradient-to-b from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 lg:w-64 lg:h-screen overflow-y-auto shadow-lg">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <div class="flex justify-center py-4">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center" wire:navigate>
            <x-app-logo style="width: 140px; height: 100px;" />
        </a>
    </div>

    <flux:navlist variant="outline" class="flex-1 px-2">
        <flux:navlist.group :heading="__('Platform')" class="grid">
            <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="border border-zinc-300 dark:border-zinc-500 rounded-xl mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-md">
                <x-bxs-dashboard class="inline-block w-5 h-5 shrink-0 me-2" /> {{ __('Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('maps')" :current="request()->routeIs('maps')" wire:navigate class="border border-zinc-300 dark:border-zinc-500 rounded-xl mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-md">
                <x-bxs-map class="inline-block w-5 h-5 shrink-0 me-2" /> {{ __('Maps') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('locations')" :current="request()->routeIs('locations')" wire:navigate class="border border-zinc-300 dark:border-zinc-500 rounded-xl mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-md">
                <x-bxs-map class="inline-block w-5 h-5 shrink-0 me-2" /> {{ __('Location') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate class="border border-zinc-300 dark:border-zinc-500 rounded-xl mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-md">
                <x-bxs-envelope class="inline-block w-5 h-5 shrink-0 me-2" /> {{ __('Contact') }}
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />

    <!-- Desktop User Menu -->
    @auth
        <flux:dropdown class="mb-4 mx-4" position="top" align="start">
            <flux:profile
                :name="auth()->user()->name ?? 'Guest'"
                :initials="auth()->user()?->initials() ?? 'GU'"
                :src="auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null"
                icon:trailing="chevrons-up-down"
                class="border-2 border-zinc-300 dark:border-zinc-600 rounded-xl p-2 hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-md"
                data-test="sidebar-menu-button"
            />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-3 px-2 py-2 text-start text-sm">
                            <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                                @if (auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                @else
                                    <span class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-bold">
                                        {{ auth()->user()?->initials() ?? 'GU' }}
                                    </span>
                                @endif
                            </span>

                            <div class="grid flex-1 text-start leading-tight">
                                <span class="font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->name ?? 'Guest' }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" wire:navigate>
                        <x-bxs-cog class="inline-block w-5 h-5 me-2" /> {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" class="w-full" data-test="logout-button">
                        <x-bxs-log-out class="inline-block w-5 h-5 me-2" /> {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    @endauth
</flux:sidebar>
