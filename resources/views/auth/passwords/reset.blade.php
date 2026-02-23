@section('title')
    {{ trans('auth.reset.title') }} |
@stop

@section('metatags')
    <meta name="robots" content="noindex, nofollow">
@stop

@section('account-content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h2 class="text-primary m-b-30 m-t-15">Repôr Palavra-passe</h2>

            {{ Form::open(['route' => 'account.password.reset.submit']) }}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {{ Form::label('email', 'E-mail') }}
                {{ Form::email('email', null, ['class' => 'form-control', 'autofocus']) }}
                @if ($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {{ Form::label('password', 'Nova Palavra-passe') }}
                {{ Form::password('password', ['class' => 'form-control']) }}
                @if ($errors->has('password'))
                    <span class="help-block">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                {{ Form::label('password_confirmation', 'Confirmar Nova Palavra-passe') }}
                {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary">
                    {{ trans('auth.reset.title') }}
                </button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
