<div class="btn-group btn-table-actions">
    @if ($row->is_operator && !hasModule('human_resources'))
        <a href="{{ route('admin.users.edit', [$row->id, 'source' => 'operators']) }}" data-toggle="modal"
            data-target="#modal-remote-lg" class="btn btn-sm btn-default">
            Editar
        </a>
    @else
        <a href="{{ route('admin.users.edit', $row->id) }}" class="btn btn-sm btn-default">
            Editar
        </a>
    @endif
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Opções Extra</span>
    </button>
    <ul class="dropdown-menu pull-right">
        @if ($row->status !== 'active')
            <li>
                <a href="{{ route('admin.users.updateStatus', [$row->id, 'active']) }}" data-method="put"
                    data-confirm="Tem a certeza que quer ativar esta conta?"
                    data-confirm-title="Ativar Conta"
                    data-confirm-label="Ativar"
                    data-confirm-class="btn-success" class="text-green">
                    <i class="fas fa-check-circle"></i> Ativar
                </a>
            </li>
        @endif
        @if ($row->status !== 'inactive')
            <li>
                <a href="{{ route('admin.users.updateStatus', [$row->id, 'inactive']) }}" data-method="put"
                    data-confirm="Tem a certeza que quer desativar esta conta?"
                    data-confirm-title="Desativar Conta"
                    data-confirm-label="Desativar"
                    data-confirm-class="btn-danger" class="text-orange">
                    <i class="fas fa-ban"></i> Desativar
                </a>
            </li>
        @endif
        @if (Auth::user()->id !== $row->id)
            <li class="divider"></li>
            <li>
                <a href="{{ route('admin.users.destroy', $row->id) }}" data-method="delete"
                    data-confirm="Confirma a remoção do registo selecionado?" class="text-red">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
            </li>
        @endif
    </ul>
</div>
