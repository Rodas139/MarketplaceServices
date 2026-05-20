@section('title')
    Detalhes do Pedido #{{ $order->id }}
@stop

@section('account-content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border" style="display: block; padding-left: 0;">
                    <div style="margin-bottom: 10px; margin-left: 0; padding-left: 0;">
                        <a href="{{ route('account.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Voltar
                        </a>
                        @if($order->status == 'accepted')
                            <a href="{{ route('account.chat.conversation.show', $order) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-comments"></i> Chat
                            </a>
                        @endif
                    </div>
                    <h3 class="box-title">Pedido Nº {{ $order->id }}</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 style="margin-bottom: 20px;"><strong>Informações do Pedido</strong></h4>
                            <dl class="dl-horizontal">
                                <dt>Nº Pedido:</dt>
                                <dd>{{ $order->id }}</dd>

                                <dt>Data:</dt>
                                <dd>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</dd>

                                <dt>Hora:</dt>
                                <dd>{{ $order->hour ? $order->hour . ':00' : '-' }}</dd>

                                <dt>Status:</dt>
                                <dd>
                                    <span class="label {{ $order->status == 'pending' ? 'label-warning' : ($order->status == 'declined' ? 'label-danger' : 'label-success') }}">
                                        {{ \App\Helpers\OrderHelper::getStatusInPortuguese($order->status) }}
                                    </span>
                                </dd>

                                <dt>Descrição:</dt>
                                <dd>{{ $order->description ?? '-' }}</dd>
                            </dl>
                        </div>

                        <div class="col-md-6">
                            <h4 style="margin-bottom: 20px;"><strong>Informações do Prestador</strong></h4>
                            <dl class="dl-horizontal">
                                <dt>Nome:</dt>
                                <dd>{{ $order->user->name ?? 'N/A' }}</dd>

                                @if($order->user && $order->user->service)
                                    <dt>Serviço:</dt>
                                    <dd>{{ $order->user->service->name ?? '-' }}</dd>

                                    <dt>Preço/Hora:</dt>
                                    <dd>€ {{ number_format($order->user->service->price, 2, ',', '.') }}</dd>

                                    <dt>Preço Total:</dt>
                                    <dd>€ {{ number_format($order->hour * $order->user->service->price, 2, ',', '.') }}</dd>
                                @endif

                                <dt>Email:</dt>
                                <dd>{{ $order->user->email ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                @if($order->status == 'completed')
                    <div class="box-footer" style="border-top: 1px solid #ddd; padding: 15px;">
                        <h4 style="margin-bottom: 20px;"><strong>Deixar um Comentário</strong></h4>
                        
                        <form action="{{ route('account.orders.comment.store', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="rating">Classificação (1-5 estrelas):</label>
                                <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" required>
                                    <option value="">Selecione uma classificação</option>
                                    <option value="1" >1 - Muito Ruim</option>
                                    <option value="2" >2 - Ruim</option>
                                    <option value="3" >3 - Aceitável</option>
                                    <option value="4" >4 - Bom</option>
                                    <option value="5" >5 - Excelente</option>
                                </select>
                                @error('rating')
                                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="comment">Comentário:</label>
                                <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="5" placeholder="Deixe o seu comentário..." required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Foto (opcional):</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-comment"></i> Enviar Comentário
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop