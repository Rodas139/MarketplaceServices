<li class="dropdown" id="notificationsDropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fas fa-bell"></i>
        <span class="label label-danger" id="notification-badge" style="display: none;">
            <span id="notification-count">0</span>
        </span>
    </a>
    
    <ul class="dropdown-menu" style="width: 400px; max-height: 500px; overflow-y: auto;">
        <li class="header">
            <h6 class="mb-0">Notificacoes</h6>
        </li>
        
        <li id="notifications-container">
            <div class="text-center p-3 text-muted">
                <small>Nenhuma notificacao</small>
            </div>
        </li>
        
        <li class="divider"></li>
    </ul>
</li>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateUnreadCount();
        fetchNotifications();
        setupNotificationDropdown();
    });

    function updateUnreadCount() {
        const badge = document.getElementById('notification-badge');
        if (!badge) return;
        
        fetch('{{ route("provider.notifications.api.recent") }}')
            .then(res => res.json())
            .then(data => {
                const unreadCount = data.filter(n => !n.is_read_by_user).length;
                document.getElementById('notification-count').textContent = unreadCount;
                badge.style.display = unreadCount > 0 ? 'inline' : 'none';
            })
            .catch(() => {});
    }

    function setupNotificationDropdown() {
        const dropdown = document.getElementById('notificationsDropdown');
        if (!dropdown) return;

        if (window.jQuery) {
            jQuery('#notificationsDropdown').on('show.bs.dropdown', function() {
                markNotificationsAsRead().finally(function() {
                    setTimeout(function() {
                        fetchNotifications();
                        updateUnreadCount();
                    }, 200);
                });
            });
            return;
        }

        dropdown.addEventListener('show.bs.dropdown', function() {
            markNotificationsAsRead().finally(function() {
                setTimeout(function() {
                    fetchNotifications();
                    updateUnreadCount();
                }, 200);
            });
        });
    }

    function fetchNotifications() {
        fetch('{{ route("provider.notifications.api.recent") }}')
            .then(res => res.json())
            .then(data => renderNotifications(data))
            .catch(err => console.error('Erro ao buscar notificacoes:', err));
    }

    function renderNotifications(data) {
        const container = document.getElementById('notifications-container');
        if (!container) return;
        
        container.innerHTML = '';
        
        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = '<li class="text-center p-3 text-muted"><small>Nenhuma notificacao</small></li>';
            return;
        }
        
        data.forEach(notification => {
            const senderName = (notification.sender && notification.sender.name) ? notification.sender.name : 'Sistema';
            const formattedDate = notification.alert_at_formatted || formatDate(notification.alert_at);
            const isRead = notification.is_read_by_user ? 'read' : '';
            const readIcon = notification.is_read_by_user ? '<i class="fas fa-check-double text-muted" style="font-size: 0.85rem;"></i>' : '';
            
            const html = `
                <li class="notification-item ${notification.type} ${isRead}" style="${notification.is_read_by_user ? 'opacity: 0.6;' : ''}">
                    <a href="#">
                        <div class="notification-header">
                            <strong>${notification.title} ${readIcon}</strong>
                            <small class="text-muted">${formattedDate}</small>
                        </div>
                        <p class="notification-message">${notification.message}</p>
                        <small class="notification-sender">Por: ${senderName}</small>
                    </a>
                </li>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    function markNotificationsAsRead() {
        return fetch('{{ route("provider.notifications.api.mark-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(() => {
            const badge = document.getElementById('notification-badge');
            const count = document.getElementById('notification-count');
            if (count) count.textContent = '0';
            if (badge) badge.style.display = 'none';
            updateUnreadCount();
        })
        .catch(err => console.error('Erro ao marcar como lido:', err));
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString();
    }
</script>
