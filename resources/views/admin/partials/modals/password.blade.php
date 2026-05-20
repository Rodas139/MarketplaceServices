<div class="modal fade" id="account-password" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            {{ Form::open(array('route' => array('admin.account.password'))) }}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
                    <span class="sr-only">Fechar</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-lock"></i> Alterar palavra-passe</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {{ Form::label('current_password', 'Palavra-passe actual:') }}
                    {{ Form::password('current_password', array('class' => 'form-control', 'autocomplete' => 'off', 'required' => true)) }}
                </div>
                <div class="form-group">
                    {{ Form::label('password', 'Nova palavra-passe:') }}
                    {{ Form::password('password', array('class' => 'form-control', 'autocomplete' => 'off', 'required' => true)) }}
                </div>
                <div class="form-group">
                    {{ Form::label('password_confirmation', 'Confirmar palavra-passe:') }}
                    {{ Form::password('password_confirmation', array('class' => 'form-control', 'autocomplete' => 'off', 'required' => true)) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>