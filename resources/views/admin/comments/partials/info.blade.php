<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $action }}</h3>
            </div>
            <div class="box-body">
                {!! Form::model($comment, $formOptions) !!}

                    <div class="form-group is-required">
                        <label for="id_customer">Cliente</label>
                        {!! Form::select('id_customer', ['' => 'Selecione um cliente'] + $customers, null, ['class' => 'form-control select2']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="id_order">Pedido</label>
                        {!! Form::select('id_order', ['' => 'Selecione um pedido'] + $orders, null, ['class' => 'form-control select2']) !!}
                    </div>

                    <div class="form-group is-required">
                        <label for="comment">Comentário</label>
                        {!! Form::textarea('comment', null, ['class' => 'form-control', 'placeholder' => 'Escreva o comentário...', 'rows' => 5]) !!}
                    </div>

                    <div class="form-group">
                        <label for="rating">Avaliação</label>
                        {!! Form::select('rating', ['' => 'Selecione...', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'], null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        <label for="image">Imagem</label>
                        <div class="input-group">
                            {!! Form::file('image', ['class' => 'form-control', 'accept' => 'image/*']) !!}
                        </div>
                        @if($comment->image && $comment->id)
                            <div class="mt-2">
                                <small class="text-muted">Imagem atual:</small><br>
                                <img src="{{ asset($comment->image) }}" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                            </div>
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
