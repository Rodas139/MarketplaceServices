<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $action }}</h3>
            </div>
            <div class="box-body">
                {!! Form::model($comission, $formOptions) !!}

                    <div class="form-group is-required">
                        <label for="id_payment">Pagamento</label>
                        {!! Form::select('id_payment', ['' => 'Selecione um pagamento'] + $payments, null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="value">Valor(%)</label>
                        {!! Form::number('value', null, ['class' => 'form-control', 'placeholder' => '0.00', 'step' => '0.01', 'min' => '0', 'max' => '100']) !!}
                    </div>

                    <div class="form-group">
                        <label for="gain">Ganho</label>
                        {!! Form::number('gain', null, ['class' => 'form-control', 'placeholder' => '0.00', 'step' => '0.01', 'min' => '0', 'readonly']) !!}
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
        function calculateGain() {
            var paymentId = $('[name="id_payment"]').val();
            var valueInput = $('[name="value"]').val();
            var value = valueInput ? parseFloat(valueInput) : 0;
            
            if (!paymentId || value <= 0) {
                $('[name="gain"]').val('0.00');
                return;
            }

            $.ajax({
                url: "{{ route('admin.comissions.get.payment.data', ':id') }}".replace(':id', paymentId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var total = parseFloat(data.total) || 0;
                    var gain = (total * value) / 100;
                    $('[name="gain"]').val(gain.toFixed(2));
                },
                error: function (xhr) {
                    console.error('Erro ao buscar dados do pagamento:', xhr);
                    $('[name="gain"]').val('0.00');
                }
            });
        }


        $('[name="id_payment"]').on('change', function () {
            calculateGain();
        });


        $('[name="value"]').on('input', function () {
            calculateGain();
        });


        $('[name="value"]').on('blur', function () {
            calculateGain();
        });

        calculateGain();
    });
</script>
@stop
