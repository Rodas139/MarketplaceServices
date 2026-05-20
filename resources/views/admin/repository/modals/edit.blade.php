{{ Form::model($file, $formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">Fechar</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
    <div class="form-group is-required">
        {{ Form::label('name', 'Título do documento') }}
        {{ Form::text('name', null, ['class' => 'form-control', 'required']) }}
    </div>

    <div class="form-group">
        {{ Form::label('obs', 'Anotações ou Observações') }}
        {{ Form::textarea('obs', null, ['class' => 'form-control', 'rows' => 3]) }}
    </div>
    @if(Setting::get('files_customer_visible', false))
        @if (!$file->customer_id ?? false)
            <div class="form-group">
                {{ Form::hidden('customer_visible',0) }}
                {{ Form::checkbox('customer_visible',1) }}
                {{ Form::label('customer_visible', 'Anexo visivel aos clientes na Área de Cliente') }}
            </div>
        @endif
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    <button type="submit" class="btn btn-primary">Gravar</button>
</div>
{{ Form::close() }}

<script>
    $('.select2').select2(Init.select2());
</script>
