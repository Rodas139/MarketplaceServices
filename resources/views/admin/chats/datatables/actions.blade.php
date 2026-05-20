<div class="text-center">
    <a href="{{ route('admin.chats.destroy', $row->id) }}" class="btn btn-sm btn-danger" data-method="delete"
       data-confirm="Confirma a remoção deste chat?"
       data-confirm-title="Eliminar Chat"
       data-confirm-label="Remover"
       data-confirm-class="btn-danger">
        <i class="fas fa-fw fa-trash-alt"></i> Eliminar
    </a>
</div>
