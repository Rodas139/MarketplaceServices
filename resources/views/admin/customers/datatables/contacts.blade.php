@if($row->phone)
<i class="fa fa-fw fa-phone"></i> {{ $row->phone }}<br/>
@endif

@if($row->mobile)
<i class="fa fa-fw fa-mobile bigger-120"></i> {{ $row->mobile }}
@endif