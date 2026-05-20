@section('title')
Serviços
@stop

@section('content-header')
Serviços
<small>Editar Serviço</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.services.index') }}">Serviços</a>
</li>
<li class="active">Editar Serviço</li>
@stop

@section('content')
@include('admin.services.partials.info')
@stop
