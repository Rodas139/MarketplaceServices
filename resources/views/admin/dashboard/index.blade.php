@section('title')
    Painel Resumo
@stop

@section('content-header')
    Painel Resumo
@stop

@section('content')

<div class="row row-10">
    <div class="col-sm-3 col-lg-3">
        @include('admin.dashboard.partials.counters_vertical')
    </div>

    <div class="col-sm-9">
        <div class="col-sm-12 col-md-12">
            @include('admin.dashboard.partials.orders_chart')
        </div>
    </div>
</div>

@endsection

@section('scripts')
{{ Html::script('vendor/chart.js/dist/Chart.min.js') }}
<script>
        $(document).ready(function () {
        });
</script>
@stop
