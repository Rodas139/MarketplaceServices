{{ Form::model($customer, $formOptions) }}
<div class="box no-border">
    <div class="box-body">
        <div class="row row-5">
            <div class="col-sm-8">
                <div class="row row-5">
                    <div class="col-sm-7">
                        <div class="row row-5">
                            <div class="col-sm-10">
                                <div class="form-group is-required">
                                    {{ Form::label('name', 'Nome') }}
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('address', 'Morada') }}
                            {{ Form::text('address', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('email', 'E-mail') }}
                            {{ Form::text('email', null, ['class' => 'form-control email nospace']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ Form::label('phone', 'Telemóvel') }}
                            {{ Form::text('phone', null, ['class' => 'form-control phone nospace']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                            <div class="col-md-8">
                                <div class="form-group @if(!$customer->exists) is-required @endif">
                                    {{ Form::label('password', 'Palavra-passe') }}
                                    <div class="input-group input-group">
                                        {{ Form::text('password', $customer->exists ? '' : Str::random(8), ['class' => 'form-control nospace', 'placeholder' => $customer->exists ? 'Deixar vazio para não alterar' : '']) }}
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-flat" id="random-password" type="button">
                                                <i class="fas fa-sync-alt"></i> Gerar outra
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-5">
                            <div class="col-sm-8">
                                <div class="row row-5">
                                    <div class="col-md-8">
                                    <div class="form-group @if(!$customer->exists) is-required @endif">
                                    {{ Form::label('password_confirmation', 'Confirmar Palavra-passe') }}
                                    {{ Form::text('password_confirmation', '', ['class' => 'form-control nospace', 'placeholder' => $customer->exists ? 'Deixar vazio para não alterar' : '']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <button type="submit" class="btn btn-primary pull-left">Gravar</button>
        <div class="clearfix"></div>
    </div>
</div>
@if($customer->is_prospect)
{{ Form::hidden('is_prospect', 1) }}
@endif
{{ Form::close() }}
