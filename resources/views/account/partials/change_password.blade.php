{{ Form::open(array('route' => array('account.update.password'))) }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
            <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
            <span class="sr-only">{{trans('account/orders.close')}}</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-lock"></i> {{trans('account/orders.change-password-full')}}</h4>
    </div>
    <div class="modal-body">
        <div class="form-group">
            {{ Form::label('password', trans('account/orders.new-password')) }}
            {{ Form::password('password', array('class' => 'form-control', 'autocomplete' => 'off', 'required' => true)) }}
        </div>
        <div class="form-group">
            {{ Form::label('password_confirmation', trans('account/orders.confirm-password')) }}
            {{ Form::password('password_confirmation', array('class' => 'form-control', 'autocomplete' => 'off', 'required' => true)) }}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('account/orders.close')}}</button>
        <button type="submit" class="btn btn-primary">{{trans('account/orders.save')}}</button>
    </div>
{{ Form::close() }}