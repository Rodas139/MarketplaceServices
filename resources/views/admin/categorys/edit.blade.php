@section('title')
Categorias
@stop

@section('content-header')
Categorias
<small>Editar Categoria</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.categorys.index') }}">Categorias</a>
</li>
<li class="active">Editar Categoria</li>
@stop

@section('content')
@include('admin.categorys.partials.info')
@stop
