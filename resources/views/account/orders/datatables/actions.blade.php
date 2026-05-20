<div class="text-center">
    <div class="btn-group">
        <a href="{{ route('account.orders.show', $row->id) }}" class="btn btn-sm btn-default" data-toggle="modal" data-target="#modal-remote-lg">
            <i class="fas fa-fw fa-eye"></i> {{trans('account/orders.visualize')}}
        </a>
       
    </div>
</div>
