{{ Form::model($user, $formOptions) }}
<div class="box no-border">
    <div class="box-body">
        <div class="row row-5">
            <div class="col-sm-4 col-lg-9">
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
                    <div class="col-sm-3">
                        <div class="form-group is-required">
                            {{ Form::label('role_id', 'Perfíl da Conta')}}
                            @if(Auth::user()->id == $user->id)
                                {{ Form::select('role_id', [@$user->roles->first()->id => @$user->roles->first()->display_name], @$user->roles->first()->id, array('class' =>'form-control select2 roles-selection', 'required', 'disabled')) }}
                            @else
                                {{ Form::select('role_id', ['' => ''] + $roles, @$user->roles->first()->id, array('class' =>'form-control select2 roles-selection', 'required')) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-8">
                        <div class="row row-5">
                            <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('email', 'E-mail') }}
                                    {{ Form::text('email', null, ['class' => 'form-control nospace lowercase email']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-8">
                        <div class="row row-5">
                            <div class="col-md-8">
                                <div class="form-group @if(!$user->exists) is-required @endif">
                                    {{ Form::label('password', 'Palavra-passe') }}
                                    <div class="input-group input-group">
                                        {{ Form::text('password', $user->exists ? '' : Str::random(8), ['class' => 'form-control nospace', 'placeholder' => $user->exists ? 'Deixar vazio para não alterar' : '']) }}
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-flat" id="random-password" type="button">
                                                <i class="fas fa-sync-alt"></i> Gerar outra
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-8">
                        <div class="row row-5">
                            <div class="col-md-8">
                                <div class="form-group @if(!$user->exists) is-required @endif">
                                    {{ Form::label('password_confirmation', 'Confirmar Palavra-passe') }}
                                    {{ Form::text('password_confirmation', '', ['class' => 'form-control nospace', 'placeholder' => $user->exists ? 'Deixar vazio para não alterar' : '']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <hr/>
        @php
            $userRoles = $user->roles ?? collect();
            $userHasRole = $userRoles->count() > 0;
            $roleNames = $userRoles->pluck('name')->map(fn($name) => strtolower($name))->toArray();
            $isAdmin = in_array('administrator', $roleNames);
            $isServiceProvider = $userHasRole && !$isAdmin;
        @endphp
        @if($user->exists && $isServiceProvider && !$isAdmin && count($services) > 0)
        <div class="row row-5" id="service-field-container">
            <div class="col-sm-8">
                <div class="form-group">
                    {{ Form::label('id_service', 'Serviço Prestado') }}
                    {{ Form::select('id_service', ['' => 'Selecione um serviço'] + $services, $user->id_service, ['class' => 'form-control select2']) }}
                </div>
            </div>
        </div>
        <hr/>
        @endif
        <button type="submit" class="btn btn-primary pull-left">Gravar</button>
        <div class="clearfix"></div>
    </div>
</div>
{{ Form::close() }}

