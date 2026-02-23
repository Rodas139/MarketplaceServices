<div class="text-center">
    <div class="btn-group">
        <a href="{{ route('admin.customers.edit', $row->id) }}" class="btn btn-sm btn-default">
            <i class="fa fa-pencil"></i> Editar
        </a>
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Opções Extra</span>
        </button>
        <ul class="dropdown-menu pull-right">
            @if($row->is_active == 1)
                <li>
                    <a href="{{ route('admin.customers.inactivate', $row->id) }}"
                    data-method="post"
                    data-confirm-title="Inativar Cliente"
                    data-confirm-class="btn-success"
                    data-confirm-label="Inativar"
                    data-confirm="Pretende inativar o cliente?">
                        <i class="fas fa-fw fa-user-times"></i> Inativar Ficha
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('admin.customers.activate', $row->id) }}"
                    data-method="post"
                    data-confirm-title="Activar Cliente"
                    data-confirm-class="btn-success"
                    data-confirm-label="Activar"
                    data-confirm="Pretende activar o cliente?">
                        <i class="fas fa-fw fa-user-times"></i> Activar Ficha
                    </a>
                </li>
            @endif
        @if(!$row->final_consumer)
                <div class="divider"></div>
                <li>
                    <a href="{{ route('admin.customers.destroy', $row->id) }}" data-method="delete"
                       data-confirm="Confirma a remoção do registo selecionado?" class="text-red">
                        <i class="fas fa-fw fa-trash-alt"></i> Eliminar
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
