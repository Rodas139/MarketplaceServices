<div class="text-center">
    <div class="btn-group">
        <a href="{{ route('admin.payments.edit', $row->id) }}" class="btn btn-sm btn-default">
            <i class="fa fa-pencil"></i> Editar
        </a>
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Opções Extra</span>
        </button>
        <ul class="dropdown-menu pull-right">
                @if($row->status !== 'paid')
                <li>
                    <a href="{{ route('admin.payments.updateStatus', ['payment' => $row->id, 'status' => 'paid']) }}" data-method="put"
                       data-confirm="Confirma a marcação deste pagamento como pago?" 
                       data-confirm-title="Mudar para Pago"
                       data-confirm-label="Pago"
                       data-confirm-class="btn-success" class="text-green">
                        <i class="fas fa-fw fa-check"></i> Pago
                    </a>
                </li>
                @endif
                
                @if($row->status !== 'pending')
                <li>
                    <a href="{{ route('admin.payments.updateStatus', ['payment' => $row->id, 'status' => 'pending']) }}" data-method="put"
                       data-confirm="Confirma o pagamento para pendente?" 
                       data-confirm-title="Mudar para Pendente"
                       data-confirm-label="Pendente"
                       data-confirm-class="btn-warning" class="text-orange">
                        <i class="fas fa-fw fa-history"></i> Pendente
                    </a>
                </li>
                @endif
                
                <li class="divider"></li>
                <li>
                    <a href="{{ route('admin.payments.destroy', $row->id) }}" data-method="delete"
                       data-confirm="Confirma a remoção do registo selecionado?"
                       data-confirm-title="Eliminar Pagamento"
                       data-confirm-label="Remover"
                       data-confirm-class="btn-danger" class="text-red">
                        <i class="fas fa-fw fa-trash-alt"></i> Eliminar
                    </a>
                </li>
        </ul>
    </div>
</div>

