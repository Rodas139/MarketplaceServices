@section('title')
Clientes
@stop

@section('content-header')
Clientes
@stop

@section('breadcrumb')
<li class="active">Clientes</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-success btn-sm">
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
                    <li class="fltr-primary w-170px">
                        <strong>Gestor</strong><br class="visible-xs"/>
                        <div class="pull-left form-group-sm w-120px">
                            {{ Form::select('manager', ['' => 'Todos', '-1' => 'Sem Gestor'] + $managers, Request::has('manager') ? Request::get('manager') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                        </div>
                    </li>
                </ul>
                <div class="datatable-filters-extended m-t-5 hide {{ Request::has('filter') ? 'active' : null }}" data-target="#datatable">
                    <ul class="list-inline pull-left">
                        {{-- <li style="margin-bottom: 5px;"  class="col-xs-6">
                            <strong>Área Cliente</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('login', ['' => 'Todos', '1' => 'Com login', '0' => 'Sem login'], Request::has('login') ? Request::get('login') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li> --}}
                        <li style="margin-bottom: 5px;"  class="col-xs-6">
                            <strong>Estado</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('active', ['' => 'Todos', '1' => 'Ativo', '0' => 'Inativo'], Request::has('active') ? Request::get('active') : '1', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th class="w-1"></th>
                                <th></th>
                                <th></th>
                                <th class="w-1">Nº</th>
                                <th>Nome</th>
                                <th class="w-120px">Contactos</th>
                                <th class="w-70px">Categoria</th>
                                <th class="w-70px">Gestor</th>
                                <th class="w-1" title="Acesso área cliente"><i class="fas fa-mobile"></i></th>
                                <th class="w-70px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.customers.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-confirm-title="Apagar selecionados"><i class="fa fa-trash"></i> Apagar Selecionados</button>
                    {{ Form::close() }}
                    {{-- <a href="{{ route('admin.customers.export') }}" class="btn btn-sm btn-default m-l-5" data-action-url="datatable-action-url">
                        <i class="fa fa-fw fa-file-excel-o"></i> Exportar selecionados
                    </a> --}}
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
                {data: 'photo', name: 'photo', orderable: false, searchable: false},
                {data: 'id', name: 'id', visible: false},
                {data: 'email', name: 'email', visible: false},
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'phone', name: 'phone'},
                {data: 'category_id', name: 'category_id', searchable: false},
                {data: 'manager_id', name: 'manager_id', searchable: false},
                {data: 'access_account', name: 'access_account', searchable: false},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
                {data: 'mobile', name: 'mobile', visible: false},
                {data: 'vat', name: 'vat', visible: false},
                {data: 'billing_name', name: 'billing_name', visible: false},
                {data: 'billing_email', name: 'billing_email', visible: false},
                {data: 'source_name', name: 'source_name', visible: false},
            ],
            order: [[3, "desc"]],
            ajax: {
                url: "{{ route('admin.customers.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.manager   = $('[name="manager"]').val(),
                    d.category  = $('[name="category"]').val(),
                    d.login     = $('[name="login"]').val(),
                    d.active    = $('[name="active"]').val()
                },
                beforeSend: function () { Datatables.cancelDatatableRequest(oTable) },
                complete: function () { Datatables.complete() }
            }
        });

        $('.filter-datatable').on('change', function (e) {
            oTable.draw();
            e.preventDefault();

            var exportUrl = Url.removeQueryString($('[data-toggle="export-url"]').attr('href'));
            exportUrl = exportUrl + '?' + Url.getQueryString(Url.current())
            $('[data-toggle="export-url"]').attr('href', exportUrl);
        });
    });

    $(document).on('change', '[name=district]', function(){
        var district = $(this).val();
        $('.load-county').show();

        $.post('{{ route('admin.customers.get.counties') }}', {district:district}, function (data) {
            $('[name=county]').empty().select2({data:data}).trigger('change');
        }).always(function () {
            $('.load-county').hide();
        })
    })
</script>
@stop
