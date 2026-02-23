<?php

if ($customer->balance_last_update) {
    $lastBalanceDate = new \Carbon\Carbon($customer->balance_last_update);
} else {
    $lastBalanceDate = \Carbon\Carbon::tomorrow();
}

$balanceDiff = \Carbon\Carbon::now()->diffInMinutes($lastBalanceDate);
$balanceDiffHours = \Carbon\Carbon::now()->diffInHours($lastBalanceDate);

?>

@section('title')
    {{ $customer->is_prospect ? 'Prospectos' : 'Clientes' }}
@stop

@section('content-header')
    {{ $customer->is_prospect ? 'Prospectos' : 'Clientes' }}
    <small>
        Editar {{ $customer->is_prospect ? 'Prospecto' : 'Cliente' }}
    </small>
@stop

@section('breadcrumb')
    <li>
        @if ($customer->is_prospect)
            <a href="{{ route('admin.prospects.index') }}">
                Prospectos
            </a>
        @else
            <a href="{{ route('admin.customers.index') }}">
                Clientes
            </a>
        @endif
    </li>
    <li class="active">
        Editar {{ $customer->is_prospect ? 'Prospecto' : 'Fornecedor' }}
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box no-border m-b-15" style="{{ $customer->is_prospect ? 'background: #ffe997' : '' }}">
                <div class="box-body p-5">
                    <div class="row">
                        <div class="col-xs-12 col-md-5">
                            <div class="pull-left m-r-10">
                                @if ($customer->filepath)
                                    <img src="{{ asset($customer->getCroppa(200)) }}"
                                        onerror="this.src ='{{ img_broken(true) }}'" style="border:none"
                                        class="w-60px" />
                                @else
                                    <img src="{{ asset('assets/img/default/avatar.png') }}" style="border:none"
                                        class="w-60px" />
                                @endif
                            </div>
                            <div class="pull-left w-85">
                                <h4 class="m-t-5 pull-left customer-name">
                                    {{ $customer->name }}
                                    @if ($customer->is_blocked)
                                        <i class="fas fa-ban text-red" data-toggle="tooltip" title="Utilizador Inativo"></i>
                                    @endif
                                </h4>
                                <div class="clearfix"></div>
                                <ul class="list-inline m-b-0">
                                    @if (!$customer->is_active)
                                        <li>
                                            <span class="label label-danger">Inativo</span>
                                        </li>
                                    @endif
                                    @if (!$customer->is_prospect)
                                        <li><small>Código:</small> <b>{{ $customer->code }}</b></li>
                                    @endif
                                    @if ($customer->created_at)
                                        <li><small>Registo:</small> {{ @$customer->created_at->format('Y-m-d') }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-7">
                            <ul class="list-inline m-t-8 m-b-0 pull-right hidden-xs">

                                <li class="divider"></li>
                                @if ($customer->is_prospect)
                                    <li>
                                        <a href="{{ route('admin.customers.convert', $customer->id) }}"
                                            style="margin-top: -30px" class="btn btn-sm btn-primary" data-method="post"
                                            data-confirm-title="Converter em cliente" data-confirm-class="btn-success"
                                            data-confirm-label="Converter"
                                            data-confirm="Pretende converter o prospecto em cliente?" target="_blank">
                                            <i class="fas fa-user-plus"></i> Converter Cliente
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <div class="btn-group btn-group-sm" role="group" style="margin-top: -30px">
                                        @if ($prevId = $customer->previousId())
                                            <a href="{{ route('admin.customers.edit', [$prevId, Request::getQueryString()]) }}"
                                                class="btn btn-default" data-toggle="tooltip" title="Anterior">
                                                <i class="fa fa-fw fa-angle-left"></i>
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-default" disabled>
                                                <i class="fa fa-fw fa-angle-left"></i>
                                            </button>
                                        @endif

                                        @if ($nextId = $customer->nextId())
                                            <a href="{{ route('admin.customers.edit', [$nextId, Request::getQueryString()]) }}"
                                                class="btn btn-default" data-toggle="tooltip" title="Próximo">
                                                <i class="fa fa-fw fa-angle-right"></i>
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-default" disabled>
                                                <i class="fa fa-fw fa-angle-right"></i>
                                            </button>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-5">
        <div class="col-md-3 col-lg-2">
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a href="#tab-info" data-toggle="tab"><i class="fa fa-fw fa-info-circle"></i> Dados Gerais</a>
                        </li>
                        <li>
                            <a href="#tab-login" data-toggle="tab"><i class="fa fa-fw fa-lock"></i> Área de Cliente</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="tab-content">
                <div class="active tab-pane" id="tab-info">
                    @include('admin.customers.partials.info')
                </div>
                <div class="tab-pane" id="tab-login" data-empty="1">
                    @include('admin.customers.partials.login')
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.modals.vat_validation')
@stop

@section('scripts')
    <script src="{{ asset('vendor/fuzzyset/fuzzyset.js') }}"></script>
    <script>
        $('[name="bank_code"]').on('change', function() {
            var $this = $(this).find('option:selected');
            $('[name="bank_swift"]').val($this.data('swift'));
            $('[name="bank_name"]').val($this.data('name'));
        });
    </script>
    <script type="text/javascript">
        $('[name="yearly_payment_date"]').datepicker({
            format: 'yyyy-mm-dd',
            language: 'pt'
        })

        $('[name="yearly_payment_date"]').click(function() {
            $('[name="yearly_payment_date"]').datepicker("setDate",
                "{{ Date::today()->addYear()->format('Y-m-d') }}")
        })

        $(document).on('click', '#random-password', function() {
            $('input[name=password]').val(Str.random(8));
        });

        $('[data-dismiss="fileinput"]').on('click', function() {
            $('[name=delete_photo]').val(1);
        })

        $(document).ready(function() {
            $('a[href="#tab-{{ Request::get('tab') }}"]').trigger('click');
        })

        var parentTab = $('a[href="#tab-{{ Request::get('tab') }}"]').data('parent-tab');
        $('a[href="' + parentTab + '"]').trigger('click');

        //Fill nif fields with search result
        $(document).on('click', '.vv-accept', function() {
            var $modal = $(this).closest('.modal');
            var targetId = '#' + $modal.find('[name="vv-target"]').val();
            var $target = $(targetId);

            $('#name').val($modal.find('[name="vv-name"]').val())
            $('#address').val($modal.find('[name="vv-address"]').val())
            $('#zip_code').val($modal.find('[name="vv-zip-code"]').val())
            $('#city').val($modal.find('[name="vv-city"]').val())
            $('.mark-on-map').trigger('click');
            $modal.modal('hide');
        });

        //Hide vat validation modal
        $(document).on('click', '#modal-vat-validation .vv-cancel', function(e) {
            e.preventDefault();
            $('#modal-vat-validation').hide().removeClass('in');
        })
    </script>
@stop
