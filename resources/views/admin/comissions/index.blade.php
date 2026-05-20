@section('title')
Comissões
@stop

@section('content-header')
Comissões
@stop

@section('breadcrumb')
<li class="active">Comissões</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <a href="{{ route('admin.comissions.create') }}" class="btn btn-success btn-sm">
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
                        <li style="margin-bottom: 5px;" class="col-xs-12">
                            <strong>Pagamentos</strong><br/>
                            <div class="w-100px">
                                {{ Form::select('id_payment', ['' => 'Todos'] + \App\Models\Payment::pluck('id', 'id')->toArray(), Request::has('id_payment') ? Request::get('id_payment') : '', array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th>Nº</th>
                                <th>Pagamento Nº</th>
                                <th>Valor(%)</th>
                                <th>Ganho</th>
                                <th class="w-70px">Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.comissions.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-confirm-title="Apagar comissões selecionadas"><i class="fa fa-trash"></i> Apagar Selecionados</button>
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
                {data: 'id', name: 'id'},
                {data: 'payment_id', name: 'payment_id'},
                {data: 'value', name: 'value'},
                {data: 'gain', name: 'gain'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[1, "desc"]],
            ajax: {
                url: "{{ route('admin.comissions.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.id_payment = $('[name="id_payment"]').val();
                },
                beforeSend: function () { Datatables.cancelDatatableRequest(oTable) },
                complete: function () { Datatables.complete() }
            },
            rowCallback: function(row, data, index) {
                $('input[name="select"]', row).val(data.id);
            }
        });

        $('[name="select-all"]').on('change', function () {
            $('input[name="select"]').prop('checked', $(this).prop('checked')).trigger('change');
        });

        $(document).on('change', 'input[name="select"]', function () {
            var selected = $('input[name="select"]:checked').length;
            if (selected > 0) {
                $('.selected-rows-action').removeClass('hide');
            } else {
                $('.selected-rows-action').addClass('hide');
            }
        });

        $('.filter-datatable').on('change', function () {
            oTable.draw();
        });

        $('.btn-filter-datatable').on('click', function (e) {
            e.preventDefault();
            $(this).closest('.datatable-filters').find('.datatable-filters-extended').toggleClass('hide').toggleClass('active');
        });
    });
</script>
@stop
