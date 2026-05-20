@if ($row->is_static)
    <a href="{{ route('admin.attachments.edit', $row->id) }}" class="btn btn-sm btn-block btn-default" data-toggle="modal"
        data-target="#modal-remote-xs">
        Editar
    </a>
@else
    @if ($actionsMode == 'buttons')
        <div class="action-buttons">
            <a href="{{ route('admin.attachments.edit', $row->id) }}" data-toggle="modal" data-target="#modal-remote-xs"
                class="text-green">
                <i class="fas fa-pencil-alt"></i>
            </a>
            <a href="{{ route('admin.attachments.download', $row->id) }}" class="text-blue">
                <i class="fas fa-file-download"></i>
            </a>
            @if ($row->user_id == Auth::user()->id)
                <a href="{{ route('admin.attachments.destroy', $row->id) }}" data-method="delete"
                    data-confirm="Tem a certeza que pretende remover o anexo?" class="text-red">
                    <i class="fas fa-trash-alt"></i>
                </a>
            @endif
        </div>
    @else
        <div class="btn-group btn-table-actions">
            <a href="{{ route('admin.attachments.edit', $row->id) }}" class="btn btn-sm btn-default" data-toggle="modal"
                data-target="#modal-remote-xs">
                Editar
            </a>
            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Opções Extra</span>
            </button>
            <ul class="dropdown-menu pull-right">
                <li>
                    <a href="{{ route('admin.attachments.download', $row->id) }}" class="text-blue">
                        <i class="fas fa-file-download"></i> Download
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.attachments.destroy', $row->id) }}" data-method="delete"
                        data-confirm="Confirma a remoção do ficheiro selecionado?" class="text-red">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </a>
                </li>
            </ul>
        </div>
    @endif
@endif
