@extends('admin.layouts.master')

@section('title')
Notificações
@stop

@section('content-header')
Notificações
@stop

@section('breadcrumb')
<li class="active">Notificações</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-success btn-sm">
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
                <div class="datatable-filters-extended m-t-5 hide" data-target="#datatable">
                    <ul class="list-inline pull-left">
                        <li style="margin-bottom: 5px;" class="col-xs-6">
                            <strong>Tipo</strong><br/>
                            <div class="w-100px">
                                <select name="type" class="form-control input-sm filter-datatable select2">
                                    <option value="">Todos</option>
                                    <option value="info">Info</option>
                                    <option value="success">Sucesso</option>
                                    <option value="warning">Aviso</option>
                                    <option value="error">Erro</option>
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1"><input type="checkbox" name="select-all" id="select-all" class="select-all"></th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Enviado por</th>
                                <th>Data</th>
                                <th class="w-70px">Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    <form method="POST" action="{{ route('admin.notifications.mass-destroy') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" id="selected-ids" name="ids" value="">
                        <button type="submit" class="btn btn-sm btn-danger" data-action="confirm" data-confirm-title="Apagar selecionados">
                            <i class="fa fa-trash"></i> Apagar Selecionados
                        </button>
                    </form>
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
                {data: 'title', name: 'title'},
                {data: 'type', name: 'type'},
                {data: 'sender', name: 'sender'},
                {data: 'alert_at', name: 'alert_at'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[4, "desc"]],
            ajax: {
                url: "{{ route('admin.notifications.datatable') }}",
                type: "POST",
                data: function(d) {
                    d.type = $('[name="type"]').val();
                },
                beforeSend: function () { Datatables.cancelDatatableRequest(oTable) },
                complete: function () { Datatables.complete() }
            }
        });

        $('.btn-filter-datatable').on('click', function(){
            $($(this).closest('ul').data('target')).next('.datatable-filters-extended').toggleClass('active');
        });

        $(document).on('change', '[name="type"]', function() {
            oTable.draw();
        });
    });
</script>
@stop

