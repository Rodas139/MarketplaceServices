@php
    $auth = Auth::guard('customer')->user();
@endphp

<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="{{ asset('/favicon.ico') }}"/>
        <title>projetoBase</title>
        <meta http-equiv="content-language" content="{{ Lang::locale() }}">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="csrf-param" content="_token" />
        <meta name="author" content="">
        <meta name="og:url" content="{{ Request::fullUrl() }}">
        @yield('metatags')

        {{ Html::style('/assets/admin/fonts/exo_2.css') }}
        {{ Html::style('/vendor/font-awesome/css/all.min.css') }}
        {{ Html::style('/vendor/flag-icon-css/css/flag-icon.min.css') }}
        {{ Html::style('/vendor/bootstrap/dist/css/bootstrap.min.css') }}
        {{ Html::style('/vendor/iCheck/skins/minimal/blue.css') }}
        {{ Html::style('/vendor/datepicker/datepicker3.css') }}
        {{ Html::style('/vendor/datatables/dataTables.bootstrap.css') }}
        {{ Html::style('/vendor/select2/dist/css/select2.min.css') }}
        {{ Html::style('/vendor/jasny-bootstrap/dist/css/jasny-bootstrap.min.css') }}
        {{ Html::style('/vendor/magicsuggest/magicsuggest-min.css') }}
        {{ Html::style('/vendor/animate.css/animate.css') }}
        {{ Html::style('/assets/admin/css/helper.css') }}
        {{ Html::style('/assets/css/helper.css') }}
        {{ Html::style('/assets/css/custom.css') }}
        {{ Html::style('/assets/css/default.css') }}
        {{ Html::style('/assets/css/account.css') }}
        {{ Html::style('/assets/admin/css/skins/' . app_skin() . '.css') }}

        @yield('styles')
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="account {{ app_skin() }}">
        @include('account.partials.header')

        <section class="account-section">
            <div class="container account-container">
                <div class="row">
                    @if ($auth)
                    {{-- <div class="col-xs-12 col-md-2 account-left-panel" @if ($auth) style="width: 18%; padding: 0;" @endif>
                        <div class="account-sidebar">
                            @include('account.partials.sidebar')
                        </div>
                    </div> --}}
                    @endif
                    <div class="{{ $auth ? 'col-md-12' : 'col-xs-12' }} account-right-panel" @if ($auth) style="width: 100%;" @endif>
                        <div class="col-xs-12 col-md-12 account-main-panel">
                            @yield('account-content')
                        </div>
                    </div>
                     {{-- @if($auth)
                     <div class="col-xs-12 col-md-2 account-right-panel" @if ($auth) style="width: 18%; padding: 0;" @endif>
                        <div class="col-xs-12 col-md-12 account-main-panel">
                            @include('account.partials.side_img')
                        </div>
                    </div> 
                     @endif --}}
                </div>
                <div class="row">
                     @if($auth)
                        <div class="col-xs-12 col-md-12 account-right-panel" @if ($auth) style="width: 100%;" @endif>
                            <div class="col-xs-12 col-md-12 account-main-panel">
                                @include('account.partials.side_img')
                            </div>
                        </div> 
                     @endif 
                </div>
            </div>
        </section>

        @include('partials.modals.remote_xs')
        @include('partials.modals.remote_md')
        @include('partials.modals.remote_lg')
        @include('partials.modals.remote_xl')
        @include('partials.modals.remote_xlg')
        @yield('modals')
        {{-- @yield('account-content') --}}

        <script>
            var APP_COUNTRY = "{{ setting('app_country') }}";
            var LOCALE      = "{{ Lang::locale() }}";
        </script>

        {{ Html::script('/vendor/jQuery/jquery-3.4.0.min.js') }}
        {{ Html::script('/vendor/bootstrap/dist/js/bootstrap.min.js') }}
        {{ Html::script('/vendor/datatables/jquery.dataTables.min.js') }}
        {{ Html::script('/vendor/datatables/dataTables.bootstrap.min.js') }}
        {{ Html::script('/vendor/pace/pace.min.js') }}
        {{ Html::script('/vendor/bootstrap-growl/jquery.bootstrap-growl.min.js') }}
        {{ Html::script('/vendor/iCheck/icheck.min.js') }}
        {{ Html::script('/vendor/select2/dist/js/select2.min.js') }}
        {{ Html::script('/vendor/select2/dist/js/i18n/'.Lang::locale().'.js') }}
        {{ Html::script('/vendor/select2-multiple/select2-multiple.js') }}
        {{ Html::script('/vendor/magicsuggest/magicsuggest-min.js') }}
        {{ Html::script('/vendor/datepicker/bootstrap-datepicker.js') }}
        {{ Html::script('/vendor/datepicker/locales/bootstrap-datepicker.'.Lang::locale().'.js') }}
        {{ Html::script('/vendor/devbridge-autocomplete/dist/jquery.autocomplete.js') }}
        {{ Html::script('/vendor/bootbox/bootbox.js') }}
        {{ Html::script('/vendor/jasny-bootstrap/js/fileinput.js') }}
        {{ Html::script('/vendor/jquery-ujs/src/rails.js') }}
        {{ Html::script('/vendor/moment/moment.min.js') }}
        {{ Html::script('/vendor/push.js/bin/push.js') }}
        {{ Html::script('/vendor/pusher/pusher.min.js') }}
        {{ Html::script('/vendor/jquery-mask-plugin/dist/jquery.mask.js') }}
        {{ Html::script('/vendor/jsvat/jsvat.js') }}
        {{ Html::script('/assets/admin/js/helper.js') }}
        {{ Html::script('/assets/admin/js/validator.js') }}
        {{ Html::script('/assets/js/account.js') }}

        <script>
            $("#menu-{{ @$menuOption }}").addClass('active');
            $("#menu-{{ @$sidebarActiveOption }}").addClass('active');
            $('a[href="#tab-{{ Request::get("tab") }}"]').trigger('click');

            /*==================================================================
            [ Show pass ]*/
            var showPass = 0;
            $('.btn-show-pass').on('click', function(){
                if(showPass == 0) {
                    $(this).next('input').attr('type','text');
                    $(this).find('i').removeClass('fa-eye');
                    $(this).find('i').addClass('fa-eye-slash');
                    showPass = 1;
                }
                else {
                    $(this).next('input').attr('type','password');
                    $(this).find('i').addClass('fa-eye');
                    $(this).find('i').removeClass('fa-eye-slash');
                    showPass = 0;
                }
            });
        </script>

        <script>
            $(document).ready(function(){
                @if (Session::has('success'))
                $.bootstrapGrowl("<i class='fas fa-check'></i> {{ Session::get('success') }}&nbsp;&nbsp;", {type: 'success', align: 'center', width: 'auto', delay: 8000});
                @endif

                @if (Session::has('error'))
                $.bootstrapGrowl("<i class='fas fa-exclamation-circle'></i> {{ Session::get('error') }}&nbsp;&nbsp;", {type: 'error', align: 'center', width: 'auto', delay: 8000});
                @endif

                @if (Session::has('warning'))
                $.bootstrapGrowl("<i class='fas fa-exclamation-triangle'></i> {{ Session::get('warning') }}&nbsp;&nbsp;", {type: 'warning', align: 'center', width: 'auto', delay: 8000});
                @endif

                @if (Session::has('info'))
                $.bootstrapGrowl("<i class='fas fa-info-circle'></i> {{ Session::get('info') }}&nbsp;&nbsp;", {type: 'info', align: 'center', width: 'auto', delay: 8000});
                @endif
            })
        </script>

        @yield('scripts')
    </body>
</html>
