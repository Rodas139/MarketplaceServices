@extends('layouts.master')

@section('content')
    <div class="spacer-30"></div>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    @include('partials.alerts')
                    @yield('account-content')
                </div>
            </div>
        </div>
    </section>
    <div class="spacer-30"></div>
    @include('admin.partials.modals.remote_lg')
    @include('admin.partials.modals.remote_md')
    @include('admin.partials.modals.remote_xl')
    @include('admin.partials.modals.remote_xs')
@stop
