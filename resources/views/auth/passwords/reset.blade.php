@extends('account.layouts.master')

@section('title')
    {{ trans('auth.reset.title') }} |
@stop

@section('metatags')
    <meta name="robots" content="noindex, nofollow">
@stop

@section('account-content')
    <div class="login-simple">
        <div class="login-card">
            <div class="login-card-header">
                <h2>Repôr Palavra-passe</h2>
                <span class="login-subtitle">Defina uma nova palavra-passe.</span>
            </div>

            {{ Form::open(['route' => 'account.password.reset.submit']) }}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {{ Form::label('email', 'E-mail') }}
                {{ Form::email('email', $email, ['class' => 'form-control login-input', 'autofocus', 'readonly']) }}
                @if ($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {{ Form::label('password', 'Nova Palavra-passe') }}
                {{ Form::password('password', ['class' => 'form-control login-input']) }}
                @if ($errors->has('password'))
                    <span class="help-block">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                {{ Form::label('password_confirmation', 'Confirmar Nova Palavra-passe') }}
                {{ Form::password('password_confirmation', ['class' => 'form-control login-input']) }}
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary login-submit">
                    {{ trans('auth.reset.title') }}
                </button>
            </div>
@stop

@section('styles')
    {{ Html::style('/assets/css/auth.css') }}
@stop
