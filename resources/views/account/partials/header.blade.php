@php
    $auth = Auth::guard('customer')->user();
@endphp

<header>
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-sm-4 center-middle-xs">
                <a href="{{ route('account.index') }}">
                    <span class="helper"></span>
                    <img src="{{ asset('assets/img/logo/logo_white_sm.png') }}" class="header-logo"/>
                </a>
            </div>
            <div class="col-xs-6 col-sm-8">
                <ul class="list-unstyled m-0 pull-right" style="display: flex; align-items: center; gap: 10px;">
                    @if ($auth)
                        {{-- NOTIFICATIONS --}}
                        @include('account.partials.bell')

                        {{-- USER ACCOUNT --}}
                        <li>
                            <div class="btn-group btn-username" role="group">
                                <button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if($auth->filepath)
                                        <img src="{{ asset($auth->getThumb()) }}" onerror="this.src='{{ asset("assets/img/default/avatar.png") }}'"/>
                                    @else
                                        <img src="{{ asset('assets/img/default/avatar.png') }}"/>
                                    @endif
                                    <span class="username hidden-xs">
                                        {{ $auth->name }}<br/>
                                        <small>{{ $auth->email }}</small>
                                    </span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('account.profile') }}">
                                            <i class="fas fa-fw fa-user"></i> Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('account.change.password') }}" data-toggle="modal" data-target="#modal-remote">
                                            <i class="fas fa-fw fa-unlock-alt"></i> {{trans('account/orders.change-password')}}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('account.logout') }}">
                                            <i class="fas fa-fw fa-power-off"></i> {{trans('account/orders.log-out')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    
</header>
