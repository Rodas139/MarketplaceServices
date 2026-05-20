<div class="text-center">
    <div class="btn-group">
        <a href="{{ route('admin.comissions.edit', $row->id) }}" class="btn btn-sm btn-default">
            <i class="fa fa-pencil"></i> Editar
        </a>
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Opções Extra</span>
        </button>
        <ul class="dropdown-menu pull-right">
                <li>
                    <a href="{{ route('admin.comissions.destroy', $row->id) }}" data-method="delete"
                       data-confirm="Confirma a remoção do registo selecionado?"
                       data-confirm-title="Eliminar Comissão"
                       data-confirm-label="Remover"
                       data-confirm-class="btn-danger" class="text-red">
                        <i class="fas fa-fw fa-trash-alt"></i> Eliminar
                    </a>
                </li>
        </ul>
    </div>
</div>
