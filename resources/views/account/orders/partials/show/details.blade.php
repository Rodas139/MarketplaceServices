<div class="spacer-5"></div>
<div class="form-horizontal">
    <div class="row row" style="padding: 10px; margin: -30px 0 -15px 0;">
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row row-10">
                            <div class="col-sm-12">

                                <span style="font-size:18px; font-weight: bold; text-decoration: underline;">
                                    {{trans('account/orders.order-lines')}}

                                </span>

                                <table class="table table-bordered fs-10" style="font-size: 13px; margin-top:20px">
                                    <tr>
                                        <th style="background: #f2f2f2"  class="w-1 text-center">{{trans('account/orders.line')}}</th>
                                        <th style="background: #f2f2f2"  class="text-center">{{trans('account/orders.article-code')}}</th>
                                        <th style="background: #f2f2f2"  class="text-center">{{trans('account/orders.description-article')}}</th>
                                        <th style="background: #f2f2f2"  class="text-center w-1">{{trans('account/orders.mockup')}}</th>
                                        <th style="background: #f2f2f2"  class="text-center w-1">{{trans('account/orders.approve_disapprove')}}</th>
                                    </tr>
                                    <?php
                                        $needApprove =  empty($orders->first()->order_closed) && empty($orders->first()->approved_by_customer && $orders->first()->status_id != App\Models\OrderStatus::FINISH_ID);
                                    ?>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{  $order->line_code }}</td>
                                            <td> {{  $order->code_article }}</td>
                                            <td>{{  $order->desc_article }}</td>
                                            <td class="text-center">
                                                @if($order->file_path)
                                                    <a href="{{ route('orderMockup.file', $order->file_public_path) }}" target="_blank">
                                                        <i class="fas fa-file" style="font-size: 24px"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle td-actions">
                                                @if($needApprove)
                                                     @if($order->file_path)
                                                        @if(!$order->line_accept_by_customer && !$order->line_reject_by_customer)
                                                            <a href="{{ route('account.orders.line.customer.approve' , $order->id) }}" class="approve-mockup">
                                                                <i class="fas fa-check-circle" style="color: green; font-size:24px"></i>
                                                            </a>

                                                            
                                                            <a href="{{ route('account.orders.line.customer.refuse' , $order->id) }}" class="refuse-mockup">
                                                                <i class="fas fa-times-circle" style="color: red; font-size:24px"></i>
                                                            </a>
                                                        @elseif($order->line_accept_by_customer )
                                                            <i class="fas fa-check-circle" style="color: green; font-size:24px"></i> 
                                                        @elseif($order->line_reject_by_customer)
                                                            <i class="fas fa-times-circle" style="color: red; font-size:24px"></i>
                                                        @endif
                                                     @endif
                                                @elseif($order->line_accept_by_customer)
                                                        <i class="fas fa-check-circle" style="color: green; font-size:24px"></i> 
                                                @elseif($order->line_reject_by_customer)
                                                        <i class="fas fa-times-circle" style="color: red; font-size:24px"></i>
                                                @endif
                                            </td>
                                        </tr>

                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
<style>
    .loading {
        display: inline-block;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<script>
 $(document).ready(function () {
        $('.approve-mockup').click(function (e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var row = $(this).closest('tr');

            row.find('.td-actions i').hide();
            $(this).addClass('loading');

            $.ajax({
                type: 'POST',
                url: url,
                success: function (data) {
                    if(data.result){
                        Growl.success('Aprovada com sucesso.')
                        row.find('.td-actions').html('<i class="fas fa-check-circle" style="color: green; font-size:24px"></i>');
                    }
                },
                error: function (error) {
                    console.error('Erro:', error);
                    $('.approve-mockup').removeClass('loading');
                    row.find('.td-actions i').show();
                }
            });
        });

        $('.refuse-mockup').click(function (e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var row = $(this).closest('tr');

            row.find('.td-actions i').hide();
            $(this).addClass('loading');

            $.ajax({
                type: 'POST',
                url: url,
                success: function (data) {
                    if(data.result){
                        Growl.success('Recusado com sucesso.')
                        row.find('.td-actions').html('<i class="fas fa-times-circle" style="color: red; font-size:24px"></i>');
                    }
                },
                error: function (error) {
                    console.error('Erro:', error);
                    $('.refuse-mockup').removeClass('loading');
                    row.find('.td-actions i').show();
                }
            });
        });

    });
</script>

