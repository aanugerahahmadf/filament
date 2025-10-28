import Echo from 'laravel-echo';

window.Echo = new Echo({
	broadcaster: 'reverb',
	key: import.meta.env.VITE_REVERB_APP_KEY,
	wsHost: import.meta.env.VITE_REVERB_HOST,
	wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
	wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
	forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
	enabledTransports: ['ws', 'wss'],
});

// CCTV status updates (support both channel/event variants)
try {
	window.Echo.channel('cctv-monitoring')
		.listen('.cctv.status.updated', (e) => {
			window.dispatchEvent(new CustomEvent('cctv-status', { detail: e }));
		})
		.listen('.CctvStatusUpdated', (e) => {
			window.dispatchEvent(new CustomEvent('cctv-status', { detail: e }));
		});
} catch (_) {}

// Backward compatibility channel (if used elsewhere)
try {
	window.Echo.channel('cctv-status')
		.listen('.CctvStatusUpdated', (e) => {
			window.dispatchEvent(new CustomEvent('cctv-status', { detail: e }));
		});
} catch (_) {}

// System metrics for dashboard widgets
try {
	window.Echo.channel('dashboard-monitoring')
		.listen('.system.metrics.updated', (e) => {
			window.dispatchEvent(new CustomEvent('dashboard-metrics', { detail: e }));
		})
		.listen('.SystemMetricsUpdated', (e) => {
			window.dispatchEvent(new CustomEvent('dashboard-metrics', { detail: e }));
		});
} catch (_) {}

// Emergency alerts
try {
	window.Echo.channel('emergency-alerts')
		.listen('.emergency.alert', (e) => {
			window.dispatchEvent(new CustomEvent('emergency-alert', { detail: e }));
		});
} catch (_) {}

// Maps/location CRUD updates from admin panel
try {
	window.Echo.channel('maps-updates')
		.listen('.maps.data.changed', () => {
			// poke UI to refetch map/location data
			window.dispatchEvent(new CustomEvent('maps-data-changed'));
		});
} catch (_) {}

// Private user channels: messages and notifications
try {
    if (window && window.AppUserId) {
    window.Echo.private(`user.${window.AppUserId}`)
        .listen('.message.sent', (e) => {
            window.dispatchEvent(new CustomEvent('realtime-message', { detail: e }));
            try { showRealtimeToast(`Pesan baru dari ${e?.from_user?.name || 'User'}`, 'bxs-message-square', '#8B5CF6'); } catch(_) {}
            try { incrementUiUnreadBadge(); } catch(_) {}
            try { playNotificationSound(); } catch(_) {}
        })
        .listen('.message.delivered', (e) => {
            window.dispatchEvent(new CustomEvent('message-delivered', { detail: e }));
        })
        .listen('.message.read', (e) => {
            window.dispatchEvent(new CustomEvent('message-read', { detail: e }));
        })
        .listen('.user.typing', (e) => {
            window.dispatchEvent(new CustomEvent('user-typing', { detail: e }));
        })
        .listen('.notification.created', (e) => {
            window.dispatchEvent(new CustomEvent('realtime-notification', { detail: e }));
            try { showRealtimeToast('Notifikasi baru', 'bxs-bell', '#22C55E'); } catch(_) {}
            try { incrementUiUnreadBadge(); } catch(_) {}
            try { playNotificationSound(); } catch(_) {}
        });
    }
} catch (_) {}

// Lightweight toast + unread badge for UI pages
function ensureToastHost() {
    let host = document.getElementById('ui-toast-host');
    if (!host) {
        host = document.createElement('div');
        host.id = 'ui-toast-host';
        host.style.position = 'fixed';
        host.style.right = '20px';
        host.style.bottom = '20px';
        host.style.zIndex = '9999';
        host.style.display = 'flex';
        host.style.flexDirection = 'column';
        host.style.gap = '10px';
        document.body.appendChild(host);
    }
    return host;
}

function showRealtimeToast(text, icon = 'bxs-bell', color = '#3B82F6') {
    const host = ensureToastHost();
    const card = document.createElement('div');
    card.style.background = 'rgba(0,0,0,0.8)';
    card.style.color = '#fff';
    card.style.border = '1px solid rgba(255,255,255,0.15)';
    card.style.borderRadius = '12px';
    card.style.padding = '10px 14px';
    card.style.boxShadow = '0 10px 25px rgba(0,0,0,0.35)';
    card.style.display = 'flex';
    card.style.alignItems = 'center';
    card.style.gap = '10px';
    card.style.transform = 'translateY(20px)';
    card.style.opacity = '0';
    card.style.transition = 'all .25s ease';
    card.innerHTML = `<i class="bx ${icon}" style="color:${color}; font-size:18px"></i><span style="font-weight:600;">${text}</span>`;
    host.appendChild(card);
    requestAnimationFrame(() => {
        card.style.transform = 'translateY(0)';
        card.style.opacity = '1';
    });
    setTimeout(() => {
        card.style.transform = 'translateY(20px)';
        card.style.opacity = '0';
        setTimeout(() => card.remove(), 250);
    }, 3500);
}

function ensureUiUnreadBadge() {
    let badge = document.getElementById('ui-unread-badge');
    if (!badge) {
        badge = document.createElement('a');
        badge.id = 'ui-unread-badge';
        badge.href = '/notifications';
        badge.style.position = 'fixed';
        badge.style.right = '20px';
        badge.style.top = '65px'; // Better spacing - 50px gap from mute button
        badge.style.zIndex = '9999';
        badge.style.background = 'linear-gradient(135deg,#22C55E,#16A34A)';
        badge.style.color = '#fff';
        badge.style.border = '1px solid rgba(255,255,255,0.25)';
        badge.style.borderRadius = '9999px';
        badge.style.padding = '8px 12px';
        badge.style.boxShadow = '0 10px 25px rgba(0,0,0,0.35)';
        badge.style.alignItems = 'center';
        badge.style.gap = '8px';
        badge.style.fontWeight = '700';
        badge.style.backdropFilter = 'blur(6px)';
        badge.style.cursor = 'pointer';
        badge.style.transition = 'all 0.2s ease';
        badge.innerHTML = `<i class="bx bxs-bell" style="font-size:16px"></i><span id="ui-unread-count">0</span>`;
        
        // Initially hide badge (only show when there are notifications)
        badge.style.display = 'none';
        
        // Hide on mobile (< 768px) 
        const handleBadgeResize = () => {
            const badgeElement = document.getElementById('ui-unread-badge');
            if (!badgeElement) return;
            
            if (window.innerWidth < 768) {
                badgeElement.style.display = 'none';
            } else {
                // Only show if there are notifications (badge will manage its own visibility)
                const count = badgeElement.querySelector('#ui-unread-count')?.textContent;
                if (count && parseInt(count) > 0) {
                    badgeElement.style.display = 'flex';
                }
            }
        };
        
        // Check on load and on resize
        handleBadgeResize();
        window.addEventListener('resize', handleBadgeResize);
        
        // Add hover effect
        badge.addEventListener('mouseenter', () => {
            badge.style.transform = 'scale(1.05)';
            badge.style.boxShadow = '0 12px 30px rgba(34, 197, 94, 0.4)';
        });
        
        badge.addEventListener('mouseleave', () => {
            badge.style.transform = 'scale(1)';
            badge.style.boxShadow = '0 10px 25px rgba(0,0,0,0.35)';
        });
        
        document.body.appendChild(badge);
    }
    return badge;
}

function incrementUiUnreadBadge() {
    const badge = ensureUiUnreadBadge();
    const span = badge.querySelector('#ui-unread-count');
    const current = parseInt(span?.textContent || '0', 10) || 0;
    span.textContent = String(current + 1);
    // Show badge when there are notifications
    if (badge) {
        badge.style.display = 'flex';
    }
}

function updateUiUnreadBadge(count) {
    const badge = document.getElementById('ui-unread-badge');
    if (badge && badge.querySelector('#ui-unread-count')) {
        badge.querySelector('#ui-unread-count').textContent = String(count);
        // Hide badge if count is 0
        if (count === 0) {
            badge.style.display = 'none';
        } else {
            badge.style.display = 'flex';
        }
    }
}

// Subtle beep without external assets (Web Audio API)
function playNotificationSound() {
    if (getMuteState()) return;
    const AudioCtx = window.AudioContext || window.webkitAudioContext;
    if (!AudioCtx) return;
    const ctx = new AudioCtx();
    const o = ctx.createOscillator();
    const g = ctx.createGain();
    o.type = 'sine';
    o.frequency.value = 880; // A5
    g.gain.setValueAtTime(0.0001, ctx.currentTime);
    g.gain.exponentialRampToValueAtTime(0.05, ctx.currentTime + 0.01);
    g.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.25);
    o.connect(g);
    g.connect(ctx.destination);
    o.start();
    o.stop(ctx.currentTime + 0.3);
    // Close context shortly to free resources
    setTimeout(() => ctx.close(), 400);
}

// Mute toggle (persisted)
function getMuteState() {
    try { return localStorage.getItem('ui_notify_muted') === '1'; } catch(_) { return false; }
}

function setMuteState(v) {
    try { localStorage.setItem('ui_notify_muted', v ? '1' : '0'); } catch(_) {}
}

function ensureMuteButton() {
    let btn = document.getElementById('ui-mute-toggle');
    if (!btn) {
        btn = document.createElement('button');
        btn.id = 'ui-mute-toggle';
        btn.type = 'button';
        btn.style.position = 'fixed';
        btn.style.right = '20px';
        btn.style.top = '20px';
        btn.style.zIndex = '10000';
        btn.style.background = 'linear-gradient(135deg,#6B7280,#374151)';
        btn.style.color = '#fff';
        btn.style.border = '1px solid rgba(255,255,255,0.25)';
        btn.style.borderRadius = '9999px';
        btn.style.padding = '8px 12px';
        btn.style.boxShadow = '0 10px 25px rgba(0,0,0,0.35)';
        btn.style.alignItems = 'center';
        btn.style.gap = '8px';
        btn.style.fontWeight = '700';
        btn.style.backdropFilter = 'blur(6px)';
        btn.style.cursor = 'pointer';
        btn.style.transition = 'all 0.2s ease';
        
        // Hide on mobile (< 768px)
        const handleResize = () => {
            if (window.innerWidth < 768) {
                btn.style.display = 'none';
            } else {
                btn.style.display = 'flex';
            }
        };
        
        // Check on load and on resize
        handleResize();
        window.addEventListener('resize', handleResize);
        
        // Add hover effect
        btn.addEventListener('mouseenter', () => {
            btn.style.transform = 'scale(1.05)';
            btn.style.boxShadow = '0 12px 30px rgba(107, 114, 128, 0.4)';
        });
        
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'scale(1)';
            btn.style.boxShadow = '0 10px 25px rgba(0,0,0,0.35)';
        });
        
        document.body.appendChild(btn);
        btn.addEventListener('click', () => {
            const muted = !getMuteState();
            setMuteState(muted);
            renderMuteButton(btn);
        });
    }
    renderMuteButton(btn);
    return btn;
}

function renderMuteButton(btn) {
    const muted = getMuteState();
    const icon = muted ? 'bxs-volume-mute' : 'bxs-volume-full';
    const label = muted ? 'Muted' : 'Sound On';
    btn.innerHTML = `<i class="bx ${icon}" style="font-size:16px"></i><span>${label}</span>`;
}

// Initialize floating controls after DOM ready
document.addEventListener('DOMContentLoaded', () => {
    try { ensureUiUnreadBadge(); } catch(_) {}
    try { ensureMuteButton(); } catch(_) {}
});

// Enhanced message handling for WhatsApp/Messenger-like experience
document.addEventListener('DOMContentLoaded', function() {
    // Update conversation list with new messages
    window.addEventListener('realtime-message', function(e) {
        const data = e.detail;
        if (!data || !data.from_user) return;

        // Update conversation list item with latest message preview
        const conversationItems = document.querySelectorAll('.conversation-item');
        conversationItems.forEach(item => {
            const userId = item.getAttribute('data-user-id');
            if (userId && userId == data.from_user.id) {
                const previewElement = item.querySelector('.conversation-preview');
                if (previewElement) {
                    previewElement.innerHTML = `<span class="message-status">✓</span> ${data.body.substring(0, 30)}`;
                }

                // Update or create unread count badge
                let unreadElement = item.querySelector('.unread-count');
                if (!unreadElement) {
                    const metaElement = item.querySelector('.conversation-meta');
                    if (metaElement) {
                        unreadElement = document.createElement('div');
                        unreadElement.className = 'unread-count';
                        unreadElement.textContent = '1';
                        metaElement.appendChild(unreadElement);
                    }
                } else {
                    const currentCount = parseInt(unreadElement.textContent) || 0;
                    unreadElement.textContent = currentCount + 1;
                }
            }
        });
    });

    // Update message status when delivered
    window.addEventListener('message-delivered', function(e) {
        const data = e.detail;
        if (!data || !data.message_id) return;

        const messageElement = document.querySelector(`[data-message-id="${data.message_id}"]`);
        if (messageElement) {
            const statusElement = messageElement.querySelector('.message-status');
            if (statusElement) {
                statusElement.textContent = '✓';
                statusElement.className = 'message-status delivered';
            }
        }
    });

    // Update message status when read
    window.addEventListener('message-read', function(e) {
        const data = e.detail;
        if (!data || !data.message_id) return;

        const messageElement = document.querySelector(`[data-message-id="${data.message_id}"]`);
        if (messageElement) {
            const statusElement = messageElement.querySelector('.message-status');
            if (statusElement) {
                statusElement.textContent = '✓✓';
                statusElement.className = 'message-status read';
            }
        }
    });

    // Conversation selection
    const conversationItems = document.querySelectorAll('.conversation-item');
    const chatArea = document.querySelector('.chat-area');

    conversationItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            conversationItems.forEach(i => i.classList.remove('active'));

            // Add active class to clicked item
            this.classList.add('active');

            // Show chat area on mobile
            if (chatArea) {
                chatArea.classList.add('active');
            }
        });
    });

    // Back button for mobile
    const backButton = document.querySelector('.back-button');
    if (backButton && chatArea) {
        backButton.addEventListener('click', function() {
            chatArea.classList.remove('active');
        });
    }

    // Message sending
    const messageForm = document.querySelector('.message-form');
    const messageInput = document.querySelector('.message-input');

    if (messageForm && messageInput) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const message = messageInput.value.trim();
            if (message) {
                // In a real app, you would send the message via AJAX
                // For now, we'll just clear the input
                messageInput.value = '';
                messageInput.focus();
            }
        });
    }

    // Auto-scroll to bottom of messages container
    const messagesContainer = document.querySelector('.messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Typing indicator
    if (messageInput) {
        let typingTimer;
        const typingDelay = 1000;

        messageInput.addEventListener('input', function() {
            // Show typing indicator (in a real app, you would send a typing event)

            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                // Hide typing indicator after delay
            }, typingDelay);
        });
    }
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
