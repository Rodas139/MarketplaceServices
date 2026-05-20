@section('title')
Comentários
@stop

@section('content-header')
Comentários
<small>Novo Comentário</small>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('admin.comments.index') }}">Comentários</a>
</li>
<li class="active">Novo Comentário</li>
@stop

@section('content')
@include('admin.comments.partials.info')
@stop
