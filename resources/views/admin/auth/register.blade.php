@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 @if($errors->any()) animated shake @endif">
                {{ Form::open(array('route' => 'admin.register.store', 'class' => 'login100-form validate-form')) }}
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
                    @if($errors->any())
                        <div class="help-block">
                            <i class="zmdi zmdi-alert-circle"></i> Verifique os dados do formulário.
                        </div>
                    @endif
                    <div class="wrap-input100 validate-input" data-validate="Insira o nome.">
                        {{ Form::text('name', old('name'), ['class' => 'input100 nospace']) }}
                        <span class="focus-input100" data-placeholder="Nome"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Insira um e-mail válido.">
                        {{ Form::text('email', old('email'), ['class' => 'input100 nospace']) }}
                        <span class="focus-input100" data-placeholder="E-mail"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Insira a palavra-passe.">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        {{ Form::password('password', ['class' => 'input100 nospace']) }}
                        <span class="focus-input100" data-placeholder="Palavra-Passe"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Confirme a palavra-passe.">
                        {{ Form::password('password_confirmation', ['class' => 'input100 nospace']) }}
                        <span class="focus-input100" data-placeholder="Confirmar Palavra-Passe"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                Registar Prestador
                            </button>
                        </div>
                    </div>

                    <div class="text-center" style="padding-top: 20px; line-height: 0;">
                        <span class="txt1">Já tem conta?</span>
                        <br/>
                        <a href="{{ route('admin.login') }}" class="txt2">
                            Iniciar Sessão
                        </a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
