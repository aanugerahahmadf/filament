<flux:header container class="border-b border-zinc-300 dark:border-zinc-600 bg-gradient-to-r from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 h-16 shadow-sm" style="z-index: 1000; position: relative;">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <flux:spacer />

    <flux:navbar class="me-1.5 space-x-1 rtl:space-x-reverse py-0!">
        <flux:tooltip :content="__('Search')" position="bottom">
            <flux:navbar.item class="!h-10 [&>div>svg]:size-5 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all duration-200 hover:shadow-sm" :href="route('search.index')" :label="__('Search')">
                <x-vaadin-search class="inline-block w-5 h-5" />
            </flux:navbar.item>
        </flux:tooltip>

        <flux:tooltip :content="__('Notifications')" position="bottom">
            <flux:navbar.item class="!h-10 [&>div>svg]:size-5 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all duration-200 hover:shadow-sm relative" :href="route('notifications')" :label="__('Notifications')">
                <x-bxs-bell class="inline-block w-5 h-5" />
                @php
                    $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </flux:navbar.item>
        </flux:tooltip>

        <flux:tooltip :content="__('Messages')" position="bottom">
            <flux:navbar.item class="!h-10 [&>div>svg]:size-5 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-all duration-200 hover:shadow-sm relative" :href="route('messages')" :label="__('Messages')">
                <x-bxs-message class="inline-block w-5 h-5" />
                @php
                    $unreadMessages = \App\Models\Message::where('to_user_id', auth()->id())
                        ->whereNull('read_at')
                        ->count();
                @endphp
                @if($unreadMessages > 0)
                    <span class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                        {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                    </span>
                @endif
            </flux:navbar.item>
        </flux:tooltip>
    </flux:navbar>

    <flux:navbar class="me-2">
        <flux:menu>
            <flux:menu.radio.group x-data x-model="$flux.appearance">
                <flux:menu.item as="button" x-on:click="$flux.appearance='light'" icon="sun">Light</flux:menu.item>
                <flux:menu.item as="button" x-on:click="$flux.appearance='dark'" icon="moon">Dark</flux:menu.item>
                <flux:menu.item as="button" x-on:click="$flux.appearance='system'" icon="computer-desktop">System</flux:menu.item>
            </flux:menu.radio.group>
        </flux:menu>
    </flux:navbar>

    <!-- Desktop User Menu -->
    <flux:dropdown position="top" align="end">
        <flux:profile
            class="cursor-pointer border-2 border-zinc-300 dark:border-zinc-600 rounded-lg p-1 transition-all duration-200 hover:border-zinc-400 dark:hover:border-zinc-400 hover:shadow-sm"
            :initials="auth()->user()->initials()"
        />

        <flux:menu>
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                            >
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                        <div class="grid flex-1 text-start leading-tight">
                            <span class="font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</span>
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
</flux:header>

<!-- Mobile Menu -->
<flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-300 dark:border-zinc-600 bg-gradient-to-b from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Platform')">
            <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="border border-zinc-300 dark:border-zinc-600 rounded-lg mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-sm">
                <x-bxs-dashboard class="inline-block w-5 h-5 me-2" /> {{ __('Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('maps')" :current="request()->routeIs('maps')" wire:navigate class="border border-zinc-300 dark:border-zinc-600 rounded-lg mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-sm">
                <x-bxs-map class="inline-block w-5 h-5 me-2" /> {{ __('Maps') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('locations')" :current="request()->routeIs('locations')" wire:navigate class="border border-zinc-300 dark:border-zinc-600 rounded-lg mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-sm">
                <x-bxs-map class="inline-block w-5 h-5 me-2" /> {{ __('Location') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate class="border border-zinc-300 dark:border-zinc-600 rounded-lg mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-sm">
                <x-bxs-envelope class="inline-block w-5 h-5 me-2" /> {{ __('Contact') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('notifications')" :current="request()->routeIs('notifications')" wire:navigate class="border border-zinc-300 dark:border-zinc-600 rounded-lg mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-sm relative">
                <x-bxs-bell class="inline-block w-5 h-5 me-2" /> {{ __('Notification') }}
                @php
                    $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </flux:navlist.item>
            <flux:navlist.item :href="route('messages')" :current="request()->routeIs('messages*')" wire:navigate class="border border-zinc-300 dark:border-zinc-600 rounded-lg mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-sm relative">
                <x-bxs-message class="inline-block w-5 h-5 me-2" /> {{ __('Message') }}
                @php
                    $unreadMessages = \App\Models\Message::where('to_user_id', auth()->id())
                        ->whereNull('read_at')
                        ->count();
                @endphp
                @if($unreadMessages > 0)
                    <span class="absolute top-1 right-2 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                        {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                    </span>
                @endif
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />

    <flux:navlist variant="outline">
        <flux:navlist.item :href="route('settings.profile')" wire:navigate class="border border-zinc-300 dark:border-zinc-600 rounded-lg mb-2 font-bold hover:border-zinc-400 dark:hover:border-zinc-400 transition-all duration-200 hover:shadow-sm">
            <x-bxs-cog class="inline-block w-5 h-5 me-2" /> {{ __('Settings') }}
        </flux:navlist.item>
    </flux:navlist>
</flux:sidebar>
