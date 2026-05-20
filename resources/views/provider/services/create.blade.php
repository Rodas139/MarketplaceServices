@extends('admin.layouts.master')

@section('title')
Novo Serviço
@stop

@section('content-header')
Novo Serviço
@stop

@section('breadcrumb')
<li><a href="{{ route('provider.services.available') }}">Serviços Disponíveis</a></li>
<li class="active">Novo</li>
@stop

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Adicionar Novo Serviço</h3>
            </div>
            <form action="{{ route('provider.services.store') }}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="name"><strong>Nome do Serviço</strong></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Limpeza Residencial" required>
                        @if ($errors->has('name'))
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                        <label for="category_id"><strong>Categoria</strong></label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('category_id'))
                            <span class="help-block">{{ $errors->first('category_id') }}</span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                        <label for="price"><strong>Preço Por Hora (€)</strong></label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" placeholder="Ex: 15.50" required>
                        @if ($errors->has('price'))
                            <span class="help-block">{{ $errors->first('price') }}</span>
                        @endif
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa"></i> Criar
                    </button>
                    <a href="{{ route('provider.services.available') }}" class="btn btn-default">
                        <i class="fa"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
