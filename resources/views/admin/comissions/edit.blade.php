@section('title')
Editar Comissão
@stop

@section('content-header')
Comissões
@stop

@section('breadcrumb')
<li><a href="{{ route('admin.comissions.index') }}">Comissões</a></li>
<li class="active">Editar</li>
@stop

@section('content')
@include('admin.comissions.partials.info')
@stop
