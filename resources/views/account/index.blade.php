@section('title')
    Meus Pedidos
@stop

@section('account-content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border" style="display: block; padding-left: 0;">
                    <h3 class="box-title">Meus Pedidos</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('account.orders.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Novo Pedido
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Filtrar por Status:</label>
                        <form method="GET" style="display: flex; gap: 10px;">
                            <select name="status" class="form-control" style="max-width: 200px;" onchange="this.form.submit()">
                                <option value="">Todos os Pedidos</option>
                                <option value="pending">Pendente</option>
                                <option value="declined">Recusado</option>
                                <option value="accepted">Aceite</option>
                                <option value="completed">Concluído</option>
                            </select>
                            @if(request('status'))
                                <a href="{{ route('account.index') }}" class="btn btn-default">
                                    <i class="fa fa-times"></i> Limpar
                                </a>
                            @endif
                        </form>
                    </div>
                    @include('account.partials.orders-table')
                </div>
            </div>
        </div>
    </div>
@stop
