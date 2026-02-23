@section('title')
    Perfis e permissões
@stop

@section('content-header')
    Perfis e permissões
@stop

@section('breadcrumb')
    <li class="active">
        Configurações
    </li>
    <li class="active">
        Perfis e permissões
    </li>
@stop

@section('styles')

    <style type="text/css">
        .nav-stacked>li:hover .options {
            display: block;
        }

        .nav-stacked>li .options {
            position: absolute;
            right: 5px;
            top: 10px;
            display: none;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="box no-border">
                <div class="box-header with-border">
                    <h3 class="box-title">Os meus Perfis</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-role"><i
                                class="fas fa-plus"></i> Novo</a>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked no-border">
                        <?php
                        $rolesDinamic = $roles->filter(function ($item) {
                            return $item->is_static == 0;
                        });
                        ?>
                        @foreach ($rolesDinamic as $role)
                            <li class="{{ $role->id === $selectedRole->id ? 'active' : '' }}">
                                <a href="{{ route('admin.roles.show', $role->id) }}">
                                    @if ($role->is_static)
                                        <i class="fas fa-fw fa-lock"></i>
                                    @else
                                        <i class="fas fa-fw fa-users"></i>
                                    @endif
                                    {{ $role->display_name }}
                                </a>
                                @if ($role->name != Config::get('permission.role.admin'))
                                    <div class="options">
                                        @if (!$role->is_static)
                                            <a href="{{ route('admin.roles.destroy', $role->id) }}" data-method="delete"
                                                data-confirm="Tem a certeza que prentende remover o perfil <b>{{ $role->display_name }}</b>?<br>Todos os utilizadores com o perfil vão deixar de ter as permissões do perfil."
                                                class="btn btn-xs btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="box no-border">
                <div class="box-header with-border">
                    <h3 class="box-title">Perfis nativos do sistema</h3>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked no-border">
                        <?php
                        $rolesStatic = $roles->filter(function ($item) {
                            return $item->is_static == 1;
                        });
                        ?>
                        @foreach ($rolesStatic as $role)
                            @if ($role->id === $selectedRole->id)
                                <li class="active">
                                @else
                                <li>
                            @endif
                            <a href="{{ route('admin.roles.show', $role->id) }}">
                                @if ($role->is_static)
                                    <i class="fas fa-fw fa-lock"></i>
                                @else
                                    <i class="fas fa-fw fa-group"></i>
                                @endif
                                {{ $role->display_name }}
                            </a>
                            @if ($role->name != Config::get('permission.role.admin'))
                                <div class="options">
                                    @if (!$role->is_static)
                                        <a href="{{ route('admin.roles.destroy', $role->id) }}" data-method="delete"
                                            data-confirm="Tem a certeza que prentende remover o perfil <b>{{ $role->display_name }}</b>?<br>Todos os utilizadores com o perfil vão deixar de ter as permissões do perfil."
                                            class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash-alt-o"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-md-9">
            <div class="box no-border">
                <div class="box-header with-border">
                    <h3 class="box-title">Permissões - <strong>{{ $selectedRole->display_name }}</strong></h3>
                    @if (!Auth::user()->hasRole(config('permission.role.admin')) && $selectedRole->is_static)
                        <h4 class="text-yellow">
                            Este perfíl é nativo do sistema. Não é possível editar as suas permissões.
                        </h4>
                    @endif
                </div>
                {{ Form::open(['route' => ['admin.roles.update', $selectedRole->id], 'method' => 'PUT', 'disabled' => true]) }}
                <div class="box-body">
                    @foreach ($groupedPermissions as $group => $permissions)
                        <h4 class="bold">{{ $group }}</h4>
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-sm-4">
                                    <div class="form-group m-b-5">
                                        <div class="checkbox icheck m-0">
                                            <label>
                                                @if (!Auth::user()->hasRole(config('permission.role.admin')) && $selectedRole->is_static)
                                                    @if ($selectedRole->hasPermissionTo($permission->name))
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ $permission->id }}" checked disabled>
                                                        {{ $permission->display_name }}
                                                    @else
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ $permission->id }}" disabled>
                                                        {{ $permission->display_name }}
                                                    @endif
                                                @else
                                                    @if ($selectedRole->hasPermissionTo($permission->name))
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ $permission->id }}" checked>
                                                        {{ $permission->display_name }}
                                                    @else
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ $permission->id }}">
                                                        {{ $permission->display_name }}
                                                    @endif
                                                @endif
                                            </label>
                                            @if ($permission->description)
                                                <small><i class="fas fa-info-circle" data-toggle="tooltip"
                                                        title="{{ $permission->description }}"></i></small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if (!$loop->last)
                            <hr />
                        @endif
                    @endforeach
                </div>
                <div class="box-footer">
                    @if (Auth::user()->hasRole(config('permission.role.admin')) || !$selectedRole->is_static)
                        {{ Form::submit('Gravar', ['class' => 'btn btn-primary']) }}
                    @endif

                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

@section('modals')
    @include('admin.roles.modal_edit')
@stop
