@extends('layouts.account')

@section('title')
Área de Cliente
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
    <div class="login-simple">
        <div class="login-card">
            <div class="login-card-header">
                <h2>Area de Cliente</h2>
            </div>

            <div class="home-alerts">
                @include('partials.alerts')
            </div>

            {{ Form::open(['route' => 'account.login.submit', 'method' => 'POST']) }}
            <div class="form-group">
                {{ Form::label('email', 'E-mail', ['class' => 'control-label']) }}
                {{ Form::text('email', null, ['class' => 'form-control nospace login-input', 'autofocus', 'required', 'id' => 'login-email', 'placeholder' => 'seu@email.com']) }}
            </div>
            <div class="form-group" style="position: relative">
                {{ Form::label('password', 'Palavra-passe') }}
                <span class="btn-show-pass">
                    <i class="far fa-eye"></i>
                </span>
                {{ Form::password('password', ['class' => 'form-control login-input', 'required', 'placeholder' => '••••••••']) }}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-block btn-primary login-submit">
                    Iniciar Sessao
                </button>
            </div>

            <div class="login-links">
                <a href="{{ route('account.password.forgot.index') }}">
                    <i class="fas fa-key"></i> Esqueceu a palavra-passe?
                </a>
                <span class="login-dot">•</span>
                <a href="{{ route('account.register.index') }}">
                    <i class="fas fa-user-plus"></i> Criar Conta
                </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>

    @include('auth.passwords.email')
@stop

@section('styles')
    {{ Html::style('/assets/css/auth.css') }}
@stop