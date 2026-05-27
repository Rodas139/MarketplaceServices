@extends('account.layouts.master')

@section('title')
    Notificações
@stop

@section('account-content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fas fa-bell"></i> Notificações
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div id="notifications-container" style="max-height: 600px; overflow-y: auto;">
                        <p class="text-muted text-center">
                            <i class="fas fa-spinner fa-spin"></i> Carregando notificações...
                        </p>
                    </div>
                </div>

                <div class="box-footer text-center">
                    <small class="text-muted">
                        Mostrando notificações dos últimos 7 dias
                    </small>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
        });

        /**
         * Search and load notifications via API
         */
        function loadNotifications() {
            fetch('{{ route("account.notifications.api.recent") }}')
                .then(res => res.json())
                .then(data => renderNotifications(data))
                .catch(err => console.error('Erro ao carregar notificações:', err));
        }

        /**
         * Render notifications list in the dropdown
         */
        function renderNotifications(data) {
            const container = document.getElementById('notifications-container');
            if (!container) return;

            container.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-3 text-muted">
                        <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                        <p style="margin-top: 10px;">Nenhuma notificação</p>
                    </div>
                `;
                return;
            }

            data.forEach(notification => {
                const senderName = (notification.sender && notification.sender.name) ? notification.sender.name : 'Sistema';
                const formattedDate = notification.alert_at_formatted || new Date(notification.alert_at).toLocaleString();
                const isRead = notification.is_read_by_user ? 'read' : '';
                const readIcon = notification.is_read_by_user ? '<i class="fas fa-check-double text-muted" style="font-size: 0.9rem;"></i>' : '';

                const html = `
                    <div class="notification-item ${notification.type} ${isRead}" data-id="${notification.id}" style="${notification.is_read_by_user ? 'opacity: 0.6;' : ''}">
                        <a href="#">
                            <div class="notification-header">
                                <strong style="font-size: 1.2rem;">${notification.title} ${readIcon}</strong>
                                <small class="text-muted" style="font-size: 1rem; white-space: nowrap; margin-left: 10px;">${formattedDate}</small>
                            </div>
                            <p class="notification-message" style="font-size: 1.1rem;">${notification.message}</p>
                            <small class="notification-sender" style="font-size: 1rem;">Por: ${senderName}</small>
                        </a>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });


            markNotificationsAsRead();
        }

        /**
         * Mark all notifications
         */
        function markNotificationsAsRead() {
            fetch('{{ route("account.notifications.api.mark-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .catch(err => console.error('Erro ao marcar como lido:', err));
        }
    </script>
@stop
