@extends('admin.layouts.master')

@section('title')
Serviços Disponíveis
@stop

@section('content-header')
Serviços Disponíveis
@stop

@section('breadcrumb')
<li><a href="{{ route('provider.services.index') }}">Meus Serviços</a></li>
<li class="active">Disponíveis</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <a href="{{ route('provider.services.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Novo Serviço
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
                            <strong>Categoria</strong><br/>
                            <div class="w-150px">
                                {{ Form::select('category_id', ['' => 'Todas'] + \App\Models\Category::pluck('name', 'id')->toArray(), Request::has('category_id') ? Request::get('category_id') : '', array('class' => 'form-control input-sm filter-datatable select2', 'style' => 'background-color: transparent; border: none; border-bottom: none; box-shadow: none;')) }}
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Nome do Serviço</th>
                                <th>Categoria</th>
                                <th>Preço Por Hora</th>
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
                {data: 'name', name: 'name'},
                {data: 'category', name: 'category'},
                {data: 'price', name: 'price'},
                {data: 'status', name: 'status'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[0, "asc"]],
            ajax: {
                url: "{{ route('provider.services.datatable.available') }}",
                type: "POST",
                data: function (d) {
                    d.category_id = $('[name="category_id"]').val();
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
