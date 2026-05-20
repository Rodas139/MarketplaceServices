<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $action }}</h3>
            </div>
            <div class="box-body">
                {!! Form::model($order, $formOptions) !!}

                    <div class="form-group is-required">
                        <label for="id_customer">Cliente</label>
                        {!! Form::select('id_customer', ['' => 'Selecione um cliente'] + $customers, null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="user_id">Prestador</label>
                        {!! Form::select('user_id', ['' => 'Selecione um prestador'] + $users, null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        <label for="service_id">Serviço</label>
                        {!! Form::select('service_id', ['' => 'Selecione um serviço'] + $services, null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="date">Data</label>
                        {!! Form::text('date', null, ['class' => 'form-control datepicker', 'placeholder' => 'dd/mm/yyyy']) !!}
                    </div>

                    <div class="form-group">
                        <label for="hour">Horas</label>
                        {!! Form::number('hour', null, ['class' => 'form-control', 'placeholder' => '0-23', 'min' => '0', 'max' => '23']) !!}
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição</label>
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Descrição do pedido', 'rows' => '4']) !!}
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        @if($order->id)
                            {!! Form::select('status', ['pending' => 'Pendente', 'accepted' => 'Aceite', 'declined' => 'Recusada', 'completed' => 'Concluída'], null, ['class' => 'form-control']) !!}
                        @else
                            {!! Form::select('status', ['pending' => 'Pendente', 'accepted' => 'Aceite', 'declined' => 'Recusada', 'completed' => 'Concluída'], null, ['class' => 'form-control']) !!}
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
        $('[name="date"]').datepicker({
            dateFormat: 'dd/mm/yyyy'
        });
        @if($order->date)
            $('[name="date"]').datepicker("setDate", new Date("{{ $order->date->format('Y-m-d') }}"));
        @endif
    });
</script>
@stop
