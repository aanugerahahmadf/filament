<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-12rem)]">
        <!-- User List -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 h-full flex flex-col">
                <h3 class="text-lg font-semibold mb-4">Conversations</h3>
                <div class="space-y-2 overflow-y-auto flex-1">
                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                        <div
                            x-data
                            x-on:click="$wire.selectUser({{ $user->id }})"
                            class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 {{ $selectedUser?->id === $user->id ? 'bg-gray-100 dark:bg-gray-700 border-l-4 border-blue-500' : '' }} transition-all duration-200"
                        >
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3 flex-shrink-0">
                                <span class="font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                                @if($user->hasRole('super_admin'))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                                        Super Admin
                                    </span>
                                @endif
                            </div>
                            <!-- Online status indicator -->
                            <div class="w-3 h-3 rounded-full bg-green-500 ml-2 flex-shrink-0"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm flex flex-col h-full">
                <!-- Chat Header -->
                @if($selectedUser)
                    <div class="border-b border-gray-200 dark:border-gray-700 p-4 flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3">
                            <span class="font-bold text-white">{{ substr($selectedUser->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $selectedUser->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Online - Active now</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </button>
                            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Messages Area -->
                <div
                    x-data="{
                        messagesContainer: null,
                        init() {
                            this.messagesContainer = this.$el;
                            this.scrollToBottom();
                            $wire.on('message-sent', () => {
                                setTimeout(() => this.scrollToBottom(), 100);
                            });
                            $wire.on('user-selected', () => {
                                setTimeout(() => this.scrollToBottom(), 100);
                            });
                        },
                        scrollToBottom() {
                            if (this.messagesContainer) {
                                this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
                            }
                        }
                    }"
                    class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900"
                >
                    @if($selectedUser)
                        <div class="space-y-4">
                            @forelse($messages as $message)
                                <div class="flex {{ $message['from_user_id'] === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    @if($message['from_user_id'] !== auth()->id())
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center mr-2 flex-shrink-0 mt-1">
                                            <span class="font-bold text-white text-xs">{{ substr($message['fromUser']['name'], 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="max-w-xs lg:max-w-md px-4 py-3 rounded-2xl {{ $message['from_user_id'] === auth()->id() ? 'bg-blue-500 text-white rounded-br-none' : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-bl-none shadow-sm' }} relative">
                                        @if($message['from_user_id'] !== auth()->id())
                                            <div class="font-semibold text-sm mb-1">{{ $message['fromUser']['name'] }}</div>
                                        @endif
                                        <p class="break-words">{{ $message['body'] }}</p>
                                        <div class="text-xs mt-1 flex justify-end {{ $message['from_user_id'] === auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }}">
                                            {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                                            @if($message['from_user_id'] === auth()->id())
                                                @if($message['read_at'])
                                                    <span class="ml-1">✓✓</span>
                                                @elseif($message['delivered_at'])
                                                    <span class="ml-1">✓</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No messages yet</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Start a conversation with {{ $selectedUser->name }}</p>
                                </div>
                            @endforelse
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 rounded-full flex items-center justify-center mx-auto mb-6 p-4">
                                    <svg xmlns="http://www.w.w3.org/2000/svg" class="h-12 w-12 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Welcome to Chat</h3>
                                <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">Select a user from the conversation list to start messaging. Your conversations will appear here.</p>
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 max-w-md mx-auto">
                                    <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Tips for using chat:</h4>
                                    <ul class="text-sm text-blue-700 dark:text-blue-300 text-left space-y-1">
                                        <li class="flex items-start">
                                            <span class="mr-2">•</span>
                                            <span>Select a user from the left panel to start chatting</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="mr-2">•</span>
                                            <span>Messages are delivered instantly and marked as read</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="mr-2">•</span>
                                            <span>All conversations are secure and private</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Message Input -->
                @if($selectedUser)
                    <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-800">
                        <form wire:submit="sendMessage" class="flex gap-2 items-end">
                            <div class="flex-1 relative">
                                <textarea
                                    wire:model.live="data.message"
                                    placeholder="Type a message..."
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white resize-none"
                                    rows="1"
                                    autocomplete="off"
                                    x-data
                                    x-on:keydown.enter.prevent="if (!$event.shiftKey) { $wire.sendMessage() }"
                                    x-on:input="if ($el.scrollHeight <= 120) { $el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; }"
                                ></textarea>
                                <div class="absolute right-3 bottom-3 flex space-x-1">
                                    <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </button>
                                    <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-medium transition-colors flex-shrink-0 {{ empty($data['message']) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ empty($data['message']) ? 'disabled' : '' }}
                                x-on:click.prevent="$wire.sendMessage()"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </form>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                            Press Enter to send, Shift+Enter for new line
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
