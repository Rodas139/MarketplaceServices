<div class="spacer-5"></div>
<table class="table  table-bordered m-b-0">
    <tr>
        <th class="w-120px bg-gray-light" style="border-top: none">{{trans('account/orders.status')}}</th>
        <th class="bg-gray-light" style="border-top: none">{{trans('account/orders.date')}}</th>
        <th class="bg-gray-light" style="border-top: none">{{trans('account/orders.description')}}</th>

      
    </tr>

    @if(isset($firstOrder->status_history) && !$firstOrder->status_history->isEmpty())
        @foreach($firstOrder->status_history->reverse() as $history)
        <tr>
            <td style="background-color: {{$history->status->color}}"> {{$history->status->name}} </td>
            <td style="border-left: 1px solid #ddd">{{$history->date}}</td>
            <td style="border-left: 1px solid #ddd">{{$history->description}}</td>
        </tr>
        @endforeach
    @endif
</table>

