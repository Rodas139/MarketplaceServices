<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('/favicon.png') }}" />
    <title>@yield('title') projetoBase</title>
    <meta http-equiv="content-language" content="{{ Lang::locale() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="csrf-param" content="_token" />
    <meta name="author" content="projetoBase">
    <meta name="og:url" content="{{ Request::fullUrl() }}">
    @yield('metatags')
    {!! Html::style('/vendor/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('/vendor/lightgallery/dist/css/lightgallery.min.css') !!}
    {!! Html::style('/vendor/lightslider/dist/css/lightslider.min.css') !!}

    {{-- {!! Html::style('/vendor/yeti/bootstrap.css') !!}
    {!! Html::style('/vendor/jasny-bootstrap/dist/css/jasny-bootstrap.css') !!} --}}
    {!! Html::style('/vendor/bootstrap-sweetalert/dist/sweetalert.css') !!}
    {!! Html::style('/vendor/select2/dist/css/select2.css') !!}

    {{ Html::style('/vendor/font-awesome/css/all.min.css') }}
    {{ Html::style('/vendor/flag-icon-css/css/flag-icon.min.css') }}
    {{ Html::style('/vendor/bootstrap/dist/css/bootstrap.min.css') }}
    {{ Html::style('/vendor/iCheck/skins/minimal/blue.css') }}
    {{ Html::style('/vendor/intl-tel-input/build/css/intlTelInput.min.css') }}

    {{ HTML::style('/vendor/datepicker/datepicker3.css') }}
    {{ HTML::style('/vendor/datatables/dataTables.bootstrap.css') }}
    {{ HTML::style('/vendor/select2/dist/css/select2.css') }}
    {{ HTML::style('/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput-typeahead.css') }}

    {{ HTML::style('/vendor/jasny-bootstrap/dist/css/jasny-bootstrap.min.css') }}
    {{ HTML::style('/vendor/magicsuggest/magicsuggest-min.css') }}
    {{ HTML::style('/vendor/animate.css/animate.css') }}
    {!! Html::style('/assets/css/app.css') !!}
    {!! Html::style('/assets/css/helper.css') !!}


    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,100,700' rel='stylesheet' type='text/css'>
    @yield('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>
    <style>
        .logo_1D,
        .chatCopyright_3s.__chat_35 {
            display: none !important;
        }
    </style>

    <div class="wrapper">
        @include('partials.header')
        @yield('content')
        <div class="push"></div>
    </div>

    @include('partials.footer')

    {{-- {!! HTML::script(array(
        //'/vendor/jquery/dist/jquery.js',
        '/vendor/jQuery/jquery-2.2.3.min.js',
        '/vendor/bootstrap/dist/js/bootstrap.js',
        '/vendor/select2/dist/js/select2.js',
        '/vendor/lightgallery/dist/js/lightgallery-all.js',
        '/vendor/perfect-scrollbar/js/perfect-scrollbar.jquery.js',
        '/vendor/jasny-bootstrap/dist/js/jasny-bootstrap.js',
        '/vendor/bootstrap-sweetalert/dist/sweetalert.js',
        '/vendor/freewall/freewall.js',
        '/vendor/lightslider/dist/js/lightslider.js',
        '/vendor/isotope/dist/isotope.pkgd.js',
        '/assets/js/app.js',
        ))->withFullUrl()
    !!} --}}
    {{-- <script src="//code.jivosite.com/widget.js" jv-id="qZQYkuytFO" async></script> --}}

    @include('account.partials.scripts')
    @yield('scripts')
    @include('partials.analytics')
</body>

</html>
