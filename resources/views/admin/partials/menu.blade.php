<aside class="main-sidebar hidden-print">
    <section class="sidebar">
        <ul class="sidebar-menu">
            {{-- DASHBOARD --}}
            {!! Html::sidebarOption('dashboard', 'Painel Resumo', route('admin.dashboard'), null, 'fas fa-fw fa-poll-h') !!}

            {{-- ENTITIES --}}
            @if (Auth::user()->hasPermissionTo(['admin_customers', 'admin_users']))
                {!! Html::sidebarTreeOpen('customers-group', 'Entidades', 'fas fa-fw fa-users') !!}
                {!! Html::sidebarOption('customers', 'Clientes', route('admin.customers.index'), 'admin_customers') !!}
                {!! Html::sidebarOption('users', 'Colaboradores', route('admin.users.index'), 'admin_users') !!}
                {!! Html::sidebarTreeClose() !!}
            @endif

            {{-- SETTINGS --}}
            @if (Auth::user()->hasPermissionTo(['admin_roles']))
                {!! Html::sidebarTreeOpen('administration', 'Configurações', 'fas fa-fw fa-cog') !!}
                {!! Html::sidebarOption('admin_roles', 'Perfis e Permissões', route('admin.roles.index'), 'admin_roles') !!}
                {!! Html::sidebarTreeClose() !!}
            @endif

        </ul>
    </section>
</aside>
