@section('title')
Categorias
@stop

@section('content-header')
Categorias
<small>Nova Categoria</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.categorys.index') }}">Categorias</a>
</li>
<li class="active">Nova Categoria</li>
@stop

@section('content')
@include('admin.categorys.partials.info')
@stop
