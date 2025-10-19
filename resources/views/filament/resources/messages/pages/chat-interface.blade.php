<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- User List -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                <h3 class="text-lg font-semibold mb-4">Conversations</h3>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                        <div
                            x-data
                            x-on:click="$wire.selectUser({{ $user->id }})"
                            class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 {{ $selectedUser?->id === $user->id ? 'bg-gray-100 dark:bg-gray-700' : '' }}"
                        >
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3">
                                <span class="font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                @if($user->hasRole('super_admin'))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                                        Super Admin
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm flex flex-col h-[calc(100vh-12rem)]">
                <!-- Chat Header -->
                @if($selectedUser)
                    <div class="border-b border-gray-200 dark:border-gray-700 p-4 flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3">
                            <span class="font-bold text-white">{{ substr($selectedUser->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $selectedUser->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Online</p>
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
                                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message['from_user_id'] === auth()->id() ? 'bg-blue-500 text-white rounded-br-none' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-bl-none' }}">
                                        <p>{{ $message['body'] }}</p>
                                        <div class="text-xs mt-1 {{ $message['from_user_id'] === auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }}">
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
                                    <p>No messages yet. Start a conversation!</p>
                                </div>
                            @endforelse
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg xmlns="http://www.w.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Select a conversation</h3>
                                <p class="text-gray-500 dark:text-gray-400">Choose a user from the list to start chatting</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Message Input -->
                @if($selectedUser)
                    <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                        <form wire:submit.prevent="sendMessage" class="flex gap-2">
                            <input
                                type="text"
                                wire:model="data.message"
                                placeholder="Type a message..."
                                class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                autocomplete="off"
                                x-on:keydown.enter.prevent="$wire.sendMessage()"
                            >
                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-4 py-2 font-medium transition-colors"
                                {{ empty($data['message']) ? 'disabled' : '' }}
                                x-on:click.prevent="$wire.sendMessage()"
                            >
                                Send
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
