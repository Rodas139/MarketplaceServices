<div class="box no-border">
    <div class="box-body">
        @if (empty($customer->password))
            <div class="alert alert-warning">
                <h4><i class="fa fa-warning"></i> Sem acesso à plataforma</h4>
                <p>Este cliente ainda não possui acesso à plataforma. Complete os dados abaixo e grave de seguida para
                    atribuir uma conta.</p>
            </div>
        @endif

        {{ Form::model($customer, ['route' => ['admin.customers.login', $customer->id],'method' => 'PUT','files' => true]) }}
        <div class="col-sm-8 col-lg-9">
            <div class="form-group is-required">
                {{ Form::label('display_name', 'Nome de Login') }}
                {{ Form::text('display_name', empty($customer->display_name) ? $customer->name : null, ['class' => 'form-control','required' => true]) }}
            </div>
            <div class="form-group is-required">
                {{ Form::label('email', 'E-mail') }}
                {{ Form::email('email', empty($customer->email) ? $customer->contact_email : null, ['class' => 'form-control','required' => true]) }}
            </div>

            @if (empty($customer->password))
                <div class="form-group is-required">
                    {{ Form::label('password', 'Password') }}
                    <div class="input-group input-group">
                        {{ Form::text('password', Str::random(8), ['class' => 'form-control', 'required' => true]) }}
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-flat" id="random-password" type="button">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </span>
                    </div>
                </div>
            @else
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('password', 'Password') }}
                            {{ Form::password('password', ['class' => 'form-control','autocomplete' => 'off','placeholder' => 'Deixar vazio para não alterar']) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('password_confirmation', 'Confirmar Password') }}
                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'off']) }}
                        </div>
                    </div>
                </div>
            @endif
            <div class="checkbox margin-top-5 margin-bottom-15">
                <label style="padding-left: 0 !important">
                    {{ Form::checkbox('send_email', 1, $customer->password ? false : true) }}
                    Enviar email com a palavra-passe
                </label>
            </div>
        </div>
        {{-- <div class="col-sm-4 col-lg-3">
            {{ Form::label('image', 'Imagem:', ['class' => 'form-label']) }}<br />
            <div class="fileinput {{ $customer->filepath ? 'fileinput-exists' : 'fileinput-new' }}"
                data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ asset('assets/img/default/avatar.png') }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;">
                    @if ($customer->filepath)
                        <img src="{{ asset($customer->getThumb()) }}">
                    @endif
                </div>
                <div>
                    <span class="btn btn-default btn-block btn-sm btn-file">
                        <span class="fileinput-new">Procurar...</span>
                        <span class="fileinput-exists"><i class="fa fa-refresh"></i> Alterar</span>
                        <input type="file" name="image">
                    </span>
                    <a href="#" class="btn btn-danger btn-block btn-sm fileinput-exists" data-dismiss="fileinput">
                        <i class="fa fa-close"></i> Remover
                    </a>
                </div>
            </div>
        </div> --}}
        <div class="col-sm-12">
            {{ Form::hidden('delete_photo') }}
            <button class="btn btn-primary">Gravar</button>
        </div>
        {{ Form::close() }}
    </div>
</div>
