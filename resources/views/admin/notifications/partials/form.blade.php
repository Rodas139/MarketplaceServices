<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $action ?? 'Nova Notificação' }}</h3>
            </div>
            <div class="box-body">
                {!! Form::model($notification, $formOptions) !!}

                <div class="form-group">
                    {!! Form::label('title', 'Título', ['class' => 'control-label']) !!}
                    {!! Form::text('title', null, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Ex: Novo Sistema de Notificações']) !!}
                    @if($errors->has('title'))
                        <div class="invalid-feedback" style="display: block;">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('message', 'Mensagem', ['class' => 'control-label']) !!}
                    {!! Form::textarea('message', null, ['class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : ''), 'placeholder' => 'Escreva aqui a mensagem que será enviada para todos os utilizadores...', 'rows' => 6]) !!}
                    @if($errors->has('message'))
                        <div class="invalid-feedback" style="display: block;">
                            {{ $errors->first('message') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('type', 'Tipo', ['class' => 'control-label']) !!}
                    {!! Form::select('type', [
                        '' => 'Selecione um tipo',
                        'info' => 'Informação',
                        'success' => 'Sucesso',
                        'warning' => 'Aviso',
                        'error' => 'Erro'
                    ], null, ['class' => 'form-control' . ($errors->has('type') ? ' is-invalid' : '')]) !!}
                    @if($errors->has('type'))
                        <div class="invalid-feedback" style="display: block;">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
