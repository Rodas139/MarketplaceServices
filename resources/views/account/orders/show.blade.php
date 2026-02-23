<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">{{trans('account/orders.close')}}</span>
    </button>
    <h4 class="modal-title">{{trans('account/orders.order')}} Nº {{$firstOrder->code}}</h4>
</div>

<div class="tabbable-line m-b-15">
    <ul class="nav nav-tabs" style="margin: 0%;">
        <li class="{{ @$tab ? : 'active' }}">
            <a href="#tab-details" data-toggle="tab">
                {{trans('account/orders.details')}}
            </a>
        </li>
        <li class="">
            <a href="#tab-history_status" data-toggle="tab">
                {{trans('account/orders.state-history')}}
            </a>
        </li>
    </ul>
</div>

<div class="modal-body p-t-0 p-b-0 modal-shipment modal-shipment-detail">
    <div class="tab-content m-b-0" style="padding-bottom: 10px;">
        <div class="tab-pane {{ @$tab ? '' : 'active' }}"  id="tab-details">
            @include('account.orders.partials.show.details')
        </div>
        <div class="tab-pane" id="tab-history_status">
            @include('account.orders.partials.show.history_status')
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('account/orders.close')}}</button>
    </div>
</div>


<script>
    $('.select2').select2(Init.select2());
    $('.modal form.send-email-signature').on('submit', function(e){
        e.preventDefault();

        var $form = $(this);
        var $button = $('button[type=submit]');

        $button.button('loading');
        $.post($form.attr('action'), $form.serialize(), function(data){
            if(data.result) {
                Growl.success(data.text);
                $('#modal-remote-xs').modal('hide');
            } else {
                Growl.error(data.text);
            }
        }).fail(function () {
            Growl.error500();
        }).always(function(){
            $button.button('reset');
        })
    });
</script>