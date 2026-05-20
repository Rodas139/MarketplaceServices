@if($orders->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Prestador</th>
                    <th>Serviço</th>
                    <th>Data</th>
                    <th>Horas</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->service->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td>
                        <td>{{ $order->hour ? $order->hour . ':00' : '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($order->description, 50) }}</td>
                        <td>
                            <span class="label {{ $order->status == 'pending' ? 'label-warning' : ($order->status == 'declined' ? 'label-danger' : ($order->status == 'accepted' ? 'label-info' : 'label-success')) }}">
                                {{ \App\Helpers\OrderHelper::getStatusInPortuguese($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-eye"></i> Ver
                            </a>
                            @if(isset($isOrdersPage) && $isOrdersPage)
                                @php
                                    $payment = \App\Models\Payment::where('id_order', $order->id)->first();
                                @endphp
                                @if($order->status == 'accepted' && (!$payment || $payment->status !== 'paid'))
                                    <a href="{{ route('account.payments.show', $order) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-credit-card"></i> Pagar
                                    </a>
                                @elseif($payment && $payment->status === 'paid')
                                    <span class="label label-success">
                                        <i class="fa fa-check"></i> Pago
                                    </span>
                                @endif
                            @else
                                @if($order->status == 'accepted')
                                    <a href="{{ route('account.payments.show', $order) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-credit-card"></i> Pagar
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="icon fa fa-info"></i> Sem pedidos registados.
        <a href="{{ route('account.orders.create') }}">Criar um novo pedido</a>
    </div>
@endif
