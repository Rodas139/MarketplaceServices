@if (Auth::user()->id === $row->id)
    <div class="text-center">-</div>
@else
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
            <li>
                <a href="{{ route('admin.users.destroy', $row->id) }}" data-method="delete"
                    data-confirm="Confirma a remoção do registo selecionado?" class="text-red">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
            </li>
        </ul>
    </div>
@endif
