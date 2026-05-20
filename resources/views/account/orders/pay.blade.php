@section('title')
    Pagamento do Pedido
@stop

@section('account-content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box">
            <div class="box-header with-border" style="display: block; padding-left: 0;">
                <div style="margin-bottom: 10px; margin-left: 0; padding-left: 0;">
                    <a href="{{ route('account.index') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                </div>
                <h3 class="box-title">Pagamento do Pedido #{{ $order->id }}</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Prestador de Serviço</strong></label>
                            <p>{{ $order->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Data do Pedido</strong></label>
                            <p>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Horas</strong></label>
                            <p>{{ $order->hour ?? 0 }} h</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>Preço por Hora</strong></label>
                            <p>{{ $order->user && $order->user->service ? number_format($order->user->service->price, 2, ',', '.') . '€' : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-warning">
                            <div class="box-body">
                                <h4><strong>Total a Pagar: {{ number_format($order->total, 2, ',', '.') }}€</strong></h4>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $payment = \App\Models\Payment::where('id_order', $order->id)->first();
                    $isPaid = $payment && $payment->status === 'paid';
                @endphp

                @if($isPaid)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> <strong>Pagamento já foi realizado!</strong>
                                <p>Método: <strong>{{ $payment->method === 'mbway' ? 'MBway' : 'Transferência Bancária' }}</strong></p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label><strong>Método de Pagamento</strong></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-primary">
                                    <div class="box-body text-center">
                                        <i class="fa fa-mobile" style="font-size: 32px;"></i>
                                        <h4>MBway</h4>
                                        <form action="{{ route('account.payments.process', ['order' => $order->id, 'method' => 'mbway']) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa"></i> Pagar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-success">
                                    <div class="box-body text-center">
                                        <i class="fa fa-university" style="font-size: 32px;"></i>
                                        <h4>Transferência Bancária</h4>
                                    <form action="{{ route('account.payments.process', ['order' => $order->id, 'method' => 'transferencia_bancaria']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-exchange"></i> Transferir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
