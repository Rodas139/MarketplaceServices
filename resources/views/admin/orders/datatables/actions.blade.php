<div class="text-center">
    <div class="btn-group">
        <a href="{{ route('admin.orders.edit', $row->id) }}" class="btn btn-sm btn-default">
            <i class="fa fa-pencil"></i> Editar
        </a>
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Opções Extra</span>
        </button>
        <ul class="dropdown-menu pull-right">
                @if($row->status !== 'accepted')
                <li>
                    <a href="{{ route('admin.orders.updateStatus', ['order' => $row->id, 'status' => 'accepted']) }}" data-method="put"
                       data-confirm="Confirma a aceitação deste pedido?" 
                       data-confirm-title="Aceitar Pedido"
                       data-confirm-label="Aceitar"
                       data-confirm-class="btn-success" class="text-green">
                        <i class="fas fa-fw fa-check"></i> Aceitar
                    </a>
                </li>
                @endif
                
                @if($row->status !== 'declined')
                <li>
                    <a href="{{ route('admin.orders.updateStatus', ['order' => $row->id, 'status' => 'declined']) }}" data-method="put"
                       data-confirm="Confirma a recusa deste pedido?" 
                       data-confirm-title="Recusar Pedido"
                       data-confirm-label="Recusar"
                       data-confirm-class="btn-danger" class="text-red">
                        <i class="fas fa-fw fa-times"></i> Recusar
                    </a>
                </li>
                @endif

                @if($row->status !== 'completed')
                <li>
                    <a href="{{ route('admin.orders.updateStatus', ['order' => $row->id, 'status' => 'completed']) }}" data-method="put"
                       data-confirm="Confirma a conclusão deste pedido?" 
                       data-confirm-title="Concluir Pedido"
                       data-confirm-label="Concluir"
                       data-confirm-class="btn-primary" class="text-blue">
                        <i class="fas fa-fw fa-flag"></i> Concluir
                    </a>
                </li>
                @endif
                
                <li class="divider"></li>
                <li>
                    <a href="{{ route('admin.orders.destroy', $row->id) }}" data-method="delete"
                       data-confirm="Confirma a remoção do registo selecionado?"
                       data-confirm-title="Eliminar Pedido"
                       data-confirm-label="Remover"
                       data-confirm-class="btn-danger" class="text-red">
                        <i class="fas fa-fw fa-trash-alt"></i> Eliminar
                    </a>
                </li>
        </ul>
    </div>
</div>
