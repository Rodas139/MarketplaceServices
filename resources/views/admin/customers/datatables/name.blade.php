<a href="{{ route('admin.customers.edit', $row->id) }}" class="text-uppercase">
    {{ $row->name }}
</a>
@if(!$row->license_active)
    <span class="label label-danger"><i class="fas fa-exclamation-triangle"></i> LICENÇA BLOQUEADA</span>
@endif
@if(!$row->is_active)
    <span class="label label-danger" data-toggle="tooltip" title="Cliente inativo">Inativo</span>
@endif
