@extends('account.layouts.master')

@section('title')
Criar Conta
@stop

@section('account-content')
    <div class="login-simple login-simple--register">
        <div class="login-card">
            <div class="login-card-header">
                <h2>Criar Conta</h2>
                <span class="login-subtitle">Preencha os seus dados.</span>
            </div>

            <div class="home-alerts">
                @include('partials.alerts')
            </div>

            {!! Form::open(['route' => 'account.register.store', 'method' => 'POST']) !!}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', 'Nome', ['class' => 'control-label']) !!}
                {!! Form::text('name', old('name'), ['class' => 'form-control nospace login-input', 'placeholder' => 'Seu nome completo', 'required']) !!}
                @if ($errors->has('name'))
                    <span class="help-block">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {!! Form::label('email', 'E-mail', ['class' => 'control-label']) !!}
                {!! Form::email('email', old('email'), ['class' => 'form-control nospace login-input', 'placeholder' => 'seu@email.com', 'required']) !!}
                @if ($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                {!! Form::label('address', 'Endereço', ['class' => 'control-label']) !!}
                {!! Form::text('address', old('address'), ['class' => 'form-control nospace login-input', 'placeholder' => 'Sua morada', 'required']) !!}
                @if ($errors->has('address'))
                    <span class="help-block">{{ $errors->first('address') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                {!! Form::label('phone', 'Telemóvel (9 dígitos)', ['class' => 'control-label']) !!}
                {!! Form::text('phone', old('phone'), ['class' => 'form-control nospace login-input', 'placeholder' => '912345678', 'required', 'maxlength' => '9']) !!}
                @if ($errors->has('phone'))
                    <span class="help-block">{{ $errors->first('phone') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {!! Form::label('password', 'Palavra-passe (mínimo 8 caracteres)') !!}
                {!! Form::password('password', ['class' => 'form-control login-input', 'required']) !!}
                @if ($errors->has('password'))
                    <span class="help-block">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                {!! Form::label('password_confirmation', 'Confirmar Palavra-passe') !!}
                {!! Form::password('password_confirmation', ['class' => 'form-control login-input', 'required']) !!}
                @section('styles')
                    {{ Html::style('/assets/css/auth.css') }}
                @stop
            color: #1f2937;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 600px) {
            .login-card {
                padding: 20px;
            }
        }
    </style>
@stop
