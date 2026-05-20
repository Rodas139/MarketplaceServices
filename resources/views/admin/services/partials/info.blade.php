<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $action }}</h3>
            </div>
            <div class="box-body">
                {!! Form::model($service, $formOptions) !!}

                    <div class="form-group is-required">
                        <label for="name">Nome</label>
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nome do serviço']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="category_id">Categoria</label>
                        {!! Form::select('category_id', ['' => 'Selecione uma categoria'] + $categories, null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="price">Preço por Hora</label>
                        {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'Preço do serviço', 'step' => '0.01']) !!}
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        @if($service->id)
                            {!! Form::select('status', ['active' => 'Ativo', 'inactive' => 'Inativo'], null, ['class' => 'form-control']) !!}
                        @else
                            {!! Form::select('status', ['pending' => 'Pendente', 'active' => 'Ativo', 'inactive' => 'Inativo'], null, ['class' => 'form-control']) !!}
                        @endif
                    </div>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    Guardar
                </button>
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>
