@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 @if($errors->has('email')) animated shake @endif">
                {{ Form::open(array('route' => 'admin.login.submit', 'class' => 'login100-form validate-form')) }}
                <span class="login100-form-title" style="padding-bottom: 48px">
                    @if(File::exists(public_path() . '/assets/img/logo/logo.svg'))
                        <img src="{{ asset('assets/img/logo/logo.svg') }}"/>
                    @elseif(File::exists(public_path() . '/assets/img/logo/logo_sm.png'))
                        <img src="{{ asset('assets/img/logo/logo_sm.png') }}" onerror="this.src = '{{ asset('assets/img/default/logo/logo_sm.png') }}'"/>
                    @else
                        <h4>{{ Setting::get('company_name') }}</h4>
                    @endif

                </span>
                <div class="main-block">
                    @if($errors->has('email'))
                    <div class="help-block">
                        <i class="zmdi zmdi-alert-circle"></i> {{ $errors->first('email') }}
                    </div>
                    @endif
                    <div class="wrap-input100 validate-input" data-validate="Insira um e-mail válido.">
                        {{ Form::text('email', null, ['class' => 'input100 nospace']) }}
                        <span class="focus-input100" data-placeholder="E-mail"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Insira a palavra-passe.">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        {{ Form::password('password', ['class' => 'input100 nospace']) }}
                        <span class="focus-input100" data-placeholder="Palavra-Passe"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                Iniciar Sessão
                            </button>
                        </div>
                    </div>

                    <div class="text-center" style="padding-top: 20px; line-height: 0;">
                        <span class="txt1">Não consegue Iniciar Sessão?</span>
                        <br/>
                        <a href="{{ route('admin.password.forgot') }}" class="txt2">
                            Recuperar Palavra-Passe
                        </a>
                        <br/>
                        <a href="{{ route('admin.register') }}" class="txt2">
                            Registar Prestador
                        </a>
                    </div>
                </div>

                <div class="text-center submit-loading" style="padding-top: 0; line-height: 0; display: none">
                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                    <h4>Bem-vindo de volta!</h4>
                    <p>Estamos a preparar a sua sessão.</p>
                    <div style="margin-bottom: 30px"></div>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                A Iniciar Sessão...
                            </button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
