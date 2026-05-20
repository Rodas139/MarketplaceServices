import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    auth: {
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
        },
    },
});

/**
 * Listen to global notifications
 */
window.Echo.channel('notifications-global')
    .listen('NotificationCreated', (e) => {
        console.log('🔔 Notificação recebida:', e);
        

        addNotificationToUI(e);
        

        showNotificationToast(e.title, e.message, e.type);
        

        updateUnreadCount();
    });

/**
 * Adds notification to dropdown
 */
function addNotificationToUI(notification) {
    const container = document.getElementById('notifications-container');
    
    if (!container) return;
    

    const emptyMessage = container.querySelector('.text-muted');
    if (emptyMessage) emptyMessage.remove();
    
    const html = `
        <li class="notification-item ${notification.type}" data-id="${notification.id}">
            <a href="#">
                <div class="notification-header">
                    <strong>${notification.title}</strong>
                    <small class="text-muted">${new Date(notification.alert_at).toLocaleString('pt-PT')}</small>
                </div>
                <p class="notification-message">${notification.message}</p>
                <small class="notification-sender">Por: ${notification.sender_name}</small>
            </a>
        </li>
    `;
    
    container.insertAdjacentHTML('afterbegin', html);
    

    const items = container.querySelectorAll('.notification-item');
    if (items.length > 20) {
        items[items.length - 1].remove();
    }
}

/**
 * Displays real-time notifications
 */
function showNotificationToast(title, message, type = 'info') {

    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        const toastHTML = `
            <div class="toast" role="alert">
                <div class="toast-header bg-${type}">
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        const toastContainer = document.getElementById('toastContainer') || 
            (() => {
                const div = document.createElement('div');
                div.id = 'toastContainer';
                div.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(div);
                return div;
            })();
        
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        
        const toastElem = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElem);
        toast.show();
        

        toastElem.addEventListener('hidden.bs.toast', () => toastElem.remove());
    } else {

        console.log(`[${type.toUpperCase()}] ${title}: ${message}`);
    }
}

/**
 * Update unread notification counter
 */
function updateUnreadCount() {
    const badge = document.getElementById('notification-badge');
    
    if (!badge) return;
    

    fetch('/admin/notifications/api/recent')
        .then(res => res.json())
        .then(data => {

            const unreadCount = data.filter(n => !n.is_read_by_user).length;
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'inline' : 'none';
        })
        .catch(err => {
            // Error
        });
}

/**
 * Renders notifications in the dropdown
 */
function renderNotifications(data) {
    const container = document.getElementById('notifications-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!Array.isArray(data) || data.length === 0) {
        container.innerHTML = `
            <li class="text-center p-3 text-muted">
                <small>Nenhuma notificação</small>
            </li>
        `;
        return;
    }
    

    data.forEach(notification => {
        const senderName = (notification.sender && notification.sender.name) ? notification.sender.name : 'Sistema';
        const formattedDate = notification.alert_at_formatted || formatDate(notification.alert_at);
        const isRead = notification.is_read_by_user ? 'read' : '';
        const readIcon = notification.is_read_by_user ? '<i class="fas fa-check-double text-muted" style="font-size: 0.85rem;"></i>' : '';
        
        const html = `
            <li class="notification-item ${notification.type} ${isRead}" data-id="${notification.id}" style="${notification.is_read_by_user ? 'opacity: 0.6;' : ''}">
                <a href="#">
                    <div class="notification-header">
                        <strong style="font-size: 1.05rem;">${notification.title} ${readIcon}</strong>
                        <small class="text-muted" style="font-size: 0.95rem;">${formattedDate}</small>
                    </div>
                    <p class="notification-message" style="font-size: 0.98rem;">${notification.message}</p>
                    <small class="notification-sender" style="font-size: 0.92rem;">Por: ${senderName}</small>
                </a>
            </li>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });
}

/**
 * Remove badge and reset count when dropdown is opened
 */
function clearNotificationBadge() {
    const badge = document.getElementById('notification-badge');
    if (badge) {
        badge.style.display = 'none';
        const count = document.getElementById('notification-count');
        if (count) {
            count.textContent = '0';
        }
    }
}

/**
 * Fetch recent notifications from the server
 */
function fetchNotifications() {
    return fetch('/admin/notifications/api/recent')
        .then(res => res.json())
        .catch(err => {
            return [];
        });
}

/**
 * Formats a date string for display
 */
function formatDate(dateString) {
    try {
        const date = new Date(dateString);
        return date.toLocaleString('pt-PT', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch(e) {
        return 'Data inválida';
    }
}

/**
 * Marks all notifications as read when dropdown is opened
 */
function markNotificationsAsRead() {
    const token = document.querySelector('meta[name="csrf-token"]');
    
    if (!token) {
        return;
    }
    
    fetch('/admin/notifications/api/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token.content,
        }
    })
    .catch(err => console.error('Erro ao marcar como lidas:', err));
}

/**
 * Loads notifications when dropdown is opened
 */
function loadNotificationsOnDropdownOpen() {
    const dropdown = document.getElementById('notificationsDropdown');
    const toggle = dropdown?.querySelector('.dropdown-toggle');
    const menu = dropdown?.querySelector('.dropdown-menu');
    
    if (!toggle || !dropdown || !menu) {
        return;
    }
    

    if (typeof $ !== 'undefined' && $.fn.dropdown) {
        $(dropdown).on('show.bs.dropdown', function() {

            markNotificationsAsRead();
            

            fetchNotifications().then(data => renderNotifications(data));
        });
    } else {

        let isLoading = false;
        
        toggle.addEventListener('click', function(e) {
            if (isLoading) return;
            
            isLoading = true;
            menu.style.display = 'block';
            

            markNotificationsAsRead();

            fetchNotifications().then(data => {
                renderNotifications(data);
                isLoading = false;
            }).catch(() => {
                isLoading = false;
            });
        });
    }
}

/**
 * Initializes notifications system on page load
 */
function initNotifications() {

    updateUnreadCount();
    
    loadNotificationsOnDropdownOpen();
    
    fetchNotifications();
}

/**
 * Initialize notifications when DOM is ready
 */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNotifications);
} else {
    initNotifications();
}

export { addNotificationToUI, showNotificationToast, updateUnreadCount };
