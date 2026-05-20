@extends('admin.layouts.master')

@section('title')
Meus Pedidos
@stop

@section('content-header')
Meus Pedidos
@stop

@section('breadcrumb')
<li class="active">Meus Pedidos</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline pull-left" data-target="#datatable">
                    <li>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-filter-datatable btn-default">
                                <i class="fas fa-filter"></i> Filtrar <i class="fas fa-angle-down"></i>
                            </button>
                        </div>
                    </li>
                </ul>
                <div class="datatable-filters-extended m-t-5 hide {{ Request::has('filter') ? 'active' : null }}" data-target="#datatable">
                    <ul class="list-inline pull-left">
                        <li style="margin-bottom: 5px; display: inline-block; margin-right: 20px;">
                            <strong>Status</strong><br/>
                            <div style="min-width: 150px; max-width: 300px;">
                                {{ Form::select('status', ['' => 'Todos', 'pending' => 'Pendente', 'accepted' => 'Aceite', 'declined' => 'Recusada', 'completed' => 'Concluída'], Request::has('status') ? Request::get('status') : '', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Horas</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th class="w-70px">Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var oTable = $('#datatable').DataTable({
            columns: [
                {data: 'id', name: 'id'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'order_date', name: 'order_date'},
                {data: 'hour', name: 'hour'},
                {data: 'description', name: 'description'},
                {data: 'status', name: 'status'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[2, "desc"]],
            ajax: {
                url: "{{ route('provider.orders.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.status = $('[name="status"]').val();
                },
                beforeSend: function () { Datatables.cancelDatatableRequest(oTable) },
                error: function () { }
            },
            serverSide: true,
            processing: true,
            stateSave: false,
            search: false,
            initComplete: function() {
                Datatables.onInitComplete = false;
            }
        });

        $('[name="status"]').on('change', function() {
            oTable.draw();
        });

    });
</script>
@stop
