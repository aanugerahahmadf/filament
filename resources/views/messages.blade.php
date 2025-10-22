<x-layouts.app :title="__('Messages')">
    <div class="page-wrapper">
        <div class="flex flex-col h-full page-content-area">
            <!-- Page header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Messages') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Chat with other users') }}</p>
            </div>

            <!-- Main messaging container -->
            <div class="flex flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                <!-- Conversations sidebar -->
                <div class="hidden md:flex md:w-80 lg:w-96 flex-col border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <!-- Search bar -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <x-bxs-search class="w-5 h-5 text-gray-400" />
                            </div>
                            <input type="text" id="search-users" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ __('Search users...') }}">
                        </div>
                    </div>

                    <!-- Mobile Messages Responsive -->
                    <style>
                        @media (max-width: 768px) {
                            .messages-container {
                                flex-direction: column !important;
                                height: calc(100vh - 8rem) !important;
                            }
                            .messages-sidebar {
                                display: flex !important;
                                width: 100% !important;
                                height: 40% !important;
                                border-right: none !important;
                                border-bottom: 1px solid #e5e7eb !important;
                            }
                            .messages-chat {
                                height: 60% !important;
                            }
                            .messages-search {
                                padding: 0.75rem !important;
                            }
                            .messages-search input {
                                font-size: 0.875rem !important;
                                padding: 0.5rem !important;
                            }
                            .messages-conversation {
                                padding: 0.75rem !important;
                            }
                            .messages-conversation .w-12 {
                                width: 2.5rem !important;
                                height: 2.5rem !important;
                            }
                            .messages-conversation h4 {
                                font-size: 0.875rem !important;
                            }
                            .messages-conversation p {
                                font-size: 0.75rem !important;
                            }
                        }
                        @media (max-width: 480px) {
                            .messages-sidebar {
                                height: 35% !important;
                            }
                            .messages-chat {
                                height: 65% !important;
                            }
                            .messages-search {
                                padding: 0.5rem !important;
                            }
                            .messages-conversation {
                                padding: 0.5rem !important;
                            }
                            .messages-conversation .w-12 {
                                width: 2rem !important;
                                height: 2rem !important;
                            }
                        }
                    </style>

                    <!-- Conversations list -->
                    <div class="flex-1 overflow-y-auto">
                        <!-- Sample conversation items -->
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        JD
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate dark:text-white">John Doe</h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">10:30 AM</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">Hello there! How are you doing today?</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200 bg-gray-50 dark:bg-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold">
                                        SA
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate dark:text-white">Super Admin <span class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-100">Admin</span></h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Yesterday</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">Please review the new dashboard design</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-bold">
                                        MJ
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-300 border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate dark:text-white">Mary Johnson</h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Oct 10</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">The meeting has been rescheduled to next week</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat area -->
                <div class="flex flex-col flex-1">
                    <!-- Chat header -->
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="relative flex-shrink-0 md:hidden">
                                    <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <x-bxs-chevron-left class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                    </button>
                                </div>
                                <div class="relative flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        JD
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">John Doe</h4>
                                    <p class="text-xs text-green-600 dark:text-green-400">Online</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <x-bxs-phone class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                                <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <x-bxs-video class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                                <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <x-bxs-info-circle class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages container -->
                    <div class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                        <!-- Date separator -->
                        <div class="flex items-center justify-center my-4">
                            <div class="text-xs px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full">
                                Today
                            </div>
                        </div>

                        <!-- Received message -->
                        <div class="flex mb-4">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    JD
                                </div>
                            </div>
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-white dark:bg-gray-700 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">Hello! How are you doing today?</p>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">10:30 AM</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sent message -->
                        <div class="flex justify-end mb-4">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                                    <p class="text-sm text-white">I'm doing great! Just finished the project we discussed.</p>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-blue-100">10:32 AM</span>
                                        <span class="ml-1 text-xs text-blue-100">✓✓</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Received message with attachment -->
                        <div class="flex mb-4">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    JD
                                </div>
                            </div>
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-white dark:bg-gray-700 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">That's awesome! Can you share the document with me?</p>
                                    <div class="mt-2 p-2 bg-gray-100 dark:bg-gray-600 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <x-bxs-file class="w-5 h-5 text-blue-600 dark:text-blue-300" />
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">project-document.pdf</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">2.4 MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">10:35 AM</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sent message -->
                        <div class="flex justify-end mb-4">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                                    <p class="text-sm text-white">Sure! I've attached the document for your review.</p>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-blue-100">10:36 AM</span>
                                        <span class="ml-1 text-xs text-blue-100">✓✓</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Typing indicator -->
                        <div class="flex mb-4">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    JD
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message input area -->
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <div class="flex items-center">
                            <div class="flex space-x-1 mr-2">
                                <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <x-bxs-plus-circle class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                                <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <x-bxs-image class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                            </div>
                            <div class="flex-1">
                                <input type="text" class="block w-full px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ __('Type a message...') }}">
                            </div>
                            <div class="ml-2">
                                <button type="button" class="p-2 rounded-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white transition-all duration-200 shadow-md">
                                    <x-bxs-send class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
