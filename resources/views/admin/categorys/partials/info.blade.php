<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $action }}</h3>
            </div>
            <div class="box-body">
                {!! Form::model($category, $formOptions) !!}

                    <div class="form-group is-required">
                        <label for="name">Nome</label>
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nome da categoria']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="description">Descrição</label>
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Descrição da categoria']) !!}
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
