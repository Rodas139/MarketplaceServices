@section('title')
Serviços
@stop

@section('content-header')
Serviços
<small>Novo Serviço</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.services.index') }}">Serviços</a>
</li>
<li class="active">Novo Serviço</li>
@stop

@section('content')
@include('admin.services.partials.info')
@stop
