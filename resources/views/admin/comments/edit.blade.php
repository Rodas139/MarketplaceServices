@section('title')
Comentários
@stop

@section('content-header')
Comentários
<small>Editar Comentário</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.comments.index') }}">Comentários</a>
</li>
<li class="active">Editar Comentário</li>
@stop

@section('content')
@include('admin.comments.partials.info')
@stop
