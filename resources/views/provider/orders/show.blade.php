@extends('admin.layouts.master')

@section('title')
Detalhes do Pedido
@stop

@section('content-header')
Detalhes do Pedido #{{ $order->id }}
@stop

@section('breadcrumb')
<li><a href="{{ route('provider.orders.index') }}">Meus Pedidos</a></li>
<li class="active">Pedido #{{ $order->id }}</li>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Informações do Pedido</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Número do Pedido</strong></label>
                            <p>{{ $order->id }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Cliente</strong></label>
                            <p>{{ $order->customer->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Data</strong></label>
                            <p>{{ $order->date ? $order->date->format('d/m/Y') : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Hora</strong></label>
                            <p>{{ $order->hour ? str_pad($order->hour, 2, '0', STR_PAD_LEFT) . ':00' : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Serviço</strong></label>
                            <p>{{ $order->service->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Status</strong></label>
                            <p>
                                <span class="label {{ $order->status == 'pending' ? 'label-warning' : ($order->status == 'declined' ? 'label-danger' : ($order->status == 'accepted' ? 'label-info' : 'label-success')) }}">
                                    {{ \App\Helpers\OrderHelper::getStatusInPortuguese($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Descrição</strong></label>
                    <p>{{ $order->description ?? '-' }}</p>
                </div>

                @if($order->status === 'completed' && $earnings)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Ganho</strong></label>
                                <p>{{ number_format($earnings['provider_gain'], 2, ',', '.') }}€</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div style="margin-top: 20px;">
                    <a href="{{ route('provider.orders.index') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                    @if($order->status === 'accepted')
                        <a href="{{ route('provider.chat.show', $order) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-comments"></i> Chat
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
    </div>
</div>

<!-- Comentários - Apenas mostrados se o pedido está concluído -->
@if($order->status === 'completed')
    <div class="row">
        <div class="col-md-12">
            <div class="box @if($order->comments->isEmpty()) box-warning @endif">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-comments"></i> Comentários do Cliente
                    </h3>
                </div>
                <div class="box-body">
                    @if($order->comments->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa fa-info"></i> Não há comentários ainda para este pedido.
                        </div>
                    @else
                        @foreach($order->comments as $comment)
                            <div class="comment-item" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <strong style="font-size: 16px;">{{ $comment->customer->name ?? 'Cliente' }}</strong>
                                    <small style="color: #999;">
                                        {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </div>

                                @if($comment->rating)
                                    <div style="margin-bottom: 10px;">
                                        <span style="font-size: 14px;">
                                            @for($i = 0; $i < $comment->rating; $i++)
                                                <i class="fa fa-star" style="color: #ffc107;"></i>
                                            @endfor
                                            @for($i = $comment->rating; $i < 5; $i++)
                                                <i class="fa fa-star-o" style="color: #ddd;"></i>
                                            @endfor
                                        </span>
                                        <span style="margin-left: 10px; color: #666;">({{ $comment->rating }}/5)</span>
                                    </div>
                                @endif

                                <p style="margin: 10px 0; line-height: 1.6; color: #333;">
                                    {{ $comment->comment }}
                                </p>

                                @if($comment->image)
                                    <div style="margin-top: 10px;">
                                        <a href="{{ asset($comment->image) }}" data-toggle="modal" data-target="#imageModal{{ $comment->id }}" style="cursor: pointer;">
                                            <img src="{{ asset($comment->image) }}" alt="Imagem do comentário" style="max-width: 300px; max-height: 300px; border-radius: 4px; cursor: pointer; border: 2px solid #ddd;">
                                        </a>
                                    </div>

                                    <!-- Modal para visualizar imagem -->
                                    <div class="modal fade" id="imageModal{{ $comment->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title">Imagem do Comentário</h4>
                                                </div>
                                                <div class="modal-body" style="text-align: center;">
                                                    <img src="{{ asset($comment->image) }}" alt="Imagem do comentário" style="max-width: 100%; height: auto; border-radius: 4px;">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Comentários</h3>
                </div>
                <div class="box-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info"></i> Os comentários apenas aparecem quando o pedido é concluído.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@stop
