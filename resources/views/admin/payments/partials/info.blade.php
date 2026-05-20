<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $action }}</h3>
            </div>
            <div class="box-body">
                {!! Form::model($payment, $formOptions) !!}

                    <div class="form-group is-required">
                        <label for="id_order">Pedido</label>
                        {!! Form::select('id_order', ['' => 'Selecione um pedido'] + $orders, null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="total">Total</label>
                        {!! Form::number('total', null, ['class' => 'form-control', 'placeholder' => '0.00', 'step' => '0.01', 'min' => '0', 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        <label for="method">Método de Pagamento</label>
                        {!! Form::select('method', ['' => 'Selecione um método', 'mbway' => 'MBWay', 'transferencia' => 'Transferência', 'dinheiro_vivo' => 'Dinheiro Vivo'], null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        @if($payment->id)
                            {!! Form::select('status', ['pending' => 'Pendente', 'paid' => 'Pago'], null, ['class' => 'form-control']) !!}
                        @else
                            {!! Form::select('status', ['pending' => 'Pendente', 'paid' => 'Pago'], null, ['class' => 'form-control']) !!}
                        @endif
                    </div>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    Guardar
                </button>
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('[name="id_order"]').on('change', function () {
            var orderId = $(this).val();
            
            if (orderId) {
                $.ajax({
                    url: "{{ route('admin.payments.get.order.data', ':id') }}".replace(':id', orderId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('[name="total"]').val(data.total);
                    },
                    error: function () {
                        console.log('Erro ao buscar dados do pedido');
                    }
                });
            } else {
                $('[name="total"]').val('');
            }
        });
    });
</script>
@stop
