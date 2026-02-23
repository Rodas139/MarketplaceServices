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
                <ul class="list-unstyled m-0">
                    <li>
                        @if ($auth)
                        <div class="btn-group btn-username pull-right" role="group">
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
                        @endif
                    </li>
                    @if ($auth)
                        <li style="float: right; max-width: 100px; margin-right: 50px; margin-top: 5px;">
                            <div class="dropdown">
                                <div class="dropdown-toggle" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if ($auth->current_lang === 'pt')
                                        <img style="width: 50px" src="{{ asset('assets/img/pt-flag.jpg') }}" alt="Portuguese Flag">
                                    @else
                                        <img style="width: 50px" src="{{ asset('assets/img/en_flag.png') }}" alt="English Flag">
                                    @endif
                                </div>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown" style="text-align: center;">
                                    <a class="dropdown-item" href="{{ route('account.update.language', 'pt') }}" data-method="get">
                                        <img style="width: 50px" src="{{ asset('assets/img/pt-flag.jpg') }}" alt="Portuguese Flag">
                                    </a>
                                    <a class="dropdown-item" href="{{ route('account.update.language', 'en') }}" data-method="get">
                                        <img style="width: 50px" src="{{ asset('assets/img/en_flag.png') }}" alt="English Flag">
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    
</header>
