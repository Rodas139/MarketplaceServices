<div class="text-center">
    <div class="btn-group">
        <a href="{{ route('admin.services.edit', $row->id) }}" class="btn btn-sm btn-default">
            <i class="fa fa-pencil"></i> Editar
        </a>
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Opções Extra</span>
        </button>
        <ul class="dropdown-menu pull-right">
                @if($row->status !== 'active')
                <li>
                    <a href="{{ route('admin.services.updateStatus', ['service' => $row->id, 'status' => 'active']) }}" data-method="put"
                       data-confirm="Confirma a ativação deste serviço?" 
                       data-confirm-title="Ativar Serviço"
                       data-confirm-label="Ativar"
                       data-confirm-class="btn-success" class="text-green">
                        <i class="fas fa-fw fa-check"></i> Ativar
                    </a>
                </li>
                @endif
                
                @if($row->status !== 'inactive')
                <li>
                    <a href="{{ route('admin.services.updateStatus', ['service' => $row->id, 'status' => 'inactive']) }}" data-method="put"
                       data-confirm="Confirma a desativação deste serviço?" 
                       data-confirm-title="Desativar Serviço"
                       data-confirm-label="Desativar"
                       data-confirm-class="btn-danger" class="text-orange">
                        <i class="fas fa-fw fa-ban"></i> Desativar
                    </a>
                </li>
                @endif
                
                <li class="divider"></li>
                <li>
                    <a href="{{ route('admin.services.destroy', $row->id) }}" data-method="delete"
                       data-confirm="Confirma a remoção do registo selecionado?"
                       data-confirm-title="Eliminar Serviço"
                       data-confirm-label="Remover"
                       data-confirm-class="btn-danger" class="text-red">
                        <i class="fas fa-fw fa-trash-alt"></i> Eliminar
                    </a>
                </li>
        </ul>
    </div>
</div>
