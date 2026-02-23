@section('title')
Colaboradores
@stop

@section('content-header')
Colaboradores
<small>
    {{ $action }}
</small>
@stop

@section('breadcrumb')
<li>Entidades</li>
<li>
    <a href="{{ route('admin.users.index') }}">
        Colaboradores
    </a>
</li>
<li class="active">
    {{ $action }}
</li>
@stop

@section('content')
@if ($user->exists)
<div class="row">
    <div class="col-md-12">
        <div class="box no-border m-b-15">
            <div class="box-body p-5">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="pull-left m-r-10">
                            <img src="{{ asset('assets/img/default/avatar.png') }}" style="border:none" class="w-60px" />
                        </div>
                        <div class="pull-left">
                            <h4 class="m-t-5 pull-left">
                                {{ $user->name }}
                                @if (!$user->active)
                                <span class="label label-danger">Inativo</span>
                                @endif
                                @if ($user->password && ((!$user->is_operator && !$user->login_admin) || ($user->is_operator && !$user->login_app)))
                                <i class="fas fa-ban text-red" data-toggle="tooltip" title="Utilizador bloqueado"></i>
                                @endif
                            </h4>
                            <div class="clearfix"></div>
                            <ul class="list-inline m-b-0">
                                <li><small>Registo:</small> {{ $user->created_at->format('Y-m-d') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <?php $today = Date::today(); ?>
                        <ul class="list-inline m-t-8 m-b-0 pull-right hidden-xs">
                            <li class="divider"></li>
                            <li>
                                <div class="btn-group btn-group-sm" role="group" style="margin-top: -30px">
                                    @if ($nextId = $user->nextId())

                                    <a href="{{ route('admin.users.edit', [$nextId, Request::getQueryString()]) }}" class="btn btn-default" data-toggle="tooltip" title="Anterior">
                                        <i class="fa fa-fw fa-angle-left"></i>
                                    </a>
                                    @else
                                    <button type="button" class="btn btn-default" disabled>
                                        <i class="fa fa-fw fa-angle-left"></i>
                                    </button>
                                    @endif

                                    @if ($prevId = $user->previousId())
                                    <a href="{{ route('admin.users.edit', [$prevId, Request::getQueryString()]) }}" class="btn btn-default" data-toggle="tooltip" title="Próximo">
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
@endif

<div class="row row-5">
    @if ($user->exists)
    <div class="col-md-3 col-lg-2">
        <div class="box box-solid">
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active">
                        <a href="#tab-personal-info" data-toggle="tab">
                            <i class="fas fa-fw fa-info-circle"></i> Dados Pessoais
                        </a>
                    </li>
                    <li>
                        <a href="#tab-profissional-info" data-toggle="tab">
                            <i class="fas fa-fw fa-briefcase"></i> Contrato e Salário
                        </a>
                    </li>
                    <li>
                        <a href="#tab-login" data-toggle="tab">
                            <i class="fas fa-fw fa-lock"></i> Acesso à Área de Gestão
                            @if (empty($user->password))
                            <i class="fa fa-exclamation-triangle pull-right text-yellow m-t-3"></i>
                            @elseif(!$user->active)
                            <i class="fa fa-ban pull-right text-red m-t-3"></i>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-lg-10">
        @else
        <div class="col-md-12">
            @endif
            <div class="tab-content">
                <div class="active tab-pane" id="tab-personal-info">
                    @include('admin.users.partials.personal_info')
                </div>
                @if(!empty($user->id))
                    <div class="tab-pane" id="tab-profissional-info">
                        @include('admin.users.partials.profissional_info')
                    </div>
                    <div class="tab-pane" id="tab-login">
                        @include('admin.users.partials.login')
                    </div>
                @endif
            </div>
        </div>
    </div>
    @stop

    @section('styles')
    {{ HTML::style('vendor/ios-checkbox/dist/css/iosCheckbox.min.css') }}
    <style>
        .card-row {
            cursor: pointer;
        }

        .card-row .lbl-name i {
            opacity: 0;
        }

        .card-row:hover .lbl-name {
            cursor: pointer;
            color: #0f74a8;
        }

        .card-row:hover .lbl-name i {
            opacity: 1;
        }

        .check .icheckbox_minimal-blue {
            display: none;
        }
    </style>
    @stop

    @section('scripts')
    {{ HTML::script('vendor/ios-checkbox/dist/js/iosCheckbox.min.js') }}
    <script type="text/javascript">
        $(".ios").iosCheckbox();

        $('[name="password"], [name="password_confirmation"]').on('click', function() {
            $('.checkbox-send-password').show();
        })

        @if(Auth::user()->id != $user->id)
        $('[name="active"]').closest('.ios-checkbox-wrap').find('.ios-ui-select').on('click', function() {

            var checked = $(this).hasClass('checked');

            if (!checked) {
                $('[name="login_admin"], [name="login_app"]')
                    .prop('checked', false)
                    .prop('disabled', true)
                    .closest('.ios-checkbox-wrap')
                    .find('.ios-ui-select')
                    .removeClass('checked')
                    .css('opacity', 0.4)
            } else {
                changePermissions()
            }
        })

        $('[name="role_id"]').on('change', function() {
            changePermissions()
        })

        function changePermissions() {

            var permission = $('[name="role_id"]').val();

            if (permission == 3) { //MOTORISTA
                $('[name="login_admin"]')
                    .prop('checked', true)
                    .prop('disabled', true)
                    .closest('.ios-checkbox-wrap')
                    .find('.ios-ui-select')
                    .removeClass('checked')
                    .css('opacity', 0.4)

                $('[name="login_app"]')
                    .prop('checked', true)
                    .prop('disabled', false)
                    .closest('.ios-checkbox-wrap')
                    .find('.ios-ui-select')
                    .addClass('checked')
                    .css('opacity', 1)
            } else {
                $('[name="login_admin"]')
                    .prop('checked', true)
                    .prop('disabled', false)
                    .closest('.ios-checkbox-wrap')
                    .find('.ios-ui-select')
                    .addClass('checked')
                    .css('opacity', 1)

                $('[name="login_app"]')
                    .prop('checked', false)
                    .prop('disabled', false)
                    .closest('.ios-checkbox-wrap')
                    .find('.ios-ui-select')
                    .removeClass('checked')
                    .css('opacity', 1)

            }
        }
        @endif

        $('[data-toggle="popover"]').on('mouseout', function(e) {
            $('[data-toggle="popover"]').not(this).popover('hide');
        });

        $('[data-dismiss="fileinput"]').on('click', function() {
            $('[name=delete_photo]').val(1);
        })

        @if($user->exists)
        var url = "{{ config('app.core') . '/helper/ip/location' }}" + "?ip={{ $user->ip }}";
        $.ajax({
            url: url,
            type: 'GET',
            crossDomain: true,
            success: function(data) {
                if (data.status) {
                    $('.ip-country').html(data.country_name)
                    $('.ip-city').html(data.city)
                    $('.ip-postal-code').html(data.postal_code)
                    $('.ip-isp').html(data.isp)
                } else {
                    $('.ip-country')
                        .closest('table')
                        .find('.fa-circle-notch')
                        .removeClass('fa-spin')
                        .removeClass('fa-circle-notch')
                        .addClass('fa-exclamation-triangle')
                        .addClass('text-red')
                }
            },
            fail: function(data) {
                $('.ip-country')
                    .closest('table')
                    .find('.fa-circle-notch')
                    .removeClass('fa-spin')
                    .removeClass('fa-circle-notch')
                    .addClass('fa-exclamation-triangle')
                    .addClass('text-red')
            }
        });
        @endif

        $(document).ready(function() {
            $('a[href="#tab-{{ Request::get('
                tab ') }}"]').trigger('click');
        })

        var parentTab = $('a[href="#tab-{{ Request::get('
            tab ') }}"]').data('parent-tab');
        $('a[href="' + parentTab + '"]').trigger('click');

        //Add new card
        $('.btn-add-card').on('click', function() {
            var $clone = $('.table-cards tr:last').clone();

            $clone.find('input').val('');
            $clone.show();
            $clone.find('.rq-field').prop('required', true)
            $('.table-cards tr:last-child').prev().after($clone);
        })

        //Delete new card
        $(document).on('click', '.btn-delete-card', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        })

        //edit name
        $(document).on('click', '.card-row', function(e) {
            e.preventDefault();
            $(this).find('.lbl-name').hide()
            $(this).find('.inp-name').show()
        })
    </script>
    @stop
