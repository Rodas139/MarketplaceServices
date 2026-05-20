<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title','Main') - Administração {{ config('app.title') }}</title>
        <link rel="shortcut icon" type="image/png" href="{{ asset('/favicon.ico') }}"/>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="csrf-param" content="_token" />
        <meta name="user-id" content="{{ auth()->id() }}" />
        @include('admin.partials.styles')
        @vite(['resources/js/app.js'])
        @yield('styles')

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="{{ config('app.skin') }} fixed sidebar-mini">
        <div class="wrapper">
            @include('admin.partials.header')
            @include('admin.partials.menu')
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>@yield('content-header')</h1>
                    @include('admin.partials.breadcrumb')
                </section>
                <section class="content">
                    @yield('content')
                </section>
            </div>
            @include('admin.partials.footer')
        </div>
        @include('admin.partials.modals.password')
        @include('admin.partials.modals.remote_xs')
        @include('admin.partials.modals.remote_md')
        @include('admin.partials.modals.remote_lg')
        @include('admin.partials.modals.remote_xl')
        @include('admin.partials.modals.remote_xlg')
        @yield('modals')
        @include('admin.partials.scripts')
        @include('admin.partials.alerts')
    </body>
</html>
