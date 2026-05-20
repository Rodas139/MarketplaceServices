
@if(!$row->approved_by_customer && $row->customerCanApprove($row->id))
        <a href="{{ route('account.orders.approve', $row) }}" style="color: green" class="btn btn-sm btn-default" data-method="POST"
            data-confirm-title="{{trans('account/orders.approve-order')}}"
            data-confirm="{{trans('account/orders.confirm-order-text')}}"
            data-confirm-class="btn-success"
            data-confirm-label="{{trans('account/orders.to-approve')}}" class="text-green">
            <i class="fas fa-fw fa-check"></i> {{trans('account/orders.approve')}}
        </a>
@endif
