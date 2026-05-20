<aside class="main-sidebar hidden-print">
    <section class="sidebar">
        <ul class="sidebar-menu">
            {{-- DASHBOARD --}}
            @if (Auth::user()->hasRole('administrator'))
                {!! Html::sidebarOption('dashboard', 'Painel Resumo', route('admin.dashboard'), null, 'fas fa-fw fa-poll-h') !!}
            @endif

            {{-- Prestadores --}}
            @if (Auth::user()->hasRole('prestador'))
                {!! Html::sidebarOption('my-orders', 'Meus Pedidos', route('provider.orders.index'), null, 'fas fa-fw fa-shopping-cart') !!}
                {!! Html::sidebarTreeOpen('services-group', 'Serviços', 'fas fa-fw fa-briefcase') !!}
                {!! Html::sidebarOption('my-services', 'Meu Serviço', route('provider.services.index')) !!}
                {!! Html::sidebarOption('available-services', 'Disponíveis', route('provider.services.available')) !!}
                {!! Html::sidebarTreeClose() !!}
            @endif

            {{-- ENTITIES --}}
            @if (Auth::user()->hasPermissionTo(['admin_customers', 'admin_users', 'admin_services', 'admin_orders', 'admin_payments', 'admin_comissions', 'admin_comments', 'admin_categorys']))
                {!! Html::sidebarTreeOpen('customers-group', 'Entidades', 'fas fa-fw fa-users') !!}
                {!! Html::sidebarOption('customers', 'Clientes', route('admin.customers.index'), 'admin_customers') !!}
                {!! Html::sidebarOption('users', 'Colaboradores', route('admin.users.index'), 'admin_users') !!}
                {!! Html::sidebarTreeClose() !!}
                {!! Html::sidebarTreeOpen('operacional-group', 'Operacional', 'fas fa-fw fa-concierge-bell') !!}
                {!! Html::sidebarOption('categorys', 'Categorias', route('admin.categorys.index'), 'admin_categorys') !!}
                {!! Html::sidebarOption('services', 'Serviços', route('admin.services.index'), 'admin_services') !!}
                {!! Html::sidebarOption('orders', 'Pedidos', route('admin.orders.index'), 'admin_orders') !!}
                {!! Html::sidebarOption('comments', 'Comentários', route('admin.comments.index'), 'admin_comments') !!}
                {!! Html::sidebarOption('notifications', 'Notificações', route('admin.notifications.index'), null) !!}
                {!! Html::sidebarOption('chats', 'Chats', route('admin.chats.index'), null) !!}
                {!! Html::sidebarTreeClose() !!}
                {!! Html::sidebarTreeOpen('Financial-group', 'Financeiro', 'fas fa-fw fa-money-bill') !!}
                {!! Html::sidebarOption('payments', 'Pagamentos', route('admin.payments.index'), 'admin_payments') !!}
                {!! Html::sidebarOption('comissions', 'Comissões', route('admin.comissions.index'), 'admin_comissions') !!}
                {!! Html::sidebarTreeClose() !!}
            @endif

        </ul>
    </section>
</aside>
