<div class="box no-border">
    <div class="box-body">
        <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable-contacts">
            <li>
                <a href="{{ route('admin.address-book.create', ['customer' => $customer->id]) }}"
                   class="btn btn-success btn-sm"
                   data-toggle="modal"
                   data-target="#modal-remote-xs">
                    <i class="fas fa-plus"></i> Novo
                </a>
            </li>
        </ul>
        <table id="datatable-contacts" class="table table-striped table-dashed table-hover table-condensed">
            <thead>
            <tr>
                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                <th>Contacto</th>
                <th class="w-90px">Telefone</th>
                <th class="w-220px">E-mail</th>
                <th class="w-80px">Criado em</th>
                <th class="w-60px">Ações</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <div class="selected-rows-action hide">
            {{ Form::open(array('route' => 'admin.address-book.selected.destroy')) }}
            <button class="btn btn-sm btn-danger" data-action="confirm" data-title="Apagar selecionados"><i class="fas fa-trash-alt"></i> Apagar Selecionados</button>
            {{ Form::close() }}
            <a href="{{ route('admin.address-book.selected.merge.edit') }}"
               class="btn btn-sm btn-default m-l-5"
               data-action-url="datatable-action-url"
               data-toggle="modal"
               data-target="#modal-remote-xs">
                <i class="fas fa-fw fa-compress"></i> Convergir
            </a>
        </div>
    </div>
</div>