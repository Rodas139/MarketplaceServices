@section('title')
    Colaboradores
@stop

@section('content-header')
    Colaboradores
@stop

@section('breadcrumb')
    <li class="active">Colaboradores</li>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-all">
                        <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                            <li>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
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
                        <div class="datatable-filters-extended m-t-5 hide {{ Request::has('filter') ? 'active' : null }}"
                            data-target="#datatable">
                            <ul class="list-inline pull-left">
                                <li style="margin-bottom: 5px;" class="col-xs-6">
                                    <strong>Estado</strong><br />
                                    <div class="w-100px">
                                        {{ Form::select('active',['' => 'Todos', '1' => 'Ativo', '0' => 'Inativo'],Request::has('active') ? Request::get('active') : '1',['class' => 'form-control input-sm filter-datatable select2']) }}
                                    </div>
                                </li>
                                <li style="margin-bottom: 5px;" class="col-xs-6">
                                    <strong>Login Gestão</strong><br />
                                    <div class="w-100px">
                                        {{ Form::select('login_admin',['' => 'Todos', '1' => 'Sim', '0' => 'Não'],Request::has('login_admin') ? Request::get('login_admin') : null,['class' => 'form-control input-sm filter-datatable select2']) }}
                                    </div>
                                </li>
                                <li class="w-195px">
                                    <strong>Perfil</strong><br class="visible-xs" />
                                    <div class="w-140px ">
                                        {{ Form::select('role', ['' => 'Todos'] + $roles, Request::has('role') ? Request::get('role') : null, ['class' => 'form-control input-sm filter-datatable select2']) }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                        <th></th>
                                        <th class="w-1"></th>
                                        <th class="w-1">Código</th>
                                        <th>Nome</th>
                                        <th class="w-120px">Contactos</th>
                                        <th class="w-1">Perfil</th>
                                        <th class="w-1"><i class="fas fa-fw fa-laptop" data-toggle="tooltip"
                                                title="Acesso à área de gestão"></i></th>
                                        <th class="w-1">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="selected-rows-action hide">
                            {{ Form::open(['route' => 'admin.users.selected.destroy']) }}
                            <button class="btn btn-sm btn-danger" data-action="confirm" data-title="Apagar selecionados"><i
                                    class="fas fa-trash-alt"></i> Apagar Selecionados</button>
                            {{ Form::close() }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('styles')
    <style>
        .ip-isp {
            overflow: hidden;
            text-overflow: ellipsis;
            width: 165px;
            height: 65px;
        }

    </style>
@stop

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var oTable = $('#datatable').DataTable({
                columns: [{
                        data: 'select',
                        name: 'select',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code',
                        visible: false
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id',
                        'class': 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'login_admin',
                        name: 'login_admin',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        visible: false
                    },
                    {
                        data: 'fullname',
                        name: 'fullname',
                        visible: false
                    },
                ],
                order: [
                    [3, "desc"]
                ],
                ajax: {
                    url: "{{ route('admin.users.datatable') }}",
                    type: "POST",
                    data: function(d) {
                        d.role = $('select[name=role]').val();
                        d.active = $('select[name=active]').val();
                        d.agency = $('select[name=agency]').val();
                        d.workgroup = $('select[name=workgroup]').val();
                        d.login_app = $('select[name=login_app]').val();
                        d.login_admin = $('select[name=login_admin]').val();
                    },
                    complete: function() {
                        $('input').iCheck(Init.iCheck());
                        $('[data-toggle="popover"]').on('show.bs.popover', function() {
                            var ip = $(this).data('ip')
                            var url = "{{ config('app.core') . '/helper/ip/location' }}" +
                                "?ip=" + ip;
                            var $this = $(this);

                            if ($this.data('loaded') == '0') {
                                $.ajax({
                                    url: url,
                                    type: 'GET',
                                    crossDomain: true,
                                    success: function(data) {
                                        if (data.status) {
                                            $this.data('loaded', '1');

                                            html = "<div class='text-center'>" +
                                                $this.data('time') + "</div>";
                                            html +=
                                                "<table class='table table-condensed m-0'>";
                                            html += "<tr>";
                                            html += "<td>País</td>";
                                            html += "<td>" + data.country_name +
                                                "</td>";
                                            html += "</tr><tr>";
                                            html += "<td>Localidade</td>";
                                            html += "<td>" + data.city + "</td>";
                                            html += "</tr><tr>";
                                            html +=
                                                "<td style='width: 80px'>Cód. Postal</td>";
                                            html += "<td>" + data.postal_code +
                                                "</td>";
                                            html += "</tr><tr>";
                                            html += "<td>ISP</td>";
                                            html += "<td class='ip-isp'>" + data
                                                .isp + "</td>";
                                            html += "</tr>";
                                            html += "</table>";
                                        }

                                        $this.attr('data-content', html).data(
                                            'bs.popover').setContent();
                                        var popoverId = $this.attr(
                                            'aria-describedby');
                                        $('#' + popoverId).addClass($this.data(
                                            'placement'));
                                    },
                                    fail: function(data) {
                                        $.bootstrapGrowl(
                                            "<i class='fas fa-exclamation-circle'></i> Erro ao carregar informação do IP.", {
                                                type: 'error',
                                                align: 'center',
                                                width: 'auto',
                                                delay: 8000
                                            });
                                    }
                                })
                            }
                        })
                    },
                    beforeSend: function() {
                        Datatables.cancelDatatableRequest(oTable)
                    },
                }
            });

            $('.filter-datatable').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
        });

        $('.tabs-filter a').on('click', function(e) {
            e.preventDefault();
            var type = $(this).data('type');


            if (type == 'all') {
                $('.doc-type-filter').show();
                $('[name="role"]').val('').trigger('change');
            } else {
                $('.doc-type-filter').hide();
                $('[name="role"]').val(type).trigger('change');
            }

            $('.btn-add-invoice, .btn-saft').show();
            $('.btn-add-receipt').hide();
            if (type == 'receipt') {
                $('.btn-add-invoice, .btn-saft').hide();
                $('.btn-add-receipt').show();
            }
        })

        $('#modal-print-validities [type=submit], #modal-print-holidays-balance [type=submit], #modal-print-absences [type=submit]')
            .on('click', function(e) {
                e.preventDefault();
                $(this).closest('form').submit();
                $(this).closest('.modal').modal('hide');
                $(this).button('reset');
            })
    </script>
@stop
