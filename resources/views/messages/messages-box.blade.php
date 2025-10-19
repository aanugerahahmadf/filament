<x-layouts.app :title="__('Chat')">
    @php
        $recipient = \App\Models\User::findOrFail($userId);
    @endphp

    <div class="flex flex-col h-full">
        <!-- Page header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Chat') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Messaging with') }} {{ $recipient->name }}</p>
        </div>

        <!-- Main chat container -->
        <div class="flex flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <!-- Chat area -->
            <div class="flex flex-col flex-1">
                <!-- Chat header -->
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-shrink-0 md:hidden">
                                <a href="{{ route('messages') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <x-bxs-chevron-left class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </a>
                            </div>
                            <div class="relative flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                    {{ $recipient->initials() }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $recipient->name }}</h4>
                                <p class="text-xs text-green-600 dark:text-green-400">{{ __('Online') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200" onclick="showCallModal('audio')">
                                <x-bxs-phone class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                            </button>
                            <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200" onclick="showCallModal('video')">
                                <x-bxs-video class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                            </button>
                            <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200" onclick="showUserInfo()">
                                <x-bxs-info-circle class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages container -->
                <div class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800" id="messages-container">
                    <!-- Date separator -->
                    <div class="flex items-center justify-center my-4">
                        <div class="text-xs px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full">
                            {{ __('Today') }}
                        </div>
                    </div>

                    @foreach($messages as $message)
                        @if($message->from_user_id === auth()->id())
                            <!-- Sent message -->
                            <div class="flex justify-end mb-4" data-message-id="{{ $message->id }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                                        <p class="text-sm text-white">{{ $message->body }}</p>
                                        <div class="flex justify-end mt-1">
                                            <span class="text-xs text-blue-100">{{ $message->created_at->format('g:i A') }}</span>
                                            @if($message->isRead())
                                                <span class="ml-1 text-xs text-blue-100 message-status read">✓✓</span>
                                            @elseif($message->isDelivered())
                                                <span class="ml-1 text-xs text-blue-100 message-status delivered">✓</span>
                                            @else
                                                <span class="ml-1 text-xs text-blue-100 message-status sent">✓</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Received message -->
                            <div class="flex mb-4">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ $recipient->initials() }}
                                    </div>
                                </div>
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="bg-white dark:bg-gray-700 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                                        <p class="text-sm text-gray-800 dark:text-gray-200">{{ $message->body }}</p>
                                        <div class="flex justify-end mt-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Typing indicator -->
                <div class="px-4 py-2 hidden" id="typing-indicator">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ $recipient->initials() }}
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
                    <form id="message-form" action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="to_user_id" value="{{ $recipient->id }}">
                        <div class="flex items-center">
                            <div class="flex space-x-1 mr-2">
                                <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200" onclick="showAttachmentOptions()">
                                    <x-bxs-plus-circle class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                                <button type="button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200" onclick="showImagePicker()">
                                    <x-bxs-image class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                            </div>
                            <div class="flex-1">
                                <input type="text"
                                       name="message"
                                       id="message-input"
                                       class="block w-full px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="{{ __('Type a message...') }}"
                                       autocomplete="off">
                            </div>
                            <div class="ml-2">
                                <button type="submit" class="p-2 rounded-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white transition-all duration-200 shadow-md">
                                    <x-bxs-send class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Modal -->
    <div id="call-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <x-bxs-phone class="h-6 w-6 text-blue-600" id="call-modal-icon" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="call-modal-title">
                                {{ __('Start Call') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                    {{ __('Would you like to start a call with') }} <span id="call-recipient-name">{{ $recipient->name }}</span>?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" onclick="callType === 'video' ? initiateVideoCall() : initiateAudioCall()">
                        {{ __('Call') }}
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="hideCallModal()">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Info Modal -->
    <div id="user-info-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 sm:mx-0 sm:h-12 sm:w-12">
                            <span class="text-white font-bold text-xl">{{ $recipient->initials() }}</span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                {{ $recipient->name }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                    <strong>{{ __('Email:') }}</strong> {{ $recipient->email }}
                                </p>
                                @if($recipient->phone_number)
                                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                                        <strong>{{ __('Phone:') }}</strong> {{ $recipient->phone_number }}
                                    </p>
                                @endif
                                @if($recipient->hasRole('Super Admin'))
                                    <div class="mt-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-100">
                                            {{ __('Administrator') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="hideUserInfoModal()">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Call modal functionality
        let callType = 'audio';

        function showCallModal(type) {
            callType = type;
            const modal = document.getElementById('call-modal');
            const title = document.getElementById('call-modal-title');
            const icon = document.getElementById('call-modal-icon');

            if (type === 'video') {
                title.textContent = '{{ __('Start Video Call') }}';
                icon.className = 'h-6 w-6 text-blue-600 bx bxs-video';
            } else {
                title.textContent = '{{ __('Start Audio Call') }}';
                icon.className = 'h-6 w-6 text-blue-600 bx bxs-phone';
            }

            modal.classList.remove('hidden');
        }

        function hideCallModal() {
            document.getElementById('call-modal').classList.add('hidden');
        }

        function startCall() {
            // In a real application, this would initiate a call
            const recipientName = document.getElementById('call-recipient-name').textContent;

            if (callType === 'video') {
                // Simulate video call initiation
                alert('{{ __('Starting video call with') }} ' + recipientName + '... {{ __('(This is a demo - in a real app, this would connect to a video calling service)') }}');
            } else {
                // Simulate audio call initiation
                alert('{{ __('Calling') }} ' + recipientName + '... {{ __('(This is a demo - in a real app, this would connect to a calling service)') }}');
            }

            hideCallModal();
        }

        // Enhanced call functionality
        function initiateAudioCall() {
            const recipientName = document.getElementById('call-recipient-name').textContent;
            // Simulate initiating an audio call
            console.log('Initiating audio call with ' + recipientName);

            // Show a notification that the call is being initiated
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in-out';
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="bx bxs-phone mr-2 animate-pulse"></span>
                    <span>{{ __('Calling') }} ${recipientName}...</span>
                </div>
            `;
            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.add('opacity-0');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }
            }, 3000);

            hideCallModal();
        }

        function initiateVideoCall() {
            const recipientName = document.getElementById('call-recipient-name').textContent;
            // Simulate initiating a video call
            console.log('Initiating video call with ' + recipientName);

            // Show a notification that the call is being initiated
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-purple-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in-out';
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="bx bxs-video mr-2 animate-pulse"></span>
                    <span>{{ __('Starting video call with') }} ${recipientName}...</span>
                </div>
            `;
            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.add('opacity-0');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }
            }, 3000);

            hideCallModal();
        }

        // User info modal functionality
        function showUserInfo() {
            document.getElementById('user-info-modal').classList.remove('hidden');
        }

        function hideUserInfoModal() {
            document.getElementById('user-info-modal').classList.add('hidden');
        }

        // Attachment and image functionality
        function showAttachmentOptions() {
            alert('{{ __('Attachment options would appear here') }} {{ __('(This is a demo - in a real app, this would show file attachment options)') }}');
        }

        function showImagePicker() {
            alert('{{ __('Image picker would appear here') }} {{ __('(This is a demo - in a real app, this would open the image gallery)') }}');
        }

        // Close modals when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            // Close call modal when clicking outside
            document.getElementById('call-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideCallModal();
                }
            });

            // Close user info modal when clicking outside
            document.getElementById('user-info-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideUserInfoModal();
                }
            });

            // Existing message functionality
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const messagesContainer = document.getElementById('messages-container');
            const typingIndicator = document.getElementById('typing-indicator');

            // Auto-scroll to bottom of messages container
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Handle form submission
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(messageForm);
                const message = messageInput.value.trim();

                if (!message) return;

                // Send message via AJAX
                fetch(messageForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear input
                        messageInput.value = '';
                        messageInput.focus();

                        // In a real app, you would add the message to the UI here
                        // For now, we'll just reload to show the new message
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });

            // Typing indicator (simulated)
            let typingTimer;
            const typingDelay = 1000;

            messageInput.addEventListener('input', function() {
                // Show typing indicator (in a real app, you would send a typing event)
                clearTimeout(typingTimer);

                typingTimer = setTimeout(function() {
                    // Hide typing indicator after delay
                }, typingDelay);
            });

            // Listen for real-time events (if using Laravel Echo)
            if (typeof Echo !== 'undefined') {
                // Listen for new messages
                Echo.private(`user.${{{ auth()->id() }}}`)
                    .listen('MessageSent', (e) => {
                        // Add new message to UI
                        const newMessage = document.createElement('div');
                        newMessage.className = 'flex mb-4';
                        newMessage.innerHTML = `
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    ${e.from_user.name.substring(0, 2).toUpperCase()}
                                </div>
                            </div>
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-white dark:bg-gray-700 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">${e.body}</p>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        messagesContainer.appendChild(newMessage);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    })
                    .listen('MessageRead', (e) => {
                        // Update message status
                        const messageElement = document.querySelector(`[data-message-id="${e.message_id}"] .message-status`);
                        if (messageElement) {
                            messageElement.textContent = '✓✓';
                            messageElement.className = 'ml-1 text-xs text-blue-100 message-status read';
                        }
                    })
                    .listen('UserTyping', (e) => {
                        // Show/hide typing indicator
                        if (e.is_typing) {
                            typingIndicator.classList.remove('hidden');
                        } else {
                            typingIndicator.classList.add('hidden');
                        }
                    });
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @keyframes fade-in-out {
            0% { opacity: 0; transform: translateY(-10px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }

        .animate-fade-in-out {
            animation: fade-in-out 3s ease-in-out forwards;
        }
    </style>
    @endpush
</x-layouts.app>
