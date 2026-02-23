@section('title')

@stop

@section('metatags')
    <meta name="description" content="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:image" content="{{ trans('seo.og-image.url') }}">
    <meta property="og:image:width" content="{{ trans('seo.og-image.width') }}">
    <meta property="og:image:height" content="{{ trans('seo.og-image.height') }}">
    <meta name="robots" content="index, follow">
@stop

@section('account-content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="text-primary text-center m-b-30 m-t-15">Área de Cliente</h2>
            <div class="home-alerts">
                @include('partials.alerts')
            </div>
            {{ Form::open(['route' => 'account.login.submit', 'method' => 'POST']) }}
            <div class="form-group">
                {{ Form::label('email', 'E-mail', ['class' => 'control-label']) }}
                {{ Form::text('email', null, ['class' => 'form-control nospace', 'autofocus', 'required']) }}
            </div>
            <div class="form-group" style="position: relative">
                {{ Form::label('password', 'Palavra-passe') }}
                <span class="btn-show-pass">
                    <i class="far fa-eye"></i>
                </span>
                {{ Form::password('password', ['class' => 'form-control', 'required']) }}
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label style="padding: 0; margin-bottom: 5px;">
                        <input style=" margin-left: 0px" type="checkbox" name="remember">
                        <span style="margin-left: 15px;">{{ trans('auth.remember') }}</span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-block btn-primary">
                    Iniciar Sessão
                </button>
                {{-- <div class="spacer-15"></div>
                <a href="#" data-toggle="modal" data-target="#reset-password" class="reset-password">
                    <i class="fas fa-lock"></i> {{ trans('auth.forgot.title') }}
                </a> --}}
            </div>
            {{ Form::close() }}

            <img class="img-responsive" src="{{ asset('assets/img/default/portugal2020.png') }}" alt="">
        </div>
    </div>

    @include('auth.passwords.email')
@stop

@section('styles')
    <style>
        @media (max-width: 767px) {
            body {
                padding-top: 0 !important;
            }
        }
    </style>
@stop