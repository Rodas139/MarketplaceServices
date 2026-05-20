@extends('admin.layouts.master')

@section('title')
Chat Pedido #{{ $order->id }}
@stop

@section('content-header')
Chat Pedido #{{ $order->id }}
@stop

@section('breadcrumb')
<li><a href="{{ route('provider.orders.index') }}">Meus Pedidos</a></li>
<li class="active">Chat #{{ $order->id }}</li>
@stop

@section('content')
<div class="box">
    <div class="box-header with-border">
        <a href="{{ route('provider.orders.show', $order) }}" class="btn btn-default btn-sm" style="margin-right: 10px;">
            <i class="fa fa-arrow-left"></i> Voltar
        </a>
        <h3 class="box-title">Chat com Cliente</h3>
    </div>
    <div class="box-body">
        <div class="chat-thread">
            @forelse ($chat->messages as $message)
                <div class="chat-message {{ $message->sender_type === 'provider' ? 'chat-message-provider' : 'chat-message-customer' }}">
                    <div class="chat-bubble">
                        {{ $message->message }}
                        <div class="chat-time">
                            {{ $message->created_at ? $message->created_at->format('d/m/Y H:i') : '' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-muted">Sem mensagens ainda.</div>
            @endforelse
        </div>

        <form method="POST" action="{{ route('provider.chat.store', $order) }}" class="chat-form">
            @csrf
            <div class="form-group">
                <textarea name="message" class="form-control" rows="3" placeholder="Escreve uma mensagem..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</div>

@stop

@section('styles')
    {{ Html::style('/assets/admin/css/provider-chat.css') }}
@stop
