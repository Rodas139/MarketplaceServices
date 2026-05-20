@extends('admin.layouts.master')

@section('title')
Meus Serviços
@stop

@section('content-header')
Meus Serviços
@stop

@section('breadcrumb')
<li class="active">Meus Serviços</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        @if($service)
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $service->name }}</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nome do Serviço</strong></label>
                                <p>{{ $service->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Categoria</strong></label>
                                <p>{{ $service->category->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Preço Por Hora</strong></label>
                                <p>{{ number_format($service->price, 2, ',', '.') }}€</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Status</strong></label>
                                <p>
                                    <span class="label {{ $service->status === 'active' ? 'label-success' : 'label-danger' }}">
                                        {{ $service->status === 'active' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('provider.services.available') }}" class="btn btn-info">
                                <i class="fa"></i> Ver Serviços Disponíveis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="box">
                <div class="box-body">
                    <div class="alert alert-warning">
                        <i class="icon fa fa-warning"></i> Nenhum serviço atribuído.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@stop
