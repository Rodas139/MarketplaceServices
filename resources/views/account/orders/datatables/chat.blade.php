<span class="alert btn btn-sm btn-default p-4" style="min-width: 107px;">
    @if(!$row->chat)
        <a class="chat_{{$row->id}}" href="{{ route('account.chat.create', $row->id) }}"  data-toggle="modal" data-target="#modal-remote-lg">
            <span style="color:#005ea3; font-weight: bold">{{trans('account/orders.talk')}} </span><i style="color:#005ea3" class="fas fa-comments"></i> 
             <br>
        </a>  {{--Create chat --}}
    @elseif($row->chat->closed_in)
        <a class="chat_{{$row->id}}" href="{{ route('account.chat.message.create', $row->id) }}"  data-toggle="modal" data-target="#modal-remote-lg">
            <span style="color:red; font-weight: bold">{{trans('account/orders.talk')}} </span><i style="color:red" class="fas fa-comments"></i> 
        </a>  
    @else
        @if($row->chat->messages->where('message_customer', 0)->isNotEmpty())
            @if($row->chat->messages->where('message_customer', 0)->last()->date > $row->chat->customer_last_time_open)
                <a class="chat_{{$row->id}}" href="{{ route('account.chat.message.create', $row->id) }}"  data-toggle="modal" data-target="#modal-remote-lg">   
                    <span class="badge badge-danger" style="position: relative; background-color: transparent; margin-top:7%; font-size: 15px; margin-left: -8px" >
                        <span style="color:green; font-weight: bold; font-size: 12px">{{trans('account/orders.talk')}} </span><i class="fas fa-comments" style="color:green;"></i>
                        <span  class="badge badge-light" style="position: absolute; top: -10px; right: -3px; color:red;  background-color: transparent; font: bold 16px">1</span>
                    </span>
                </a>
            @else
                <a class="chat_{{$row->id}}" href="{{ route('account.chat.message.create', $row->id) }}"  data-toggle="modal" data-target="#modal-remote-lg">
                    <span style="color:green; font-weight: bold">{{trans('account/orders.talk')}} </span><i style="color:green" class="fas fa-comments"></i> 
                </a> 
            @endif
            
        @else
            <a class="chat_{{$row->id}}" href="{{ route('account.chat.message.create', $row->id) }}"  data-toggle="modal" data-target="#modal-remote-lg">
                <span style="color:green; font-weight: bold">{{trans('account/orders.talk')}} </span><i style="color:green" class="fas fa-comments"></i> 
            </a> 
        @endif
        
    @endif
</span>
{{-- 
se existir mensagens em aberto meter icon a laranja.
se existir chat e não existir mensagens a verde. --}}
