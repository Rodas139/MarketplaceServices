@extends('admin.layouts.master')

@section('title')
Notificações
@stop

@section('content-header')
Notificações
<small>Editar Notificação</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.notifications.index') }}">Notificações</a>
</li>
<li class="active">Editar Notificação</li>
@stop

@section('content')
@include('admin.notifications.partials.form')
@stop
