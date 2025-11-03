<x-layouts.app :title="__('Message')">
    @php
        $recipient = \App\Models\User::findOrFail($userId);
    @endphp

    <div class="flex flex-col h-full">
        <!-- Page header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Message') }}</h1>
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

                    @forelse($messages as $message)
                        @if($message->from_user_id === auth()->id())
                            <!-- Sent message -->
                            <div class="flex justify-end mb-4" data-message-id="{{ $message->id }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                                        <p class="text-sm text-white">{{ $message->body ?? $message->message }}</p>
                                        <div class="flex justify-end mt-1">
                                            <span class="text-xs text-blue-100">{{ $message->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
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
                                        <p class="text-sm text-gray-800 dark:text-gray-200">{{ $message->body ?? $message->message }}</p>
                                        <div class="flex justify-end mt-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex justify-center items-center h-full">
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No messages yet. Start a conversation!') }}</p>
                        </div>
                    @endforelse
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
                    <form id="message-form" action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="to_user_id" value="{{ $recipient->id }}">
                        <div class="flex items-center">
                            <div class="flex space-x-1 mr-2">
                                <label for="doc-upload" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer" title="Upload File/Document" role="button" tabindex="0">
                                    <x-bxs-plus-circle class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </label>
                                <button type="button" id="open-live-camera" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200" title="Open Live Camera">
                                    <x-bxs-camera class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </button>
                                <label for="image-upload" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer" title="Upload Image" role="button" tabindex="0">
                                    <x-bxs-image class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                                </label>
                                <input type="file" id="image-upload" accept="image/*" class="sr-only" onchange="handleImageSelection(event)">
                                <input type="file" id="camera-upload" accept="image/*" capture="environment" class="sr-only" onchange="handleImageSelection(event)">
                                <input type="file" id="camera-upload-front" accept="image/*" capture="user" class="sr-only" onchange="handleImageSelection(event)">
                                <input type="file" id="doc-upload" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.csv,.json" class="sr-only" onchange="handleDocumentSelection(event)">
                            </div>
                            <div class="flex-1">
                                <input type="text"
                                       name="body"
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

    <!-- Mobile Messages Box Responsive -->
    <style>
        @media (max-width: 768px) {
            .messages-box-container {
                height: calc(100vh - 8rem) !important;
            }
            .messages-box-header {
                margin-bottom: 1rem !important;
            }
            .messages-box-header h1 {
                font-size: 1.5rem !important;
            }
            .messages-box-header p {
                font-size: 0.875rem !important;
            }
            .messages-box-chat-header {
                padding: 0.75rem !important;
            }
            .messages-box-chat-header .w-10 {
                width: 2rem !important;
                height: 2rem !important;
            }
            .messages-box-chat-header h4 {
                font-size: 0.875rem !important;
            }
            .messages-box-chat-header p {
                font-size: 0.625rem !important;
            }
            .messages-box-chat-header button {
                padding: 0.5rem !important;
            }
            .messages-box-chat-header button svg {
                width: 1rem !important;
                height: 1rem !important;
            }
            .messages-box-messages {
                padding: 0.75rem !important;
            }
            .messages-box-message {
                margin-bottom: 0.75rem !important;
            }
            .messages-box-message .max-w-xs {
                max-width: 16rem !important;
            }
            .messages-box-message .w-8 {
                width: 1.5rem !important;
                height: 1.5rem !important;
            }
            .messages-box-message .text-sm {
                font-size: 0.75rem !important;
            }
            .messages-box-message .text-xs {
                font-size: 0.625rem !important;
            }
            .messages-box-input {
                padding: 0.75rem !important;
            }
            .messages-box-input button {
                padding: 0.5rem !important;
            }
            .messages-box-input button svg {
                width: 1rem !important;
                height: 1rem !important;
            }
            .messages-box-input input {
                padding: 0.5rem !important;
                font-size: 0.875rem !important;
            }
        }
        @media (max-width: 480px) {
            .messages-box-container {
                height: calc(100vh - 6rem) !important;
            }
            .messages-box-header {
                margin-bottom: 0.75rem !important;
            }
            .messages-box-header h1 {
                font-size: 1.25rem !important;
            }
            .messages-box-header p {
                font-size: 0.75rem !important;
            }
            .messages-box-chat-header {
                padding: 0.5rem !important;
            }
            .messages-box-chat-header .w-10 {
                width: 1.75rem !important;
                height: 1.75rem !important;
            }
            .messages-box-chat-header h4 {
                font-size: 0.75rem !important;
            }
            .messages-box-chat-header p {
                font-size: 0.625rem !important;
            }
            .messages-box-chat-header button {
                padding: 0.375rem !important;
            }
            .messages-box-chat-header button svg {
                width: 0.875rem !important;
                height: 0.875rem !important;
            }
            .messages-box-messages {
                padding: 0.5rem !important;
            }
            .messages-box-message {
                margin-bottom: 0.5rem !important;
            }
            .messages-box-message .max-w-xs {
                max-width: 12rem !important;
            }
            .messages-box-message .w-8 {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }
            .messages-box-message .text-sm {
                font-size: 0.625rem !important;
            }
            .messages-box-message .text-xs {
                font-size: 0.5rem !important;
            }
            .messages-box-input {
                padding: 0.5rem !important;
            }
            .messages-box-input button {
                padding: 0.375rem !important;
            }
            .messages-box-input button svg {
                width: 0.875rem !important;
                height: 0.875rem !important;
            }
            .messages-box-input input {
                padding: 0.375rem !important;
                font-size: 0.75rem !important;
            }
        }
    </style>

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
        // Removed as we're now initiating calls directly

        // Enhanced call functionality - Direct call initiation
        function initiateAudioCall() {
            const recipientName = "{{ $recipient->name }}";

            // Show immediate visual feedback
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in-out';
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="bx bxs-phone mr-2 animate-pulse"></span>
                    <span>{{ __('Calling') }} ${recipientName}...</span>
                </div>
            `;
            document.body.appendChild(notification);

            // In a real application, this would connect to a WebRTC or similar service
            // For demo purposes, we'll simulate a call connection
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <span class="bx bxs-phone mr-2"></span>
                            <span>{{ __('Connected with') }} ${recipientName}</span>
                        </div>
                    `;

                    // Change background to indicate active call
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in-out';

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
                }
            }, 2000);
        }

        function initiateVideoCall() {
            const recipientName = "{{ $recipient->name }}";

            // Show immediate visual feedback
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-purple-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in-out';
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="bx bxs-video mr-2 animate-pulse"></span>
                    <span>{{ __('Starting video call with') }} ${recipientName}...</span>
                </div>
            `;
            document.body.appendChild(notification);

            // In a real application, this would connect to a WebRTC or similar service
            // For demo purposes, we'll simulate a video call connection
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <span class="bx bxs-video mr-2"></span>
                            <span>{{ __('Video call connected with') }} ${recipientName}</span>
                        </div>
                    `;

                    // Change background to indicate active video call
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in-out';

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
                }
            }, 2000);
        }

        function startCall() {
            // This function is kept for backward compatibility
            // In a real application, this would initiate a call
            const recipientName = "{{ $recipient->name }}";

            if (callType === 'video') {
                initiateVideoCall();
            } else {
                initiateAudioCall();
            }
        }

        // User info modal functionality
        function showUserInfo() {
            document.getElementById('user-info-modal').classList.remove('hidden');
        }

        function hideUserInfoModal() {
            document.getElementById('user-info-modal').classList.add('hidden');
        }

        // Attachment and image functionality
        function showAttachmentOptions() {}

        // Image picker functionality
        function triggerImageUpload() {
            const input = document.getElementById('image-upload');
            if (input) input.click();
        }

        function triggerCameraCapture() {
            const input = document.getElementById('camera-upload');
            if (input) input.click();
        }

        function handleImageSelection(event) {
            const file = event.target.files[0];
            console.log('Image selected from ' + event.target.id + ':', file);

            if (!file) return;

            // Check if file is an image
            if (!file.type.startsWith('image/')) {
                alert('{{ __('Please select an image file') }}');
                // Clear the input
                event.target.value = '';
                return;
            }

            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('{{ __('Image size should be less than 5MB') }}');
                // Clear the input
                event.target.value = '';
                return;
            }

            // Immediately send without waiting for preview to avoid UX blocks
            sendSelectedImage(file);
        }

        function handleDocumentSelection(event) {
            const file = event.target.files[0];
            console.log('Document selected from ' + event.target.id + ':', file);

            if (!file) return;

            // Optional basic size check (20MB)
            if (file.size > 20 * 1024 * 1024) {
                alert('{{ __('File size should be less than 20MB') }}');
                event.target.value = '';
                return;
            }

            // Send immediately
            sendDocument(file);
        }

        function sendDocument(file) {
            console.log('Sending document:', file);

            // Create a clean FormData object
            const formData = new FormData();
            formData.append('to_user_id', document.querySelector('input[name="to_user_id"]').value);

            const messageText = document.getElementById('message-input').value.trim();
            if (messageText) formData.set('body', messageText);

            formData.append('file', file);
            // Also set the message type
            formData.set('message_type', 'file');

            const submitBtn = document.querySelector('#message-form button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="bx bx-loader-alt animate-spin w-5 h-5"></span>';
            }

            fetch(document.getElementById('message-form').action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                console.log('Document upload response status:', res.status);
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(data => {
                console.log('Document upload response data:', data);
                if (data.success) {
                    const container = document.getElementById('messages-container');
                    console.log('Messages container:', container);
                    const div = document.createElement('div');
                    div.className = 'flex justify-end mb-4';
                    const url = data.message.attachment_url || (data.message.attachment_path ? ('/storage/' + data.message.attachment_path.replace('public/', '')) : '#');
                    const name = data.message.attachment_name || file.name;
                    const captionHtml = messageText ? `<div class=\"mt-2 text-white text-sm\">${messageText}</div>` : '';
                    div.innerHTML = `
                        <div class=\"max-w-xs lg:max-w-md\">
                            <div class=\"bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none p-3 shadow-sm\">
                                <a href=\"${url}\" target=\"_blank\" class=\"flex items-center gap-2 text-white underline\">
                                    <i class='bx bxs-file-blank'></i>
                                    <span>${name}</span>
                                </a>
                                ${captionHtml}
                                <div class=\"flex justify-end mt-1\">
                                    <span class=\"text-xs text-blue-100\">${new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', timeZone: 'Asia/Jakarta'})}</span>
                                    <span class=\"ml-1 text-xs text-blue-100 message-status sent\">✓</span>
                                </div>
                            </div>
                        </div>`;
                    console.log('Appending document message to container');
                    container.appendChild(div);
                    console.log('Scrolling to bottom');
                    container.scrollTop = container.scrollHeight;
                    document.getElementById('message-input').value = '';
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(', ') : '{{ __('Failed to send file.') }}';
                    alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error sending document:', error);
                alert('{{ __('Failed to send file. Please try again.') }}');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<x-bxs-send class="w-5 h-5" />';
                }
                const docInput = document.getElementById('doc-upload');
                if (docInput) docInput.value = '';
            });
        }

        function sendSelectedImage(file) {
            console.log('Sending selected image:', file);

            // Create a clean FormData object
            const formData = new FormData();
            formData.append('to_user_id', document.querySelector('input[name="to_user_id"]').value);

            const messageText = document.getElementById('message-input').value.trim();
            if (messageText) formData.set('body', messageText);

            formData.append('image', file);
            // Also set the message type
            formData.set('message_type', 'image');

            const submitBtn = document.querySelector('#message-form button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="bx bx-loader-alt animate-spin w-5 h-5"></span>';
            }

            fetch(document.getElementById('message-form').action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                console.log('Image upload response status:', res.status);
                if (!res.ok) {
                    const text = await res.text();
                    throw new Error(text || `HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(data => {
                console.log('Image upload response data:', data);
                if (data.success) {
                    // append image bubble
                    const container = document.getElementById('messages-container');
                    console.log('Messages container:', container);
                    const div = document.createElement('div');
                    div.className = 'flex justify-end mb-4';
                    const imgUrl = data.message.attachment_url || (data.message.attachment_path ? ('/storage/' + data.message.attachment_path.replace('public/', '')) : '');
                    const captionHtml = messageText ? `<div class="mt-2 text-white text-sm">${messageText}</div>` : '';
                    div.innerHTML = `
                        <div class="max-w-xs lg:max-w-md">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none p-2 shadow-sm">
                                <img src="${imgUrl}" alt="sent image" class="rounded-lg max-h-60 object-contain" />
                                ${captionHtml}
                                <div class="flex justify-end mt-1">
                                    <span class="text-xs text-blue-100">${new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', timeZone: 'Asia/Jakarta'})}</span>
                                    <span class="ml-1 text-xs text-blue-100 message-status sent">✓</span>
                                </div>
                            </div>
                        </div>`;
                    console.log('Appending image message to container');
                    container.appendChild(div);
                    console.log('Scrolling to bottom');
                    container.scrollTop = container.scrollHeight;
                    document.getElementById('message-input').value = '';
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(', ') : '{{ __('Failed to send image.') }}';
                    alert(errorMsg);
                }
            })
            .catch((e) => {
                console.error('Error sending image:', e);
                alert('{{ __('Failed to send image. Please try again.') }}');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<x-bxs-send class="w-5 h-5" />';
                }
                // reset inputs
                document.getElementById('image-upload').value = '';
                document.getElementById('camera-upload').value = '';
                document.getElementById('camera-upload-front').value = '';
            });
        }

        // Backward-compatible function used by preview modal (if ever triggered)
        function sendImage() {
            const file = document.getElementById('image-upload').files[0] || document.getElementById('camera-upload').files[0] || document.getElementById('camera-upload-front').files[0];
            if (!file) { closeImagePreview(); return; }
            sendSelectedImage(file);
            closeImagePreview();
        }

        // Live camera modal
        let mediaStream = null;
        let mediaRecorder = null;
        let recordedChunks = [];
        let currentFacingMode = 'environment';

        function openLiveCameraModal(facingMode = 'environment') {
            currentFacingMode = facingMode;

            // Create modal for live camera
            const modal = document.createElement('div');
            modal.id = 'live-camera-modal';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Live Camera</h3>
                        <button id="close-live-camera" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <x-bxs-x-circle class="w-6 h-6" />
                        </button>
                    </div>
                    <div class="p-4">
                        <video id="live-video" autoplay playsinline class="w-full rounded-lg bg-black max-h-[60vh]"></video>
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-center gap-4">
                        <button id="switch-camera" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Switch Camera</button>
                        <button id="capture-photo" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">Capture Photo</button>
                        <button id="start-record" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Record Video</button>
                        <button id="stop-record" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg hidden">Stop Recording</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // Get camera access
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: facingMode },
                audio: true
            }).then((stream) => {
                mediaStream = stream;
                const video = document.getElementById('live-video');
                if (video) video.srcObject = stream;
            }).catch((err) => {
                alert('Camera permission denied or not available.');
                closeLiveCameraModal();
            });

            // Set up event listeners
            document.getElementById('close-live-camera').onclick = closeLiveCameraModal;
            document.getElementById('capture-photo').onclick = capturePhoto;
            document.getElementById('start-record').onclick = startRecording;
            document.getElementById('stop-record').onclick = stopRecording;
            document.getElementById('switch-camera').onclick = switchCamera;
        }

        function closeLiveCameraModal() {
            const modal = document.getElementById('live-camera-modal');
            if (modal) modal.remove();

            // Stop all tracks
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
                mediaStream = null;
            }

            // Reset recorder
            if (mediaRecorder) {
                mediaRecorder = null;
                recordedChunks = [];
            }
        }

        function switchCamera() {
            // Stop current stream
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
            }

            // Switch to opposite camera
            currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';

            // Get new stream
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: currentFacingMode },
                audio: true
            }).then((stream) => {
                mediaStream = stream;
                const video = document.getElementById('live-video');
                if (video) video.srcObject = stream;
            }).catch((err) => {
                alert('Camera switch failed.');
            });
        }

        function capturePhoto() {
            const video = document.getElementById('live-video');
            if (!video) return;

            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob((blob) => {
                if (!blob) {
                    console.error('Failed to create blob from canvas');
                    return;
                }
                const file = new File([blob], `photo_${Date.now()}.jpg`, { type: 'image/jpeg' });
                console.log('Captured photo from camera:', file);
                sendSelectedImage(file);
                closeLiveCameraModal();
            }, 'image/jpeg', 0.9);
        }

        function startRecording() {
            if (!mediaStream) return;

            recordedChunks = [];

            try {
                mediaRecorder = new MediaRecorder(mediaStream, { mimeType: 'video/webm;codecs=vp9' });
            } catch (e) {
                try {
                    mediaRecorder = new MediaRecorder(mediaStream, { mimeType: 'video/webm' });
                } catch (e) {
                    mediaRecorder = new MediaRecorder(mediaStream);
                }
            }

            mediaRecorder.ondataavailable = (e) => {
                if (e.data && e.data.size > 0) {
                    recordedChunks.push(e.data);
                }
            };

            mediaRecorder.onstop = () => {
                const blob = new Blob(recordedChunks, { type: 'video/webm' });
                sendRecordedVideo(blob);
            };

            mediaRecorder.start();

            // Update UI
            document.getElementById('start-record').classList.add('hidden');
            document.getElementById('stop-record').classList.remove('hidden');
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();

                // Update UI
                document.getElementById('start-record').classList.remove('hidden');
                document.getElementById('stop-record').classList.add('hidden');
            }
        }

        function sendRecordedVideo(blob) {
            console.log('Sending recorded video:', blob);

            if (!blob || blob.size === 0) {
                alert('No video recorded.');
                return;
            }

            // Check size (max 50MB)
            if (blob.size > 50 * 1024 * 1024) {
                alert('Video size should be less than 50MB');
                return;
            }

            const file = new File([blob], `video_${Date.now()}.webm`, { type: blob.type || 'video/webm' });
            console.log('Created file from blob:', file);

            // Create a clean FormData object
            const formData = new FormData();
            formData.append('to_user_id', document.querySelector('input[name="to_user_id"]').value);

            const messageText = document.getElementById('message-input').value.trim();
            if (messageText) formData.set('body', messageText);

            formData.append('video', file);
            // Also set the message type
            formData.set('message_type', 'video');

            const submitBtn = document.querySelector('#message-form button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="bx bx-loader-alt animate-spin w-5 h-5"></span>';
            }

            fetch(document.getElementById('message-form').action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Video upload response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Video upload response data:', data);
                if (data.success) {
                    const container = document.getElementById('messages-container');
                    console.log('Messages container:', container);
                    const div = document.createElement('div');
                    div.className = 'flex justify-end mb-4';

                    const url = data.message.attachment_url ||
                               (data.message.attachment_path ?
                                ('/storage/' + data.message.attachment_path.replace('public/', '')) : '');

                    const captionHtml = messageText ?
                                      `<div class="mt-2 text-white text-sm">${messageText}</div>` : '';

                    div.innerHTML = `
                        <div class="max-w-xs lg:max-w-md">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none p-2 shadow-sm">
                                <video controls class="rounded-lg max-h-60" src="${url}"></video>
                                ${captionHtml}
                                <div class="flex justify-end mt-1">
                                    <span class="text-xs text-blue-100">${new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', timeZone: 'Asia/Jakarta'})}</span>
                                    <span class="ml-1 text-xs text-blue-100 message-status sent">✓</span>
                                </div>
                            </div>
                        </div>
                    `;

                    console.log('Appending video message to container');
                    container.appendChild(div);
                    console.log('Scrolling to bottom');
                    container.scrollTop = container.scrollHeight;
                    document.getElementById('message-input').value = '';
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to send video.';
                    alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error sending video:', error);
                alert('Failed to send video. Please try again.');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<x-bxs-send class="w-5 h-5" />';
                }
            });
        }

        // Bind live camera button
        document.addEventListener('DOMContentLoaded', function() {
            const liveBtn = document.getElementById('open-live-camera');
            if (liveBtn) {
                liveBtn.addEventListener('click', function() {
                    openLiveCameraModal('environment');
                });
            }
        });

        // Close modals when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            // Close user info modal when clicking outside
            const userInfoModal = document.getElementById('user-info-modal');
            if (userInfoModal) {
                userInfoModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideUserInfoModal();
                    }
                });
            }

            // Existing message functionality
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const messagesContainer = document.getElementById('messages-container');
            const typingIndicator = document.getElementById('typing-indicator');

            if (!messageForm || !messageInput || !messagesContainer) {
                return; // Exit if essential elements are missing
            }

            // Auto-scroll to bottom of messages container
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Handle form submission with AJAX to prevent page reload
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const messageText = messageInput.value.trim();

                // Check all possible file inputs
                const imageFile = document.getElementById('image-upload').files[0];
                const cameraFile = document.getElementById('camera-upload').files[0];
                const frontCameraFile = document.getElementById('camera-upload-front').files[0];
                const docFile = document.getElementById('doc-upload').files[0];
                const videoFile = document.getElementById('video-upload') ? document.getElementById('video-upload').files[0] : null;

                // Log for debugging
                console.log('Form submission detected');
                console.log('Message text:', messageText);
                console.log('Image file:', imageFile);
                console.log('Camera file:', cameraFile);
                console.log('Front camera file:', frontCameraFile);
                console.log('Document file:', docFile);
                console.log('Video file:', videoFile);

                // Determine which file to send (priority order)
                let fileToSend = null;
                let fileType = null;

                if (imageFile) {
                    fileToSend = imageFile;
                    fileType = 'image';
                } else if (cameraFile) {
                    fileToSend = cameraFile;
                    fileType = 'image';
                } else if (frontCameraFile) {
                    fileToSend = frontCameraFile;
                    fileType = 'image';
                } else if (docFile) {
                    fileToSend = docFile;
                    fileType = 'file';
                } else if (videoFile) {
                    fileToSend = videoFile;
                    fileType = 'video';
                }

                // If we have a file, use the specific file sending functions
                if (fileToSend) {
                    console.log('Sending ' + fileType + ' file through specific handler');
                    if (fileType === 'image') {
                        sendSelectedImage(fileToSend);
                    } else if (fileType === 'file') {
                        sendDocument(fileToSend);
                    } else if (fileType === 'video') {
                        sendRecordedVideo(fileToSend);
                    }
                    return;
                }

                // If no file and no text, show error
                if (!messageText) {
                    alert('{{ __('Please enter a message or select a file to send.') }}');
                    return;
                }

                // For text-only messages, we still need to handle file inputs properly
                // Create a clean FormData object
                const formData = new FormData();
                formData.append('to_user_id', document.querySelector('input[name="to_user_id"]').value);
                formData.append('body', messageText);

                console.log('Sending text message with clean FormData');

                // Disable the submit button to prevent double submission
                const submitButton = messageForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="bx bx-loader-alt animate-spin w-5 h-5"></span>';
                }

                // Send message via AJAX
                fetch(messageForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Create and append the new message to the UI immediately
                        const newMessageDiv = document.createElement('div');
                        newMessageDiv.className = 'flex justify-end mb-4';
                        newMessageDiv.setAttribute('data-message-id', data.message.id);

                        newMessageDiv.innerHTML = `
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                                    <p class="text-sm text-white">${messageText}</p>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-blue-100">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit', timeZone: 'Asia/Jakarta'})}</span>
                                        <span class="ml-1 text-xs text-blue-100 message-status sent">✓</span>
                                    </div>
                                </div>
                            </div>
                        `;

                        const container = document.getElementById('messages-container');
                        console.log('Appending text message to container');
                        container.appendChild(newMessageDiv);

                        // Clear input and focus
                        messageInput.value = '';
                        messageInput.focus();

                        // Scroll to bottom
                        console.log('Scrolling to bottom');
                        container.scrollTop = container.scrollHeight;

                        // Clear file inputs
                        document.getElementById('image-upload').value = '';
                        document.getElementById('camera-upload').value = '';
                        document.getElementById('camera-upload-front').value = '';
                        if (document.getElementById('doc-upload')) {
                            document.getElementById('doc-upload').value = '';
                        }
                    } else {
                        // Handle error
                        const errorMsg = data.errors ? Object.values(data.errors).flat().join(', ') : '{{ __('Failed to send message. Please try again.') }}';
                        alert(errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __('Failed to send message. Please try again.') }}');
                })
                .finally(() => {
                    // Re-enable the submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<x-bxs-send class="w-5 h-5" />';
                    }
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
                Echo.private('user.{{ auth()->id() }}')
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
                                    <p class="text-sm text-gray-800 dark:text-gray-200">${e.body || e.message}</p>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit', timeZone: 'Asia/Jakarta'})}</span>
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
                        if (typingIndicator) {
                            if (e.is_typing) {
                                typingIndicator.classList.remove('hidden');
                            } else {
                                typingIndicator.classList.add('hidden');
                            }
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
            will-change: transform, opacity;
        }

        /* Optimize scrolling performance */
        #messages-container {
            -webkit-overflow-scrolling: touch;
            will-change: scroll-position;
        }

        /* Optimize message rendering */
        .message-bubble {
            will-change: transform;
            transform: translateZ(0);
        }

        /* Optimize typing indicator animation */
        .animate-bounce {
            will-change: transform;
        }
    </style>
    @endpush
</x-layouts.app>
