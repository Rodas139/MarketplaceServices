@section('title')
Categorias
@stop

@section('content-header')
Categorias
@stop

@section('breadcrumb')
<li class="active">Categorias</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <a href="{{ route('admin.categorys.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </li>
                </ul>
                <div class="datatable-filters-extended m-t-5 hide {{ Request::has('filter') ? 'active' : null }}" data-target="#datatable">
                    <ul class="list-inline pull-left">
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th class="w-70px">Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.categorys.selected.destroy')) }}
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
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[1, "asc"]],
            ajax: {
                url: "{{ route('admin.categorys.datatable') }}",
                type: "POST",
                beforeSend: function () { Datatables.cancelDatatableRequest(oTable) },
                complete: function () { Datatables.complete() }
            }
        });

        $('.btn-filter-datatable').on('click', function(){
            $($(this).closest('ul').data('target')).next('.datatable-filters-extended').toggleClass('active');
        });

        $(document).on('change', '[name="filter-datatable"]', function() {
            oTable.draw();
        });
    });
</script>
@stop
