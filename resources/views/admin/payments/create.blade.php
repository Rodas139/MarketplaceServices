@section('title')
Novo Pagamento
@stop

@section('content-header')
Pagamentos
@stop

@section('breadcrumb')
<li><a href="{{ route('admin.payments.index') }}">Pagamentos</a></li>
<li class="active">Novo</li>
@stop

@section('content')
@include('admin.payments.partials.info')
@stop
