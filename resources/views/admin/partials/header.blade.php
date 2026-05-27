@if (Session::has('source_user_id'))
    <div class="remote-login-warning">
        <i class="fas fa-exclamation-triangle"></i> Sessão iniciada como {{ Auth::user()->name }}. <a
            href="{{ route('admin.users.remote-logout', Session::get('source_user_id')) }}">Voltar à minha sessão</a>
    </div>
@endif

@if (Setting::get('maintenance_mode'))
    <div class="remote-login-warning">
        <i class="fas fa-exclamation-triangle"></i> Sistema em modo de Manutenção.</a>
    </div>
@endif

<header class="main-header">
    <a href="{{ Auth::user()->hasRole('prestador') ? route('provider.orders.index') : route('admin.dashboard') }}" class="logo">
        <span class="logo-mini">
            <img src="{{ asset(config('app.logo_square')) }}"
                onerror="this.src = '{{ asset('assets/img/logo/logo_square_xs.png') }}'" />
        </span>
        <span class="logo-lg">
            <img src="{{ asset(config('app.logo_xs')) }}"
                onerror="this.src = '{{ asset('assets/img/logo/logo_white_xs.png') }}'" />
        </span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{-- SETTINGS --}}
                @if (Auth::user()->isAdmin())
                    {{-- @if (Auth::user()->ability(Config::get('permissions.role.admin'), 'admin_roles,admin_settings,log_errors,modules,terminal,email_accounts')) --}}
                    <li class="dropdown user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span><i class="fas fa-wrench"></i> <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header bold">Definições e Registo da Aplicação</li>
                            <li>
                                <ul class="options-menu">
                                    <li>
                                        <a href="{{ route('admin.logs.errors.index') }}">
                                            <i class="fas fa-fw fa-exclamation-triangle text-red"></i> Registo de Erros
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- NOTIFICATIONS --}}
                @if (Auth::user()->hasRole('prestador'))
                    @include('provider.notifications.bell')
                @else
                    @include('admin.notifications.bell')
                @endif

                {{-- USER ACCOUNT --}}
                <!-- User Account Menu -->
                <li class="dropdown user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><i class="fa fa-user"></i> {{ Auth::user()->name }} <i
                                class="fa fa-angle-down"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header bold">{{ Auth::user()->name }}</li>
                        <li>
                            <ul class="options-menu">
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#account-password">
                                        <i class="fa fa-lock"></i> Alterar Palavra-passe
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.logout') }}"><i class="fa fa-power-off"></i> Terminar
                                        Sessão</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                {{-- LANGUAGE
                <li class="dropdown user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="text-uppercase">{{ App::getLocale() }}</span> <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header bold">Língua do Sistema</li>
                        <li>
                            <ul class="options-menu">
                                @foreach (trans('languages') as $key => $locale)
                                <li>
                                    <a href="#">
                                        <i class="flag-icon flag-icon-{{ $key }}"></i> {{ trans('languages.'.$key) }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </li> --}}
                <li>
                    <a href="{{ route('admin.logout') }}" data-toggle="tooltip" title="Terminar Sessão"
                        data-placement="bottom">
                        <i class="fa fa-power-off fa-lg"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
