@extends('account.layouts.master')

@section('account-content')
<div class="box">
    <div class="box-header with-border">
        <a href="{{ route('account.orders.show', $order) }}" class="btn btn-default btn-sm" style="margin-right: 10px;">
            <i class="fa fa-arrow-left"></i> Voltar
        </a>
        <h3 class="box-title">Chat Pedido #{{ $order->id }}</h3>
    </div>
    <div class="box-body">
        <div class="chat-thread">
            @forelse ($chat->messages as $message)
                <div class="chat-message {{ $message->sender_type === 'customer' ? 'chat-message-customer' : 'chat-message-provider' }}">
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

        <form method="POST" action="{{ route('account.chat.conversation.store', $order) }}" class="chat-form">
            @csrf
            <div class="form-group">
                <textarea name="message" class="form-control" rows="3" placeholder="Escreve uma mensagem..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</div>

@endsection
