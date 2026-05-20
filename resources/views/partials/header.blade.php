<header>
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">
                    <img src="{{ asset('assets/img/logo/logo.svg') }}" height="39" alt="projetoBase" />
                </a>
            </div>
            <div class="navbar-collapse collapse">
                @if (Auth::guard('customer')->user())
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="">Pastas</a>
                        </li>

                        <li>
                            <a href="{{ route('account.inquiries.index') }}">Inquéritos</a>
                        </li>
                    </ul>
                @endif

                <ul class="nav navbar-nav">
                    @if (Auth::guard('customer')->guest())
                        <li id="menu-login">
                            <a href="{{ route('account.login') }}" title="login">Login</a>
                        </li>
                    @else
                        <li class="dropdown" id="menu-logout">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">{{ @Auth::guard('customer')->user()->name }}
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Editar os meus dados</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('account.logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>
