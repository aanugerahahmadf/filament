<x-layouts.app :title="__('Message')">
    <div class="flex flex-col h-full">
        <!-- Page header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Message') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Select a conversation to start messaging') }}</p>
        </div>

        <!-- Main messaging container -->
        <div class="flex flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <!-- Conversations sidebar -->
            <div class="w-full md:w-full lg:w-full xl:w-full flex flex-col bg-white dark:bg-gray-800">
                <!-- Search bar -->
                <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-vaadin-search class="w-5 h-5 text-gray-400" />
                        </div>
                        <input type="text" id="search-users" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ __('Search users...') }}">
                    </div>
                </div>

                <!-- Conversations list -->
                <div class="flex-1 overflow-y-auto">
                    @php
                        $users = \App\Models\User::where('id', '!=', auth()->id())->orderBy('name')->get();
                    @endphp

                    @forelse($users as $user)
                        <a href="{{ route('messages.conversation', $user->id) }}" class="block p-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="relative flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $user->initials() }}
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate dark:text-white">
                                            {{ $user->name }}
                                            @if($user->hasRole('Super Admin'))
                                                <span class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-100">
                                                    {{ __('Admin') }}
                                                </span>
                                            @endif
                                        </h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            @php
                                                $lastMessage = \App\Models\Message::where(function($query) use ($user) {
                                                    $query->where('from_user_id', auth()->id())
                                                          ->where('to_user_id', $user->id);
                                                })->orWhere(function($query) use ($user) {
                                                    $query->where('from_user_id', $user->id)
                                                          ->where('to_user_id', auth()->id());
                                                })->latest()->first();
                                            @endphp
                                            @if($lastMessage)
                                                {{ $lastMessage->created_at->format('M d') }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate dark:text-gray-400 mt-1">
                                        @if($lastMessage)
                                            {{ Str::limit($lastMessage->body, 30) }}
                                        @else
                                            {{ __('No messages yet') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center">
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No users found') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Messages List Responsive -->
    <style>
        @media (max-width: 768px) {
            .messages-list-container {
                height: calc(100vh - 8rem) !important;
            }
            .messages-list-header {
                margin-bottom: 1rem !important;
            }
            .messages-list-header h1 {
                font-size: 1.5rem !important;
            }
            .messages-list-header p {
                font-size: 0.875rem !important;
            }
            .messages-list-sidebar {
                width: 100% !important;
            }
            .messages-list-search {
                padding: 0.75rem !important;
            }
            .messages-list-search input {
                font-size: 0.875rem !important;
                padding: 0.5rem !important;
            }
            .messages-list-item {
                padding: 0.75rem !important;
            }
            .messages-list-item .w-12 {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }
            .messages-list-item h4 {
                font-size: 0.875rem !important;
            }
            .messages-list-item p {
                font-size: 0.75rem !important;
            }
            .messages-list-item .text-xs {
                font-size: 0.625rem !important;
            }
        }
        @media (max-width: 480px) {
            .messages-list-container {
                height: calc(100vh - 6rem) !important;
            }
            .messages-list-header {
                margin-bottom: 0.75rem !important;
            }
            .messages-list-header h1 {
                font-size: 1.25rem !important;
            }
            .messages-list-header p {
                font-size: 0.75rem !important;
            }
            .messages-list-search {
                padding: 0.5rem !important;
            }
            .messages-list-item {
                padding: 0.5rem !important;
            }
            .messages-list-item .w-12 {
                width: 2rem !important;
                height: 2rem !important;
            }
            .messages-list-item h4 {
                font-size: 0.75rem !important;
            }
            .messages-list-item p {
                font-size: 0.625rem !important;
            }
        }
    </style>
</x-layouts.app>
