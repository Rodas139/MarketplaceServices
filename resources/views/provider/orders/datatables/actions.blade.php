<div class="text-center">
    <div class="btn-group">
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <i class="fas"></i> Ações
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-right">
                @if($row->status !== 'accepted' && $row->status !== 'completed' && $row->status !== 'declined')
                <li>
                    <a href="{{ route('provider.orders.updateStatus', ['order' => $row->id, 'status' => 'accepted']) }}" data-method="put"
                       data-confirm="Confirma a aceitação deste pedido?" 
                       data-confirm-title="Aceitar Pedido"
                       data-confirm-label="Aceitar"
                       data-confirm-class="btn-success" class="text-green">
                        <i class="fas fa-fw fa-check"></i> Aceitar
                    </a>
                </li>
                @endif
                
                @if($row->status !== 'declined' && $row->status !== 'completed' && $row->status !== 'accepted')
                <li>
                    <a href="{{ route('provider.orders.updateStatus', ['order' => $row->id, 'status' => 'declined']) }}" data-method="put"
                       data-confirm="Confirma a recusa deste pedido?" 
                       data-confirm-title="Recusar Pedido"
                       data-confirm-label="Recusar"
                       data-confirm-class="btn-danger" class="text-red">
                        <i class="fas fa-fw fa-times"></i> Recusar
                    </a>
                </li>
                @endif

                @if($row->status !== 'completed' && $row->status !== 'declined' && $row->status !== 'pending')
                <li>
                    <a href="{{ route('provider.orders.updateStatus', ['order' => $row->id, 'status' => 'completed']) }}" data-method="put"
                       data-confirm="Confirma a conclusão deste pedido?" 
                       data-confirm-title="Concluir Pedido"
                       data-confirm-label="Concluir"
                       data-confirm-class="btn-primary" class="text-blue">
                        <i class="fas fa-fw fa-check"></i> Concluir
                    </a>
                </li>
                @endif

                <li class="divider"></li>
                
                <li>
                    <a href="{{ route('provider.orders.show', $row->id) }}" class="text-info">
                        <i class="fas fa-fw fa-eye"></i> Ver Detalhes
                    </a>
                </li>
        </ul>
    </div>
</div>
