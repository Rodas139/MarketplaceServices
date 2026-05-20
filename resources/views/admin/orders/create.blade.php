@section('title')
Pedidos
@stop

@section('content-header')
Pedidos
<small>Novo Pedido</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.orders.index') }}">Pedidos</a>
</li>
<li class="active">Novo Pedido</li>
@stop

@section('content')
@include('admin.orders.partials.info')
@stop
