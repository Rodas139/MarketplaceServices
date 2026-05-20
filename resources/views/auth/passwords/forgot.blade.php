@extends('account.layouts.master')

@section('title')
Recuperar Palavra-Passe
@stop

@section('account-content')
    <div class="login-simple">
        <div class="login-card">
            <div class="login-card-header">
                <h2>Recuperar Palavra-Passe</h2>
    
            </div>

            <div class="home-alerts">
                @include('partials.alerts')
            </div>
            
            {{ Form::open(['route' => 'account.password.email', 'method' => 'POST']) }}
            
            <div class="form-group">
                {{ Form::label('email', 'E-mail', ['class' => 'control-label']) }}
                {{ Form::text('email', null, ['class' => 'form-control nospace login-input', 'placeholder' => 'seu@email.com', 'autofocus', 'required', 'id' => 'reset-email']) }}
            </div>

            @if($errors->has('email'))
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fa fa-check"></i> {{ session('status') }}
                </div>
            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-block btn-primary login-submit">
                    <i class="fa"></i> Confirmar E-mail
                </button>
            </div>

            <div class="login-links">
                <a href="{{ route('account.login') }}">
                    <i class="fa fa-arrow-left"></i> Voltar ao Login
                </a>
            </div>

            {{ Form::close() }}
        </div>
    </div>
@stop

@section('styles')
    @section('styles')
        {{ Html::style('/assets/css/auth.css') }}
    @stop
        }
