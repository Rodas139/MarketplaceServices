@section('title')
Chats
@stop

@section('content-header')
Chats
@stop

@section('breadcrumb')
<li class="active">Chats</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-filter-datatable btn-default">
                                <i class="fas fa-filter"></i> Filtrar <i class="fas fa-angle-down"></i>
                            </button>
                        </div>
                    </li>
                </ul>
                <div class="datatable-filters-extended m-t-5 hide" data-target="#datatable">
                    <ul class="list-inline pull-left">
                        <li style="margin-bottom: 5px;" class="col-xs-6">
                            <strong>Cliente</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('customer_id', ['' => 'Todos'] + \App\Models\Customer::pluck('name', 'id')->toArray(), Request::has('customer_id') ? Request::get('customer_id') : '', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                        <li style="margin-bottom: 5px;" class="col-xs-6">
                            <strong>Prestador</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('provider_id', ['' => 'Todos'] + \App\Models\User::role('prestador')->pluck('name', 'id')->toArray(), Request::has('provider_id') ? Request::get('provider_id') : '', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th>Pedido Nº</th>
                                <th>Cliente</th>
                                <th>Prestador</th>
                                <th>Assunto</th>
                                <th>Data</th>
                                <th class="w-70px">Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.chats.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-confirm-title="Apagar selecionados">
                        <i class="fa fa-trash"></i> Apagar Selecionados
                    </button>
                    {{ Form::close() }}
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
                {data: 'select', name: 'select', orderable: false, searchable: false},
                {data: 'order', name: 'order'},
                {data: 'customer', name: 'customer'},
                {data: 'provider', name: 'provider'},
                {data: 'subject', name: 'subject'},
                {data: 'created_at', name: 'created_at'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[5, "desc"]],
            ajax: {
                url: "{{ route('admin.chats.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.customer_id = $('[name="customer_id"]').val();
                    d.provider_id = $('[name="provider_id"]').val();
                },
                beforeSend: function () { Datatables.cancelDatatableRequest(oTable) },
                complete: function () { Datatables.complete() }
            }
        });

        $('.btn-filter-datatable').on('click', function(){
            $($(this).closest('ul').data('target')).next('.datatable-filters-extended').toggleClass('active');
        });

        $(document).on('change', '.filter-datatable', function() {
            oTable.draw();
        });
    });
</script>
@stop
