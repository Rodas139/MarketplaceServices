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

    <div class="col-sm-5">
        <div class="col-sm-12 col-md-12">
            <div class="box">
                <div class="box-body">
                    <h4 class="m-t-0 m-b-0 bold">Encomendas por cliente</h4>
                    <p><small>Listagem de encomendas por cliente</small></p>
                    <div class="nicescroll" style="height: 326px; overflow-y: scroll">
                        
                    </div>
                </div>
            </div>
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
