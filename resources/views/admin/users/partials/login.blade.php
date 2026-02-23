<div class="box no-border">
    <div class="box-body">
        @if (empty($user->password) && hasModule('human_resources'))
            <div class="alert bg-yellow">
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="alert-message">
                    <h4>Sem acesso à área de gestão.</h4>
                    <p>Este colaborador ainda não tem acesso à área de gestão. Complete os dados abaixo e grave de
                        seguida para atribuir uma conta.</p>
                </div>
            </div>
        @endif

        {{ Form::model($user, $loginFormOptions) }}
        
        <div class="col-sm-10">
            <div class="row row-5">
                <div class="col-sm-8" style="padding-left: 25px">
                    <h4 class="form-divider no-border" style="margin-top: -8px">Dados de acesso</h4>
                    <div class="row row-5">
                        <div class="col-sm-2">
                            <div class="form-group is-required">
                                {{ Form::label('code', 'Código') }}
                                {{ Form::text('code', null, ['class' => 'form-control', 'maxlength' => 6, 'required']) }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                {{ Form::label('code_abbrv', 'Cod. Abrv.') }}
                                {{ Form::text('code_abbrv', null, ['class' => 'form-control', 'maxlength' => 6]) }}
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group is-required">
                                {{ Form::label('name', 'Nome a apresentar na aplicação', ['data-content' => '']) }}
                                {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => 30, 'required']) }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group is-required">
                        {{ Form::label('email', 'E-mail de acesso') }}
                        {{ Form::text('email', empty($user->email) ? $user->professional_email : null, ['class' => 'form-control nospace lowercase','required' => true]) }}
                    </div>

                    @if (empty($user->password))
                        <div class="form-group is-required m-0">
                            {{ Form::label('password', 'Palavra-passe') }}
                            <div class="input-group input-group">
                                {{ Form::text('password', Str::random(8), ['class' => 'form-control nospace', 'required' => true]) }}
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-flat" id="random-password" type="button">
                                        <i class="fas fa-sync-alt"></i> Gerar outra
                                    </button>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="row row-5">
                            <div class="col-sm-6">
                                <div class="form-group m-b-0">
                                    {{ Form::label('password', 'Nova palavra-passe') }}
                                    {{ Form::password('password', ['class' => 'form-control nospace','autofill' => false,'autocomplete' => 'off','placeholder' => 'Vazio para não alterar']) }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group m-b-0">
                                    {{ Form::label('password_confirmation', 'Confirmar palavra-passe') }}
                                    {{ Form::password('password_confirmation', ['class' => 'form-control nospace','autofill' => false,'autocomplete' => 'off']) }}
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="checkbox m-b-15 checkbox-send-password"
                            style="{{ $user->password ? 'display:none' : 'display:block' }}">
                            <label style="padding-left: 0 !important;">
                                {{ Form::checkbox('send_email', 1, $user->password ? false : true) }}
                                <i class="fas fa-envelope"></i> Ao gravar, enviar email com a palavra-passe
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" style="padding-left: 30px">
                    <h4 class="form-divider no-border" style="margin-top: -8px"><i class="fas fa-lock"></i>
                        Permissões</h4>
                    <div class="form-group is-required">
                        {{ Form::label('role_id', 'Perfíl da Conta') }}
                        @if (Auth::user()->perm('admin_roles'))
                            <a href="{{ route('admin.roles.index') }}" class="pull-right m-l-10"
                                target="_blank">Gerir Perfis</a>
                        @endif
                        @if (Auth::user()->id == $user->id)
                            {{ Form::select('role_id',[@$user->roles->first()->id => @$user->roles->first()->display_name],@$user->roles->first()->id,['class' => 'form-control select2 roles-selection', 'required', 'disabled']) }}
                        @else
                            {{ Form::select('role_id', ['' => ''] + $roles, @$user->roles->first()->id, ['class' => 'form-control select2 roles-selection','required']) }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row row-5">
                <div class="col-sm-12" style="padding-left: 25px">
                    <h4 class="form-divider"><i class="fas fa-cog"></i> Opções da conta</h4>
                </div>
                <div class="col-sm-4" style="padding-left: 25px">
                    <table class="table table-condensed m-0">
                        <tr>
                            <td>
                                <p class="form-control-static" style="border: none; padding-left: 0; margin: 0">
                                    <i class="fas fa-fw fa-check-circle"></i> Colaborador ativo
                                </p>
                            </td>
                            <td class="check">
                                {{ Form::checkbox('active', 1, $user->exists ? null : 1, ['class' => 'ios',Auth::user()->id == $user->id ? 'disabled' : '']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="form-control-static" style="border: none; padding-left: 0; margin: 0">
                                    <i class="fas fa-fw fa-laptop"></i> Entrar na Área Gestão
                                </p>
                            </td>
                            <td class="check">
                                {{ Form::checkbox('login_admin', 1, $user->exists ? null : 1, ['class' => 'ios',$user->is_operator || Auth::user()->id == $user->id || !$user->active ? 'disabled' : '']) }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-4" style="margin-left: 25px">

                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <hr />
            @if (!$user->isAdmin())
                {{ Form::hidden('source', config('app.source')) }}
            @endif
            <button class="btn btn-primary">Gravar</button>
        </div>
        @if (Auth::user()->id == $user->id)
            {{ Form::hidden('active', 0) }}
            {{ Form::hidden('location_enabled') }}
        @endif
        {{ Form::hidden('delete_photo') }}
        {{ Form::close() }}
    </div>
</div>
