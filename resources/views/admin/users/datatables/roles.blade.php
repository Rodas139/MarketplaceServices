<?php
/*$count = $row->roles->count();
$html  = '';
foreach($row->roles as $key => $role) {
    $html.= $role->display_name .'<br/>';
}*/
?>
{{--
@if($count > 2)
    {{ $row->roles->first()->display_name }}
    <br/>
    <small class="text-muted cursor-pointer"
        data-toggle="popover"
        data-title="Perfis do utilizador"
        data-html="true"
        data-content="{!! $html !!}">
        +{{ $count - 2 }} perfis <i class="fas fa-external-link-square-alt"></i>
    </small>
@else
    @if($row->roles->isEmpty())
        <i class="text-muted">Sem Login</i>
    @else
        @foreach($row->roles as $key => $role)
            {{ $role->display_name }}<br/>
        @endforeach
    @endif
@endif
--}}
<div class="text-center">
    <span class="label" style="background: {{ @$row->roles->first()->color }}">{{ @$row->roles->first()->display_name }}</span>
</div>