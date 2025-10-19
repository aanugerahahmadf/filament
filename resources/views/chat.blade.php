<x-layouts.app :title="__('Chat')">
    @push('styles')
    <style>
        /* Enhanced Dark WhatsApp Style Chat Interface */
        /* Ensure proper flexbox behavior */
        .chat-container {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 0;
            height: calc(100vh - 2rem);
            max-height: calc(100vh - 2rem);
            background: #0a0a0a;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
            border-radius: 12px;
            overflow: hidden;
            margin: 10px;
            min-height: 0;
            border: 1px solid rgba(42, 57, 66, 0.5);
            animation: containerAppear 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes containerAppear {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .conversation-list {
            background: linear-gradient(135deg, #1e2d34, #1a252f);
            border-right: 1px solid #2a3942;
            padding: 0;
            overflow-y: auto;
            height: 100%;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.3);
            min-height: 0;
            padding-top: 0.5rem;
            animation: slideInLeft 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .chat-area {
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #0a161c, #091217);
            height: 100%;
            overflow: hidden;
            position: relative;
            min-height: 0;
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .chat-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #2a3942;
            display: flex;
            align-items: center;
            background: linear-gradient(90deg, #202c33, #1a2429);
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            z-index: 10;
            animation: headerAppear 0.3s ease-out;
        }

        @keyframes headerAppear {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            background: #0a161c;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%231e3a4c' fill-opacity='0.15' fill-rule='evenodd'/%3E%3C/svg%3E");
            scroll-behavior: smooth;
        }

        /* Add a subtle glow effect to active elements */
        .conversation-item.active,
        .conversation-item.highlighted {
            box-shadow: 0 0 15px rgba(0, 168, 132, 0.3);
            position: relative;
        }

        .conversation-item.active::before,
        .conversation-item.highlighted::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 12px;
            pointer-events: none;
            animation: glowPulse 2s infinite;
        }

        @keyframes glowPulse {
            0% {
                box-shadow: inset 0 0 5px rgba(0, 168, 132, 0.2);
            }
            50% {
                box-shadow: inset 0 0 15px rgba(0, 168, 132, 0.4);
            }
            100% {
                box-shadow: inset 0 0 5px rgba(0, 168, 132, 0.2);
            }
        }

        /* Improve the send button animation */
        .send-button {
            width: 48px;
            height: 48px;
            font-size: 1.35rem;
        }

        /* Enhanced animations and visual effects */
        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes headerAppear {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes containerAppear {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes itemAppear {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes listAppear {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced visual effects */
        .message {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: messageAppear 0.3s ease-out;
        }

        .message.sent {
            background: linear-gradient(135deg, #005c4b, #004d3e);
            border-bottom-right-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 92, 75, 0.4);
        }

        .message.received {
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-bottom-left-radius: 8px;
            box-shadow: 0 4px 15px rgba(42, 57, 66, 0.4);
        }

        .conversation-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: itemAppear 0.3s ease-out;
            animation-fill-mode: both;
        }

        .conversation-item:hover {
            transform: translateX(8px);
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.25);
        }

        .conversation-item.highlighted {
            animation: glowPulse 2s infinite;
        }

        .user-avatar {
            animation: pulse 2s infinite;
        }

        .unread-count {
            animation: unreadPulse 2s infinite;
        }

        .send-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .send-button:hover {
            transform: translateY(-4px) scale(1.05);
        }

        .send-button:active {
            transform: translateY(-2px) scale(1.02);
        }

        .action-button,
        .header-action,
        .header-search {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .action-button:hover,
        .header-action:hover,
        .header-search:hover {
            transform: translateY(-3px) scale(1.05);
        }

        .action-button:active,
        .header-action:active,
        .header-search:active {
            transform: translateY(-1px) scale(1.02);
        }
    </style>
    @endpush

    <!-- Rest of the HTML content would go here -->

    <!-- Adding the missing closing tags -->
    </div>
    @push('scripts')
    <!-- JavaScript content would go here -->
    @endpush
</x-layouts.app>
        }

        .message {
            max-width: 75%;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            line-height: 1.5;
            animation: messageAppear 0.3s ease-out;
            clear: both;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            flex-shrink: 0;
            align-self: flex-start;
            width: fit-content;
            min-width: 120px;
        }

        .message.sent {
            align-self: flex-end;
            background: linear-gradient(135deg, #005c4b, #004d3e);
            color: #e9edef;
            border-bottom-right-radius: 6px;
            box-shadow: 0 4px 15px rgba(0, 92, 75, 0.4);
            border: 1px solid rgba(0, 168, 132, 0.2);
            margin-left: auto;
        }

        .message.received {
            align-self: flex-start;
            background: linear-gradient(135deg, #2a3942, #222f35);
            color: #e9edef;
            border-bottom-left-radius: 6px;
            box-shadow: 0 4px 15px rgba(42, 57, 66, 0.4);
            border: 1px solid rgba(134, 150, 160, 0.1);
            margin-right: auto;
        }

        .message-content {
            font-size: 1rem;
            margin-bottom: 0.35rem;
            word-break: break-word;
            white-space: pre-wrap;
            font-weight: 400;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .message-time {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 0.35rem;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.3rem;
            flex-wrap: nowrap;
            color: #a0a0a0;
            white-space: nowrap;
        }

        .message-status {
            display: inline-block;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .message-status.sent { color: rgba(233, 237, 239, 0.7); }
        .message-status.delivered { color: rgba(233, 237, 239, 0.9); }
        .message-status.read { color: #53bdeb; }

        .reply-preview {
            background: rgba(255, 255, 255, 0.07);
            border-left: 3px solid #00a884;
            padding: 0.6rem;
            margin-bottom: 0.6rem;
            border-radius: 0.3rem;
            font-size: 0.9rem;
            overflow: hidden;
        }

        .reply-preview-sender {
            font-weight: 600;
            margin-bottom: 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .reply-preview-content {
            opacity: 0.9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .forwarded-indicator {
            font-size: 0.85rem;
            color: #8696a0;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        .edited-indicator {
            font-size: 0.8rem;
            opacity: 0.8;
            font-style: italic;
            margin-left: 0.3rem;
        }

        /* Critical fix for text overlapping */
        .chat-container,
        .messages-page-container {
            isolation: isolate;
            contain: layout style;
        }

        .conversation-list,
        .messages-container {
            contain: layout style;
        }

        /* Ensure proper text rendering */
        body,
        .chat-container,
        .messages-page-container {
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Fix for all text elements to prevent overlapping */
        .conversation-name,
        .conversation-status,
        .conversation-last-message,
        .message-content,
        .reply-preview-content,
        .forwarded-indicator,
        .edited-indicator,
        .message-time,
        .conversation-time,
        .status-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            line-height: 1.4;
        }

        /* Fix for message content specifically */
        .message-content {
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            max-width: 100%;
        }

        /* Fix for conversation items */
        .conversation-item {
            contain: layout style;
        }

        /* Fix for messages */
        .message {
            contain: layout style;
        }

        /* Ensure proper stacking context */
        .message.sent,
        .message.received {
            position: relative;
            z-index: 1;
        }

        /* Fix for input areas */
        .message-input,
        .search-input {
            line-height: 1.4;
            text-align: left;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* Additional fixes for text overlapping */
        .message {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: fit-content;
            max-width: 75%;
        }

        .message.sent {
            align-self: flex-end;
            margin-left: auto;
        }

        .message.received {
            align-self: flex-start;
            margin-right: auto;
        }

        .message-content {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            line-height: 1.5;
        }

        .message-time {
            align-self: flex-end;
            margin-top: 0.25rem;
        }

        .message-reactions {
            display: flex;
            gap: 0.3rem;
            margin-top: 0.3rem;
            flex-wrap: wrap;
        }

        .reaction {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 1.25rem;
            padding: 0.25rem 0.7rem;
            font-size: 0.9rem;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            white-space: nowrap;
        }

        .input-area {
            padding: 1.25rem;
            border-top: 1px solid #2a3942;
            display: flex;
            gap: 1rem;
            background: linear-gradient(90deg, #202c33, #1a2429);
            align-items: center;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.3);
            flex-wrap: nowrap;
        }

        .message-input {
            flex: 1;
            padding: 0.85rem 0;
            color: #e9edef;
            outline: none;
            background: transparent;
            border: none;
            font-size: 1.05rem;
            resize: none;
            max-height: 150px;
            min-height: 26px;
            font-family: inherit;
        }

        .message-input::placeholder {
            color: #8696a0;
        }

        .action-button {
            background: linear-gradient(135deg, #2a3942, #222f35);
            border: none;
            color: #8696a0;
            font-size: 1.35rem;
            cursor: pointer;
            padding: 0.65rem;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 0.3rem;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .action-button:hover {
            background: linear-gradient(135deg, #2a3942, #25343d);
            color: #e9edef;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(134, 150, 160, 0.4);
        }

        .action-button:active {
            transform: translateY(-1px);
        }

        .conversation-item {
            display: flex;
            align-items: center;
            padding: 1.15rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border-bottom: 1px solid rgba(42, 57, 66, 0.6);
            background: transparent;
            transform: translateZ(0);
            backface-visibility: hidden;
            min-height: 75px;
            margin: 0 0.65rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: calc(100% - 1.3rem);
            box-sizing: border-box;
        }

        .conversation-item:hover {
            background: linear-gradient(90deg, rgba(42, 57, 66, 0.7), transparent);
            transform: translateX(8px);
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.25);
        }

        .conversation-item.active {
            background: linear-gradient(90deg, rgba(42, 57, 66, 0.8), rgba(34, 47, 53, 0.8));
            border-left: 4px solid #00a884;
        }

        .user-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a884, #25d366);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1.15rem;
            font-size: 1.55rem;
            color: white;
            position: relative;
            box-shadow: 0 6px 15px rgba(0, 168, 132, 0.5);
            border: 3px solid #1e2d34;
            animation: pulse 2s infinite;
            overflow: hidden;
        }

        .avatar-content {
            position: relative;
            z-index: 2;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .online-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 16px;
            height: 16px;
            background: #00a884;
            border: 3px solid #1e2d34;
            border-radius: 50%;
            box-shadow: 0 0 12px rgba(0, 168, 132, 0.7);
            z-index: 3;
        }

        .conversation-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            height: 100%;
        }

        .conversation-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.35rem;
            min-width: 0;
            width: 100%;
        }

        .conversation-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: #e9edef;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
            flex-shrink: 1;
            width: 100%;
        }

        .conversation-status {
            font-size: 0.95rem;
            color: #8696a0;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 1;
            width: 100%;
        }

        .status-text {
            color: #a0a0a0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 1;
        }

        .super-admin-badge {
            background: linear-gradient(135deg, #ff6b6b, #ff5252);
            color: white;
            font-size: 0.75rem;
            padding: 0.3rem 0.65rem;
            border-radius: 14px;
            font-weight: 700;
            margin-left: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 3px 8px rgba(255, 107, 107, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 26px;
            flex-shrink: 0;
        }

        .conversation-status {
            font-size: 0.95rem;
            color: #8696a0;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 1;
        }

        .status-text {
            color: #a0a0a0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 1;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #00a884;
            margin-right: 0.5rem;
            box-shadow: 0 0 6px rgba(0, 168, 132, 0.7);
            flex-shrink: 0;
        }

        .message {
            max-width: 75%;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: 20px;
            position: relative;
            word-wrap: break-word;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            line-height: 1.5;
            animation: messageAppear 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            clear: both;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        /* Fix for message display to prevent overlapping */
        .message.sent,
        .message.received {
            position: relative;
            display: block;
            clear: both;
            float: none;
            align-self: flex-start;
        }

        .message.sent {
            align-self: flex-end;
            margin-left: auto;
        }

        .message.received {
            align-self: flex-start;
            margin-right: auto;
        }

        /* Ensure proper message container behavior */
        .messages-container {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        /* Fix for conversation items to prevent text overlapping */
        .conversation-item {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
        }

        .conversation-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .conversation-header {
            display: flex;
            align-items: center;
            width: 100%;
            min-width: 0;
        }

        .conversation-name {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .conversation-last-message {
            width: 100%;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .conversation-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            flex-shrink: 0;
            margin-left: 0.75rem;
        }

        .message-content {
            font-size: 1rem;
            margin-bottom: 0.35rem;
            word-break: break-word;
            white-space: pre-wrap;
            font-weight: 400;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .message-time {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 0.35rem;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.3rem;
            flex-wrap: nowrap;
            color: #a0a0a0;
            white-space: nowrap;
        }

        .message-status {
            display: inline-block;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .message-status.sent { color: rgba(233, 237, 239, 0.7); }
        .message-status.delivered { color: rgba(233, 237, 239, 0.9); }
        .message-status.read { color: #53bdeb; }

        .reply-preview {
            background: rgba(255, 255, 255, 0.07);
            border-left: 3px solid #00a884;
            padding: 0.6rem;
            margin-bottom: 0.6rem;
            border-radius: 0.3rem;
            font-size: 0.9rem;
            overflow: hidden;
        }

        .reply-preview-sender {
            font-weight: 600;
            margin-bottom: 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .reply-preview-content {
            opacity: 0.9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .forwarded-indicator {
            font-size: 0.85rem;
            color: #8696a0;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        .edited-indicator {
            font-size: 0.8rem;
            opacity: 0.8;
            font-style: italic;
            margin-left: 0.3rem;
        }

        .message-reactions {
            display: flex;
            gap: 0.3rem;
            margin-top: 0.3rem;
            flex-wrap: wrap;
        }

        .reaction {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 1.25rem;
            padding: 0.25rem 0.7rem;
            font-size: 0.9rem;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            white-space: nowrap;
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            background: linear-gradient(135deg, #202c33, #1a2429);
            border-radius: 24px;
            margin-bottom: 1.25rem;
            align-self: flex-start;
            font-size: 1rem;
            color: #8696a0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .typing-dots {
            display: flex;
            margin-left: 0.85rem;
        }

        .typing-dot {
            width: 10px;
            height: 10px;
            background: #00a884;
            border-radius: 50%;
            margin: 0 4px;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) { animation-delay: 0s; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-6px); }
        }

        .search-container {
            padding: 1.25rem;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #1e2d34, #1a252f);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            border-radius: 0 0 12px 12px;
        }

        .search-input {
            width: 100%;
            padding: 0.85rem 1.5rem 0.85rem 3.25rem;
            border-radius: 24px;
            border: 2px solid transparent;
            background: linear-gradient(135deg, #2a3942, #222f35);
            color: #e9edef;
            font-size: 1.05rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-input:focus {
            outline: none;
            border-color: #00a884;
            background: rgba(42, 57, 66, 0.8);
            box-shadow: 0 0 0 4px rgba(0,168,132,0.3);
        }

        .search-input::placeholder {
            color: #8696a0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-icon {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #8696a0;
            font-size: 1.25rem;
        }

        .header-action {
            background: linear-gradient(135deg, #2a3942, #222f35);
            border: none;
            color: #8696a0;
            font-size: 1.45rem;
            cursor: pointer;
            padding: 0.7rem;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 0.35rem;
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .header-action:hover {
            background: linear-gradient(135deg, #2a3942, #25343d);
            color: #e9edef;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(134, 150, 160, 0.4);
        }

        .header-action:active {
            transform: translateY(-1px);
        }

        .message-date-divider {
            text-align: center;
            margin: 1.75rem 0;
            position: relative;
        }

        .message-date-divider span {
            background: linear-gradient(135deg, #2a3942, #222f35);
            color: #8696a0;
            padding: 0.6rem 1.75rem;
            border-radius: 24px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .message-date-divider:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #2a3942, transparent);
            z-index: -1;
        }

        /* Scrollbar styling */
        .messages-container::-webkit-scrollbar {
            width: 10px;
        }

        .messages-container::-webkit-scrollbar-track {
            background: #0a161c;
            border-radius: 5px;
        }

        .messages-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #2a3942, #00a884);
            border-radius: 5px;
            border: 2px solid #0a161c;
        }

        .messages-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #8696a0, #00a884);
        }

        .conversation-list::-webkit-scrollbar {
            width: 10px;
        }

        .conversation-list::-webkit-scrollbar-track {
            background: #1e2d34;
            border-radius: 5px;
        }

        .conversation-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #2a3942, #00a884);
            border-radius: 5px;
            border: 2px solid #1e2d34;
        }

        .conversation-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #8696a0, #00a884);
        }

        @media (max-width: 1024px) {
            .chat-container {
                grid-template-columns: 1fr;
                margin: 0;
                border-radius: 0;
            }

            .conversation-list {
                display: none;
            }
        }

        /* Empty state styling */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #8696a0;
            text-align: center;
            padding: 2.5rem;
        }

        .empty-state-icon {
            font-size: 5.5rem;
            margin-bottom: 1.75rem;
            color: #8696a0;
            opacity: 0.8;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-12px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .empty-state-title {
            font-size: 1.85rem;
            font-weight: 600;
            margin-bottom: 0.85rem;
            color: #e9edef;
        }

        .empty-state-subtitle {
            font-size: 1.15rem;
            margin-bottom: 1.75rem;
            max-width: 420px;
            line-height: 1.6;
            color: #a0a0a0;
        }

        /* Super admin badge */
        .super-admin-badge {
            background: linear-gradient(135deg, #ff6b6b, #ff5252);
            color: white;
            font-size: 0.75rem;
            padding: 0.3rem 0.65rem;
            border-radius: 14px;
            font-weight: 700;
            margin-left: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 3px 8px rgba(255, 107, 107, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 26px;
        }

        /* Profile picture styling */
        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a884, #25d366);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.3rem;
            color: white;
            margin-right: 1.25rem;
            box-shadow: 0 6px 15px rgba(0, 168, 132, 0.5);
            border: 3px solid #1e2d34;
        }

        /* Header info */
        .header-info {
            flex: 1;
        }

        .header-name {
            font-weight: 600;
            color: #e9edef;
            font-size: 1.25rem;
        }

        .header-status {
            font-size: 0.9rem;
            color: #8696a0;
            margin-top: 0.2rem;
        }

        /* Search icon in header */
        .header-search {
            color: #8696a0;
            font-size: 1.45rem;
            cursor: pointer;
            padding: 0.7rem;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 0.35rem;
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #2a3942, #222f35);
            border: none;
        }

        .header-search:hover {
            background: linear-gradient(135deg, #2a3942, #25343d);
            color: #e9edef;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(134, 150, 160, 0.4);
        }

        .header-search:active {
            transform: translateY(-1px);
        }

        /* Online status indicator */
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #00a884;
            margin-right: 0.5rem;
            box-shadow: 0 0 6px rgba(0, 168, 132, 0.7);
        }

        /* Message context menu */
        .context-menu {
            position: absolute;
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
            border: 1px solid #00a884;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .context-menu-item {
            padding: 1rem 1.75rem;
            cursor: pointer;
            font-size: 1rem;
            color: #e9edef;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            transition: all 0.2s ease;
        }

        .context-menu-item:hover {
            background: linear-gradient(90deg, #00a884, #008f73);
            color: white;
        }

        /* Message reactions */
        .message-reactions {
            display: flex;
            gap: 0.3rem;
            margin-top: 0.3rem;
        }

        .reaction {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 1.25rem;
            padding: 0.25rem 0.7rem;
            font-size: 0.9rem;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
        }

        /* Edited indicator */
        .edited-indicator {
            font-size: 0.8rem;
            opacity: 0.8;
            font-style: italic;
            margin-left: 0.3rem;
        }

        /* Unread messages indicator */
        .unread-indicator {
            position: sticky;
            top: 0.6rem;
            background: linear-gradient(135deg, #00a884, #008f73);
            color: white;
            padding: 0.3rem 0.85rem;
            border-radius: 1.25rem;
            font-size: 0.85rem;
            font-weight: 500;
            margin: 0.6rem auto;
            width: fit-content;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 168, 132, 0.5);
            backdrop-filter: blur(5px);
        }

        /* Message options */
        .message-options {
            position: absolute;
            top: -0.6rem;
            right: 0.85rem;
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.4);
            display: none;
            z-index: 100;
            border: 1px solid #00a884;
            backdrop-filter: blur(10px);
            transform-origin: top right;
            animation: messageOptionsAppear 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .message:hover .message-options {
            display: flex;
        }

        .message-option {
            padding: 0.7rem;
            color: #e9edef;
            cursor: pointer;
            font-size: 1.15rem;
            transition: all 0.2s ease;
            border-radius: 12px;
        }

        .message-option:hover {
            background: linear-gradient(135deg, #00a884, #008f73);
            color: white;
        }

        /* Attachments */
        .attachment {
            margin-top: 0.6rem;
            border-radius: 0.6rem;
            overflow: hidden;
            max-width: 320px;
        }

        .attachment-image {
            max-width: 100%;
            max-height: 220px;
            object-fit: cover;
        }

        .attachment-file {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.85rem;
            border-radius: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .attachment-icon {
            font-size: 2.2rem;
            color: #00a884;
        }

        .attachment-info {
            flex: 1;
        }

        .attachment-name {
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        .attachment-size {
            font-size: 0.85rem;
            color: #8696a0;
        }

        /* Reply preview */
        .reply-preview {
            background: rgba(255, 255, 255, 0.07);
            border-left: 3px solid #00a884;
            padding: 0.6rem;
            margin-bottom: 0.6rem;
            border-radius: 0.3rem;
            font-size: 0.9rem;
        }

        .reply-preview-sender {
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .reply-preview-content {
            opacity: 0.9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Forwarded message indicator */
        .forwarded-indicator {
            font-size: 0.85rem;
            color: #8696a0;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Pinned message indicator */
        .pinned-indicator {
            background: rgba(0, 168, 132, 0.25);
        }

        /* Universal text handling fixes */
        * {
            box-sizing: border-box;
        }

        /* Ensure proper line height and text alignment */
        .conversation-name,
        .conversation-status,
        .conversation-last-message,
        .message-content,
        .reply-preview-content,
        .forwarded-indicator,
        .edited-indicator {
            line-height: 1.4;
            text-align: left;
        }

        /* Fix for message options to prevent overlap */
        .message-options {
            position: absolute;
            top: -0.6rem;
            right: 0.85rem;
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.4);
            display: none;
            z-index: 100;
            border: 1px solid #00a884;
            backdrop-filter: blur(10px);
            transform-origin: top right;
            animation: messageOptionsAppear 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        /* Fix for context menu */
        .context-menu {
            position: absolute;
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
            border: 1px solid #00a884;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transform-origin: top right;
            animation: contextMenuAppear 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        /* Fix for input areas */
        .message-input,
        .search-input {
            line-height: 1.4;
            text-align: left;
        }

        /* Ensure proper overflow handling for all text elements */
        .conversation-name,
        .conversation-status,
        .conversation-last-message,
        .message-content,
        .reply-preview-content,
        .forwarded-indicator,
        .edited-indicator,
        .message-time,
        .conversation-time,
        .status-text {
            overflow: hidden;
            text-overflow: ellipsis;
        }
            padding: 0.3rem 0.6rem;
            border-radius: 0.3rem;
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Search container positioning */
        .search-wrapper {
            position: relative;
            border-radius: 24px;
            background: linear-gradient(135deg, #2a3942, #222f35);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        /* Ripple effect for click animation */
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 168, 132, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            z-index: 1;
        }

        /* Modern enhancements for WhatsApp/Messenger-like experience */
        /* Enhanced message appearance */
        .message {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            max-width: 80%;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: messageAppear 0.3s ease-out;
        }

        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.sent {
            background: linear-gradient(135deg, #005c4b, #004d3e);
            border-bottom-right-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 92, 75, 0.4);
        }

        .message.received {
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-bottom-left-radius: 8px;
            box-shadow: 0 4px 15px rgba(42, 57, 66, 0.4);
        }

        /* Enhanced typing indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            background: linear-gradient(135deg, #202c33, #1a2429);
            border-radius: 24px;
            margin-bottom: 1.25rem;
            align-self: flex-start;
            font-size: 1rem;
            color: #8696a0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            max-width: 120px;
        }

        .typing-dots {
            display: flex;
            margin-left: 0.85rem;
        }

        .typing-dot {
            width: 10px;
            height: 10px;
            background: #00a884;
            border-radius: 50%;
            margin: 0 4px;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) { animation-delay: 0s; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-6px); }
        }

        /* Enhanced message options */
        .message-options {
            position: absolute;
            top: -0.5rem;
            right: 0.75rem;
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
            display: none;
            z-index: 100;
            border: 1px solid #00a884;
            backdrop-filter: blur(5px);
        }

        .message-option {
            padding: 0.6rem;
            color: #e9edef;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .message-option:hover {
            background: linear-gradient(135deg, #00a884, #008f73);
            color: white;
        }

        /* Enhanced context menu */
        .context-menu {
            position: absolute;
            background: linear-gradient(135deg, #2a3942, #222f35);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
            border: 1px solid #00a884;
            overflow: hidden;
            backdrop-filter: blur(5px);
        }

        .context-menu-item {
            padding: 0.9rem 1.5rem;
            cursor: pointer;
            font-size: 0.95rem;
            color: #e9edef;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .context-menu-item:hover {
            background: linear-gradient(90deg, #00a884, #008f73);
            color: white;
        }

        /* Enhanced search box */
        .search-wrapper {
            position: relative;
            border-radius: 24px;
            background: linear-gradient(135deg, #2a3942, #222f35);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .search-input {
            padding: 0.9rem 1.25rem 0.9rem 3.25rem;
            border-radius: 24px;
            font-size: 1rem;
        }

        .search-icon {
            left: 1.25rem;
            font-size: 1.25rem;
        }

        /* Enhanced header */
        .chat-header {
            padding: 1.1rem 1.6rem;
        }

        .header-name {
            font-size: 1.3rem;
        }

        .header-status {
            font-size: 0.95rem;
        }

        .header-action,
        .header-search {
            width: 48px;
            height: 48px;
            font-size: 1.35rem;
        }

        /* Enhanced conversation items */
        .conversation-item {
            padding: 1.1rem 1.6rem;
            min-height: 80px;
            margin: 0 0.6rem;
            border-radius: 10px;
        }

        .user-avatar {
            width: 55px;
            height: 55px;
            font-size: 1.55rem;
        }

        .conversation-name {
            font-size: 1.1rem;
        }

        .conversation-status {
            font-size: 0.9rem;
        }

        .super-admin-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.6rem;
            height: 24px;
        }

        /* Enhanced input area */
        .input-area {
            padding: 1.1rem;
        }

        .action-button {
            width: 48px;
            height: 48px;
            font-size: 1.35rem;
        }

        .message-input-container {
            border-radius: 24px;
            min-height: 48px;
        }

        .message-input {
            padding: 0.8rem 0;
            font-size: 1rem;
        }

        .send-button {
            width: 48px;
        }

        /* Responsive design improvements */
        @media (max-width: 1200px) {
            .chat-container {
                grid-template-columns: 1fr 2fr;
            }

            .conversation-item {
                padding: 1rem 1.25rem;
                min-height: 70px;
            }

            .user-avatar {
                width: 50px;
                height: 50px;
                font-size: 1.4rem;
            }

            .conversation-name {
                font-size: 1rem;
            }

            .conversation-status {
                font-size: 0.85rem;
            }

            .message {
                max-width: 85%;
                padding: 0.85rem 1.25rem;
            }

            .message-content {
                font-size: 0.95rem;
            }

            .message-time {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 992px) {
            .chat-container {
                grid-template-columns: 1fr 1fr;
            }

            .sidebar-header .title {
                font-size: 1.5rem;
            }

            .header-name {
                font-size: 1.15rem;
            }

            .header-status {
                font-size: 0.85rem;
            }

            .header-action,
            .header-search {
                width: 42px;
                height: 42px;
                font-size: 1.25rem;
            }

            .action-button {
                width: 42px;
                height: 42px;
                font-size: 1.25rem;
            }

            .send-button {
                width: 42px;
                height: 42px;
                font-size: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .chat-container {
                grid-template-columns: 1fr;
                margin: 0;
                border-radius: 0;
                height: 100vh;
            }

            .conversation-list {
                display: none;
            }

            .chat-header {
                padding: 0.9rem 1.25rem;
            }

            .sidebar-header {
                padding: 1rem 1.5rem;
            }

            .sidebar-header .title {
                font-size: 1.4rem;
            }

            .sidebar-header .action-icons .icon-button {
                width: 44px;
                height: 44px;
                font-size: 1.4rem;
            }

            .search-box {
                padding: 1rem 1.5rem;
            }

            .search-input {
                padding: 0.75rem 1rem 0.75rem 2.75rem;
                font-size: 0.95rem;
            }

            .search-icon {
                left: 1rem;
                font-size: 1.15rem;
            }

            .conversation-item {
                padding: 0.9rem 1.25rem;
                min-height: 65px;
                margin: 0 0.5rem;
            }

            .user-avatar {
                width: 45px;
                height: 45px;
                font-size: 1.25rem;
                margin-right: 1rem;
            }

            .conversation-name {
                font-size: 0.95rem;
            }

            .conversation-last-message {
                font-size: 0.85rem;
            }

            .conversation-time {
                font-size: 0.75rem;
            }

            .unread-count {
                width: 20px;
                height: 20px;
                font-size: 0.7rem;
            }

            .input-area {
                padding: 0.9rem;
            }

            .action-button {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
                padding: 0.5rem;
            }

            .message-input-container {
                border-radius: 20px;
                min-height: 40px;
            }

            .message-input {
                padding: 0.7rem 0;
                font-size: 0.95rem;
            }

            .send-button {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .message {
                max-width: 90%;
                padding: 0.75rem 1rem;
                margin-bottom: 0.75rem;
            }

            .message-content {
                font-size: 0.9rem;
            }

            .message-time {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .chat-header {
                padding: 0.75rem 1rem;
            }

            .header-name {
                font-size: 1rem;
            }

            .header-status {
                font-size: 0.75rem;
            }

            .sidebar-header {
                padding: 0.85rem 1.25rem;
            }

            .sidebar-header .title {
                font-size: 1.25rem;
            }

            .sidebar-header .action-icons .icon-button {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }

            .search-box {
                padding: 0.85rem 1.25rem;
            }

            .search-input {
                padding: 0.65rem 0.85rem 0.65rem 2.5rem;
                font-size: 0.9rem;
            }

            .search-icon {
                left: 0.85rem;
                font-size: 1rem;
            }

            .conversation-item {
                padding: 0.75rem 1rem;
                min-height: 60px;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
                margin-right: 0.75rem;
            }

            .conversation-name {
                font-size: 0.9rem;
            }

            .conversation-last-message {
                font-size: 0.8rem;
            }

            .input-area {
                padding: 0.75rem;
            }

            .message {
                max-width: 95%;
                padding: 0.65rem 0.85rem;
            }

            .message-content {
                font-size: 0.85rem;
            }
        }

        /* Additional responsive fixes */
        @media (max-height: 600px) {
            .conversation-item {
                min-height: 55px;
                padding: 0.65rem 1rem;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .conversation-item:hover {
                transform: none;
                box-shadow: none;
            }

            .conversation-item:active {
                transform: translateX(5px);
                box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2);
            }

            .action-button:hover,
            .header-action:hover,
            .header-search:hover {
                transform: none;
            }

            .action-button:active,
            .header-action:active,
            .header-search:active {
                transform: translateY(-2px) scale(1.02);
            }
        }
