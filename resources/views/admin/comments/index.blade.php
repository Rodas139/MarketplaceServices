@section('title')
Comentários
@stop

@section('content-header')
Comentários
@stop

@section('breadcrumb')
<li class="active">Comentários</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <a href="{{ route('admin.comments.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </li>
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
                        <li style="margin-bottom: 5px;" class="col-xs-4">
                            <strong>Cliente</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('id_customer', ['' => 'Todos'] + \App\Models\Customer::pluck('name', 'id')->toArray(), Request::has('id_customer') ? Request::get('id_customer') : '', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                        <li style="margin-bottom: 5px;" class="col-xs-4">
                            <strong>Pedido</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('id_order', ['' => 'Todos'] + \App\Models\Order::pluck('id', 'id')->toArray(), Request::has('id_order') ? Request::get('id_order') : '', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                        <li style="margin-bottom: 5px;" class="col-xs-4">
                            <strong>Avaliação</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('rating', ['' => 'Todas', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'], Request::has('rating') ? Request::get('rating') : '', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th>Cliente</th>
                                <th>Pedido Nº</th>
                                <th>Comentário</th>
                                <th>Avaliação</th>
                                <th>Imagem</th>
                                <th class="w-70px">Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.comments.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-confirm-title="Apagar selecionados"><i class="fa fa-trash"></i> Apagar Selecionados</button>
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
                {data: 'customer', name: 'customer'},
                {data: 'order', name: 'order'},
                {data: 'comment', name: 'comment'},
                {data: 'rating', name: 'rating'},
                {data: 'image', name: 'image'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[1, "asc"]],
            ajax: {
                url: "{{ route('admin.comments.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.id_customer = $('[name="id_customer"]').val();
                    d.id_order = $('[name="id_order"]').val();
                    d.rating = $('[name="rating"]').val();
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
